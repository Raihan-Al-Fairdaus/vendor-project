@extends('layouts.admin')

@section('page_title', 'Edit Billboard')
@section('page_subtitle', 'Perbarui data billboard.')

@section('content')

<div class="card animate-on-scroll" style="max-width: 600px; margin: 0 auto; padding: 2rem;">
    <form action="{{ route('admin.billboards.update', $billboard->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="form-label" style="display:block; margin-bottom:0.5rem; font-weight:600;">Kota / Kabupaten</label>
            <input type="text" name="city" class="form-control" value="{{ old('city', $billboard->city) }}" placeholder="Contoh: Sidoarjo" required>
            @error('city') <span class="text-danger" style="font-size:0.8rem; color:var(--error);">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="form-label" style="display:block; margin-bottom:0.5rem; font-weight:600;">Nama / ID Billboard</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $billboard->name) }}" placeholder="Contoh: Billboard RA Mustika" required>
            @error('name') <span class="text-danger" style="font-size:0.8rem; color:var(--error);">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="form-label" style="display:block; margin-bottom:0.5rem; font-weight:600;">Alamat / Keterangan Lokasi</label>
            <textarea name="address" class="form-control" rows="3" placeholder="Contoh: Jl. RA Mustika No. 10" required>{{ old('address', $billboard->address) }}</textarea>
            @error('address') <span class="text-danger" style="font-size:0.8rem; color:var(--error);">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="form-label" style="display:block; margin-bottom:0.5rem; font-weight:600;">Link Peta (Opsional)</label>
            <input type="text" name="map_link" class="form-control" value="{{ old('map_link', $billboard->map_link) }}" placeholder="Contoh: https://maps.app.goo.gl/xxxx">
            @error('map_link') <span class="text-danger" style="font-size:0.8rem; color:var(--error);">{{ $message }}</span> @enderror
        </div>

        <div class="mb-6">
            <label class="form-label" style="display:block; margin-bottom:0.5rem; font-weight:600;">Status</label>
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
