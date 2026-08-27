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

        $billboards = $query->orderBy('city')->paginate(12);
        
        $cityCounts = Billboard::available()
            ->select('city', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy('city')
            ->orderBy('city')
            ->get();
            
        $types = Billboard::available()->distinct()->pluck('jenis');

        return view('bilboard.index', compact('billboards', 'cityCounts', 'types'));
    }
}
