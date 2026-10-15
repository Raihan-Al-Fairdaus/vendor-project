@extends('layouts.admin')

@section('page_title', 'Tambah Billboard')

@section('content')

{{-- Top header bar --}}
<div class="admin-page-header" style="background-color: #1b3a60; padding: 1.75rem 2rem 1.5rem; display: flex; justify-content: space-between; align-items: flex-start;">
    <div>
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.35rem;">
            <a href="{{ route('admin.billboards.index') }}" style="color: rgba(255,255,255,0.7); text-decoration: none; display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 6px; background: rgba(255,255,255,0.1); transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.2)'; this.style.color='#fff'" onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.color='rgba(255,255,255,0.7)'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            </a>
            <h1 style="color: #ffffff; font-size: 1.5rem; font-weight: 700; margin: 0; line-height: 1.2;">Tambah Billboard</h1>
        </div>
        <p style="color: rgba(255,255,255,0.55); font-size: 0.85rem; margin: 0; padding-left: 2.75rem;">Masukkan data billboard baru. Kode akan di-generate otomatis.</p>
    </div>
</div>

<div style="padding: 0 2rem 2rem;">
    <div style="background-color: #ffffff; border-radius: 10px; padding: 2rem; width: 100%; margin: 0;">
        
        {{-- Preview Kode (akan terisi otomatis via JS) --}}
        <div style="background: #f8fafc; color: #0f172a; padding: 1.25rem 1.5rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <span style="font-size: 0.75rem; font-weight: 600; color: #64748b; display: block; margin-bottom: 4px; letter-spacing: 0.05em; text-transform: uppercase;">PREVIEW KODE BILLBOARD</span>
                <span id="code-text" style="font-size: 1.25rem; font-weight: 700; font-family: monospace; letter-spacing: 0.5px;">— Isi jenis, kota, dan sisi —</span>
            </div>
            <div style="width: 48px; height: 48px; background: #eff6ff; color: #3b82f6; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
            </div>
        </div>

        <form action="{{ route('admin.billboards.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                {{-- Jenis --}}
                <div>
                    <label style="display:block; margin-bottom:0.5rem; font-weight:600; color:#0f172a; font-size: 0.9rem;">Jenis *</label>
                    <select name="jenis" id="jenis" style="width: 100%; padding: 0.6rem 0.75rem; border-radius: 6px; border: 1px solid #cbd5e1; background: #ffffff; color: #0f172a; font-family: inherit; font-size: 0.95rem; appearance: auto;" required>
                        <option value="billboard" {{ old('jenis') === 'midiboard' ? '' : 'selected' }}>Billboard</option>
                        <option value="midiboard" {{ old('jenis') === 'midiboard' ? 'selected' : '' }}>Midiboard</option>
                    </select>
                    @error('jenis') <span style="font-size:0.8rem; color:#ef4444; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>
                
                {{-- Sisi --}}
                <div>
                    <label style="display:block; margin-bottom:0.5rem; font-weight:600; color:#0f172a; font-size: 0.9rem;">Sisi *</label>
                    <select name="sisi" id="sisi" style="width: 100%; padding: 0.6rem 0.75rem; border-radius: 6px; border: 1px solid #cbd5e1; background: #ffffff; color: #0f172a; font-family: inherit; font-size: 0.95rem; appearance: auto;" required>
                        <option value="1" {{ old('sisi', '1') == '1' ? 'selected' : '' }}>Sisi 1</option>
                        <option value="2" {{ old('sisi') == '2' ? 'selected' : '' }}>Sisi 2</option>
                    </select>
                    @error('sisi') <span style="font-size:0.8rem; color:#ef4444; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Kota --}}
            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; margin-bottom:0.5rem; font-weight:600; color:#0f172a; font-size: 0.9rem;">Kota / Kabupaten *</label>
                <input type="text" name="city" id="city" value="{{ old('city') }}" placeholder="Contoh: Sidoarjo" style="width: 100%; padding: 0.6rem 0.75rem; border-radius: 6px; border: 1px solid #cbd5e1; background: #ffffff; color: #0f172a; font-family: inherit; font-size: 0.95rem;" required>
                @error('city') <span style="font-size:0.8rem; color:#ef4444; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                {{-- Ukuran --}}
                <div>
                    <label style="display:block; margin-bottom:0.5rem; font-weight:600; color:#0f172a; font-size: 0.9rem;">Ukuran</label>
                    <input type="text" name="ukuran" value="{{ old('ukuran') }}" placeholder="Contoh: 5 × 10 m" style="width: 100%; padding: 0.6rem 0.75rem; border-radius: 6px; border: 1px solid #cbd5e1; background: #ffffff; color: #0f172a; font-family: inherit; font-size: 0.95rem;">
                    @error('ukuran') <span style="font-size:0.8rem; color:#ef4444; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>
                
                {{-- Orientasi --}}
                <div>
                    <label style="display:block; margin-bottom:0.5rem; font-weight:600; color:#0f172a; font-size: 0.9rem;">Orientasi *</label>
                    <select name="orientasi" style="width: 100%; padding: 0.6rem 0.75rem; border-radius: 6px; border: 1px solid #cbd5e1; background: #ffffff; color: #0f172a; font-family: inherit; font-size: 0.95rem; appearance: auto;" required>
                        <option value="landscape" {{ old('orientasi', 'landscape') === 'landscape' ? 'selected' : '' }}>Landscape</option>
                        <option value="portrait" {{ old('orientasi') === 'portrait' ? 'selected' : '' }}>Portrait</option>
                    </select>
                    @error('orientasi') <span style="font-size:0.8rem; color:#ef4444; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Alamat --}}
            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; margin-bottom:0.5rem; font-weight:600; color:#0f172a; font-size: 0.9rem;">Alamat / Keterangan Lokasi *</label>
                <textarea name="address" rows="3" placeholder="Contoh: Jl. Ahmad Yani No.13 A, Sidokumpul, Sidoarjo" style="width: 100%; padding: 0.6rem 0.75rem; border-radius: 6px; border: 1px solid #cbd5e1; background: #ffffff; color: #0f172a; font-family: inherit; font-size: 0.95rem; resize: vertical;" required>{{ old('address') }}</textarea>
                @error('address') <span style="font-size:0.8rem; color:#ef4444; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
            </div>

            {{-- Link Peta --}}
            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; margin-bottom:0.5rem; font-weight:600; color:#0f172a; font-size: 0.9rem;">Link Peta (Opsional)</label>
                <input type="text" name="map_link" value="{{ old('map_link') }}" placeholder="Contoh: https://maps.app.goo.gl/xxxx" style="width: 100%; padding: 0.6rem 0.75rem; border-radius: 6px; border: 1px solid #cbd5e1; background: #ffffff; color: #0f172a; font-family: inherit; font-size: 0.95rem;">
                @error('map_link') <span style="font-size:0.8rem; color:#ef4444; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
            </div>

            {{-- Status --}}
            <div style="margin-bottom: 2.5rem;">
                <label style="display:block; margin-bottom:0.5rem; font-weight:600; color:#0f172a; font-size: 0.9rem;">Status *</label>
                <select name="status" style="width: 100%; padding: 0.6rem 0.75rem; border-radius: 6px; border: 1px solid #cbd5e1; background: #ffffff; color: #0f172a; font-family: inherit; font-size: 0.95rem; appearance: auto;" required>
                    <option value="tersedia" {{ old('status') === 'tersedia' || !old('status') ? 'selected' : '' }}>🟢 Tersedia</option>
                    <option value="terisi" {{ old('status') === 'terisi' ? 'selected' : '' }}>🔴 Terisi</option>
                </select>
                @error('status') <span style="font-size:0.8rem; color:#ef4444; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
            </div>

            {{-- Simulasi Billboard (Opsional) --}}
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem; margin-bottom: 2rem;">
                <label style="display:block; font-weight:700; color:#0f172a; font-size: 0.95rem; margin: 0 0 0.25rem 0;">
                    Simulasi Billboard (PDF / Gambar / Video) <span style="font-weight: 400; color: #64748b; font-size: 0.85rem;">(Opsional)</span>
                </label>
                <span style="font-size: 0.8rem; color: #64748b; display: block; margin-bottom: 0.75rem;">
                    Unggah file simulasi (dokumen PDF, mockup foto/video) yang nantinya dapat dilihat calon klien di halaman publik.
                </span>

                <input type="file" name="simulation_file" id="simulation_file" accept=".jpg,.jpeg,.png,.webp,.mp4,.mov,.webm,.pdf" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 6px; padding: 0.5rem 0.75rem; background: #ffffff; color: #0f172a; font-size: 0.85rem; box-sizing: border-box;" onchange="previewSimulation(this)">
                <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.35rem;">
                    Format yang didukung: <strong>PDF, JPG, PNG, WEBP, MP4, MOV, WEBM</strong> (Maksimal 50MB).
                </div>
                @error('simulation_file') <span style="font-size:0.8rem; color:#ef4444; margin-top: 4px; display: block;">{{ $message }}</span> @enderror

                {{-- Live Preview Box --}}
                <div id="new-preview-box" style="display: none; margin-top: 1rem; background: #ffffff; border: 1px solid #3b82f6; border-radius: 6px; padding: 0.75rem;">
                    <span style="font-size: 0.8rem; font-weight: 700; color: #2563eb; display: block; margin-bottom: 0.5rem;">
                        👀 Preview File (Sebelum Disimpan):
                    </span>
                    <div id="new-preview-container" style="max-width: 480px; border-radius: 6px; overflow: hidden; background: #0f172a;"></div>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; align-items: center; border-top: 1px solid #f1f5f9; padding-top: 1.5rem;">
                <button type="submit" style="background: #1b3a60; color: #ffffff; border: none; padding: 0.75rem 1.5rem; border-radius: 6px; font-weight: 600; font-size: 0.95rem; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#13294b'" onmouseout="this.style.background='#1b3a60'">
                    Simpan Billboard
                </button>
                <a href="{{ route('admin.billboards.index') }}" style="background: #ffffff; color: #64748b; border: 1px solid #cbd5e1; padding: 0.75rem 1.5rem; border-radius: 6px; font-weight: 600; font-size: 0.95rem; text-decoration: none; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'; this.style.color='#0f172a'" onmouseout="this.style.background='#ffffff'; this.style.color='#64748b'">
                    Batal
                </a>
            </div>
        </form>
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

{{-- Auto-preview kode billboard --}}
<script>
const cityMap = @json(\App\Models\Billboard::getCityMapForJs());

function getAbbr(city) {
    const upper = city.trim().toUpperCase();
    if (cityMap[upper]) return cityMap[upper];

    // Fallback: consonants
    const clean = upper.replace(/[^A-Z]/g, '');
    const cons = clean.replace(/[AEIOU]/g, '');
    if (cons.length >= 3) return cons.substring(0, 3);
    return clean.substring(0, 3) || '???';
}

function updateCodePreview() {
    const jenis = document.getElementById('jenis').value;
    const city = document.getElementById('city').value;
    const sisi = document.getElementById('sisi').value;

    if (!city.trim()) {
        document.getElementById('code-text').textContent = '— Isi jenis, kota, dan sisi —';
        return;
    }

    const prefix = jenis === 'midiboard' ? '01' : '00';
    const abbr = getAbbr(city);
    const side = sisi === '2' ? 'II' : 'I';
    const seq = '{{ $nextSeq ?? "?" }}';

    document.getElementById('code-text').textContent = `#${prefix}${seq}-${abbr}-${side}`;
}

document.getElementById('jenis').addEventListener('change', updateCodePreview);
document.getElementById('city').addEventListener('input', updateCodePreview);
document.getElementById('sisi').addEventListener('change', updateCodePreview);

updateCodePreview();
</script>

@endsection
