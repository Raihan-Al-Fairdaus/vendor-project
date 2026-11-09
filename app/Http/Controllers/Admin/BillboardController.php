<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Billboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
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

        // Upload simulasi jika ada
        if ($request->hasFile('simulation_file')) {
            $request->validate([
                'simulation_file' => 'file|mimes:jpg,jpeg,png,webp,mp4,mov,webm,pdf|max:51200',
            ]);
            $file = $request->file('simulation_file');
            $ext  = strtolower($file->getClientOriginalExtension());
            if (in_array($ext, ['mp4', 'mov', 'webm'])) {
                $data['simulation_type'] = 'video';
            } elseif ($ext === 'pdf') {
                $data['simulation_type'] = 'pdf';
            } else {
                $data['simulation_type'] = 'image';
            }
            $data['simulation_path'] = $this->storeSimulationFile($file);
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

        // Upload atau ganti simulasi jika ada
        if ($request->hasFile('simulation_file')) {
            $request->validate([
                'simulation_file' => 'file|mimes:jpg,jpeg,png,webp,mp4,mov,webm,pdf|max:51200',
            ]);
            if ($billboard->simulation_path) {
                $this->removeSimulationFile($billboard->simulation_path);
            }
            $file = $request->file('simulation_file');
            $ext  = strtolower($file->getClientOriginalExtension());
            if (in_array($ext, ['mp4', 'mov', 'webm'])) {
                $data['simulation_type'] = 'video';
            } elseif ($ext === 'pdf') {
                $data['simulation_type'] = 'pdf';
            } else {
                $data['simulation_type'] = 'image';
            }
            $data['simulation_path'] = $this->storeSimulationFile($file);
        }

        $billboard->update($data);

        return Redirect::route('admin.billboards.index')
            ->with('success', 'Billboard berhasil diupdate.');
    }

    /**
     * Upload or replace simulation directly.
     */
    public function uploadSimulation(Request $request, Billboard $billboard)
    {
        $request->validate([
            'simulation_file' => 'required|file|mimes:jpg,jpeg,png,webp,mp4,mov,webm,pdf|max:51200',
        ], [
            'simulation_file.required' => 'Pilih file simulasi (PDF, gambar, atau video) terlebih dahulu.',
            'simulation_file.mimes'    => 'Format simulasi harus PDF, gambar (JPG, PNG, WEBP), atau video (MP4, MOV, WEBM).',
            'simulation_file.max'      => 'Ukuran file simulasi maksimal 50MB.',
        ]);

        if ($billboard->simulation_path) {
            $this->removeSimulationFile($billboard->simulation_path);
        }

        $file = $request->file('simulation_file');
        $ext  = strtolower($file->getClientOriginalExtension());
        if (in_array($ext, ['mp4', 'mov', 'webm'])) {
            $type = 'video';
        } elseif ($ext === 'pdf') {
            $type = 'pdf';
        } else {
            $type = 'image';
        }
        $path = $this->storeSimulationFile($file);

        $billboard->update([
            'simulation_path' => $path,
            'simulation_type' => $type,
        ]);

        return Redirect::back()->with('success', "Simulasi untuk billboard {$billboard->code} berhasil diunggah.");
    }

    /**
     * Delete simulation from billboard.
     */
    public function deleteSimulation(Billboard $billboard)
    {
        if ($billboard->simulation_path) {
            $this->removeSimulationFile($billboard->simulation_path);
        }

        $billboard->update([
            'simulation_path' => null,
            'simulation_type' => null,
        ]);

        return Redirect::back()->with('success', "Simulasi untuk billboard {$billboard->code} berhasil dihapus.");
    }

    /**
     * Helper simpan file simulasi ke Supabase (atau fallback ke public disk).
     */
    protected function storeSimulationFile($file): string
    {
        if (config('filesystems.disks.supabase.key')) {
            try {
                return Storage::disk('supabase')->putFile('simulations', $file, 'public');
            } catch (\Exception $e) {
                // fallback
            }
        }
        return $file->store('simulations', 'public');
    }

    /**
     * Helper hapus file simulasi dari storage.
     */
    protected function removeSimulationFile(string $path): void
    {
        if (config('filesystems.disks.supabase.key')) {
            try {
                Storage::disk('supabase')->delete($path);
            } catch (\Exception $e) {}
        }
        try {
            Storage::disk('public')->delete($path);
        } catch (\Exception $e) {}
    }

    /**
     * Delete a billboard.
     */
    public function destroy(Billboard $billboard)
    {
        if ($billboard->simulation_path) {
            $this->removeSimulationFile($billboard->simulation_path);
        }
        $billboard->delete();
        return Redirect::back()->with('success', 'Billboard berhasil dihapus.');
    }

    /**
     * Auto match simulation file with billboard in database and attach photo.
     */
    public function autoMatchSimulation(Request $request)
    {
        // 0. BATCH PRE-CHECK INSTAN (Cek kilat dalam hitungan milidetik tanpa kirim file fisik)
        if ($request->input('mode') === 'batch_precheck') {
            $filenames = (array) $request->input('filenames', []);
            $results = [];

            $allBillboards = Billboard::select('id', 'code', 'name', 'address', 'city', 'simulation_path')->get();
            $byCode = [];
            $byNum = [];

            foreach ($allBillboards as $b) {
                $codeClean = strtoupper(ltrim($b->code, '#'));
                $byCode[$codeClean] = $b;
                if (preg_match('/\b0*(\d{3,5})\b/', $b->code, $m)) {
                    $byNum[$m[1]][] = $b;
                }
            }

            foreach ($filenames as $fname) {
                $found = null;
                // Pola kode lengkap: misal 00182-SBY-I
                if (preg_match('/\b(\d{4,5}-[A-Z]{3}-[I|V|X]+)\b/i', $fname, $m)) {
                    $targetCode = strtoupper($m[1]);
                    $found = $byCode[$targetCode] ?? null;
                }
                // Pola nomor titik unik (3-5 digit)
                if (!$found && preg_match('/\b0*(\d{3,5})\b/', $fname, $m)) {
                    $num = $m[1];
                    if (isset($byNum[$num])) {
                        if (count($byNum[$num]) === 1) {
                            $found = $byNum[$num][0];
                        } else {
                            $cityMatch = null;
                            if (stripos($fname, 'surabaya') !== false || stripos($fname, 'sby') !== false) {
                                $cityMatch = 'SURABAYA';
                            } elseif (stripos($fname, 'sidoarjo') !== false || stripos($fname, 'sda') !== false) {
                                $cityMatch = 'SIDOARJO';
                            } elseif (stripos($fname, 'gresik') !== false || stripos($fname, 'grk') !== false) {
                                $cityMatch = 'GRESIK';
                            }
                            if ($cityMatch) {
                                foreach ($byNum[$num] as $cand) {
                                    if (strtoupper($cand->city) === $cityMatch) {
                                        $found = $cand;
                                        break;
                                    }
                                }
                            }
                            if (!$found) {
                                $found = $byNum[$num][0];
                            }
                        }
                    }
                }

                if ($found) {
                    if (!empty($found->simulation_path)) {
                        $results[$fname] = [
                            'status'  => 'skipped',
                            'message' => "Titik {$found->code} sudah memiliki simulasi (dilewati).",
                            'code'    => $found->code,
                            'address' => $found->address,
                        ];
                    } else {
                        $results[$fname] = [
                            'status'  => 'needs_upload',
                            'code'    => $found->code,
                            'address' => $found->address,
                        ];
                    }
                } else {
                    $results[$fname] = [
                        'status' => 'unknown', // Perlu dibedah teks/foto untuk fuzzy matching
                    ];
                }
            }

            return response()->json([
                'status'   => 'success',
                'precheck' => $results,
            ]);
        }

        $request->validate([
            'simulation_file' => 'required|file|max:51200',
            'extracted_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
            'filename'        => 'nullable|string',
            'extracted_text'  => 'nullable|string',
        ]);

        $file = $request->file('simulation_file');
        $rawFilename = $request->input('filename') ?: $file->getClientOriginalName();
        $ext = strtolower($file->getClientOriginalExtension());
        $extractedText = $request->input('extracted_text', '');

        // 1. Tentukan jenis simulasi
        if (in_array($ext, ['mp4', 'mov', 'webm'])) {
            $type = 'video';
        } elseif ($ext === 'pdf') {
            $type = 'pdf';
        } else {
            $type = 'image';
        }

        // 2. LOGIKA PENCOCOKAN BILLBOARD
        $billboard = null;

        // A. Coba cocokkan dengan Pola Kode Lengkap: misal 00182-SBY-I, 00148-SDA-I, 01360-SBY-I
        if (preg_match('/\b(\d{4,5}-[A-Z]{3}-[I|V|X]+)\b/i', $rawFilename, $m)) {
            $code = strtoupper($m[1]);
            $billboard = Billboard::where('code', $code)
                ->orWhere('code', '#' . $code)
                ->first();
        }

        // B. Coba cocokkan jika ada nomor titik unik (3-5 digit) dalam nama file
        if (!$billboard && preg_match('/\b0*(\d{3,5})\b/', $rawFilename, $m)) {
            $num = $m[1];
            $cityMatch = null;
            if (stripos($rawFilename, 'surabaya') !== false || stripos($rawFilename, 'sby') !== false) {
                $cityMatch = 'SURABAYA';
            } elseif (stripos($rawFilename, 'sidoarjo') !== false || stripos($rawFilename, 'sda') !== false) {
                $cityMatch = 'SIDOARJO';
            } elseif (stripos($rawFilename, 'gresik') !== false || stripos($rawFilename, 'grk') !== false) {
                $cityMatch = 'GRESIK';
            }

            $query = Billboard::query();
            if ($cityMatch) {
                $query->where('city', $cityMatch);
            }
            $query->where(function ($q) use ($num) {
                $q->where('code', 'like', "%{$num}%")
                  ->orWhere('name', 'like', "%{$num}%");
            });
            $billboard = $query->first();
        }

        // C. Coba cocokkan berdasarkan Nama Jalan / Alamat (Fuzzy matching)
        if (!$billboard) {
            $combinedText = strtolower($rawFilename . ' ' . $extractedText);
            $combinedText = str_replace(
                ['rachmat', 'jl.', 'jl ', 'jalan ', '_', '-', '.', ','],
                ['rahmat', '', '', '', ' ', ' ', ' ', ' '],
                $combinedText
            );

            $candidates = Billboard::all(['id', 'code', 'name', 'address', 'city']);
            $bestScore = 0;
            $bestCandidate = null;

            foreach ($candidates as $cand) {
                $candAddr = strtolower(str_replace(
                    ['rachmat', 'jl.', 'jl ', 'jalan ', '.', ',', '-'],
                    ['rahmat', '', '', '', ' ', ' ', ' '],
                    $cand->address
                ));

                $words = array_filter(explode(' ', preg_replace('/[^a-z0-9]/', ' ', $candAddr)), function ($w) {
                    return strlen($w) >= 4 && !in_array($w, [
                        'raya', 'nomor', 'surabaya', 'sidoarjo', 'gresik', 'timur', 'jawa', 'kecamatan', 'kabupaten', 'kelurahan', 'kota'
                    ]);
                });

                if (!empty($words)) {
                    $matchedWords = 0;
                    foreach ($words as $w) {
                        if (str_contains($combinedText, $w)) {
                            $matchedWords++;
                        }
                    }
                    $score = $matchedWords / count($words);
                    if ($score > $bestScore && $score >= 0.5) {
                        $bestScore = $score;
                        $bestCandidate = $cand;
                    }
                }
            }

            if ($bestCandidate) {
                $billboard = $bestCandidate;
            }
        }

        // Jika tidak ditemukan billboard yang cocok
        if (!$billboard) {
            return response()->json([
                'status'   => 'unmatched',
                'message'  => "Tidak ditemukan titik billboard yang cocok untuk file '{$rawFilename}'.",
                'filename' => $rawFilename,
            ], 200);
        }

        // Cek opsi: Lewati jika billboard sudah memiliki simulasi (anti-tertimpa)
        if ($request->boolean('skip_existing', true) && !empty($billboard->simulation_path)) {
            return response()->json([
                'status'          => 'skipped',
                'message'         => "Titik {$billboard->code} sudah memiliki simulasi (dilewati).",
                'billboard_id'    => $billboard->id,
                'code'            => $billboard->code,
                'name'            => $billboard->name,
                'city'            => $billboard->city,
                'address'         => $billboard->address,
                'filename'        => $rawFilename,
            ]);
        }

        // 3. SIMPAN SIMULASI
        if ($billboard->simulation_path) {
            $this->removeSimulationFile($billboard->simulation_path);
        }
        $simPath = $this->storeSimulationFile($file);
        $billboard->simulation_path = $simPath;
        $billboard->simulation_type = $type;

        // 4. SIMPAN FOTO COVER SIMULASI
        $codeClean = ltrim($billboard->code, '#');
        $photoSaved = false;

        if ($request->hasFile('extracted_photo')) {
            $photoFile = $request->file('extracted_photo');
            $photoPath = null;
            if (config('filesystems.disks.supabase.key')) {
                try {
                    $photoPath = Storage::disk('supabase')->putFileAs('billboards/photos', $photoFile, "{$codeClean}.jpg", 'public');
                } catch (\Throwable $e) {}
            }
            if (!$photoPath) {
                $photoPath = $photoFile->storeAs('billboards/photos', "{$codeClean}.jpg", 'public');
            }

            try {
                $destDir = public_path('images/billboards');
                if (!is_dir($destDir)) {
                    @mkdir($destDir, 0777, true);
                }
                @copy($photoFile->getRealPath(), "{$destDir}/{$codeClean}.jpg");
            } catch (\Throwable $e) {}

            $billboard->photo_path = $photoPath;
            $photoSaved = true;
        } elseif ($type === 'image') {
            $photoPath = null;
            if (config('filesystems.disks.supabase.key')) {
                try {
                    $photoPath = Storage::disk('supabase')->putFileAs('billboards/photos', $file, "{$codeClean}.{$ext}", 'public');
                } catch (\Throwable $e) {}
            }
            if (!$photoPath) {
                $photoPath = $file->storeAs('billboards/photos', "{$codeClean}.{$ext}", 'public');
            }
            try {
                $destDir = public_path('images/billboards');
                if (!is_dir($destDir)) {
                    @mkdir($destDir, 0777, true);
                }
                @copy($file->getRealPath(), "{$destDir}/{$codeClean}.{$ext}");
            } catch (\Throwable $e) {}

            $billboard->photo_path = $photoPath;
            $photoSaved = true;
        }

        $billboard->save();

        return response()->json([
            'status'          => 'matched',
            'message'         => "Berhasil dicocokkan ke Titik {$billboard->code}!",
            'billboard_id'    => $billboard->id,
            'code'            => $billboard->code,
            'name'            => $billboard->name,
            'city'            => $billboard->city,
            'address'         => $billboard->address,
            'simulation_type' => $type,
            'has_photo'       => $photoSaved,
            'filename'        => $rawFilename,
        ]);
    }

    /**
     * Delete / Reset all simulations from all billboards.
     */
    public function deleteAllSimulations(Request $request)
    {
        $count = Billboard::whereNotNull('simulation_path')
            ->orWhereNotNull('photo_path')
            ->count();

        // Bersihkan file simulasi dari storage Supabase/local
        $simulations = Billboard::whereNotNull('simulation_path')->pluck('simulation_path');
        foreach ($simulations as $path) {
            $this->removeSimulationFile($path);
        }

        // Hapus juga file foto yang disimpan di storage jika ada
        $photos = Billboard::whereNotNull('photo_path')->pluck('photo_path');
        foreach ($photos as $photoPath) {
            if (config('filesystems.disks.supabase.key')) {
                try {
                    Storage::disk('supabase')->delete($photoPath);
                } catch (\Throwable $e) {}
            }
            try {
                Storage::disk('public')->delete($photoPath);
            } catch (\Throwable $e) {}
        }

        // Kosongkan kolom di database
        Billboard::query()->update([
            'simulation_path' => null,
            'simulation_type' => null,
            'photo_path'      => null,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status'  => 'success',
                'message' => "Berhasil menghapus seluruh simulasi dan foto dari {$count} titik billboard.",
                'count'   => $count,
            ]);
        }

        return Redirect::back()->with('success', "Berhasil menghapus seluruh data simulasi dari {$count} titik billboard.");
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
