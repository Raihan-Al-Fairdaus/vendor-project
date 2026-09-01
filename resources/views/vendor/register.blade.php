@extends('layouts.public')
@section('title', 'Register Vendor - DNA Vendor Portal')
@section('content')
<style>
/* Reset and Defaults */
nav.navbar { display: none !important; }
footer.vc-footer { display: none !important; }
body { background-color: var(--navy); font-family: 'Inter', sans-serif; margin: 0; }
:root {
    --navy: #1b3a60;
    --navy-light: #244b7a;
    --navy-dark: #122845;
    --gold: #f59e0b;
    --gold-hover: #d97706;
    --text-gray: #94a3b8;
}

/* NAVBAR */
.custom-nav {
    background: transparent;
    padding: 1.5rem 5%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: absolute;
    top: 0; left: 0; right: 0;
    z-index: 100;
}
.nav-logo { display: flex; align-items: center; gap: 0.5rem; text-decoration: none; }
.nav-logo img { height: 28px; filter: brightness(0) invert(1); }
.nav-logo span { font-weight: 700; color: #fff; font-size: 1.2rem; }
.nav-logo span .text-red { color: #e11d48; }
.nav-links { display: flex; gap: 2rem; align-items: center; }
.nav-links a { text-decoration: none; color: rgba(255,255,255,0.8); font-weight: 600; font-size: 0.95rem; transition: 0.2s; }
.nav-links a:hover, .nav-links a.active { color: var(--gold); }
.nav-links a.active { border-bottom: 2px solid var(--gold); padding-bottom: 4px; }
.btn-nav-contact { background: transparent; border: 1px solid rgba(255,255,255,0.3); color: #fff; padding: 0.5rem 1.25rem; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; transition: 0.2s; font-size: 0.9rem; }
.btn-nav-contact:hover { background: rgba(255,255,255,0.1); }
.mobile-menu-btn { display: none; font-size: 1.5rem; color: #fff; background: none; border: none; cursor: pointer; }

/* HERO SECTION */
.hero-wrapper {
    padding: 8rem 5% 4rem;
    display: flex;
    position: relative;
    overflow: hidden;
    min-height: 400px;
}
.hero-bg-image {
    position: absolute;
    top: 0; right: 0; bottom: 0;
    width: 60%;
    background-image: url('{{ asset("images/hero-billboard.png") }}');
    background-size: cover;
    background-position: center;
    mask-image: linear-gradient(to right, transparent, black 40%);
    -webkit-mask-image: linear-gradient(to right, transparent, black 40%);
    opacity: 0.85;
    z-index: 0;
}
.hero-content {
    position: relative;
    z-index: 10;
    max-width: 600px;
}
.eyebrow-pill {
    color: var(--gold);
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    margin-bottom: 1rem;
}
.hero-title { font-size: 3.2rem; font-weight: 800; line-height: 1.15; margin-bottom: 1.5rem; color: #fff; }
.hero-title .text-gold { color: var(--gold); }
.hero-desc { font-size: 0.95rem; color: rgba(255,255,255,0.8); line-height: 1.6; margin-bottom: 3rem; max-width: 90%; }
.hero-features { display: flex; gap: 2rem; }
.hf-item { display: flex; align-items: center; gap: 0.75rem; color: #fff; }
.hf-icon { color: var(--gold); font-size: 1.25rem; }
.hf-text { font-size: 0.8rem; line-height: 1.3; }
.hf-text strong { display: block; font-weight: 600; margin-bottom: 2px; }

/* FORM LAYOUT */
.form-layout {
    max-width: 1200px;
    margin: 0 auto 4rem;
    padding: 0 5%;
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 2rem;
    position: relative;
    z-index: 10;
}

/* SIDEBAR STEPS */
.sidebar-steps {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}
.steps-box {
    background: rgba(18, 40, 69, 0.5);
    border-radius: 16px;
    padding: 1.5rem;
    border: 1px solid rgba(255,255,255,0.05);
    backdrop-filter: blur(10px);
}
.steps-title { color: rgba(255,255,255,0.9); font-size: 0.95rem; font-weight: 600; margin-bottom: 1.5rem; }
.step-item { display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem; color: rgba(255,255,255,0.6); padding: 0.75rem 1rem; border-radius: 12px; cursor: pointer; transition: 0.3s; }
.step-item.active { background: #fff; color: var(--navy); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
.step-number { width: 32px; height: 32px; border-radius: 50%; border: 1px solid rgba(255,255,255,0.3); display: flex; align-items: center; justify-content: center; font-size: 0.85rem; font-weight: 700; flex-shrink: 0; }
.step-item.active .step-number { background: #eff6ff; border-color: #3b82f6; color: #3b82f6; }
.step-item.completed .step-number { background: #10b981; border-color: #10b981; color: #fff; }
.step-text h4 { margin: 0 0 2px; font-size: 0.9rem; font-weight: 700; }
.step-text p { margin: 0; font-size: 0.75rem; opacity: 0.8; }
.step-item.active .step-text h4 { color: var(--navy); }
.step-item.active .step-text p { color: var(--text-gray); }

.help-box {
    background: rgba(18, 40, 69, 0.5);
    border-radius: 16px;
    padding: 1.5rem;
    border: 1px solid rgba(255,255,255,0.05);
    display: flex;
    flex-direction: column;
    gap: 1rem;
    backdrop-filter: blur(10px);
}
.help-box .icon { font-size: 1.5rem; color: #fff; margin-bottom: -0.5rem; }
.help-box h4 { color: #fff; margin: 0; font-size: 0.95rem; font-weight: 600; }
.help-box p { color: rgba(255,255,255,0.7); font-size: 0.8rem; margin: 0; line-height: 1.5; }
.btn-help { background: #fff; color: var(--navy); padding: 0.6rem 1rem; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.85rem; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; }

/* RIGHT CONTENT (FORM) */
.form-card {
    background: #fff;
    border-radius: 20px;
    padding: 2.5rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}
.form-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
    border-bottom: 1px solid #f1f5f9;
    padding-bottom: 1.5rem;
}
.form-header .icon { width: 48px; height: 48px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--navy); font-size: 1.25rem; }
.form-header h2 { margin: 0; font-size: 1.15rem; color: var(--navy); font-weight: 700; }

.form-group { margin-bottom: 1.5rem; }
.form-label { display: block; font-size: 0.85rem; font-weight: 600; color: var(--navy); margin-bottom: 0.5rem; }
.form-label span.req { color: #ef4444; }
.form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.95rem;
    color: var(--navy);
    background: #fff;
    transition: 0.2s;
}
.form-control:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
.help-text { display: block; font-size: 0.75rem; color: var(--text-gray); margin-top: 0.5rem; line-height: 1.5; }
.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }

/* File Upload specific styles */
.file-upload-box {
    border: 1px dashed #cbd5e1;
    border-radius: 8px;
    padding: 1.5rem;
    text-align: center;
    background: #f8fafc;
    cursor: pointer;
    transition: 0.2s;
    position: relative;
}
.file-upload-box:hover { border-color: #3b82f6; background: #eff6ff; }
.file-upload-box input[type="file"] { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
.file-upload-box i { font-size: 1.5rem; color: #94a3b8; margin-bottom: 0.5rem; }
.file-upload-box p { margin: 0; font-size: 0.85rem; color: var(--navy); font-weight: 500; }
.file-upload-box span { font-size: 0.75rem; color: var(--text-gray); }

.photo-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 10px; margin-top: 10px; }
.photo-item { width: 100%; aspect-ratio: 1; border-radius: 8px; overflow: hidden; position: relative; border: 1px solid #e2e8f0; }
.photo-item img { width: 100%; height: 100%; object-fit: cover; }

/* Agreement styling */
.agreement-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; }
.agreement-box label { display: flex; gap: 1rem; align-items: flex-start; cursor: pointer; }
.agreement-box input[type="checkbox"] { margin-top: 4px; width: 18px; height: 18px; }
.agreement-text { font-size: 0.85rem; color: var(--text-gray); line-height: 1.5; }

.form-actions { display: flex; justify-content: flex-end; gap: 1rem; margin-top: 3rem; border-top: 1px solid #f1f5f9; padding-top: 1.5rem; }
.btn-draft { background: #fff; border: 1px solid #e2e8f0; color: var(--navy); padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.2s; font-size: 0.9rem; }
.btn-draft:hover { background: #f8fafc; border-color: #cbd5e1; }
.btn-next, .btn-submit { background: var(--gold); border: none; color: var(--navy); padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 700; cursor: pointer; transition: 0.2s; display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; }
.btn-next:hover, .btn-submit:hover { background: var(--gold-hover); }
.btn-prev { background: #f1f5f9; border: none; color: var(--navy); padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.2s; font-size: 0.9rem; }
.btn-prev:hover { background: #e2e8f0; }

.step-content { display: none; }
.step-content.active { display: block; animation: fadeIn 0.3s ease; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

/* CUSTOM FOOTER */
.custom-footer { background: #0c1a2c; color: #fff; padding: 4rem 5% 2rem; border-top: 1px solid rgba(255,255,255,0.05); }
.footer-grid { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 2fr 1fr 1.5fr 1.5fr; gap: 3rem; }
.footer-col h4 { font-size: 0.95rem; font-weight: 600; margin: 0 0 1.5rem; color: #fff; }
.footer-logo-wrap { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; }
.footer-logo-wrap img { height: 24px; filter: brightness(0) invert(1); }
.footer-logo-wrap span { font-weight: 700; font-size: 1.1rem; }
.footer-col p { color: rgba(255,255,255,0.7); font-size: 0.85rem; line-height: 1.6; margin-bottom: 1.5rem; }
.social-links { display: flex; gap: 1rem; }
.social-links a { color: #fff; opacity: 0.7; font-size: 1.2rem; transition: 0.2s; }
.social-links a:hover { opacity: 1; color: var(--gold); }
.footer-links { list-style: none; padding: 0; margin: 0; }
.footer-links li { margin-bottom: 0.75rem; }
.footer-links a { color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.85rem; transition: 0.2s; }
.footer-links a:hover { color: var(--gold); }
.contact-item { display: flex; gap: 0.75rem; margin-bottom: 1rem; color: rgba(255,255,255,0.7); font-size: 0.85rem; line-height: 1.5; }
.contact-item i { margin-top: 0.2rem; }
.footer-bottom { max-width: 1200px; margin: 3rem auto 0; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.1); display: flex; justify-content: space-between; color: rgba(255,255,255,0.5); font-size: 0.75rem; }

/* READY TO JOIN BOX in Footer */
.ready-box { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 12px; }
.ready-box h4 { margin-bottom: 0.5rem; font-size: 1rem; font-weight: 700; color: #fff; }
.ready-box p { font-size: 0.8rem; margin-bottom: 1.25rem; color: rgba(255,255,255,0.7); line-height: 1.5; }
.btn-gold { background: var(--gold); color: var(--navy); padding: 0.6rem 1rem; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 0.85rem; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; transition: 0.2s; }
.btn-gold:hover { background: var(--gold-hover); }

/* MOBILE RESPONSIVE */
@media (max-width: 992px) {
    .nav-links, .btn-nav-contact { display: none; }
    .mobile-menu-btn { display: block; }
    .hero-wrapper { padding: 6rem 5% 3rem; flex-direction: column; }
    .hero-bg-image { width: 100%; mask-image: linear-gradient(to bottom, transparent, black 80%); -webkit-mask-image: linear-gradient(to bottom, transparent, black 80%); }
    .hero-features { flex-direction: column; gap: 1rem; }
    .hero-title { font-size: 2.25rem; }
    .form-layout { grid-template-columns: 1fr; }
    .footer-grid { grid-template-columns: 1fr; gap: 2rem; }
    .grid-2 { grid-template-columns: 1fr; }
    
    .sidebar-steps { display: none; } 
    .mobile-steps { display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1.5rem; }
    .mobile-step-item { background: rgba(18, 40, 69, 0.5); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 1rem; display: flex; align-items: center; gap: 1rem; color: rgba(255,255,255,0.6); backdrop-filter: blur(10px); }
    .mobile-step-item.active { background: #fff; color: var(--navy); }
    .mobile-step-item .step-number { width: 32px; height: 32px; border-radius: 50%; border: 1px solid rgba(255,255,255,0.3); display: flex; align-items: center; justify-content: center; font-size: 0.85rem; font-weight: 700; flex-shrink: 0; }
    .mobile-step-item.active .step-number { background: #eff6ff; color: #3b82f6; border-color: #3b82f6; }
    .mobile-step-item .step-text h4 { margin: 0; font-size: 0.9rem; font-weight: 700; }
    .mobile-step-item .step-text p { margin: 0; font-size: 0.75rem; opacity: 0.8; }
    .mobile-step-item.active .step-text p { color: var(--text-gray); }
    
    .mobile-help { margin-top: 1.5rem; }
}
@media (min-width: 993px) {
    .mobile-steps, .mobile-help { display: none; }
}
</style>

<!-- NAVBAR -->
<nav class="custom-nav">
    <button class="mobile-menu-btn"><i class="fa-solid fa-bars"></i></button>
    <a href="#" class="nav-logo">
        <img src="{{ asset('images/logo.png') }}" alt="Logo">
        <span>DNA <span class="text-red">Vendor</span> Portal</span>
    </a>
    <div class="nav-links">
        <a href="{{ url('/') }}">Beranda</a>
        <a href="#" class="active">Daftar Vendor</a>
        <a href="{{ url('/') }}#why-partner">Mengapa Bermitra</a>
        <a href="{{ route('faq') }}">FAQ</a>
    </div>
    <a href="https://wa.me/6281228358630" target="_blank" class="btn-nav-contact"><i class="fa-regular fa-user"></i> Hubungi Kami</a>
</nav>

<!-- HERO SECTION -->
<section class="hero-wrapper">
    <div class="hero-bg-image"></div>
    <div class="hero-content">
        <div class="eyebrow-pill">GABUNG BERSAMA KAMI</div>
        <h1 class="hero-title">Menjadi Mitra<br><span class="text-gold">Terpercaya</span></h1>
        <p class="hero-desc">Lengkapi data perusahaan Anda untuk bergabung dalam jaringan vendor terverifikasi DNA Advertising. Proses verifikasi kami hanya memakan waktu 2-3 hari kerja.</p>
        <div class="hero-features">
            <div class="hf-item">
                <i class="fa-solid fa-shield-halved hf-icon"></i>
                <div class="hf-text">
                    <strong>Proses Aman</strong>
                    dan Terverifikasi
                </div>
            </div>
            <div class="hf-item">
                <i class="fa-regular fa-clock hf-icon"></i>
                <div class="hf-text">
                    <strong>Verifikasi Cepat</strong>
                    2-3 Hari Kerja
                </div>
            </div>
            <div class="hf-item">
                <i class="fa-solid fa-users hf-icon"></i>
                <div class="hf-text">
                    <strong>Jaringan Mitra</strong>
                    Terpercaya
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FORM LAYOUT -->
<div class="form-layout">
    
    <!-- LEFT SIDEBAR -->
    <div class="sidebar-steps">
        <div class="steps-box">
            <div class="steps-title">Langkah Pendaftaran</div>
            <div class="step-item active" id="nav-step1">
                <div class="step-number">1</div>
                <div class="step-text">
                    <h4>Informasi Perusahaan</h4>
                    <p>Data umum perusahaan</p>
                </div>
            </div>
            <div class="step-item" id="nav-step2">
                <div class="step-number">2</div>
                <div class="step-text">
                    <h4>Kontak & Dokumen</h4>
                    <p>Informasi kontak & dokumen</p>
                </div>
            </div>
            <div class="step-item" id="nav-step3">
                <div class="step-number">3</div>
                <div class="step-text">
                    <h4>Tinjau & Kirim</h4>
                    <p>Periksa dan kirim pendaftaran</p>
                </div>
            </div>
        </div>
        
        <div class="help-box">
            <i class="fa-solid fa-headset icon"></i>
            <h4>Butuh Bantuan?</h4>
            <p>Tim kami siap membantu Anda selama proses pendaftaran.</p>
            <a href="https://wa.me/6281228358630" target="_blank" class="btn-help"><i class="fa-regular fa-user"></i> Hubungi Kami</a>
        </div>
    </div>

    <!-- RIGHT FORM CARD -->
    <div class="form-card">
        
        @if(session('success'))
            <div style="background: #10b981; color: #fff; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="background: #ef4444; color: #fff; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div style="background: #ef4444; color: #fff; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                <ul style="margin: 0; padding-left: 1.5rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="vendorForm" action="{{ route('vendor.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Hidden inputs for Supabase presigned URL -->
            <input type="hidden" name="id_card_url" id="id_card_url">
            <input type="hidden" name="bank_book_url" id="bank_book_url">
            <input type="hidden" name="npwp_file_url" id="npwp_file_url">
            <input type="hidden" name="office_photos_urls" id="office_photos_urls">
            
            <!-- MOBILE STEPS (Shown only on mobile above current step) -->
            <div class="mobile-steps" id="mobile-steps-container">
                <!-- Javascript will render active step here -->
            </div>

            <!-- STEP 1 -->
            <div class="step-content active" id="step1">
                <div class="form-header">
                    <div class="icon"><i class="fa-regular fa-building"></i></div>
                    <h2>Informasi Perusahaan</h2>
                </div>
                
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Nama Perusahaan <span class="req">*</span></label>
                        <input type="text" name="company_name" class="form-control" placeholder="Masukkan nama perusahaan" value="{{ old('company_name') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kategori Vendor <span class="req">*</span></label>
                        <select name="business_category" class="form-control" required>
                            <option value="" disabled selected>Pilih kategori vendor</option>
                            <option value="Perorangan" {{ old('business_category') == 'Perorangan' ? 'selected' : '' }}>Perorangan</option>
                            <option value="Perusahaan" {{ old('business_category') == 'Perusahaan' ? 'selected' : '' }}>Perusahaan</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Alamat Perusahaan <span class="req">*</span></label>
                    <textarea name="company_address" class="form-control" rows="3" placeholder="Masukkan alamat lengkap perusahaan" required>{{ old('company_address') }}</textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Lokasi di Google Maps <span class="req">*</span></label>
                    <input type="url" name="google_maps_link" class="form-control" placeholder="https://maps.app.goo.gl/xxxxxxxxxxxx" value="{{ old('google_maps_link') }}" required>
                    <span class="help-text">Buka Google Maps → pilih lokasi → Bagikan → Salin link → Tempel di sini.</span>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Nomor NPWP <span class="req">*</span></label>
                    <input type="text" name="npwp" class="form-control" placeholder="00.000.000.0-000.000" value="{{ old('npwp') }}" required>
                    <span class="help-text">Masukkan NPWP perusahaan Anda</span>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn-draft">Simpan Draf</button>
                    <button type="button" class="btn-next" onclick="goToStep(2)">Selanjutnya <i class="fa-solid fa-arrow-right"></i></button>
                </div>
            </div>

            <!-- STEP 2 -->
            <div class="step-content" id="step2">
                <div class="form-header">
                    <div class="icon"><i class="fa-regular fa-id-badge"></i></div>
                    <h2>Kontak & Dokumen</h2>
                </div>
                
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Email Perusahaan <span class="req">*</span></label>
                        <input type="email" name="company_email" class="form-control" placeholder="email@perusahaan.com" value="{{ old('company_email') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nomor Telepon <span class="req">*</span></label>
                        <input type="text" name="company_phone" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('company_phone') }}" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Nama PIC / Penanggung Jawab <span class="req">*</span></label>
                    <input type="text" name="pic_name" class="form-control" placeholder="Masukkan nama PIC" value="{{ old('pic_name') }}" required>
                </div>
                
                <hr style="border:0; border-top: 1px solid #f1f5f9; margin: 2rem 0;">
                
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Upload KTP <span class="req">*</span></label>
                        <div class="file-upload-box">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            <p>Pilih file KTP</p>
                            <span>JPG, PNG, PDF max 5MB</span>
                            <input type="file" name="id_card" id="idCardInput" accept=".jpg,.jpeg,.png,.pdf" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Upload NPWP <span class="req">*</span></label>
                        <div class="file-upload-box">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            <p>Pilih file NPWP</p>
                            <span>JPG, PNG, PDF max 5MB</span>
                            <input type="file" name="npwp_file" id="npwpInput" accept=".jpg,.jpeg,.png,.pdf" required>
                        </div>
                    </div>
                </div>
                
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Upload Buku Rekening <span class="req">*</span></label>
                        <div class="file-upload-box">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            <p>Pilih file Rekening</p>
                            <span>Halaman depan buku rekening</span>
                            <input type="file" name="bank_book" id="bankBookInput" accept=".jpg,.jpeg,.png,.pdf" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Upload Foto Kantor <span class="req">*</span></label>
                        <div class="file-upload-box">
                            <i class="fa-solid fa-images"></i>
                            <p>Pilih Foto Kantor</p>
                            <span>Tampak depan & dalam (Min 2)</span>
                            <input type="file" name="office_photos[]" id="officePhotosInput" accept=".jpg,.jpeg,.png" multiple required>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn-prev" onclick="goToStep(1)">Kembali</button>
                    <button type="button" class="btn-next" onclick="goToStep(3)">Selanjutnya <i class="fa-solid fa-arrow-right"></i></button>
                </div>
            </div>

            <!-- STEP 3 -->
            <div class="step-content" id="step3">
                <div class="form-header">
                    <div class="icon"><i class="fa-solid fa-check-double"></i></div>
                    <h2>Tinjau & Kirim</h2>
                </div>
                
                <p style="color: var(--text-gray); font-size: 0.9rem; margin-bottom: 2rem;">Silakan tinjau kembali data yang telah Anda masukkan pada langkah sebelumnya. Jika sudah benar, setujui syarat dan ketentuan untuk mengirimkan pendaftaran.</p>
                
                <div class="agreement-box">
                    <label>
                        <input type="checkbox" name="agreement" id="agreement" required>
                        <div class="agreement-text">
                            <strong>Saya menyetujui Syarat dan Ketentuan</strong><br>
                            Dengan mencentang kotak ini, saya menyatakan bahwa data yang saya berikan adalah benar dan dapat dipertanggungjawabkan. Saya setuju untuk terikat oleh MOU Perjanjian Kerjasama Vendor DNA Advertising.
                        </div>
                    </label>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn-prev" onclick="goToStep(2)">Kembali</button>
                    <button type="submit" class="btn-submit" id="submitBtn">Kirim Pendaftaran</button>
                </div>
            </div>
            
        </form>
        
        <!-- MOBILE HELP (Shown below form on mobile) -->
        <div class="help-box mobile-help">
            <i class="fa-solid fa-headset icon"></i>
            <h4>Butuh Bantuan?</h4>
            <p>Tim kami siap membantu Anda selama proses pendaftaran.</p>
            <a href="https://wa.me/6281228358630" target="_blank" class="btn-help"><i class="fa-regular fa-user"></i> Hubungi Kami</a>
        </div>
        
    </div>
</div>

<!-- CUSTOM FOOTER -->
<footer class="custom-footer">
    <div class="footer-grid">
        <div class="footer-col">
            <div class="footer-logo-wrap">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
                <span>DNA <span class="text-red">Vendor</span> Portal</span>
            </div>
            <p>Platform pendaftaran vendor resmi untuk kemitraan bisnis terpercaya bersama DNA Advertising.</p>
            <div class="social-links">
                <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                <a href="https://wa.me/6281228358630" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>
        <div class="footer-col">
            <h4>Navigasi</h4>
            <ul class="footer-links">
                <li><a href="{{ url('/') }}">Beranda</a></li>
                <li><a href="{{ route('vendor.register') }}">Daftar Vendor</a></li>
                <li><a href="{{ url('/') }}#why-partner">Mengapa Bermitra</a></li>
                <li><a href="{{ route('faq') }}">FAQ</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Informasi Kontak</h4>
            <div class="contact-item">
                <i class="fa-solid fa-location-dot"></i>
                <div>Jl. Taman Dhika BL 6 No. 3A<br>Sono, Sidoarjo<br>Buduran, Sidoarjo</div>
            </div>
            <div class="contact-item">
                <i class="fa-regular fa-clock"></i>
                <div>Senin - Jumat<br>08:00 - 17:00 WIB</div>
            </div>
            <div class="contact-item">
                <i class="fa-solid fa-envelope"></i>
                <div>Email Segera Hadir</div>
            </div>
            <div class="contact-item">
                <i class="fa-solid fa-phone"></i>
                <div>Telepon Segera Hadir</div>
            </div>
        </div>
        <div class="footer-col">
            <div class="ready-box">
                <h4>Siap Bergabung?</h4>
                <p>Jadilah bagian dari jaringan vendor terpercaya DNA Advertising.</p>
                <a href="{{ route('vendor.register') }}" class="btn-gold">Daftar Sekarang <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div>© {{ date('Y') }} DNA Advertising. Semua hak dilindungi.</div>
        <div>Vendor Registration Portal</div>
    </div>
</footer>

<script>
// Step Navigation Logic
const steps = [
    { id: 1, title: 'Informasi Perusahaan', desc: 'Data umum perusahaan' },
    { id: 2, title: 'Kontak & Dokumen', desc: 'Informasi kontak & dokumen' },
    { id: 3, title: 'Tinjau & Kirim', desc: 'Periksa dan kirim pendaftaran' }
];

function goToStep(stepNumber) {
    // Hide all steps
    document.querySelectorAll('.step-content').forEach(el => el.classList.remove('active'));
    // Un-active all nav items
    document.querySelectorAll('.step-item').forEach(el => el.classList.remove('active'));
    
    // Show selected step
    document.getElementById('step' + stepNumber).classList.add('active');
    
    // Update nav items
    for(let i = 1; i <= 3; i++) {
        let navItem = document.getElementById('nav-step' + i);
        if(i < stepNumber) {
            navItem.classList.add('completed');
            navItem.classList.remove('active');
            navItem.querySelector('.step-number').innerHTML = '<i class="fa-solid fa-check"></i>';
        } else if (i === stepNumber) {
            navItem.classList.add('active');
            navItem.classList.remove('completed');
            navItem.querySelector('.step-number').innerHTML = i;
        } else {
            navItem.classList.remove('active', 'completed');
            navItem.querySelector('.step-number').innerHTML = i;
        }
    }
    
    // Update Mobile Step View
    const mobileContainer = document.getElementById('mobile-steps-container');
    let mobileHTML = '';
    
    // active step
    mobileHTML += `
        <div class="mobile-step-item active">
            <div class="step-number">${stepNumber}</div>
            <div class="step-text">
                <h4>${steps[stepNumber-1].title}</h4>
                <p>${steps[stepNumber-1].desc}</p>
            </div>
        </div>
    `;
    
    // next steps below
    const nextStepsDiv = document.createElement('div');
    nextStepsDiv.className = 'mobile-steps';
    nextStepsDiv.style.marginTop = '1.5rem';
    let nextHTML = '';
    for(let i = stepNumber + 1; i <= 3; i++) {
        nextHTML += `
            <div class="mobile-step-item" style="opacity: 0.6;">
                <div class="step-number">${i}</div>
                <div class="step-text">
                    <h4>${steps[i-1].title}</h4>
                    <p>${steps[i-1].desc}</p>
                </div>
            </div>
        `;
    }
    nextStepsDiv.innerHTML = nextHTML;
    
    mobileContainer.innerHTML = mobileHTML;
    
    // Put next steps below form-card
    const oldNextSteps = document.getElementById('mobile-next-steps');
    if(oldNextSteps) oldNextSteps.remove();
    
    if(nextHTML !== '') {
        nextStepsDiv.id = 'mobile-next-steps';
        document.querySelector('.form-card').appendChild(nextStepsDiv);
    }
    
    window.scrollTo({ top: document.querySelector('.form-layout').offsetTop - 100, behavior: 'smooth' });
}

// Init
document.addEventListener("DOMContentLoaded", function () {
    goToStep(1);
});

// ============================================================
// PRESIGNED UPLOAD - Upload langsung ke Supabase dari browser
// ============================================================
document.getElementById('vendorForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = this;
    const submitBtn = document.getElementById('submitBtn');

    // Cek semua file sudah dipilih
    const idCardFile = document.getElementById('idCardInput').files[0];
    const bankBookFile = document.getElementById('bankBookInput').files[0];
    const npwpFile = document.getElementById('npwpInput').files[0];
    const officePhotos = Array.from(document.getElementById('officePhotosInput').files);

    if (!idCardFile || !bankBookFile || !npwpFile) {
        alert('Mohon lengkapi semua file yang diperlukan pada langkah 2.');
        goToStep(2);
        return;
    }

    if (officePhotos.length < 2) {
        alert('Minimal 2 foto kantor diperlukan pada langkah 2.');
        goToStep(2);
        return;
    }

    // Tampilkan loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengupload file...';

    try {
        const csrfToken = document.querySelector('input[name="_token"]').value;

        // Fungsi upload satu file
        async function uploadFile(file, folder) {
            const presignRes = await fetch('/upload/presign', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    filename: file.name,
                    folder: folder,
                    type: file.type,
                })
            });

            if (!presignRes.ok) throw new Error('Gagal mendapatkan URL upload');
            const { upload_url, public_url } = await presignRes.json();

            const uploadRes = await fetch(upload_url, {
                method: 'PUT',
                headers: { 'Content-Type': file.type },
                body: file,
            });

            if (!uploadRes.ok) throw new Error('Gagal mengupload file: ' + file.name);
            return public_url;
        }

        // Upload semua file secara bersamaan
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengupload KTP...';
        const idCardUrl = await uploadFile(idCardFile, 'id_cards');
        document.getElementById('id_card_url').value = idCardUrl;

        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengupload Buku Rekening...';
        const bankBookUrl = await uploadFile(bankBookFile, 'bank_books');
        document.getElementById('bank_book_url').value = bankBookUrl;

        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengupload NPWP...';
        const npwpUrl = await uploadFile(npwpFile, 'npwp');
        document.getElementById('npwp_file_url').value = npwpUrl;

        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengupload Foto Kantor...';
        const officePhotoUrls = [];
        for (const photo of officePhotos) {
            const url = await uploadFile(photo, 'office_photos');
            officePhotoUrls.push(url);
        }
        document.getElementById('office_photos_urls').value = JSON.stringify(officePhotoUrls);

        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan data...';

        // Disable file inputs so they don't upload directly to server
        form.querySelectorAll('input[type="file"]').forEach(input => {
            input.removeAttribute('name');
            input.disabled = true;
        });

        form.submit();

    } catch (err) {
        console.error(err);
        alert('Terjadi kesalahan saat mengupload file: ' + err.message);
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Kirim Pendaftaran';
    }
});
</script>
@endsection
