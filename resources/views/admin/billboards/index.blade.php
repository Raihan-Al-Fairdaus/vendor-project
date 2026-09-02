@extends('layouts.admin')

@section('page_title', 'Billboards')

@section('content')

{{-- Top header bar: title + action button on dark navy bg --}}
<div class="admin-page-header" style="background-color: #1b3a60; padding: 1.75rem 2rem 1.5rem; display: flex; justify-content: space-between; align-items: flex-start;">
    <div>
        <h1 style="color: #ffffff; font-size: 1.5rem; font-weight: 700; margin: 0 0 0.35rem 0; line-height: 1.2;">Billboards</h1>
        <p style="color: rgba(255,255,255,0.55); font-size: 0.85rem; margin: 0;">Kelola data billboard dan status ketersediaannya.</p>
    </div>
    <div>
        <a href="{{ route('admin.billboards.create') }}" style="background-color: #ffffff; color: #1e293b; text-decoration: none; border: none; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; font-size: 0.8rem; display: flex; align-items: center; gap: 0.4rem; cursor: pointer;">
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
                        <td colspan="8" style="padding: 2.5rem; text-align: center; color: #64748b; font-size: 0.9rem;">
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

@endsection
