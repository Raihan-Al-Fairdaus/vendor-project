<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Billboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use PhpOffice\PhpSpreadsheet\IOFactory;

class BillboardController extends Controller
{
    /**
     * List all billboards (admin).
     */
    public function index(Request $request)
    {
        $query = Billboard::query();

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

        // Jenis filter
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->input('jenis'));
        }

        $billboards = $query->orderBy('city')->paginate(15)->appends($request->all());
        $cities     = Billboard::distinct()->pluck('city');
        $types      = Billboard::distinct()->pluck('jenis');

        return view('admin.billboards.index', compact('billboards', 'cities', 'types'));
    }

    /**
     * Show form to create a new billboard.
     */
    public function create()
    {
        $nextSeq = Billboard::count() + 1;
        return view('admin.billboards.create', compact('nextSeq'));
    }

    /**
     * Store a new billboard.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'jenis'        => 'required|in:billboard,midiboard',
            'city'         => 'required|string|max:255',
            'sisi'         => 'required|integer|in:1,2',
            'ukuran'       => 'nullable|string|max:255',
            'orientasi'    => 'required|in:portrait,landscape',
            'kepemilikan'  => 'nullable|string|max:255',
            'address'      => 'required|string',
            'map_link'     => 'nullable|string',
            'status'       => 'required|in:tersedia,terisi',
        ]);

        // Auto-generate code
        $code = Billboard::generateCode($data['jenis'], $data['city'], (int) $data['sisi']);
        $data['code'] = $code;
        $data['name'] = $code; // Keep name in sync for backward compat

        // Default kepemilikan jika kosong
        if (empty($data['kepemilikan'])) {
            $data['kepemilikan'] = 'DNA Advertising';
        }

        Billboard::create($data);

        return Redirect::route('admin.billboards.index')
            ->with('success', "Billboard {$code} berhasil ditambahkan.");
    }

    /**
     * Show edit form.
     */
    public function edit(Billboard $billboard)
    {
        return view('admin.billboards.edit', compact('billboard'));
    }

    /**
     * Update existing billboard.
     */
    public function update(Request $request, Billboard $billboard)
    {
        $data = $request->validate([
            'jenis'        => 'required|in:billboard,midiboard',
            'city'         => 'required|string|max:255',
            'sisi'         => 'required|integer|in:1,2',
            'ukuran'       => 'nullable|string|max:255',
            'orientasi'    => 'required|in:portrait,landscape',
            'kepemilikan'  => 'nullable|string|max:255',
            'address'      => 'required|string',
            'map_link'     => 'nullable|string',
            'status'       => 'required|in:tersedia,terisi',
        ]);

        // Re-generate code jika jenis, kota, atau sisi berubah
        $needsNewCode = $billboard->jenis !== $data['jenis']
            || strtolower(trim($billboard->city)) !== strtolower(trim($data['city']))
            || $billboard->sisi != $data['sisi'];

        if ($needsNewCode) {
            $code = Billboard::generateCode($data['jenis'], $data['city'], (int) $data['sisi']);
            $data['code'] = $code;
            $data['name'] = $code;
        }

        if (empty($data['kepemilikan'])) {
            $data['kepemilikan'] = 'DNA Advertising';
        }

        $billboard->update($data);

        return Redirect::route('admin.billboards.index')
            ->with('success', 'Billboard berhasil diupdate.');
    }

    /**
     * Delete a billboard.
     */
    public function destroy(Billboard $billboard)
    {
        $billboard->delete();
        return Redirect::back()->with('success', 'Billboard berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();

        try {
            $reader = IOFactory::createReaderForFile($path);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($path);
            $sheet       = $spreadsheet->getActiveSheet();
            
            // Baca seluruh data ke array sekaligus (Jauh lebih cepat dari getCell di dalam loop)
            $rows = $sheet->toArray(null, true, true, true); 
        } catch (\Exception $e) {
            return Redirect::route('admin.billboards.index')
                ->with('error', 'Gagal membaca file: ' . $e->getMessage());
        }

        if (empty($rows) || count($rows) < 2) {
            return Redirect::route('admin.billboards.index')
                ->with('error', 'File Excel kosong atau tidak ada data.');
        }

        // Ambil header dari baris 1
        $headerRow = $rows[1];
        $headerMap = [];
        
        foreach ($headerRow as $colLetter => $val) {
            $val = strtolower(trim($val ?? ''));
            if (empty($val)) continue;
            
            if (str_contains($val, 'kota') || str_contains($val, 'city')) {
                $headerMap[$colLetter] = 'city';
            } elseif (str_contains($val, 'alamat') || str_contains($val, 'lokasi') || str_contains($val, 'address')) {
                $headerMap[$colLetter] = 'address';
            } elseif (str_contains($val, 'link') || str_contains($val, 'peta') || str_contains($val, 'map')) {
                $headerMap[$colLetter] = 'map_link';
            } elseif (str_contains($val, 'milik') || str_contains($val, 'owner')) {
                $headerMap[$colLetter] = 'kepemilikan';
            } elseif (str_contains($val, 'ukuran')) {
                $headerMap[$colLetter] = 'ukuran';
            } elseif (str_contains($val, 'sisi')) {
                $headerMap[$colLetter] = 'sisi';
            } elseif (str_contains($val, 'orientasi')) {
                $headerMap[$colLetter] = 'orientasi';
            } elseif (str_contains($val, 'jenis')) {
                $headerMap[$colLetter] = 'jenis';
            } elseif (str_contains($val, 'status')) {
                $headerMap[$colLetter] = 'status';
            }
        }

        $imported = 0;
        $errors   = 0;

        $lastKepemilikan = 'DNA Advertising';
        $lastMapLink = null;
        $lastAddress = 'Tidak Diketahui';
        $lastCity = 'Tidak Diketahui';

        // Hitung urutan awal HANYA SEKALI untuk menghindari query berulang
        $currentSeq = Billboard::count();
        $insertData = [];
        $now = now();

        // Mulai dari baris 2
        $rowKeys = array_keys($rows);
        for ($i = 1; $i < count($rowKeys); $i++) {
            $row = $rows[$rowKeys[$i]];
            $data = [];
            $hasData = false;

            foreach ($headerMap as $colLetter => $key) {
                $val = $row[$colLetter] ?? '';
                if ($val !== null && trim($val) !== '') {
                    $hasData = true;
                }
                $data[$key] = trim($val ?? '');
            }

            if (!$hasData) {
                continue;
            }

            // 1. Kota & Alamat
            $city = !empty($data['city']) ? $data['city'] : $lastCity;
            if (strtolower($city) === 'tidak diketahui' || strtolower($city) === 'unknown') {
                $city = 'Tidak Diketahui';
            }
            $lastCity = $city;

            $address = !empty($data['address']) ? $data['address'] : $lastAddress;
            $lastAddress = $address;

            // 2. Map Link
            $mapLink = !empty($data['map_link']) ? $data['map_link'] : null;
            if (empty($mapLink) && $address === $lastAddress) {
                $mapLink = $lastMapLink;
            }
            $lastMapLink = $mapLink;

            // 3. Kepemilikan
            $kepemilikan = !empty($data['kepemilikan']) ? $data['kepemilikan'] : $lastKepemilikan;
            $lastKepemilikan = $kepemilikan;

            // 4. Status
            $rawStatus = strtolower($data['status'] ?? '');
            $status = 'tersedia';
            if (str_contains($rawStatus, 'terisi') || str_contains($rawStatus, 'centang') || 
                $rawStatus === 'v' || $rawStatus === '✓' || $rawStatus === '✔' || $rawStatus === '1') {
                $status = 'terisi';
            }

            // 5. Jenis & Sisi & Orientasi
            $rawJenis = strtolower($data['jenis'] ?? '');
            $jenis = str_contains($rawJenis, 'midi') ? 'midiboard' : 'billboard';

            $rawSisi = strtolower($data['sisi'] ?? '');
            $sisi = (str_contains($rawSisi, '2') || str_contains($rawSisi, 'ii')) ? 2 : 1;

            $rawOrientasi = strtolower($data['orientasi'] ?? '');
            $orientasi = str_contains($rawOrientasi, 'port') ? 'portrait' : 'landscape';

            $ukuran = $data['ukuran'] ?? null;

            // 6. Generate kode manual berdasarkan currentSeq (Sangat Cepat, tanpa query DB)
            $currentSeq++;
            $typePrefix = $jenis === Billboard::JENIS_MIDIBOARD ? '01' : '00';
            $cityAbbr   = Billboard::getCityAbbreviation($city);
            $sideRoman  = $sisi == 2 ? 'II' : 'I';
            $code       = "#{$typePrefix}{$currentSeq}-{$cityAbbr}-{$sideRoman}";

            $insertData[] = [
                'code'         => $code,
                'name'         => $code,
                'jenis'        => $jenis,
                'city'         => $city,
                'sisi'         => $sisi,
                'ukuran'       => $ukuran,
                'orientasi'    => $orientasi,
                'kepemilikan'  => $kepemilikan,
                'address'      => $address,
                'map_link'     => $mapLink,
                'status'       => $status,
                'created_at'   => $now,
                'updated_at'   => $now,
            ];
            $imported++;
        }

        // Batch Insert ke database sekaligus! (Sangat cepat, hitungan milidetik)
        if (!empty($insertData)) {
            try {
                // Gunakan chunk jika data sangat besar (misal 500 per insert)
                foreach (array_chunk($insertData, 500) as $chunk) {
                    Billboard::insert($chunk);
                }
            } catch (\Exception $e) {
                return Redirect::route('admin.billboards.index')
                    ->with('error', 'Terjadi kesalahan saat menyimpan ke database: ' . $e->getMessage());
            }
        }

        $msg = "$imported billboard berhasil diimport dengan kode urut otomatis.";
        if ($errors > 0) {
            $msg .= " $errors baris gagal diproses.";
        }

        return Redirect::route('admin.billboards.index')->with('success', $msg);
    }
}
