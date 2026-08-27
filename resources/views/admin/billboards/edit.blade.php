@extends('layouts.admin')

@section('page_title', 'Edit Billboard')
@section('page_subtitle', 'Perbarui data billboard.')

@section('content')

<div class="card animate-on-scroll" style="max-width: 700px; margin: 0 auto; padding: 2rem;">

    {{-- Kode Billboard (Read-only) --}}
    <div style="
        background: linear-gradient(135deg, #163056, #1e3a68);
        color: #fff;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        text-align: center;
        font-size: 1.1rem;
        font-weight: 700;
        letter-spacing: 1px;
    ">
        <span style="font-size: 0.75rem; font-weight: 400; display: block; margin-bottom: 4px; opacity: 0.7;">KODE BILLBOARD</span>
        <span>{{ $billboard->code ?? '—' }}</span>
    </div>

    <form action="{{ route('admin.billboards.update', $billboard) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Row: Jenis + Sisi --}}
        <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
            <div style="flex: 1;">
                <label class="form-label" style="display:block; margin-bottom:0.5rem; font-weight:600;">Jenis *</label>
                <select name="jenis" class="form-control" required>
                    <option value="billboard" {{ old('jenis', $billboard->jenis) === 'billboard' ? 'selected' : '' }}>Billboard</option>
                    <option value="midiboard" {{ old('jenis', $billboard->jenis) === 'midiboard' ? 'selected' : '' }}>Midiboard</option>
                </select>
                @error('jenis') <span class="text-danger" style="font-size:0.8rem; color:var(--error);">{{ $message }}</span> @enderror
            </div>
            <div style="flex: 1;">
                <label class="form-label" style="display:block; margin-bottom:0.5rem; font-weight:600;">Sisi *</label>
                <select name="sisi" class="form-control" required>
                    <option value="1" {{ old('sisi', $billboard->sisi) == 1 ? 'selected' : '' }}>Sisi 1</option>
                    <option value="2" {{ old('sisi', $billboard->sisi) == 2 ? 'selected' : '' }}>Sisi 2</option>
                </select>
                @error('sisi') <span class="text-danger" style="font-size:0.8rem; color:var(--error);">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Kota --}}
        <div class="mb-4">
            <label class="form-label" style="display:block; margin-bottom:0.5rem; font-weight:600;">Kota / Kabupaten *</label>
            <input type="text" name="city" class="form-control" value="{{ old('city', $billboard->city) }}" placeholder="Contoh: Sidoarjo" required>
            @error('city') <span class="text-danger" style="font-size:0.8rem; color:var(--error);">{{ $message }}</span> @enderror
        </div>

        {{-- Row: Ukuran + Orientasi --}}
        <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
            <div style="flex: 1;">
                <label class="form-label" style="display:block; margin-bottom:0.5rem; font-weight:600;">Ukuran</label>
                <input type="text" name="ukuran" class="form-control" value="{{ old('ukuran', $billboard->ukuran) }}" placeholder="Contoh: 5 × 10 m">
                @error('ukuran') <span class="text-danger" style="font-size:0.8rem; color:var(--error);">{{ $message }}</span> @enderror
            </div>
            <div style="flex: 1;">
                <label class="form-label" style="display:block; margin-bottom:0.5rem; font-weight:600;">Orientasi *</label>
                <select name="orientasi" class="form-control" required>
                    <option value="landscape" {{ old('orientasi', $billboard->orientasi) === 'landscape' ? 'selected' : '' }}>Landscape</option>
                    <option value="portrait" {{ old('orientasi', $billboard->orientasi) === 'portrait' ? 'selected' : '' }}>Portrait</option>
                </select>
                @error('orientasi') <span class="text-danger" style="font-size:0.8rem; color:var(--error);">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Kepemilikan --}}
        <div class="mb-4">
            <label class="form-label" style="display:block; margin-bottom:0.5rem; font-weight:600;">Kepemilikan</label>
            <input type="text" name="kepemilikan" class="form-control" value="{{ old('kepemilikan', $billboard->kepemilikan) }}" placeholder="Contoh: DNA Advertising">
            @error('kepemilikan') <span class="text-danger" style="font-size:0.8rem; color:var(--error);">{{ $message }}</span> @enderror
        </div>

        {{-- Alamat --}}
        <div class="mb-4">
            <label class="form-label" style="display:block; margin-bottom:0.5rem; font-weight:600;">Alamat / Keterangan Lokasi *</label>
            <textarea name="address" class="form-control" rows="3" placeholder="Contoh: Jl. Ahmad Yani No.13 A, Sidokumpul, Sidoarjo" required>{{ old('address', $billboard->address) }}</textarea>
            @error('address') <span class="text-danger" style="font-size:0.8rem; color:var(--error);">{{ $message }}</span> @enderror
        </div>

        {{-- Link Peta --}}
        <div class="mb-4">
            <label class="form-label" style="display:block; margin-bottom:0.5rem; font-weight:600;">Link Peta (Opsional)</label>
            <input type="text" name="map_link" class="form-control" value="{{ old('map_link', $billboard->map_link) }}" placeholder="Contoh: https://maps.app.goo.gl/xxxx">
            @error('map_link') <span class="text-danger" style="font-size:0.8rem; color:var(--error);">{{ $message }}</span> @enderror
        </div>

        {{-- Status --}}
        <div class="mb-6">
            <label class="form-label" style="display:block; margin-bottom:0.5rem; font-weight:600;">Status *</label>
            <select name="status" class="form-control" required>
                <option value="tersedia" {{ old('status', $billboard->status) === 'tersedia' ? 'selected' : '' }}>🟢 Tersedia</option>
                <option value="terisi" {{ old('status', $billboard->status) === 'terisi' ? 'selected' : '' }}>🔴 Terisi</option>
            </select>
            @error('status') <span class="text-danger" style="font-size:0.8rem; color:var(--error);">{{ $message }}</span> @enderror
        </div>

        <div class="d-flex gap-3" style="margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('admin.billboards.index') }}" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>

@endsection
