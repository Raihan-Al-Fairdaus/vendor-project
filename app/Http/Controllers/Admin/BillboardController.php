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
            'city'     => 'required|string|max:255',
            'name'     => 'required|string|max:255|unique:billboards,name',
            'address'  => 'required|string',
            'map_link' => 'nullable|string',
            'status'   => 'required|in:tersedia,terisi',
        ]);
        Billboard::create($data);
        return Redirect::route('admin.billboards.index')->with('success', 'Billboard berhasil ditambahkan.');
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
            'city'     => 'required|string|max:255',
            'name'     => 'required|string|max:255|unique:billboards,name,' . $billboard->id,
            'address'  => 'required|string',
            'map_link' => 'nullable|string',
            'status'   => 'required|in:tersedia,terisi',
        ]);
        $billboard->update($data);
        return Redirect::route('admin.billboards.index')->with('success', 'Billboard berhasil diupdate.');
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
     * Import billboards dari file Excel (.xlsx / .xls).
     * Kolom header yang diperlukan: city | name | address | map_link | status
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();

        try {
            // Gunakan reader spesifik & setReadDataOnly(true) agar loading super cepat
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

        // Ambil header dari baris 1
        $header = [];
        for ($col = 1; $col <= $highestColumnIndex; $col++) {
            $value = $sheet->getCell([$col, 1])->getValue();
            $header[] = trim(strtolower($value ?? ''));
        }

        $imported = 0;
        $errors   = 0;

        // Loop baris 2 sampai baris terakhir yang berisi data
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

            // Lewati jika baris kosong
            if (!$hasData) {
                continue;
            }

            // Map data
            $data = [];
            foreach ($header as $index => $key) {
                if ($key !== '' && isset($rowData[$index])) {
                    $data[$key] = $rowData[$index];
                }
            }

            if (empty(trim($data['name'] ?? ''))) {
                $errors++;
                continue;
            }

            try {
                Billboard::updateOrCreate(
                    ['name' => trim($data['name'])],
                    [
                        'city'     => trim($data['city']     ?? ''),
                        'address'  => trim($data['address']  ?? ''),
                        'map_link' => trim($data['map_link'] ?? '') ?: null,
                        'status'   => isset($data['status']) && in_array(trim($data['status']), ['tersedia', 'terisi'])
                                        ? trim($data['status'])
                                        : 'tersedia',
                    ]
                );
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
