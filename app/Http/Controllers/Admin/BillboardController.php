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

        $file        = $request->file('file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet       = $spreadsheet->getActiveSheet();
        $rows        = $sheet->toArray(null, true, true, false);

        if (empty($rows)) {
            return Redirect::route('admin.billboards.index')
                ->with('error', 'File Excel kosong atau tidak valid.');
        }

        // Baris pertama = header, jadikan lowercase & trim
        $header   = array_map('trim', array_map('strtolower', $rows[0]));
        $imported = 0;
        $errors   = 0;

        foreach (array_slice($rows, 1) as $row) {
            // Lewati baris yang semua kolomnya kosong
            if (!array_filter($row, fn($v) => $v !== null && $v !== '')) {
                continue;
            }

            $data = array_combine($header, array_slice($row, 0, count($header)));

            if (!$data || empty(trim($data['name'] ?? ''))) {
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
                        'status'   => in_array(trim($data['status'] ?? ''), ['tersedia', 'terisi'])
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
            $msg .= " $errors baris dilewati (duplikat/tidak valid).";
        }

        return Redirect::route('admin.billboards.index')->with('success', $msg);
    }
}
