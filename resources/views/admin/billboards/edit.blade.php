@extends('layouts.admin')

@section('page_title', 'Edit Billboard')

@section('content')

{{-- Top header bar --}}
<div class="admin-page-header" style="background-color: #1b3a60; padding: 1.75rem 2rem 1.5rem; display: flex; justify-content: space-between; align-items: flex-start;">
    <div>
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.35rem;">
            <a href="{{ route('admin.billboards.index') }}" style="color: rgba(255,255,255,0.7); text-decoration: none; display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 6px; background: rgba(255,255,255,0.1); transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.2)'; this.style.color='#fff'" onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.color='rgba(255,255,255,0.7)'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            </a>
            <h1 style="color: #ffffff; font-size: 1.5rem; font-weight: 700; margin: 0; line-height: 1.2;">Edit Billboard</h1>
        </div>
        <p style="color: rgba(255,255,255,0.55); font-size: 0.85rem; margin: 0; padding-left: 2.75rem;">Perbarui data billboard dan status ketersediaannya.</p>
    </div>
</div>

<div style="padding: 0 2rem 2rem;">
    <div style="background-color: #ffffff; border-radius: 10px; padding: 2rem; width: 100%; margin: 0;">
        
        {{-- Kode Billboard (Read-only) --}}
        <div style="background: #f8fafc; color: #0f172a; padding: 1.25rem 1.5rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <span style="font-size: 0.75rem; font-weight: 600; color: #64748b; display: block; margin-bottom: 4px; letter-spacing: 0.05em; text-transform: uppercase;">Kode Billboard</span>
                <span style="font-size: 1.25rem; font-weight: 700; font-family: monospace; letter-spacing: 0.5px;">{{ $billboard->code ?? '—' }}</span>
            </div>
            <div style="width: 48px; height: 48px; background: #eff6ff; color: #3b82f6; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
            </div>
        </div>

        <form action="{{ route('admin.billboards.update', $billboard) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                {{-- Jenis --}}
                <div>
                    <label style="display:block; margin-bottom:0.5rem; font-weight:600; color:#0f172a; font-size: 0.9rem;">Jenis *</label>
                    <select name="jenis" style="width: 100%; padding: 0.6rem 0.75rem; border-radius: 6px; border: 1px solid #cbd5e1; background: #ffffff; color: #0f172a; font-family: inherit; font-size: 0.95rem; appearance: auto;" required>
                        <option value="billboard" {{ old('jenis', $billboard->jenis) === 'billboard' ? 'selected' : '' }}>Billboard</option>
                        <option value="midiboard" {{ old('jenis', $billboard->jenis) === 'midiboard' ? 'selected' : '' }}>Midiboard</option>
                    </select>
                    @error('jenis') <span style="font-size:0.8rem; color:#ef4444; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>
                
                {{-- Sisi --}}
                <div>
                    <label style="display:block; margin-bottom:0.5rem; font-weight:600; color:#0f172a; font-size: 0.9rem;">Sisi *</label>
                    <select name="sisi" style="width: 100%; padding: 0.6rem 0.75rem; border-radius: 6px; border: 1px solid #cbd5e1; background: #ffffff; color: #0f172a; font-family: inherit; font-size: 0.95rem; appearance: auto;" required>
                        <option value="1" {{ old('sisi', $billboard->sisi) == 1 ? 'selected' : '' }}>Sisi 1</option>
                        <option value="2" {{ old('sisi', $billboard->sisi) == 2 ? 'selected' : '' }}>Sisi 2</option>
                    </select>
                    @error('sisi') <span style="font-size:0.8rem; color:#ef4444; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Kota --}}
            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; margin-bottom:0.5rem; font-weight:600; color:#0f172a; font-size: 0.9rem;">Kota / Kabupaten *</label>
                <input type="text" name="city" value="{{ old('city', $billboard->city) }}" placeholder="Contoh: Sidoarjo" style="width: 100%; padding: 0.6rem 0.75rem; border-radius: 6px; border: 1px solid #cbd5e1; background: #ffffff; color: #0f172a; font-family: inherit; font-size: 0.95rem;" required>
                @error('city') <span style="font-size:0.8rem; color:#ef4444; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                {{-- Ukuran --}}
                <div>
                    <label style="display:block; margin-bottom:0.5rem; font-weight:600; color:#0f172a; font-size: 0.9rem;">Ukuran</label>
                    <input type="text" name="ukuran" value="{{ old('ukuran', $billboard->ukuran) }}" placeholder="Contoh: 5 × 10 m" style="width: 100%; padding: 0.6rem 0.75rem; border-radius: 6px; border: 1px solid #cbd5e1; background: #ffffff; color: #0f172a; font-family: inherit; font-size: 0.95rem;">
                    @error('ukuran') <span style="font-size:0.8rem; color:#ef4444; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>
                
                {{-- Orientasi --}}
                <div>
                    <label style="display:block; margin-bottom:0.5rem; font-weight:600; color:#0f172a; font-size: 0.9rem;">Orientasi *</label>
                    <select name="orientasi" style="width: 100%; padding: 0.6rem 0.75rem; border-radius: 6px; border: 1px solid #cbd5e1; background: #ffffff; color: #0f172a; font-family: inherit; font-size: 0.95rem; appearance: auto;" required>
                        <option value="landscape" {{ old('orientasi', $billboard->orientasi) === 'landscape' ? 'selected' : '' }}>Landscape</option>
                        <option value="portrait" {{ old('orientasi', $billboard->orientasi) === 'portrait' ? 'selected' : '' }}>Portrait</option>
                    </select>
                    @error('orientasi') <span style="font-size:0.8rem; color:#ef4444; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Alamat --}}
            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; margin-bottom:0.5rem; font-weight:600; color:#0f172a; font-size: 0.9rem;">Alamat / Keterangan Lokasi *</label>
                <textarea name="address" rows="3" placeholder="Contoh: Jl. Ahmad Yani No.13 A, Sidokumpul, Sidoarjo" style="width: 100%; padding: 0.6rem 0.75rem; border-radius: 6px; border: 1px solid #cbd5e1; background: #ffffff; color: #0f172a; font-family: inherit; font-size: 0.95rem; resize: vertical;" required>{{ old('address', $billboard->address) }}</textarea>
                @error('address') <span style="font-size:0.8rem; color:#ef4444; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
            </div>

            {{-- Link Peta --}}
            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; margin-bottom:0.5rem; font-weight:600; color:#0f172a; font-size: 0.9rem;">Link Peta (Opsional)</label>
                <input type="text" name="map_link" value="{{ old('map_link', $billboard->map_link) }}" placeholder="Contoh: https://maps.app.goo.gl/xxxx" style="width: 100%; padding: 0.6rem 0.75rem; border-radius: 6px; border: 1px solid #cbd5e1; background: #ffffff; color: #0f172a; font-family: inherit; font-size: 0.95rem;">
                @error('map_link') <span style="font-size:0.8rem; color:#ef4444; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
            </div>

            {{-- Status --}}
            <div style="margin-bottom: 2.5rem;">
                <label style="display:block; margin-bottom:0.5rem; font-weight:600; color:#0f172a; font-size: 0.9rem;">Status *</label>
                <select name="status" style="width: 100%; padding: 0.6rem 0.75rem; border-radius: 6px; border: 1px solid #cbd5e1; background: #ffffff; color: #0f172a; font-family: inherit; font-size: 0.95rem; appearance: auto;" required>
                    <option value="tersedia" {{ old('status', $billboard->status) === 'tersedia' ? 'selected' : '' }}>🟢 Tersedia</option>
                    <option value="terisi" {{ old('status', $billboard->status) === 'terisi' ? 'selected' : '' }}>🔴 Terisi</option>
                </select>
                @error('status') <span style="font-size:0.8rem; color:#ef4444; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
            </div>

            {{-- Simulasi Billboard --}}
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem; margin-bottom: 2rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
                    <div>
                        <label style="display:block; font-weight:700; color:#0f172a; font-size: 0.95rem; margin: 0;">
                            Simulasi Billboard (Gambar / Video)
                        </label>
                        <span style="font-size: 0.8rem; color: #64748b;">
                            Unggah preview simulasi billboard untuk dapat dilihat pengunjung di halaman publik via tombol "Lihat Simulasi".
                        </span>
                    </div>
                    <div>
                        @if($billboard->has_simulation)
                            <span style="background: #d1fae5; color: #059669; padding: 0.3rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.35rem;">
                                {{ $billboard->is_video_simulation ? '🎥 Video Simulasi Aktif' : '🖼️ Gambar Simulasi Aktif' }}
                            </span>
                        @else
                            <span style="background: #f1f5f9; color: #64748b; padding: 0.3rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">
                                ⚪ Belum ada simulasi
                            </span>
                        @endif
                    </div>
                </div>

                @if($billboard->has_simulation)
                    <div style="margin-bottom: 1.25rem; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                            <span style="font-size: 0.8rem; font-weight: 600; color: #0f172a;">Simulasi Tersimpan:</span>
                            <button type="button" onclick="if(confirm('⚠️ Yakin ingin menghapus file simulasi billboard ini?')) document.getElementById('delete-simulation-form').submit();" style="background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; border-radius: 6px; padding: 0.35rem 0.75rem; font-size: 0.8rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.35rem;">
                                <i class="fa-regular fa-trash-can"></i> Hapus Simulasi
                            </button>
                        </div>
                        <div style="max-width: 480px; border-radius: 6px; overflow: hidden; background: #0f172a; display: flex; align-items: center; justify-content: center;">
                            @if($billboard->is_video_simulation)
                                <video controls style="width: 100%; max-height: 280px; display: block;">
                                    <source src="{{ $billboard->simulation_url }}">
                                    Browser tidak mendukung pemutar video.
                                </video>
                            @elseif($billboard->is_pdf_simulation)
                                <div style="background: #ffffff; width: 100%; padding: 1.5rem; text-align: center; border: 1px solid #e2e8f0;">
                                    <div style="font-size: 2.5rem; color: #dc2626; margin-bottom: 0.5rem;">📄</div>
                                    <div style="font-weight: 600; font-size: 0.9rem; color: #0f172a; margin-bottom: 0.5rem;">Dokumen Simulasi PDF</div>
                                    <a href="{{ $billboard->simulation_url }}" target="_blank" rel="noopener" style="display: inline-flex; align-items: center; gap: 0.35rem; color: #2563eb; font-size: 0.82rem; font-weight: 600; text-decoration: none; background: #eff6ff; padding: 0.4rem 0.8rem; border-radius: 6px;">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka File PDF di Tab Baru
                                    </a>
                                </div>
                            @else
                                <img src="{{ $billboard->simulation_url }}" alt="Simulasi {{ $billboard->code }}" style="width: 100%; max-height: 280px; object-fit: contain; display: block;">
                            @endif
                        </div>
                    </div>
                @endif

                {{-- File Input --}}
                <div>
                    <label style="display:block; margin-bottom:0.4rem; font-weight:600; color:#0f172a; font-size: 0.85rem;">
                        {{ $billboard->has_simulation ? 'Ganti File Simulasi:' : 'Unggah File Simulasi:' }}
                    </label>
                    <input type="file" name="simulation_file" id="simulation_file" accept=".jpg,.jpeg,.png,.webp,.mp4,.mov,.webm,.pdf" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 6px; padding: 0.5rem 0.75rem; background: #ffffff; color: #0f172a; font-size: 0.85rem; box-sizing: border-box;" onchange="previewSimulation(this)">
                    <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.35rem;">
                        Format yang didukung: <strong>PDF, JPG, PNG, WEBP, MP4, MOV, WEBM</strong> (Maksimal 50MB).
                    </div>
                    @error('simulation_file') <span style="font-size:0.8rem; color:#ef4444; margin-top: 4px; display: block;">{{ $message }}</span> @enderror

                    {{-- Live Preview Box --}}
                    <div id="new-preview-box" style="display: none; margin-top: 1rem; background: #ffffff; border: 1px solid #3b82f6; border-radius: 6px; padding: 0.75rem;">
                        <span style="font-size: 0.8rem; font-weight: 700; color: #2563eb; display: block; margin-bottom: 0.5rem;">
                            👀 Preview File Baru (Sebelum Disimpan):
                        </span>
                        <div id="new-preview-container" style="max-width: 480px; border-radius: 6px; overflow: hidden; background: #0f172a;"></div>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; align-items: center; border-top: 1px solid #f1f5f9; padding-top: 1.5rem;">
                <button type="submit" style="background: #1b3a60; color: #ffffff; border: none; padding: 0.75rem 1.5rem; border-radius: 6px; font-weight: 600; font-size: 0.95rem; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#13294b'" onmouseout="this.style.background='#1b3a60'">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.billboards.index') }}" style="background: #ffffff; color: #64748b; border: 1px solid #cbd5e1; padding: 0.75rem 1.5rem; border-radius: 6px; font-weight: 600; font-size: 0.95rem; text-decoration: none; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'; this.style.color='#0f172a'" onmouseout="this.style.background='#ffffff'; this.style.color='#64748b'">
                    Batal
                </a>
            </div>
        </form>

        @if($billboard->has_simulation)
        <form id="delete-simulation-form" action="{{ route('admin.billboards.simulation.delete', $billboard->id) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
        @endif

    </div>
</div>

<script>
function previewSimulation(input) {
    const box = document.getElementById('new-preview-box');
    const container = document.getElementById('new-preview-container');
    container.innerHTML = '';

    if (!input.files || !input.files[0]) {
        box.style.display = 'none';
        return;
    }

    const file = input.files[0];
    const fileType = file.type;

    if (fileType === 'application/pdf' || file.name.match(/\.pdf$/i)) {
        const pdfCard = document.createElement('div');
        pdfCard.style.cssText = 'background: #ffffff; padding: 1.5rem; text-align: center; border: 1px solid #cbd5e1; border-radius: 6px; width: 100%; box-sizing: border-box;';
        pdfCard.innerHTML = '<div style="font-size: 2.5rem; color: #dc2626; margin-bottom: 0.4rem;">📄</div>' +
                            '<div style="font-weight: 700; color: #0f172a; font-size: 0.9rem; word-break: break-all;">' + file.name + '</div>' +
                            '<div style="font-size: 0.78rem; color: #64748b; margin-top: 0.25rem;">File Dokumen PDF (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)</div>';
        container.appendChild(pdfCard);
        box.style.display = 'block';
    } else if (fileType.startsWith('image/')) {
        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.style.width = '100%';
        img.style.maxHeight = '280px';
        img.style.objectFit = 'contain';
        img.style.display = 'block';
        container.appendChild(img);
        box.style.display = 'block';
    } else if (fileType.startsWith('video/') || file.name.match(/\.(mp4|mov|webm)$/i)) {
        const video = document.createElement('video');
        video.src = URL.createObjectURL(file);
        video.controls = true;
        video.style.width = '100%';
        video.style.maxHeight = '280px';
        video.style.display = 'block';
        container.appendChild(video);
        box.style.display = 'block';
    }
}
</script>

@endsection
