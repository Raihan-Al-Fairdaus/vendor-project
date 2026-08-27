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
        return view('admin.billboards.create');
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

    /**
     * Import billboards dari file Excel (.xlsx / .xls / .csv).
     * Kolom header: jenis | city | sisi | ukuran | orientasi | kepemilikan | address | map_link | status
     */
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

        // Header dari baris 1
        $header = [];
        for ($col = 1; $col <= $highestColumnIndex; $col++) {
            $value = $sheet->getCell([$col, 1])->getValue();
            $header[] = trim(strtolower($value ?? ''));
        }

        $imported = 0;
        $errors   = 0;

        for ($row = 2; $row <= $highestRow; $row++) {
            $rowData = [];
            $hasData = false;

            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $val = $sheet->getCell([$col, $row])->getValue();
                if ($val !== null && trim($val) !== '') {
                    $hasData = true;
                }
                $rowData[] = $val;
            }

            if (!$hasData) {
                continue;
            }

            $data = [];
            foreach ($header as $index => $key) {
                if ($key !== '' && isset($rowData[$index])) {
                    $data[$key] = $rowData[$index];
                }
            }

            // City is required
            if (empty(trim($data['city'] ?? ''))) {
                $errors++;
                continue;
            }

            try {
                $jenis = isset($data['jenis']) && in_array(trim(strtolower($data['jenis'])), ['billboard', 'midiboard'])
                    ? trim(strtolower($data['jenis']))
                    : 'billboard';

                $sisi = isset($data['sisi']) && in_array((int) $data['sisi'], [1, 2])
                    ? (int) $data['sisi']
                    : 1;

                $orientasi = isset($data['orientasi']) && in_array(trim(strtolower($data['orientasi'])), ['portrait', 'landscape'])
                    ? trim(strtolower($data['orientasi']))
                    : 'landscape';

                $status = isset($data['status']) && in_array(trim($data['status']), ['tersedia', 'terisi'])
                    ? trim($data['status'])
                    : 'tersedia';

                $code = Billboard::generateCode($jenis, trim($data['city']), $sisi);

                Billboard::create([
                    'code'         => $code,
                    'name'         => $code,
                    'jenis'        => $jenis,
                    'city'         => trim($data['city']),
                    'sisi'         => $sisi,
                    'ukuran'       => trim($data['ukuran'] ?? '') ?: null,
                    'orientasi'    => $orientasi,
                    'kepemilikan'  => trim($data['kepemilikan'] ?? '') ?: 'DNA Advertising',
                    'address'      => trim($data['address'] ?? ''),
                    'map_link'     => trim($data['map_link'] ?? '') ?: null,
                    'status'       => $status,
                ]);

                $imported++;
            } catch (\Exception $e) {
                $errors++;
            }
        }

        $msg = "$imported billboard berhasil diimport.";
        if ($errors > 0) {
            $msg .= " $errors baris dilewati (tidak valid/error).";
        }

        return Redirect::route('admin.billboards.index')->with('success', $msg);
    }
}
