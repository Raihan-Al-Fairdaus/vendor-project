@extends('layouts.admin')

@section('page_title', 'Billboard Management')
@section('page_subtitle', 'Kelola data billboard dan status ketersediaannya.')

@section('header_actions')
    <a href="{{ route('admin.billboards.create') }}" class="btn btn-primary btn-sm">➕ Tambah Billboard</a>
@endsection

@section('content')

{{-- Import Box --}}
<div class="card mb-4" style="padding: 1.5rem 2rem;">
    <h3 style="margin-top:0; margin-bottom:1rem; font-size:1.1rem; color:var(--primary);">Import Billboard dari Excel</h3>
    <form action="{{ route('admin.billboards.import') }}" method="POST" enctype="multipart/form-data" class="d-flex align-center gap-4">
        @csrf
        <div style="flex:1;">
            <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
            <small style="color:var(--text-muted); display:block; margin-top:0.25rem;">
                File Excel (.xlsx / .xls) harus memiliki kolom header: <code>jenis</code>, <code>city</code>, <code>sisi</code>, <code>address</code>, <code>ukuran</code>, <code>orientasi</code>, <code>kepemilikan</code>, <code>map_link</code>.
                Kode billboard akan di-generate otomatis.
            </small>
        </div>
        <button type="submit" class="btn btn-primary">Upload &amp; Import</button>
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

<div class="mt-4">
    {{ $billboards->links() }}
</div>

@endsection
