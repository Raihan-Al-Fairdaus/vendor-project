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

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $billboards = $query->orderBy('city')->paginate(15)->appends($request->all());
        
        // Hitung per kota: berapa billboard, berapa midiboard
        $cityData = Billboard::select('city', 'jenis', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('city', 'jenis')
            ->orderBy('city')
            ->get();

        // Kelompokkan jadi: ['BANDUNG' => ['billboard' => 15, 'midiboard' => 30], ...]
        $cityCounts = [];
        foreach ($cityData as $row) {
            $cityCounts[$row->city][$row->jenis] = $row->total;
        }
        ksort($cityCounts); // Urutkan A-Z

        $types = Billboard::distinct()->pluck('jenis');

        return view('admin.billboards.index', compact('billboards', 'cityCounts', 'types'));
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
            'address'      => 'required|string',
            'map_link'     => 'nullable|string',
            'status'       => 'required|in:tersedia,terisi',
        ]);

        // Auto-generate code
        $code = Billboard::generateCode($data['jenis'], $data['city'], (int) $data['sisi']);
        $data['code'] = $code;
        $data['name'] = $code; // Keep name in sync for backward compat

        // Hilangkan format kepemilikan
        $data['kepemilikan'] = 'DNA Advertising';

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

        // Hilangkan format kepemilikan
        $data['kepemilikan'] = 'DNA Advertising';

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
            'files' => 'required|array',
            'files.*' => 'required|file|mimes:xlsx,xls,csv',
            'jenis_import' => 'required|in:billboard,midiboard',
        ]);

        $files = $request->file('files');
        $imported = 0;
        $errors   = 0;
        
        // Hitung urutan awal HANYA SEKALI untuk menghindari query berulang
        $currentSeq = Billboard::count();
        $insertData = [];
        $now = now();
        $jenisLabel = $request->input('jenis_import', 'auto') === 'midiboard' ? 'MIDIBOARD' : 'BILLBOARD';

        foreach ($files as $file) {
            $path = $file->getRealPath();

            try {
                $reader = IOFactory::createReaderForFile($path);
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load($path);
                $sheet       = $spreadsheet->getActiveSheet();
                
                // Baca seluruh data ke array sekaligus (Jauh lebih cepat dari getCell di dalam loop)
                $rows = $sheet->toArray(null, true, true, true); 
            } catch (\Exception $e) {
                // Lewati file ini jika error baca
                $errors++;
                continue;
            }

            if (empty($rows) || count($rows) < 2) {
                // File Excel kosong
                continue;
            }

            // Ambil header dari baris 1
            $headerRow = $rows[1];
            $headerMap = [];
            
            foreach ($headerRow as $colLetter => $val) {
                $val = strtolower(trim($val ?? ''));
                if (empty($val)) continue;
                
                if (!isset($headerMap[$colLetter])) {
                    if (!in_array('city', $headerMap) && (str_contains($val, 'kota') || str_contains($val, 'city'))) {
                        $headerMap[$colLetter] = 'city';
                    } elseif (!in_array('address', $headerMap) && (str_contains($val, 'alamat') || str_contains($val, 'lokasi') || str_contains($val, 'address'))) {
                        $headerMap[$colLetter] = 'address';
                    } elseif (!in_array('map_link', $headerMap) && (str_contains($val, 'link') || str_contains($val, 'peta') || str_contains($val, 'map'))) {
                        $headerMap[$colLetter] = 'map_link';
                    } elseif (!in_array('ukuran', $headerMap) && str_contains($val, 'ukuran')) {
                        $headerMap[$colLetter] = 'ukuran';
                    } elseif (!in_array('sisi', $headerMap) && str_contains($val, 'sisi')) {
                        $headerMap[$colLetter] = 'sisi';
                    } elseif (!in_array('orientasi', $headerMap) && (str_contains($val, 'orientasi') || str_contains($val, 'posisi') || str_contains($val, 'bentuk') || str_contains($val, 'tampilan') || str_contains($val, 'potrait') || str_contains($val, 'landscape'))) {
                        $headerMap[$colLetter] = 'orientasi';
                    } elseif (!in_array('jenis', $headerMap) && str_contains($val, 'jenis')) {
                        $headerMap[$colLetter] = 'jenis';
                    } elseif (!in_array('status', $headerMap) && str_contains($val, 'status')) {
                        $headerMap[$colLetter] = 'status';
                    }
                }
            }

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

            // 1. Kota
            $city = trim($data['city'] ?? '');
            $address = trim($data['address'] ?? '');

            // Jika kota kosong, coba cari dari alamat menggunakan city map
            if (empty($city) && !empty($address)) {
                foreach (\App\Models\Billboard::getCityMapForJs() as $knownCity => $abbr) {
                    if (stripos($address, $knownCity) !== false) {
                        $city = $knownCity;
                        break;
                    }
                }
            }
            
            // Jika masih kosong, coba mewarisi dari baris sebelumnya (hanya jika masuk akal)
            if (empty($city)) {
                $city = $lastCity;
            }

            // Validasi fallback
            if (empty($city) || strtolower($city) === 'unknown' || strtolower($city) === 'tidak diketahui') {
                $city = 'Tidak Diketahui';
            }
            $lastCity = $city;

            // 1b. Alamat
            if (empty($address)) {
                $address = $lastAddress;
            }
            $lastAddress = $address;

            // 2. Map Link
            $mapLink = !empty($data['map_link']) ? $data['map_link'] : null;
            if (empty($mapLink) && $address === $lastAddress) {
                $mapLink = $lastMapLink;
            }
            $lastMapLink = $mapLink;

            // 3. Kepemilikan (Dihilangkan, selalu default)
            $kepemilikan = 'DNA Advertising';

            // 4. Status
            $rawStatus = strtolower($data['status'] ?? '');
            $status = 'tersedia';
            if (str_contains($rawStatus, 'terisi') || str_contains($rawStatus, 'centang') || 
                $rawStatus === 'v' || $rawStatus === '✓' || $rawStatus === '✔' || $rawStatus === '1') {
                $status = 'terisi';
            }

            // 5. Jenis & Sisi & Orientasi
            $jenisImport = $request->input('jenis_import', 'auto');
            if ($jenisImport === 'auto') {
                // Auto-detect dari kolom jenis di Excel
                $rawJenis = strtolower($data['jenis'] ?? '');
                $jenis = str_contains($rawJenis, 'midi') ? 'midiboard' : 'billboard';
            } else {
                // Pakai pilihan user dari dropdown
                $jenis = $jenisImport;
            }

            $rawSisi = strtolower($data['sisi'] ?? '');
            $sisi = (str_contains($rawSisi, '2') || str_contains($rawSisi, 'ii')) ? 2 : 1;

            $ukuran = trim($data['ukuran'] ?? null);

            $rawOrientasi = strtolower($data['orientasi'] ?? '');
            if (str_contains($rawOrientasi, 'port')) {
                $orientasi = 'portrait';
            } elseif (str_contains($rawOrientasi, 'land')) {
                $orientasi = 'landscape';
            } else {
                // Auto-detect dari ukuran (misal "4x8", "4 x 8", "5×10", "5m x 10m")
                $orientasi = 'landscape'; // default
                if (!empty($ukuran)) {
                    // Cari angka sebelum dan sesudah x / X / * / ×, bisa diapit spasi atau huruf (seperti 'm')
                    if (preg_match('/(\d+(?:\.\d+)?)[a-zA-Z\s]*[xX\*×][a-zA-Z\s]*(\d+(?:\.\d+)?)/u', $ukuran, $matches)) {
                        $width = (float) $matches[1];
                        $height = (float) $matches[2];
                        if ($height > $width) {
                            $orientasi = 'portrait';
                        }
                    }
                }
            }

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
        } // end foreach ($files as $file)

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

        $jenisLabel = $request->input('jenis_import', 'auto') === 'midiboard' ? 'MIDIBOARD' : 'BILLBOARD';
        $msg = "✅ $imported {$jenisLabel} berhasil diimport dengan kode urut otomatis.";
        if ($errors > 0) {
            $msg .= " $errors baris gagal diproses.";
        }

        return Redirect::route('admin.billboards.index')
            ->with('success', $msg)
            ->with('last_import_count', $imported)
            ->with('last_import_jenis', $jenisLabel);
    }

    /**
     * Undo last import: hapus N data terakhir berdasarkan ID terbesar.
     */
    public function undoImport(Request $request)
    {
        $count = (int) $request->input('count', 0);

        if ($count <= 0 || $count > 500) {
            return Redirect::route('admin.billboards.index')
                ->with('error', 'Jumlah data yang akan dihapus tidak valid.');
        }

        // Ambil ID terakhir sebanyak $count
        $ids = Billboard::orderBy('id', 'desc')
            ->limit($count)
            ->pluck('id');

        if ($ids->isEmpty()) {
            return Redirect::route('admin.billboards.index')
                ->with('error', 'Tidak ada data untuk dihapus.');
        }

        $deleted = Billboard::whereIn('id', $ids)->delete();

        return Redirect::route('admin.billboards.index')
            ->with('success', "🗑️ $deleted data terakhir berhasil dihapus (undo import).");
    }
}
