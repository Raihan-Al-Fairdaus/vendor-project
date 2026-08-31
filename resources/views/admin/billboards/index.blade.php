@extends('layouts.admin')

@section('page_title', 'Billboard Management')
@section('page_subtitle', 'Kelola data billboard dan status ketersediaannya.')

@section('header_actions')
    <a href="{{ route('admin.billboards.create') }}" class="btn btn-primary btn-sm">➕ Tambah Billboard</a>
@endsection

@section('content')

{{-- Undo Last Import Button --}}
@if(session('last_import_count'))
<div class="card mb-3" style="padding: 1rem 1.5rem; border: 2px solid #ef4444; background: rgba(239, 68, 68, 0.08);">
    <div class="d-flex align-center gap-4" style="flex-wrap: wrap; justify-content: space-between;">
        <div>
            <span style="font-size: 1rem; color: #f87171; font-weight: 600;">
                📦 Baru saja import {{ session('last_import_count') }} {{ session('last_import_jenis', 'data') }}
            </span>
            <small style="display: block; color: var(--text-muted); margin-top: 0.25rem;">
                Salah import? Klik tombol di samping untuk membatalkan dan menghapus {{ session('last_import_count') }} data terakhir.
            </small>
        </div>
        <form action="{{ route('admin.billboards.undoImport') }}" method="POST" onsubmit="return confirm('⚠️ YAKIN ingin menghapus {{ session('last_import_count') }} data terakhir?\n\nAksi ini TIDAK BISA dibatalkan!');">
            @csrf
            <input type="hidden" name="count" value="{{ session('last_import_count') }}">
            <button type="submit" class="btn btn-sm" style="background: #ef4444; color: white; border: none; padding: 0.6rem 1.5rem; border-radius: 8px; font-weight: 700; cursor: pointer;">
                🗑️ Undo Import (Hapus {{ session('last_import_count') }} data)
            </button>
        </form>
    </div>
</div>
@endif

{{-- Import Box --}}
<div class="card mb-4" style="padding: 1.5rem 2rem;">
    <h3 style="margin-top:0; margin-bottom:1rem; font-size:1.1rem; color:var(--primary);">Import Billboard dari Excel</h3>
    <form action="{{ route('admin.billboards.import') }}" method="POST" enctype="multipart/form-data" class="d-flex align-center gap-4" style="flex-wrap: wrap;" id="importForm" onsubmit="return confirmImport()">
        @csrf
        <div style="flex:1; min-width: 250px;">
            <input type="file" name="files[]" class="form-control" accept=".xlsx,.xls,.csv" multiple required>
            <small style="color:var(--text-muted); display:block; margin-top:0.25rem;">
                Bisa pilih banyak file sekaligus (Blok semua file di dalam folder). Kode otomatis.
            </small>
        </div>
        <div style="min-width: 200px;">
            <select name="jenis_import" id="jenisImport" class="form-control" required style="height: 42px; background: rgba(255,255,255,0.1); color: #fff; border: 2px solid #f59e0b; border-radius: 6px; font-weight: 600;">
                <option value="" disabled selected style="background:#1e293b;">⚠️ -- PILIH JENIS --</option>
                <option value="billboard" style="background:#1e293b;">📋 Billboard</option>
                <option value="midiboard" style="background:#1e293b;">📺 Midiboard</option>
            </select>
            <small style="color:#f59e0b; display:block; margin-top:0.25rem; font-weight:600;">
                ⬆️ WAJIB pilih jenis dulu!
            </small>
        </div>
        <button type="submit" class="btn btn-primary">Upload &amp; Import</button>
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

{{-- Search & Filter Box --}}
<div class="admin-search-outer mb-4">
    <style>
        .admin-search-outer {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .admin-search-form {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }
        .asf-group {
            position: relative;
            flex: 1;
            min-width: 200px;
        }
        .asf-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.1rem;
            pointer-events: none;
            z-index: 10;
        }
        .asf-input, .asf-select {
            width: 100%;
            height: 48px;
            padding: 0 1rem 0 3rem !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
            font-size: 0.95rem;
            outline: none;
            transition: all 0.2s ease;
        }
        .asf-input::placeholder {
            color: rgba(255, 255, 255, 0.6) !important;
        }
        .asf-select option {
            background: #1e293b; /* Dark bg for dropdown options */
            color: #ffffff;
        }
        .asf-input:focus, .asf-select:focus {
            border-color: rgba(255, 255, 255, 0.5) !important;
            background: rgba(255, 255, 255, 0.15) !important;
        }
        .asf-btn-search {
            height: 48px;
            padding: 0 1.5rem;
            background: #f59e0b !important;
            color: white !important;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .asf-btn-search:hover {
            background: #d97706 !important;
        }
        .asf-btn-reset {
            height: 48px;
            padding: 0 1.5rem;
            background: rgba(255, 255, 255, 0.1) !important;
            color: white !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            border-radius: 8px;
            font-weight: 600;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.2s;
        }
        .asf-btn-reset:hover {
            background: rgba(255, 255, 255, 0.2) !important;
        }
    </style>
    
    <form action="{{ route('admin.billboards.index') }}" method="GET" class="admin-search-form">
        <div class="asf-group" style="flex: 2;">
            <span class="asf-icon">🔍</span>
            <input type="text" name="search" class="asf-input" placeholder="Cari kode, alamat billboard..." value="{{ request('search') }}">
        </div>
        
        <div class="asf-group">
            <span class="asf-icon">📍</span>
            <select name="city" class="asf-select" onchange="this.form.submit()">
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
        </div>
        
        <div class="asf-group">
            <span class="asf-icon">📋</span>
            <select name="jenis" class="asf-select" onchange="this.form.submit()">
                <option value="">Semua Jenis</option>
                <option value="billboard" {{ request('jenis') === 'billboard' ? 'selected' : '' }}>Billboard</option>
                <option value="midiboard" {{ request('jenis') === 'midiboard' ? 'selected' : '' }}>Midiboard</option>
            </select>
        </div>

        <div class="asf-group">
            <span class="asf-icon">🚦</span>
            <select name="status" class="asf-select" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="tersedia" {{ request('status') === 'tersedia' ? 'selected' : '' }}>🟢 Tersedia</option>
                <option value="terisi" {{ request('status') === 'terisi' ? 'selected' : '' }}>🔴 Terisi</option>
            </select>
        </div>
        
        <button type="submit" class="asf-btn-search">Cari</button>
        
        @if(request()->filled('search') || request()->filled('city') || request()->filled('jenis') || request()->filled('status'))
            <a href="{{ route('admin.billboards.index') }}" class="asf-btn-reset">✕ Reset</a>
        @endif
    </form>
</div>

<div class="card animate-on-scroll" style="padding:0; overflow:hidden;">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>KODE</th>
                    <th>JENIS</th>
                    <th>KOTA</th>
                    <th>ALAMAT</th>
                    <th>UKURAN</th>
                    <th>SISI</th>
                    <th>STATUS</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($billboards as $board)
                    <tr>
                        <td data-label="KODE" style="font-weight:700; font-family:monospace; letter-spacing:0.5px;">{{ $board->code ?? '-' }}</td>
                        <td data-label="JENIS">
                            <span style="
                                background: {{ $board->jenis === 'midiboard' ? 'rgba(245,158,11,0.15)' : 'rgba(59,130,246,0.15)' }};
                                color: {{ $board->jenis === 'midiboard' ? '#d97706' : '#3b82f6' }};
                                padding: .25rem .6rem;
                                border-radius: 6px;
                                font-size: .75rem;
                                font-weight: 600;
                            ">{{ ucfirst($board->jenis ?? 'billboard') }}</span>
                        </td>
                        <td data-label="KOTA" style="font-weight:600;">{{ $board->city }}</td>
                        <td data-label="ALAMAT">{{ $board->address }}</td>
                        <td data-label="UKURAN">{{ $board->ukuran ?? '-' }}</td>
                        <td data-label="SISI">{{ $board->sisi ?? 1 }}</td>
                        <td data-label="STATUS">
                            @php
                                $isTersedia = $board->status === 'tersedia';
                                $bg = $isTersedia ? 'rgba(16,185,129,.2)' : 'rgba(239,68,68,.2)';
                                $color = $isTersedia ? '#34d399' : '#f87171';
                                $border = $isTersedia ? '#10b981' : '#ef4444';
                            @endphp
                            <span style="background:{{ $bg }}; border:1px solid {{ $border }}; color:{{ $color }}; padding:.3rem .75rem; border-radius:20px; font-size:.75rem; font-weight:600; display:inline-flex; align-items:center; gap:.4rem;">
                                <span style="font-size:12px; color:{{ $color }} !important;">●</span>
                                <span style="color:{{ $color }} !important;">{{ ucfirst($board->status) }}</span>
                            </span>
                        </td>
                        <td data-label="AKSI">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.billboards.edit', $board->id) }}" class="btn btn-outline btn-sm">Edit</a>
                                <form action="{{ route('admin.billboards.destroy', $board->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus billboard ini?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline btn-sm" style="color:var(--error); border-color:var(--error);">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted" style="padding: 2rem;">
                            Belum ada data billboard.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-5 mb-4 d-flex justify-content-center">
    <style>
        .custom-pagination-container {
            background: #ffffff;
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            display: inline-flex;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .custom-pagination {
            display: flex;
            align-items: center;
            gap: 1rem;
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
            color: #4b5563; /* Dark gray for text */
            font-weight: 600;
            text-decoration: none;
            border: none;
            background: transparent;
            min-width: 35px;
            height: 35px;
            border-radius: 50%;
            transition: all 0.2s;
            font-size: 0.95rem;
        }
        .custom-pagination .mypill-link:hover {
            color: var(--primary, #3b82f6);
            background: rgba(59, 130, 246, 0.1);
        }
        .custom-pagination .mypill-item.active .mypill-link {
            background: var(--primary, #3b82f6); /* Use their primary color */
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.4);
        }
        .custom-pagination .mypill-item.disabled .mypill-link {
            color: #9ca3af;
            cursor: not-allowed;
            background: transparent;
        }
        .custom-pagination li:first-child .mypill-link,
        .custom-pagination li:last-child .mypill-link {
            border-radius: 20px;
            padding: 0 0.75rem;
            width: auto;
            color: var(--primary, #3b82f6);
        }
        .custom-pagination li:first-child.disabled .mypill-link,
        .custom-pagination li:last-child.disabled .mypill-link {
            color: #9ca3af;
        }
    </style>
    {{ $billboards->appends(request()->query())->links('admin.billboards.pagination') }}
</div>

@endsection
