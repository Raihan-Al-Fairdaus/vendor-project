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
    public function index()
    {
        $billboards = Billboard::orderBy('city')->paginate(15);
        return view('admin.billboards.index', compact('billboards'));
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

            $highestRow         = $sheet->getHighestRow();
            $highestColumn      = $sheet->getHighestColumn();
            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
        } catch (\Exception $e) {
            return Redirect::route('admin.billboards.index')
                ->with('error', 'Gagal membaca file: ' . $e->getMessage());
        }

        if ($highestRow < 1) {
            return Redirect::route('admin.billboards.index')
                ->with('error', 'File Excel kosong.');
        }

        // Mapping Header Fleksibel
        $headerMap = [];
        for ($col = 1; $col <= $highestColumnIndex; $col++) {
            $val = strtolower(trim($sheet->getCell([$col, 1])->getValue() ?? ''));
            // Kita cari pola nama kolom
            if (str_contains($val, 'kota') || str_contains($val, 'city')) {
                $headerMap[$col] = 'city';
            } elseif (str_contains($val, 'alamat') || str_contains($val, 'lokasi') || str_contains($val, 'address')) {
                $headerMap[$col] = 'address';
            } elseif (str_contains($val, 'link') || str_contains($val, 'peta') || str_contains($val, 'map')) {
                $headerMap[$col] = 'map_link';
            } elseif (str_contains($val, 'milik') || str_contains($val, 'owner')) {
                $headerMap[$col] = 'kepemilikan';
            } elseif (str_contains($val, 'ukuran')) {
                $headerMap[$col] = 'ukuran';
            } elseif (str_contains($val, 'sisi')) {
                $headerMap[$col] = 'sisi';
            } elseif (str_contains($val, 'orientasi')) {
                $headerMap[$col] = 'orientasi';
            } elseif (str_contains($val, 'jenis')) {
                $headerMap[$col] = 'jenis';
            } elseif (str_contains($val, 'status')) {
                $headerMap[$col] = 'status';
            }
        }

        $imported = 0;
        $errors   = 0;

        // Variabel untuk menyimpan data dari baris sebelumnya (jika baris sekarang kosong)
        $lastKepemilikan = 'DNA Advertising';
        $lastMapLink = null;
        $lastAddress = 'Tidak Diketahui';
        $lastCity = 'Tidak Diketahui';

        for ($row = 2; $row <= $highestRow; $row++) {
            $data = [];
            $hasData = false;

            foreach ($headerMap as $colIndex => $key) {
                $val = $sheet->getCell([$colIndex, $row])->getValue();
                if ($val !== null && trim($val) !== '') {
                    $hasData = true;
                }
                $data[$key] = trim($val ?? '');
            }

            if (!$hasData) {
                continue;
            }

            // 1. Kota & Alamat (Toleransi data "tidak diketahui")
            $city = !empty($data['city']) ? $data['city'] : $lastCity;
            if (strtolower($city) === 'tidak diketahui' || strtolower($city) === 'unknown') {
                $city = 'Tidak Diketahui';
            }
            $lastCity = $city;

            $address = !empty($data['address']) ? $data['address'] : $lastAddress;
            $lastAddress = $address;

            // 2. Map Link (Toleransi billboard sisi 2 yg gak punya link, ngikut sisi 1)
            $mapLink = !empty($data['map_link']) ? $data['map_link'] : null;
            if (empty($mapLink) && $address === $lastAddress) {
                $mapLink = $lastMapLink; // pinjam dari row atasnya yg alamatnya sama
            }
            $lastMapLink = $mapLink;

            // 3. Kepemilikan (Kalau kosong ngikut atasnya)
            $kepemilikan = !empty($data['kepemilikan']) ? $data['kepemilikan'] : $lastKepemilikan;
            $lastKepemilikan = $kepemilikan;

            // 4. Status (Bisa baca centang/silang)
            $rawStatus = strtolower($data['status'] ?? '');
            $status = 'tersedia';
            if (
                str_contains($rawStatus, 'terisi') || 
                str_contains($rawStatus, 'centang') || 
                $rawStatus === 'v' || $rawStatus === '✓' || $rawStatus === '✔' || $rawStatus === '1'
            ) {
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

            try {
                // 6. Generate kode urut (Kode BB di excel diabaikan)
                $code = Billboard::generateCode($jenis, $city, $sisi);

                Billboard::create([
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
                ]);

                $imported++;
            } catch (\Exception $e) {
                $errors++;
            }
        }

        $msg = "$imported billboard berhasil diimport dengan kode urut otomatis.";
        if ($errors > 0) {
            $msg .= " $errors baris gagal diproses.";
        }

        return Redirect::route('admin.billboards.index')->with('success', $msg);
    }
}
