<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Billboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

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
            'city'      => 'required|string|max:255',
            'name'      => 'required|string|max:255|unique:billboards,name',
            'address'   => 'required|string',
            'latitude'  => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status'    => 'required|in:tersedia,terisi',
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
            'city'      => 'required|string|max:255',
            'name'      => 'required|string|max:255|unique:billboards,name,' . $billboard->id,
            'address'   => 'required|string',
            'latitude'  => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status'    => 'required|in:tersedia,terisi',
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
     * Import billboards from CSV/Excel.
     * Expected columns: city,name,address,latitude,longitude,status
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);
        $path = $request->file('file')->getRealPath();
        $header = null;
        $imported = 0;
        if (($handle = fopen($path, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (!$header) {
                    $header = $row;
                    continue;
                }
                $data = array_combine($header, $row);
                if ($data) {
                    Billboard::updateOrCreate(
                        ['name' => $data['name']],
                        [
                            'city'      => $data['city'] ?? '',
                            'address'   => $data['address'] ?? '',
                            'latitude'  => $data['latitude'] ?? null,
                            'longitude' => $data['longitude'] ?? null,
                            'status'    => $data['status'] ?? Billboard::STATUS_TERSEDIA,
                        ]
                    );
                    $imported++;
                }
            }
            fclose($handle);
        }
        return Redirect::route('admin.billboards.index')->with('success', "$imported billboards diimport.");
    }
}
?>
