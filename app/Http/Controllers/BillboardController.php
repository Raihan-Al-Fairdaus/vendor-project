<?php

namespace App\Http\Controllers;

use App\Models\Billboard;
use Illuminate\Http\Request;

class BillboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Billboard::available();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', '%' . $search . '%')
                  ->orWhere('name', 'like', '%' . $search . '%')
                  ->orWhere('city', 'like', '%' . $search . '%')
                  ->orWhere('address', 'like', '%' . $search . '%');
            });
        }

        // City filter
        if ($request->filled('city')) {
            $query->where('city', $request->input('city'));
        }

        // Jenis filter (billboard / midiboard)
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->input('jenis'));
        }

        // Orientasi filter (portrait / landscape)
        if ($request->filled('orientasi')) {
            $query->where('orientasi', $request->input('orientasi'));
        }

        $billboards = $query->orderBy('city')->paginate(12);
        
        // Hitung per kota: berapa billboard, berapa midiboard (hanya yang tersedia)
        $cityData = Billboard::available()
            ->select('city', 'jenis', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('city', 'jenis')
            ->orderBy('city')
            ->get();

        $cityCounts = [];
        foreach ($cityData as $row) {
            $cityCounts[$row->city][$row->jenis] = $row->total;
        }
        ksort($cityCounts);
            
        $types = Billboard::available()->distinct()->pluck('jenis');

        // Hitung terpisah billboard vs midiboard
        $billboardCount  = Billboard::available()->where('jenis', 'billboard')->count();
        $midiboardCount  = Billboard::available()->where('jenis', 'midiboard')->count();

        return view('bilboard.index', compact('billboards', 'cityCounts', 'types', 'billboardCount', 'midiboardCount'));
    }

    /**
     * Stream or redirect to simulation file (handles PDF, video, image with CORS headers).
     */
    public function simulationFile(Billboard $billboard)
    {
        if (!$billboard->simulation_path) {
            abort(404, 'File simulasi tidak ditemukan');
        }

        // Jika URL eksternal langsung
        if (str_starts_with($billboard->simulation_path, 'http://') || str_starts_with($billboard->simulation_path, 'https://')) {
            return redirect($billboard->simulation_path);
        }

        // Cek jika tersimpan di Supabase
        if (config('filesystems.disks.supabase.key')) {
            try {
                if (\Illuminate\Support\Facades\Storage::disk('supabase')->exists($billboard->simulation_path)) {
                    $stream = \Illuminate\Support\Facades\Storage::disk('supabase')->readStream($billboard->simulation_path);
                    $mime   = \Illuminate\Support\Facades\Storage::disk('supabase')->mimeType($billboard->simulation_path) ?? 'application/octet-stream';
                    return response()->stream(function() use ($stream) {
                        fpassthru($stream);
                    }, 200, [
                        'Content-Type' => $mime,
                        'Access-Control-Allow-Origin' => '*',
                        'Access-Control-Allow-Methods' => 'GET, OPTIONS',
                        'Content-Disposition' => 'inline; filename="' . basename($billboard->simulation_path) . '"',
                    ]);
                }
            } catch (\Exception $e) {}
        }

        // Cek jika tersimpan di public disk
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($billboard->simulation_path)) {
            $path = \Illuminate\Support\Facades\Storage::disk('public')->path($billboard->simulation_path);
            return response()->file($path, [
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            ]);
        }

        return redirect($billboard->simulation_url);
    }
}
