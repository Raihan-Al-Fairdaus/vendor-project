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

        <div class="d-flex gap-4 mb-4">
            <div style="flex:1;">
                <label class="form-label" style="display:block; margin-bottom:0.5rem; font-weight:600;">Latitude (Opsional)</label>
                <input type="text" name="latitude" class="form-control" value="{{ old('latitude', $billboard->latitude) }}" placeholder="Contoh: -7.4475">
                @error('latitude') <span class="text-danger" style="font-size:0.8rem; color:var(--error);">{{ $message }}</span> @enderror
            </div>
            <div style="flex:1;">
                <label class="form-label" style="display:block; margin-bottom:0.5rem; font-weight:600;">Longitude (Opsional)</label>
                <input type="text" name="longitude" class="form-control" value="{{ old('longitude', $billboard->longitude) }}" placeholder="Contoh: 112.7180">
                @error('longitude') <span class="text-danger" style="font-size:0.8rem; color:var(--error);">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mb-6">
            <label class="form-label" style="display:block; margin-bottom:0.5rem; font-weight:600;">Status</label>
            <select name="status" class="form-control" required>
                <option value="tersedia" {{ old('status', $billboard->status) === 'tersedia' ? 'selected' : '' }}>🟢 Tersedia</option>
                <option value="terisi" {{ old('status', $billboard->status) === 'terisi' ? 'selected' : '' }}>🔴 Terisi</option>
            </select>
            @error('status') <span class="text-danger" style="font-size:0.8rem; color:var(--error);">{{ $message }}</span> @enderror
        </div>

        <div class="d-flex gap-3">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('admin.billboards.index') }}" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>

@endsection
