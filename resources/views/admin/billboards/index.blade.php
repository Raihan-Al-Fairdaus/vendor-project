@extends('layouts.admin')

@section('page_title', 'Billboards')

@section('content')

{{-- Top header bar: title + action button on dark navy bg --}}
<div class="admin-page-header" style="background-color: #1b3a60; padding: 1.75rem 2rem 1.5rem; display: flex; justify-content: space-between; align-items: flex-start;">
    <div>
        <h1 style="color: #ffffff; font-size: 1.5rem; font-weight: 700; margin: 0 0 0.35rem 0; line-height: 1.2;">Billboards</h1>
        <p style="color: rgba(255,255,255,0.55); font-size: 0.85rem; margin: 0;">Kelola data billboard dan status ketersediaannya.</p>
    </div>
    <div style="display: flex; gap: 0.65rem; align-items: center; flex-wrap: wrap;">
        <button type="button" onclick="openAutoMatchModal()" style="background-color: #10b981; color: #ffffff; border: none; padding: 0.55rem 1.15rem; border-radius: 6px; font-weight: 600; font-size: 0.82rem; display: flex; align-items: center; gap: 0.45rem; cursor: pointer; box-shadow: 0 2px 5px rgba(16,185,129,0.3); transition: all 0.2s;">
            <i class="fa-solid fa-wand-magic-sparkles"></i> Auto-Match Simulasi (Drag Folder)
        </button>
        <button type="button" onclick="handleDeleteAllSimulations()" style="background-color: rgba(239, 68, 68, 0.25); color: #fecaca; border: 1px solid rgba(239, 68, 68, 0.4); padding: 0.55rem 0.95rem; border-radius: 6px; font-weight: 600; font-size: 0.82rem; display: flex; align-items: center; gap: 0.4rem; cursor: pointer; transition: all 0.2s;">
            <i class="fa-solid fa-trash-can"></i> Hapus All Simulasi
        </button>
        <a href="{{ route('admin.billboards.create') }}" style="background-color: #ffffff; color: #1e293b; text-decoration: none; border: none; padding: 0.55rem 1rem; border-radius: 6px; font-weight: 600; font-size: 0.82rem; display: flex; align-items: center; gap: 0.4rem; cursor: pointer;">
            <i class="fa-solid fa-plus" style="color: #1e293b;"></i> Tambah Billboard
        </a>
    </div>
</div>

{{-- Undo Last Import Banner --}}
@if(session('last_import_count'))
<div style="padding: 0 2rem 1.25rem;">
    <div style="background-color: #ffffff; border-radius: 10px; border-left: 4px solid #ef4444; padding: 1rem 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <div>
            <div style="font-size: 0.95rem; color: #ef4444; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                <span>📦</span> Baru saja import {{ session('last_import_count') }} {{ session('last_import_jenis', 'data') }}
            </div>
            <div style="font-size: 0.8rem; color: #64748b; margin-top: 0.25rem;">
                Salah import? Klik tombol di samping untuk membatalkan dan menghapus {{ session('last_import_count') }} data terakhir.
            </div>
        </div>
        <form action="{{ route('admin.billboards.undoImport') }}" method="POST" onsubmit="return confirm('⚠️ YAKIN ingin menghapus {{ session('last_import_count') }} data terakhir?\n\nAksi ini TIDAK BISA dibatalkan!');" style="margin: 0;">
            @csrf
            <input type="hidden" name="count" value="{{ session('last_import_count') }}">
            <button type="submit" style="background-color: #ef4444; color: #ffffff; border: none; padding: 0.55rem 1.25rem; border-radius: 6px; font-weight: 700; font-size: 0.85rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem;">
                🗑️ Undo Import (Hapus {{ session('last_import_count') }} data)
            </button>
        </form>
    </div>
</div>
@endif

{{-- Import Box --}}
<div style="padding: 0 2rem 1.25rem;">
    <div style="background-color: #ffffff; border-radius: 10px; padding: 1.25rem 1.5rem;">
        <h3 style="margin: 0 0 1rem 0; font-size: 1rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fa-solid fa-file-excel" style="color: #10b981;"></i> Import Billboard dari Excel
        </h3>
        <form action="{{ route('admin.billboards.import') }}" method="POST" enctype="multipart/form-data" style="display: flex; gap: 1rem; align-items: flex-start; flex-wrap: wrap;" id="importForm" onsubmit="return confirmImport()">
            @csrf
            <div style="flex: 1; min-width: 260px;">
                <input type="file" name="files[]" accept=".xlsx,.xls,.csv" multiple required style="width: 100%; border: 1px solid #e2e8f0; border-radius: 6px; padding: 0.45rem 0.75rem; color: #0f172a; background: #ffffff; font-size: 0.85rem; outline: none; box-sizing: border-box;">
                <div style="color: #64748b; font-size: 0.75rem; margin-top: 0.35rem;">
                    Bisa pilih banyak file sekaligus (Blok semua file di dalam folder). Kode otomatis.
                </div>
            </div>
            <div style="min-width: 200px;">
                <select name="jenis_import" id="jenisImport" required style="width: 100%; height: 38px; border: 1px solid #f59e0b; border-radius: 6px; padding: 0 0.75rem; color: #0f172a; background: #ffffff; font-size: 0.85rem; font-weight: 600; outline: none; box-sizing: border-box; cursor: pointer;">
                    <option value="" disabled selected>⚠️ -- PILIH JENIS --</option>
                    <option value="billboard">📋 Billboard</option>
                    <option value="midiboard">📺 Midiboard</option>
                </select>
                <div style="color: #d97706; font-size: 0.75rem; margin-top: 0.35rem; font-weight: 600;">
                    ⬆️ WAJIB pilih jenis dulu!
                </div>
            </div>
            <div>
                <button type="submit" style="background-color: #1b3a60; color: #ffffff; border: none; border-radius: 6px; padding: 0.55rem 1.25rem; font-weight: 600; font-size: 0.85rem; display: flex; align-items: center; gap: 0.4rem; cursor: pointer; height: 38px; box-sizing: border-box;">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Upload &amp; Import
                </button>
            </div>
        </form>
        <script>
            function confirmImport() {
                var jenis = document.getElementById('jenisImport').value;
                if (!jenis) {
                    alert('⚠️ Kamu HARUS pilih jenis (Billboard / Midiboard) dulu!');
                    return false;
                }
                var label = jenis === 'midiboard' ? 'MIDIBOARD' : 'BILLBOARD';
                return confirm('Kamu akan mengimport data sebagai: ' + label + '\n\nApakah sudah benar?');
            }
        </script>
    </div>
</div>

{{-- Search & Filter Box --}}
<div style="padding: 0 2rem 1.25rem;">
    <form action="{{ route('admin.billboards.index') }}" method="GET" style="background-color: #ffffff; border-radius: 10px; padding: 0.85rem 1rem; display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
        <div style="flex: 2; min-width: 220px; display: flex; align-items: center; gap: 0.5rem; border: 1px solid #e2e8f0; border-radius: 6px; padding: 0.5rem 0.75rem;">
            <i class="fa-solid fa-search" style="color: #94a3b8; font-size: 0.85rem;"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode, alamat billboard..." style="border: none; outline: none; width: 100%; color: #0f172a; background: transparent; font-size: 0.85rem;" onkeydown="if(event.key==='Enter'){this.form.submit();}">
        </div>

        <select name="city" style="border: 1px solid #e2e8f0; border-radius: 6px; padding: 0.5rem 0.75rem; color: #0f172a; outline: none; background: #ffffff; cursor: pointer; font-size: 0.85rem; min-width: 150px;" onchange="this.form.submit()">
            <option value="">Semua Kota</option>
            @foreach($cityCounts as $cityName => $counts)
                @php
                    $bb = $counts['billboard'] ?? 0;
                    $mb = $counts['midiboard'] ?? 0;
                    $parts = [];
                    if ($bb > 0) $parts[] = $bb . ' BB';
                    if ($mb > 0) $parts[] = $mb . ' MB';
                    $label = strtoupper($cityName) . ' (' . implode(' · ', $parts) . ')';
                @endphp
                <option value="{{ $cityName }}" {{ request('city') === $cityName ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>

        <select name="jenis" style="border: 1px solid #e2e8f0; border-radius: 6px; padding: 0.5rem 0.75rem; color: #0f172a; outline: none; background: #ffffff; cursor: pointer; font-size: 0.85rem;" onchange="this.form.submit()">
            <option value="">Semua Jenis</option>
            <option value="billboard" {{ request('jenis') === 'billboard' ? 'selected' : '' }}>Billboard</option>
            <option value="midiboard" {{ request('jenis') === 'midiboard' ? 'selected' : '' }}>Midiboard</option>
        </select>

        <select name="status" style="border: 1px solid #e2e8f0; border-radius: 6px; padding: 0.5rem 0.75rem; color: #0f172a; outline: none; background: #ffffff; cursor: pointer; font-size: 0.85rem;" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="tersedia" {{ request('status') === 'tersedia' ? 'selected' : '' }}>🟢 Tersedia</option>
            <option value="terisi" {{ request('status') === 'terisi' ? 'selected' : '' }}>🔴 Terisi</option>
        </select>

        <button type="submit" style="background-color: #1b3a60; color: #ffffff; border: none; border-radius: 6px; padding: 0.55rem 1.25rem; font-weight: 600; display: flex; align-items: center; gap: 0.4rem; cursor: pointer; font-size: 0.85rem;">
            <i class="fa-solid fa-search"></i> Cari
        </button>

        @if(request()->filled('search') || request()->filled('city') || request()->filled('jenis') || request()->filled('status'))
            <a href="{{ route('admin.billboards.index') }}" style="background: #ffffff; border: 1px solid #e2e8f0; color: #0f172a; border-radius: 6px; padding: 0.5rem 1.25rem; font-weight: 600; display: flex; align-items: center; gap: 0.4rem; cursor: pointer; text-decoration: none; font-size: 0.85rem;">
                <i class="fa-solid fa-rotate-right" style="color: #64748b;"></i> Reset
            </a>
        @endif
    </form>
</div>

{{-- Data Table --}}
<div style="padding: 0 2rem 1.25rem;">
    <div style="background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 1rem 1.25rem; font-size: 0.7rem; color: #1e3a8a; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">KODE</th>
                        <th style="padding: 1rem 1.25rem; font-size: 0.7rem; color: #1e3a8a; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">JENIS</th>
                        <th style="padding: 1rem 1.25rem; font-size: 0.7rem; color: #1e3a8a; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">KOTA</th>
                        <th style="padding: 1rem 1.25rem; font-size: 0.7rem; color: #1e3a8a; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">ALAMAT</th>
                        <th style="padding: 1rem 1.25rem; font-size: 0.7rem; color: #1e3a8a; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">UKURAN</th>
                        <th style="padding: 1rem 1.25rem; font-size: 0.7rem; color: #1e3a8a; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">SISI</th>
                        <th style="padding: 1rem 1.25rem; font-size: 0.7rem; color: #1e3a8a; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">STATUS</th>
                        <th style="padding: 1rem 1.25rem; font-size: 0.7rem; color: #1e3a8a; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">SIMULASI</th>
                        <th style="padding: 1rem 1.25rem; font-size: 0.7rem; color: #1e3a8a; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($billboards as $board)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 0.85rem 1.25rem; font-weight: 700; font-family: monospace; letter-spacing: 0.5px; color: #0f172a; font-size: 0.85rem;">
                            {{ $board->code ?? '-' }}
                        </td>
                        <td style="padding: 0.85rem 1.25rem;">
                            <span style="
                                background: {{ $board->jenis === 'midiboard' ? 'rgba(245,158,11,0.15)' : 'rgba(59,130,246,0.15)' }};
                                color: {{ $board->jenis === 'midiboard' ? '#d97706' : '#2563eb' }};
                                padding: 0.25rem 0.6rem;
                                border-radius: 6px;
                                font-size: 0.75rem;
                                font-weight: 600;
                            ">{{ ucfirst($board->jenis ?? 'billboard') }}</span>
                        </td>
                        <td style="padding: 0.85rem 1.25rem; font-weight: 600; color: #0f172a; font-size: 0.85rem;">
                            {{ $board->city }}
                        </td>
                        <td style="padding: 0.85rem 1.25rem; color: #0f172a; font-size: 0.85rem;">
                            {{ $board->address }}
                        </td>
                        <td style="padding: 0.85rem 1.25rem; color: #0f172a; font-size: 0.85rem;">
                            {{ $board->ukuran ?? '-' }}
                        </td>
                        <td style="padding: 0.85rem 1.25rem; color: #0f172a; font-size: 0.85rem;">
                            {{ $board->sisi ?? 1 }}
                        </td>
                        <td style="padding: 0.85rem 1.25rem;">
                            @php
                                $isTersedia = $board->status === 'tersedia';
                                $bg = $isTersedia ? '#d1fae5' : '#fee2e2';
                                $color = $isTersedia ? '#059669' : '#dc2626';
                            @endphp
                            <span style="background-color: {{ $bg }}; color: {{ $color }}; padding: 0.3rem 0.65rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.3rem;">
                                <span style="font-size: 8px;">●</span> {{ ucfirst($board->status) }}
                            </span>
                        </td>
                        <td style="padding: 0.85rem 1.25rem;">
                            @if($board->simulation_path)
                                <div style="display: flex; align-items: center; gap: 0.4rem;">
                                    @if($board->is_video_simulation)
                                        <span style="background: #e0f2fe; color: #0284c7; padding: 0.25rem 0.6rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.3rem;">
                                            🎥 Video
                                        </span>
                                    @elseif($board->is_pdf_simulation)
                                        <span style="background: #fef3c7; color: #b45309; padding: 0.25rem 0.6rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.3rem;">
                                            📄 PDF
                                        </span>
                                    @else
                                        <span style="background: #f0fdf4; color: #16a34a; padding: 0.25rem 0.6rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.3rem;">
                                            🖼️ Gambar
                                        </span>
                                    @endif
                                    <a href="{{ $board->simulation_url }}" target="_blank" rel="noopener" style="color: #64748b; font-size: 0.8rem; text-decoration: none;" title="Buka File Simulasi">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </a>
                                </div>
                            @else
                                <span style="background: #f1f5f9; color: #94a3b8; padding: 0.25rem 0.6rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">
                                    Belum Ada
                                </span>
                            @endif
                        </td>
                        <td style="padding: 0.85rem 1.25rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <a href="{{ route('admin.billboards.edit', $board->id) }}" style="border: 1px solid #e2e8f0; color: #3b82f6; text-decoration: none; padding: 0.4rem 0.7rem; border-radius: 6px; font-size: 0.8rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.35rem; background: #ffffff;">
                                    <i class="fa-regular fa-pen-to-square"></i> Edit
                                </a>
                                <form action="{{ route('admin.billboards.destroy', $board->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus billboard ini?');" style="display: inline; margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="border: 1px solid #fee2e2; color: #ef4444; background: #ffffff; padding: 0.4rem 0.7rem; border-radius: 6px; font-size: 0.8rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.35rem; cursor: pointer;">
                                        <i class="fa-regular fa-trash-can"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="padding: 2.5rem; text-align: center; color: #64748b; font-size: 0.9rem;">
                            Belum ada data billboard.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Pagination --}}
<div style="padding: 0 2rem 2rem; display: flex; justify-content: center;">
    <style>
        .custom-pagination-container {
            background: #ffffff;
            border-radius: 50px;
            padding: 0.4rem 1.25rem;
            display: inline-flex;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        .custom-pagination {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .custom-pagination .mypill-item {
            margin: 0;
        }
        .custom-pagination .mypill-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #475569;
            font-weight: 600;
            text-decoration: none;
            border: none;
            background: transparent;
            min-width: 32px;
            height: 32px;
            border-radius: 50%;
            transition: all 0.2s;
            font-size: 0.85rem;
        }
        .custom-pagination .mypill-link:hover {
            color: #1e3a8a;
            background: rgba(30, 58, 138, 0.08);
        }
        .custom-pagination .mypill-item.active .mypill-link {
            background: #1e3a8a;
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(30, 58, 138, 0.3);
        }
        .custom-pagination .mypill-item.disabled .mypill-link {
            color: #cbd5e1;
            cursor: not-allowed;
            background: transparent;
        }
        .custom-pagination li:first-child .mypill-link,
        .custom-pagination li:last-child .mypill-link {
            border-radius: 20px;
            padding: 0 0.75rem;
            width: auto;
            color: #1e3a8a;
        }
        .custom-pagination li:first-child.disabled .mypill-link,
        .custom-pagination li:last-child.disabled .mypill-link {
            color: #cbd5e1;
        }
    </style>
    {{ $billboards->appends(request()->query())->links('admin.billboards.pagination') }}
</div>

{{-- MODAL AUTO-MATCH SIMULASI (DRAG FOLDER & BATCH UPLOAD) --}}
<div id="autoMatchModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 1.5rem;">
    <div style="background: #ffffff; border-radius: 14px; width: 100%; max-width: 820px; max-height: 90vh; display: flex; flex-direction: column; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2); overflow: hidden;">
        
        {{-- Header Modal --}}
        <div style="background: #1b3a60; padding: 1.25rem 1.5rem; display: flex; justify-content: space-between; align-items: center; color: #ffffff;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="background: rgba(255,255,255,0.15); width: 36px; height: 36px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.2rem;">⚡</div>
                <div>
                    <h3 style="margin: 0; font-size: 1.1rem; font-weight: 700; color: #ffffff;">Auto-Match &amp; Upload Simulasi Billboard</h3>
                    <p style="margin: 0; font-size: 0.78rem; color: rgba(255,255,255,0.7);">Membaca lokasi simulasi otomatis, kompresi foto, dan pasangkan ke titik billboard</p>
                </div>
            </div>
            <button type="button" onclick="closeAutoMatchModal()" style="background: transparent; border: none; color: #ffffff; font-size: 1.4rem; cursor: pointer; padding: 0.2rem 0.5rem; border-radius: 4px; line-height: 1;">&times;</button>
        </div>

        {{-- Body Modal --}}
        <div style="padding: 1.5rem; overflow-y: auto; flex: 1;">
            
            {{-- Drop Zone --}}
            <div id="matchDropZone" style="border: 2px dashed #94a3b8; border-radius: 12px; padding: 2.25rem 1.5rem; text-align: center; background: #f8fafc; cursor: pointer; transition: all 0.2s;">
                <div style="font-size: 3rem; margin-bottom: 0.5rem;">📁</div>
                <div style="font-size: 1.05rem; font-weight: 700; color: #1e293b; margin-bottom: 0.35rem;">
                    Drag &amp; Drop 1 Folder Penuh ke Sini
                </div>
                <div style="font-size: 0.85rem; color: #64748b; margin-bottom: 1.25rem;">
                    Contoh: Tarik langsung folder <b>"simulasi billboard surabaya"</b> atau kumpulan file PDF / Gambar
                </div>
                <div style="display: inline-flex; gap: 0.6rem; flex-wrap: wrap; justify-content: center;">
                    <button type="button" onclick="event.stopPropagation(); document.getElementById('matchFolderInput').click();" style="background: #1b3a60; color: #ffffff; border: none; padding: 0.5rem 1.15rem; border-radius: 6px; font-weight: 600; font-size: 0.82rem; cursor: pointer;">
                        📂 Pilih Folder dari Komputer
                    </button>
                    <button type="button" onclick="event.stopPropagation(); document.getElementById('matchFileInput').click();" style="background: #ffffff; color: #1b3a60; border: 1px solid #1b3a60; padding: 0.5rem 1.15rem; border-radius: 6px; font-weight: 600; font-size: 0.82rem; cursor: pointer;">
                        📄 Pilih File Satuan
                    </button>
                </div>
                <input type="file" id="matchFolderInput" webkitdirectory directory multiple style="display: none;">
                <input type="file" id="matchFileInput" multiple accept=".pdf,.jpg,.jpeg,.png,.webp,.mp4" style="display: none;">
            </div>

            {{-- Progress & Results Section --}}
            <div id="matchProcessSection" style="display: none; margin-top: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem;">
                    <span id="matchStatusText" style="font-size: 0.88rem; font-weight: 700; color: #1e293b;">Mempersiapkan antrean file...</span>
                    <span id="matchPercentText" style="font-size: 0.88rem; font-weight: 700; color: #2563eb;">0%</span>
                </div>
                <div style="background: #e2e8f0; border-radius: 999px; height: 10px; overflow: hidden; margin-bottom: 1rem;">
                    <div id="matchProgressBar" style="background: linear-gradient(90deg, #10b981, #2563eb); height: 100%; width: 0%; transition: width 0.25s;"></div>
                </div>

                {{-- Status Pills --}}
                <div style="display: flex; gap: 0.75rem; margin-bottom: 1rem; flex-wrap: wrap;">
                    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; padding: 0.4rem 0.85rem; font-size: 0.82rem; color: #15803d; font-weight: 600;">
                        🟢 Cocok &amp; Terpasang: <span id="statMatchedCount">0</span>
                    </div>
                    <div style="background: #fefce8; border: 1px solid #fef08a; border-radius: 6px; padding: 0.4rem 0.85rem; font-size: 0.82rem; color: #a16207; font-weight: 600;">
                        🟡 Tidak Ditemukan: <span id="statUnmatchedCount">0</span>
                    </div>
                    <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; padding: 0.4rem 0.85rem; font-size: 0.82rem; color: #b91c1c; font-weight: 600;">
                        🔴 Gagal Upload: <span id="statFailedCount">0</span>
                    </div>
                </div>

                {{-- Live Log Table --}}
                <div style="border: 1px solid #e2e8f0; border-radius: 8px; max-height: 250px; overflow-y: auto; background: #ffffff;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.8rem; text-align: left;">
                        <thead style="background: #f1f5f9; position: sticky; top: 0; border-bottom: 1px solid #e2e8f0;">
                            <tr>
                                <th style="padding: 0.55rem 0.75rem; color: #475569;">Nama File</th>
                                <th style="padding: 0.55rem 0.75rem; color: #475569;">Titik Billboard Cocok</th>
                                <th style="padding: 0.55rem 0.75rem; color: #475569;">Status</th>
                            </tr>
                        </thead>
                        <tbody id="matchLogTbody">
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- Footer Modal --}}
        <div style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <button type="button" onclick="handleDeleteAllSimulations()" style="background: transparent; color: #ef4444; border: 1px solid #fecaca; padding: 0.45rem 0.85rem; border-radius: 6px; font-weight: 600; font-size: 0.78rem; cursor: pointer; display: flex; align-items: center; gap: 0.35rem;">
                    <i class="fa-solid fa-trash-can"></i> Hapus All Simulasi
                </button>
                <span style="font-size: 0.75rem; color: #64748b;">
                    💡 Cover foto diekstrak &amp; dikompres otomatis di browser agar ringan.
                </span>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <button type="button" id="btnMatchClose" onclick="closeAutoMatchModal()" style="background: #e2e8f0; color: #475569; border: none; padding: 0.5rem 1.15rem; border-radius: 6px; font-weight: 600; font-size: 0.82rem; cursor: pointer;">
                    Tutup
                </button>
                <button type="button" id="btnMatchReload" onclick="window.location.reload()" style="display: none; background: #10b981; color: #ffffff; border: none; padding: 0.5rem 1.25rem; border-radius: 6px; font-weight: 700; font-size: 0.82rem; cursor: pointer;">
                    ✓ Selesai &amp; Refresh Halaman
                </button>
            </div>
        </div>

    </div>
</div>

{{-- Load PDF.js untuk ekstraksi cover & teks otomatis di browser --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    if (typeof pdfjsLib !== 'undefined') {
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    }

    const autoMatchModal = document.getElementById('autoMatchModal');
    const matchDropZone = document.getElementById('matchDropZone');
    const matchFolderInput = document.getElementById('matchFolderInput');
    const matchFileInput = document.getElementById('matchFileInput');
    const matchProcessSection = document.getElementById('matchProcessSection');
    const matchStatusText = document.getElementById('matchStatusText');
    const matchPercentText = document.getElementById('matchPercentText');
    const matchProgressBar = document.getElementById('matchProgressBar');
    const statMatchedCount = document.getElementById('statMatchedCount');
    const statUnmatchedCount = document.getElementById('statUnmatchedCount');
    const statFailedCount = document.getElementById('statFailedCount');
    const matchLogTbody = document.getElementById('matchLogTbody');
    const btnMatchReload = document.getElementById('btnMatchReload');
    const btnMatchClose = document.getElementById('btnMatchClose');

    let isProcessing = false;
    let matchedTotal = 0;
    let unmatchedTotal = 0;
    let failedTotal = 0;

    function openAutoMatchModal() {
        autoMatchModal.style.display = 'flex';
    }

    function closeAutoMatchModal() {
        if (isProcessing) {
            if (!confirm('Proses upload & pencocokan sedang berlangsung. Yakin ingin menutup?')) {
                return;
            }
        }
        autoMatchModal.style.display = 'none';
    }

    // Drag and Drop Event Listeners
    ['dragenter', 'dragover'].forEach(eventName => {
        matchDropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            matchDropZone.style.borderColor = '#2563eb';
            matchDropZone.style.background = '#eff6ff';
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        matchDropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            matchDropZone.style.borderColor = '#94a3b8';
            matchDropZone.style.background = '#f8fafc';
        }, false);
    });

    matchDropZone.addEventListener('drop', async (e) => {
        const files = await getFilesFromDataTransfer(e.dataTransfer);
        if (files.length > 0) {
            handleSelectedFiles(files);
        } else {
            alert('Tidak ada file simulasi (PDF / Gambar) yang terdeteksi di dalam folder yang di-drop.');
        }
    });

    matchFolderInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleSelectedFiles(Array.from(e.target.files));
        }
    });

    matchFileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleSelectedFiles(Array.from(e.target.files));
        }
    });

    // Helper: Recursive traverse folder from DataTransfer
    async function getFilesFromDataTransfer(dataTransfer) {
        const fileList = [];
        const items = dataTransfer.items;

        async function traverseEntry(entry) {
            if (entry.isFile) {
                const file = await new Promise((res, rej) => entry.file(res, rej));
                fileList.push(file);
            } else if (entry.isDirectory) {
                const dirReader = entry.createReader();
                const readEntries = () => new Promise((res) => dirReader.readEntries(res, () => res([])));
                let batch = await readEntries();
                while (batch.length > 0) {
                    for (const sub of batch) {
                        await traverseEntry(sub);
                    }
                    batch = await readEntries();
                }
            }
        }

        if (items && items.length > 0) {
            for (let i = 0; i < items.length; i++) {
                const item = items[i];
                if (item.webkitGetAsEntry) {
                    const entry = item.webkitGetAsEntry();
                    if (entry) await traverseEntry(entry);
                } else if (item.kind === 'file') {
                    const f = item.getAsFile();
                    if (f) fileList.push(f);
                }
            }
        } else if (dataTransfer.files) {
            for (let i = 0; i < dataTransfer.files.length; i++) {
                fileList.push(dataTransfer.files[i]);
            }
        }
        return fileList;
    }

    // Ekstraksi & Kompresi Halaman 1 PDF menjadi Cover JPEG
    async function extractCoverFromPdf(file) {
        if (typeof pdfjsLib === 'undefined') {
            return { photoBlob: null, text: '' };
        }
        try {
            const arrayBuffer = await file.arrayBuffer();
            const pdf = await pdfjsLib.getDocument({ data: arrayBuffer }).promise;
            const page = await pdf.getPage(1);

            let extractedText = '';
            try {
                const tc = await page.getTextContent();
                extractedText = tc.items.map(it => it.str).join(' ');
            } catch(e) {}

            const viewport = page.getViewport({ scale: 1.5 });
            const canvas = document.createElement('canvas');
            canvas.width = viewport.width;
            canvas.height = viewport.height;
            const ctx = canvas.getContext('2d');

            await page.render({ canvasContext: ctx, viewport }).promise;

            const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', 0.82));
            return { photoBlob: blob, text: extractedText };
        } catch(err) {
            console.warn('PDF extraction notice:', err);
            return { photoBlob: null, text: '' };
        }
    }

    // Kompresi Gambar sebelum Upload
    async function compressImage(file) {
        return new Promise((resolve) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    const maxDim = 1600;
                    let w = img.width;
                    let h = img.height;
                    if (w > maxDim || h > maxDim) {
                        if (w > h) {
                            h = Math.round((h * maxDim) / w);
                            w = maxDim;
                        } else {
                            w = Math.round((w * maxDim) / h);
                            h = maxDim;
                        }
                    }
                    const canvas = document.createElement('canvas');
                    canvas.width = w;
                    canvas.height = h;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, w, h);
                    canvas.toBlob(blob => resolve(blob || file), 'image/jpeg', 0.82);
                };
                img.onerror = () => resolve(file);
                img.src = e.target.result;
            };
            reader.onerror = () => resolve(file);
            reader.readAsDataURL(file);
        });
    }

    // Handler Utama Memproses Daftar File
    async function handleSelectedFiles(rawFiles) {
        // Filter hanya ekstensi simulasi yang valid
        const validExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'webp', 'mp4', 'mov', 'webm'];
        const files = rawFiles.filter(f => {
            const ext = f.name.split('.').pop().toLowerCase();
            return validExtensions.includes(ext) && !f.name.startsWith('.');
        });

        if (files.length === 0) {
            alert('Tidak ditemukan file simulasi yang valid (.pdf, .jpg, .png, .mp4) pada folder yang dipilih.');
            return;
        }

        // Reset state & UI
        isProcessing = true;
        matchedTotal = 0;
        unmatchedTotal = 0;
        failedTotal = 0;

        statMatchedCount.textContent = '0';
        statUnmatchedCount.textContent = '0';
        statFailedCount.textContent = '0';
        matchLogTbody.innerHTML = '';

        matchDropZone.style.display = 'none';
        matchProcessSection.style.display = 'block';
        btnMatchReload.style.display = 'none';
        btnMatchClose.textContent = 'Batal';

        const totalFiles = files.length;
        const csrfToken = document.querySelector('meta[name="csrf-token"]') ? 
                          document.querySelector('meta[name="csrf-token"]').getAttribute('content') : 
                          '{{ csrf_token() }}';

        for (let i = 0; i < totalFiles; i++) {
            const file = files[i];
            const currentNum = i + 1;
            const percent = Math.round((currentNum / totalFiles) * 100);

            matchStatusText.textContent = `Memproses file ${currentNum} dari ${totalFiles}: ${file.name}...`;
            matchPercentText.textContent = `${percent}%`;
            matchProgressBar.style.width = `${percent}%`;

            const ext = file.name.split('.').pop().toLowerCase();
            let photoBlob = null;
            let extractedText = '';

            try {
                if (ext === 'pdf') {
                    const extracted = await extractCoverFromPdf(file);
                    photoBlob = extracted.photoBlob;
                    extractedText = extracted.text;
                } else if (['jpg', 'jpeg', 'png', 'webp'].includes(ext)) {
                    photoBlob = await compressImage(file);
                }

                // Kirim FormData ke Backend
                const formData = new FormData();
                formData.append('simulation_file', file);
                formData.append('filename', file.name);
                if (photoBlob) {
                    formData.append('extracted_photo', photoBlob, `${file.name}.jpg`);
                }
                if (extractedText) {
                    formData.append('extracted_text', extractedText);
                }

                const response = await fetch("{{ route('admin.billboards.autoMatchSimulation') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();

                if (response.ok && result.status === 'matched') {
                    matchedTotal++;
                    statMatchedCount.textContent = matchedTotal;
                    appendLogRow(file.name, `${result.code} (${result.address})`, '🟢 Cocok &amp; Terpasang', '#15803d');
                } else if (result.status === 'unmatched') {
                    unmatchedTotal++;
                    statUnmatchedCount.textContent = unmatchedTotal;
                    appendLogRow(file.name, '-', '🟡 Titik Tidak Ditemukan', '#a16207');
                } else {
                    failedTotal++;
                    statFailedCount.textContent = failedTotal;
                    appendLogRow(file.name, '-', `🔴 ${result.message || 'Gagal'}`, '#b91c1c');
                }

            } catch (err) {
                console.error(err);
                failedTotal++;
                statFailedCount.textContent = failedTotal;
                appendLogRow(file.name, '-', '🔴 Koneksi Terputus / Error', '#b91c1c');
            }
        }

        // Selesai
        isProcessing = false;
        matchStatusText.textContent = `Selesai! ${matchedTotal} dari ${totalFiles} file berhasil dipasangkan otomatis.`;
        matchPercentText.textContent = '100%';
        btnMatchReload.style.display = 'inline-block';
        btnMatchClose.textContent = 'Tutup';
    }

    function appendLogRow(fileName, targetBillboard, statusHtml, color) {
        const tr = document.createElement('tr');
        tr.style.borderBottom = '1px solid #f1f5f9';
        tr.innerHTML = `
            <td style="padding: 0.5rem 0.75rem; color: #1e293b; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${fileName}">${fileName}</td>
            <td style="padding: 0.5rem 0.75rem; color: #334155; max-width: 280px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${targetBillboard}">${targetBillboard}</td>
            <td style="padding: 0.5rem 0.75rem; font-weight: 600; color: ${color}; white-space: nowrap;">${statusHtml}</td>
        `;
        matchLogTbody.insertBefore(tr, matchLogTbody.firstChild);
    }

    async function handleDeleteAllSimulations() {
        if (!confirm('⚠️ PERINGATAN KERAS:\n\nApakah Anda YAKIN ingin MENGHAPUS SEMUA file simulasi dan foto cover dari seluruh titik billboard di database?\n\nSemua billboard akan kembali bersih tanpa simulasi. Tindakan ini TIDAK DAPAT dibatalkan!')) {
            return;
        }

        const confirmWord = prompt('Ketik "HAPUS" untuk mengonfirmasi penghapusan seluruh simulasi:');
        if (confirmWord !== 'HAPUS') {
            alert('Penghapusan dibatalkan (kata konfirmasi salah).');
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]') ? 
                          document.querySelector('meta[name="csrf-token"]').getAttribute('content') : 
                          '{{ csrf_token() }}';

        try {
            const res = await fetch("{{ route('admin.billboards.deleteAllSimulations') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            alert(data.message || 'Semua simulasi dan foto billboard berhasil dihapus!');
            window.location.reload();
        } catch (err) {
            alert('Terjadi kesalahan saat menghapus simulasi: ' + err.message);
        }
    }
</script>


@endsection
