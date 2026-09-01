@extends('layouts.public')
@section('title', 'Register Vendor - DNA Vendor Portal')
@section('content')
<style>
/* Reset and Defaults */
nav.navbar { display: none !important; }
footer.vc-footer { display: none !important; }
body { margin: 0; padding: 0; }

:root {
    --navy: #1b3a60;
    --navy-light: #244b7a;
    --navy-dark: #122845;
    --gold: #f59e0b;
    --gold-hover: #d97706;
    --text-gray: #64748b;
}

.page-wrapper {
    background-color: var(--navy) !important;
    min-height: 100vh;
    width: 100%;
    font-family: 'Inter', sans-serif;
    color: #fff;
}

/* BRANDING TEXT */
.brand-text { font-weight: 700; color: #fff; }
.brand-text .brand-red { color: #e11d48; }

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

.nav-right-container {
    display: flex;
    align-items: center;
    gap: 2rem;
}

.nav-links { display: flex; gap: 2rem; align-items: center; }
.nav-links a { text-decoration: none; color: rgba(255,255,255,0.8); font-weight: 600; font-size: 0.95rem; transition: 0.2s; }
.nav-links a:hover, .nav-links a.active { color: var(--gold); }
.nav-links a.active { border-bottom: 2px solid var(--gold); padding-bottom: 4px; }
.btn-nav-contact { background: transparent; border: 1px solid rgba(255,255,255,0.3); color: #fff; padding: 0.5rem 1.25rem; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; transition: 0.2s; font-size: 0.9rem; }
.btn-nav-contact:hover { background: rgba(255,255,255,0.1); }
.mobile-menu-btn { display: none; font-size: 1.5rem; color: #fff; background: none; border: none; cursor: pointer; }

/* LANG SWITCHER (DROPDOWN) */
.lang-switcher-select {
    background: rgba(255,255,255,0.1);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.2);
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    outline: none;
    font-family: inherit;
}
.lang-switcher-select option {
    color: var(--navy);
}

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
.step-item.active { background: #f8fafc; color: var(--navy); box-shadow: 0 10px 20px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; }
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
    background: #f8fafc; /* Dimmed slightly from pure white #fff */
    border-radius: 20px;
    padding: 2.5rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    color: var(--navy); 
    border: 1px solid #e2e8f0;
}
.form-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 1.5rem;
}
.form-header .icon { width: 48px; height: 48px; background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--navy); font-size: 1.25rem; }
.form-header h2 { margin: 0; font-size: 1.15rem; color: var(--navy); font-weight: 700; }

.form-group { margin-bottom: 1.5rem; }
.form-label { display: block; font-size: 0.85rem; font-weight: 600; color: var(--navy); margin-bottom: 0.5rem; }
.form-label span.req { color: #ef4444; }
.form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-size: 0.95rem;
    color: var(--navy);
    background: #fff;
    transition: 0.2s;
    box-sizing: border-box;
}
.form-control:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
.help-text { display: block; font-size: 0.75rem; color: var(--text-gray); margin-top: 0.5rem; line-height: 1.5; }
.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }

/* File Upload specific styles */
.file-upload-box {
    border: 2px dashed #94a3b8;
    border-radius: 8px;
    padding: 1.5rem;
    text-align: center;
    background: #e2e8f0; /* Darker than before */
    cursor: pointer;
    transition: 0.2s;
    position: relative;
}
.file-upload-box.has-file {
    border-color: #10b981;
    background: #d1fae5;
}
.file-upload-box:hover { border-color: #3b82f6; background: #eff6ff; }
.file-upload-box input[type="file"] { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
.file-upload-box i { font-size: 1.5rem; color: #64748b; margin-bottom: 0.5rem; transition: 0.2s; }
.file-upload-box.has-file i { color: #10b981; }
.file-upload-box p { margin: 0; font-size: 0.85rem; color: var(--navy); font-weight: 600; }
.file-upload-box span { font-size: 0.75rem; color: #475569; display: block; margin-top: 4px; }
.file-indicator { font-size: 0.75rem; color: #10b981; font-weight: 700; margin-top: 8px; display: none; }
.file-upload-box.has-file .file-indicator { display: block; }

/* Agreement styling */
.agreement-box { background: rgba(27, 58, 96, 0.05); border: 1px solid #cbd5e1; border-radius: 12px; padding: 1.5rem; }
.agreement-box label { display: flex; gap: 1rem; align-items: flex-start; cursor: pointer; }
.agreement-box input[type="checkbox"] { margin-top: 4px; width: 18px; height: 18px; }
.agreement-text { font-size: 0.85rem; color: #334155; line-height: 1.6; }
.btn-mou { background: #3b82f6; color: #fff; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-size: 0.8rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.4rem; margin-top: 0.75rem; border: none; cursor: pointer; }
.btn-mou:hover { background: #2563eb; }

.form-actions { display: flex; justify-content: flex-end; gap: 1rem; margin-top: 3rem; border-top: 1px solid #e2e8f0; padding-top: 1.5rem; }
.btn-draft { background: #fff; border: 1px solid #cbd5e1; color: var(--navy); padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.2s; font-size: 0.9rem; }
.btn-draft:hover { background: #f1f5f9; border-color: #94a3b8; }
.btn-next, .btn-submit { background: var(--gold); border: none; color: var(--navy); padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 700; cursor: pointer; transition: 0.2s; display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; }
.btn-next:hover, .btn-submit:hover { background: var(--gold-hover); }
.btn-prev { background: #e2e8f0; border: none; color: var(--navy); padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.2s; font-size: 0.9rem; }
.btn-prev:hover { background: #cbd5e1; }

.step-content { display: none; }
.step-content.active { display: block; animation: fadeIn 0.3s ease; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

/* CUSTOM FOOTER (Restored exactly to match welcome.blade.php) */
.custom-footer { background: var(--navy); color: #fff; padding: 4rem 5% 2rem; border-top: 1px solid rgba(255,255,255,0.1); margin-top: 4rem; }
.footer-grid { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 2fr 1fr 1.5fr 1.5fr; gap: 3rem; }
.footer-col h4 { font-size: 1.1rem; font-weight: 600; margin: 0 0 1.5rem; color: #fff; }
.footer-logo-wrap { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; }
.footer-logo-wrap img { height: 28px; filter: brightness(0) invert(1); }
.footer-logo-wrap span { font-weight: 700; font-size: 1.2rem; }
.footer-logo-wrap span .text-red { color: #e11d48; }
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

/* MODAL MOU */
.mou-modal { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); z-index: 9999; align-items: center; justify-content: center; padding: 2rem; backdrop-filter: blur(5px); }
.mou-modal.show { display: flex; }
.mou-content { background: #fff; width: 100%; max-width: 800px; border-radius: 16px; display: flex; flex-direction: column; max-height: 90vh; position: relative; overflow: hidden; }
.mou-watermark { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 70%; height: 70%; background-image: url('{{ asset("images/logo.png") }}'); background-repeat: no-repeat; background-position: center; background-size: contain; opacity: 0.05; pointer-events: none; z-index: 0; }
.mou-header { padding: 1.5rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 2; background: #fff; }
.mou-header h3 { margin: 0; color: var(--navy); font-size: 1.25rem; font-weight: 800; text-align: center; width: 100%; }
.mou-close { position: absolute; right: 1.5rem; background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #64748b; }
.mou-body { padding: 2rem; overflow-y: auto; color: #1e293b; font-size: 0.95rem; line-height: 1.6; position: relative; z-index: 1; text-align: justify; }
.mou-body h4 { color: var(--navy); font-size: 1.05rem; font-weight: 700; margin-top: 1.5rem; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem; }
.mou-body h4 .num { background: var(--navy); color: var(--gold); border-radius: 4px; padding: 2px 8px; font-size: 0.9rem; }
.mou-footer { padding: 1.5rem; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 1rem; background: #f8fafc; position: relative; z-index: 2; }

/* MOBILE RESPONSIVE */
@media (max-width: 992px) {
    .nav-links { display: none; }
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
    .mobile-step-item.active { background: #f8fafc; color: var(--navy); }
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

.lang-en { display: none; }
</style>

<div class="page-wrapper">
    <!-- NAVBAR -->
    <nav class="custom-nav">
        <button class="mobile-menu-btn"><i class="fa-solid fa-bars"></i></button>
        <a href="#" class="nav-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
            <span>DNA <span class="text-red">Vendor</span> Portal</span>
        </a>
        
        <div class="nav-right-container">
            <div class="nav-links">
                <a href="{{ url('/') }}"><span class="lang-id">Beranda</span><span class="lang-en">Home</span></a>
                <a href="#" class="active"><span class="lang-id">Daftar Vendor</span><span class="lang-en">Register Vendor</span></a>
                <a href="{{ url('/') }}#why-partner"><span class="lang-id">Mengapa Bermitra</span><span class="lang-en">Why Partner</span></a>
                <a href="{{ route('faq') }}">FAQ</a>
            </div>
            
            <select class="lang-switcher-select" onchange="switchLang(this.value)">
                <option value="id">Bahasa Indonesia</option>
                <option value="en">English</option>
            </select>

            <a href="https://wa.me/6281228358630" target="_blank" class="btn-nav-contact">
                <i class="fa-regular fa-user"></i> <span class="lang-id">Hubungi Kami</span><span class="lang-en">Contact Us</span>
            </a>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero-wrapper">
        <div class="hero-bg-image"></div>
        <div class="hero-content">
            <div class="eyebrow-pill"><span class="lang-id">GABUNG BERSAMA KAMI</span><span class="lang-en">JOIN US TODAY</span></div>
            <h1 class="hero-title">
                <span class="lang-id">Menjadi Mitra<br><span class="text-gold">Terpercaya</span></span>
                <span class="lang-en">Become a Trusted<br><span class="text-gold">Partner</span></span>
            </h1>
            <p class="hero-desc">
                <span class="lang-id">Lengkapi data perusahaan Anda untuk bergabung dalam jaringan vendor terverifikasi <span class="brand-text">DNA <span class="brand-red">Advertising</span></span>. Proses verifikasi kami hanya memakan waktu 2-3 hari kerja.</span>
                <span class="lang-en">Complete your company profile to join <span class="brand-text">DNA <span class="brand-red">Advertising</span></span>'s verified vendor network. Our verification process only takes 2-3 working days.</span>
            </p>
            <div class="hero-features">
                <div class="hf-item">
                    <i class="fa-solid fa-shield-halved hf-icon"></i>
                    <div class="hf-text">
                        <strong><span class="lang-id">Proses Aman</span><span class="lang-en">Secure Process</span></strong>
                        <span class="lang-id">dan Terverifikasi</span><span class="lang-en">and Verified</span>
                    </div>
                </div>
                <div class="hf-item">
                    <i class="fa-regular fa-clock hf-icon"></i>
                    <div class="hf-text">
                        <strong><span class="lang-id">Verifikasi Cepat</span><span class="lang-en">Fast Verification</span></strong>
                        <span class="lang-id">2-3 Hari Kerja</span><span class="lang-en">2-3 Work Days</span>
                    </div>
                </div>
                <div class="hf-item">
                    <i class="fa-solid fa-users hf-icon"></i>
                    <div class="hf-text">
                        <strong><span class="lang-id">Jaringan Mitra</span><span class="lang-en">Partner Network</span></strong>
                        <span class="lang-id">Terpercaya</span><span class="lang-en">Trusted Globally</span>
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
                <div class="steps-title"><span class="lang-id">Langkah Pendaftaran</span><span class="lang-en">Registration Steps</span></div>
                
                <div class="step-item active" id="nav-step1" onclick="goToStep(1)">
                    <div class="step-number">1</div>
                    <div class="step-text">
                        <h4><span class="lang-id">Informasi Perusahaan</span><span class="lang-en">Company Information</span></h4>
                        <p><span class="lang-id">Data umum perusahaan</span><span class="lang-en">General company data</span></p>
                    </div>
                </div>
                <div class="step-item" id="nav-step2" onclick="goToStep(2)">
                    <div class="step-number">2</div>
                    <div class="step-text">
                        <h4><span class="lang-id">Kontak & Dokumen</span><span class="lang-en">Contact & Documents</span></h4>
                        <p><span class="lang-id">Informasi kontak & dokumen</span><span class="lang-en">Contact info & legal docs</span></p>
                    </div>
                </div>
                <div class="step-item" id="nav-step3" onclick="goToStep(3)">
                    <div class="step-number">3</div>
                    <div class="step-text">
                        <h4><span class="lang-id">Tinjau & Kirim</span><span class="lang-en">Review & Submit</span></h4>
                        <p><span class="lang-id">Periksa dan kirim pendaftaran</span><span class="lang-en">Review and send application</span></p>
                    </div>
                </div>
            </div>
            
            <div class="help-box">
                <i class="fa-solid fa-headset icon"></i>
                <h4><span class="lang-id">Butuh Bantuan?</span><span class="lang-en">Need Help?</span></h4>
                <p><span class="lang-id">Tim kami siap membantu Anda selama proses pendaftaran.</span><span class="lang-en">Our team is ready to assist you during the registration process.</span></p>
                <a href="https://wa.me/6281228358630" target="_blank" class="btn-help">
                    <i class="fa-regular fa-user"></i> <span class="lang-id">Hubungi Kami</span><span class="lang-en">Contact Us</span>
                </a>
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
                        <h2><span class="lang-id">Informasi Perusahaan</span><span class="lang-en">Company Information</span></h2>
                    </div>
                    
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label"><span class="lang-id">Nama Perusahaan</span><span class="lang-en">Company Name</span> <span class="req">*</span></label>
                            <input type="text" name="company_name" class="form-control" placeholder="..." value="{{ old('company_name') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><span class="lang-id">Kategori Vendor</span><span class="lang-en">Vendor Category</span> <span class="req">*</span></label>
                            <select name="business_category" class="form-control" required>
                                <option value="" disabled selected class="lang-id">Pilih kategori vendor</option>
                                <option value="" disabled selected class="lang-en">Select category</option>
                                <option value="Perorangan" {{ old('business_category') == 'Perorangan' ? 'selected' : '' }}>Perorangan / Individual</option>
                                <option value="Perusahaan" {{ old('business_category') == 'Perusahaan' ? 'selected' : '' }}>Perusahaan / Enterprise</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label"><span class="lang-id">Alamat Perusahaan</span><span class="lang-en">Company Address</span> <span class="req">*</span></label>
                        <textarea name="company_address" class="form-control" rows="3" placeholder="..." required>{{ old('company_address') }}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label"><span class="lang-id">Lokasi di Google Maps</span><span class="lang-en">Google Maps Location</span> <span class="req">*</span></label>
                        <input type="url" name="google_maps_link" class="form-control" placeholder="https://maps.app.goo.gl/xxxxxxxxxxxx" value="{{ old('google_maps_link') }}" required>
                        <span class="help-text">
                            <span class="lang-id">Buka Google Maps → pilih lokasi → Bagikan → Salin link → Tempel di sini.</span>
                            <span class="lang-en">Open Google Maps → select location → Share → Copy link → Paste here.</span>
                        </span>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label"><span class="lang-id">Nomor NPWP</span><span class="lang-en">Tax ID (NPWP)</span> <span class="req">*</span></label>
                        <input type="text" name="npwp" class="form-control" placeholder="00.000.000.0-000.000" value="{{ old('npwp') }}" required>
                        <span class="help-text">
                            <span class="lang-id">Masukkan NPWP perusahaan Anda</span>
                            <span class="lang-en">Enter your company's Tax ID</span>
                        </span>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn-draft"><span class="lang-id">Simpan Draf</span><span class="lang-en">Save Draft</span></button>
                        <button type="button" class="btn-next" onclick="goToStep(2)"><span class="lang-id">Selanjutnya</span><span class="lang-en">Next Step</span> <i class="fa-solid fa-arrow-right"></i></button>
                    </div>
                </div>

                <!-- STEP 2 -->
                <div class="step-content" id="step2">
                    <div class="form-header">
                        <div class="icon"><i class="fa-regular fa-id-badge"></i></div>
                        <h2><span class="lang-id">Kontak & Dokumen</span><span class="lang-en">Contact & Documents</span></h2>
                    </div>
                    
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label"><span class="lang-id">Email Perusahaan</span><span class="lang-en">Company Email</span> <span class="req">*</span></label>
                            <input type="email" name="company_email" class="form-control" placeholder="email@perusahaan.com" value="{{ old('company_email') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><span class="lang-id">Nomor Telepon</span><span class="lang-en">Phone Number</span> <span class="req">*</span></label>
                            <input type="text" name="company_phone" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('company_phone') }}" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label"><span class="lang-id">Nama PIC / Penanggung Jawab</span><span class="lang-en">PIC / Person in Charge Name</span> <span class="req">*</span></label>
                        <input type="text" name="pic_name" class="form-control" placeholder="..." value="{{ old('pic_name') }}" required>
                    </div>
                    
                    <hr style="border:0; border-top: 1px solid #e2e8f0; margin: 2rem 0;">
                    
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label"><span class="lang-id">Upload KTP</span><span class="lang-en">Upload ID Card (KTP)</span> <span class="req">*</span></label>
                            <div class="file-upload-box" id="box-ktp" onclick="document.getElementById('idCardInput').click()">
                                <i class="fa-solid fa-id-card"></i>
                                <p><span class="lang-id">Pilih file KTP</span><span class="lang-en">Select ID file</span></p>
                                <span>JPG, PNG, PDF max 5MB</span>
                                <div class="file-indicator" id="ind-ktp"></div>
                                <input type="file" name="id_card" id="idCardInput" accept=".jpg,.jpeg,.png,.pdf" required onchange="handleFile(this, 'box-ktp', 'ind-ktp')">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><span class="lang-id">Upload NPWP</span><span class="lang-en">Upload Tax ID (NPWP)</span> <span class="req">*</span></label>
                            <div class="file-upload-box" id="box-npwp" onclick="document.getElementById('npwpInput').click()">
                                <i class="fa-solid fa-file-invoice-dollar"></i>
                                <p><span class="lang-id">Pilih file NPWP</span><span class="lang-en">Select Tax file</span></p>
                                <span>JPG, PNG, PDF max 5MB</span>
                                <div class="file-indicator" id="ind-npwp"></div>
                                <input type="file" name="npwp_file" id="npwpInput" accept=".jpg,.jpeg,.png,.pdf" required onchange="handleFile(this, 'box-npwp', 'ind-npwp')">
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label"><span class="lang-id">Upload Buku Rekening</span><span class="lang-en">Upload Bank Book</span> <span class="req">*</span></label>
                            <div class="file-upload-box" id="box-bank" onclick="document.getElementById('bankBookInput').click()">
                                <i class="fa-solid fa-book"></i>
                                <p><span class="lang-id">Pilih file Rekening</span><span class="lang-en">Select Bank Book</span></p>
                                <span><span class="lang-id">Halaman depan buku rekening</span><span class="lang-en">Front page of bank book</span></span>
                                <div class="file-indicator" id="ind-bank"></div>
                                <input type="file" name="bank_book" id="bankBookInput" accept=".jpg,.jpeg,.png,.pdf" required onchange="handleFile(this, 'box-bank', 'ind-bank')">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><span class="lang-id">Upload Foto Kantor</span><span class="lang-en">Upload Office Photos</span> <span class="req">*</span></label>
                            <div class="file-upload-box" id="box-office" onclick="document.getElementById('officePhotosInput').click()">
                                <i class="fa-solid fa-images"></i>
                                <p><span class="lang-id">Pilih Foto Kantor</span><span class="lang-en">Select Office Photos</span></p>
                                <span><span class="lang-id">Tampak depan & dalam (Min 2)</span><span class="lang-en">Front & inside view (Min 2)</span></span>
                                <div class="file-indicator" id="ind-office"></div>
                                <input type="file" name="office_photos[]" id="officePhotosInput" accept=".jpg,.jpeg,.png" multiple required onchange="handleMultipleFiles(this, 'box-office', 'ind-office')">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn-prev" onclick="goToStep(1)"><span class="lang-id">Kembali</span><span class="lang-en">Back</span></button>
                        <button type="button" class="btn-next" onclick="goToStep(3)"><span class="lang-id">Selanjutnya</span><span class="lang-en">Next Step</span> <i class="fa-solid fa-arrow-right"></i></button>
                    </div>
                </div>

                <!-- STEP 3 -->
                <div class="step-content" id="step3">
                    <div class="form-header">
                        <div class="icon"><i class="fa-solid fa-check-double"></i></div>
                        <h2><span class="lang-id">Tinjau & Kirim</span><span class="lang-en">Review & Submit</span></h2>
                    </div>
                    
                    <p style="color: var(--text-gray); font-size: 0.9rem; margin-bottom: 2rem;">
                        <span class="lang-id">Silakan tinjau kembali data yang telah Anda masukkan pada langkah sebelumnya. Jika sudah benar, setujui syarat dan ketentuan untuk mengirimkan pendaftaran.</span>
                        <span class="lang-en">Please review the data you entered in the previous steps. If everything is correct, agree to the terms and conditions to submit your application.</span>
                    </p>
                    
                    <div class="agreement-box">
                        <label>
                            <input type="checkbox" name="agreement" id="agreement" required>
                            <div class="agreement-text">
                                <strong>
                                    <span class="lang-id">Saya menyetujui Syarat dan Ketentuan</span>
                                    <span class="lang-en">I agree to the Terms and Conditions</span>
                                </strong><br>
                                <span class="lang-id">Dengan mencentang kotak ini, saya menyatakan bahwa data yang saya berikan adalah benar dan dapat dipertanggungjawabkan. Saya setuju untuk terikat oleh MOU Perjanjian Kerjasama Vendor <span class="brand-text" style="color:var(--navy)">DNA <span class="brand-red">Advertising</span></span>.</span>
                                <span class="lang-en">By checking this box, I declare that the data provided is true and accountable. I agree to be bound by the Vendor Cooperation MOU of <span class="brand-text" style="color:var(--navy)">DNA <span class="brand-red">Advertising</span></span>.</span>
                                
                                <br>
                                <button type="button" class="btn-mou" onclick="openMou()"><i class="fa-solid fa-file-contract"></i> <span class="lang-id">Lihat Dokumen MOU Selengkapnya</span><span class="lang-en">View Full MOU Document</span></button>
                            </div>
                        </label>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn-prev" onclick="goToStep(2)"><span class="lang-id">Kembali</span><span class="lang-en">Back</span></button>
                        <button type="submit" class="btn-submit" id="submitBtn"><span class="lang-id">Kirim Pendaftaran</span><span class="lang-en">Submit Registration</span></button>
                    </div>
                </div>
                
            </form>
            
            <!-- MOBILE HELP (Shown below form on mobile) -->
            <div class="help-box mobile-help">
                <i class="fa-solid fa-headset icon"></i>
                <h4><span class="lang-id">Butuh Bantuan?</span><span class="lang-en">Need Help?</span></h4>
                <p><span class="lang-id">Tim kami siap membantu Anda selama proses pendaftaran.</span><span class="lang-en">Our team is ready to assist you during the registration process.</span></p>
                <a href="https://wa.me/6281228358630" target="_blank" class="btn-help"><i class="fa-regular fa-user"></i> <span class="lang-id">Hubungi Kami</span><span class="lang-en">Contact Us</span></a>
            </div>
            
        </div>
    </div>

    <!-- CUSTOM FOOTER (Matching welcome.blade.php) -->
    <footer class="custom-footer">
        <div class="footer-grid">
            <div class="footer-col">
                <div class="footer-logo-wrap">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo">
                    <span>DNA <span class="text-red">Vendor</span> Portal</span>
                </div>
                <p>
                    <span class="lang-id">Platform pendaftaran vendor resmi untuk kemitraan bisnis terpercaya bersama <span class="brand-text">DNA <span class="brand-red">Advertising</span></span>.</span>
                    <span class="lang-en">Official vendor registration platform for trusted business partnerships with <span class="brand-text">DNA <span class="brand-red">Advertising</span></span>.</span>
                </p>
                <div class="social-links">
                    <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                    <a href="https://wa.me/6281228358630" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                </div>
            </div>
            <div class="footer-col">
                <h4><span class="lang-id">Navigasi</span><span class="lang-en">Navigation</span></h4>
                <ul class="footer-links">
                    <li><a href="{{ url('/') }}"><span class="lang-id">Beranda</span><span class="lang-en">Home</span></a></li>
                    <li><a href="{{ route('vendor.register') }}"><span class="lang-id">Daftar Vendor</span><span class="lang-en">Register Vendor</span></a></li>
                    <li><a href="{{ url('/') }}#why-partner"><span class="lang-id">Mengapa Bermitra</span><span class="lang-en">Why Partner With Us</span></a></li>
                    <li><a href="{{ route('faq') }}">FAQ</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4><span class="lang-id">Informasi Kontak</span><span class="lang-en">Contact Information</span></h4>
                <div class="contact-item">
                    <i class="fa-solid fa-location-dot"></i>
                    <div>Jl. Taman Dhika BL 6 No. 3A<br>Sono, Sidoarjo<br>Buduran, Sidoarjo</div>
                </div>
                <div class="contact-item">
                    <i class="fa-regular fa-clock"></i>
                    <div>
                        <span class="lang-id">Senin - Jumat<br>08:00 - 17:00 WIB</span>
                        <span class="lang-en">Monday - Friday<br>08:00 - 17:00 WIB</span>
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fa-solid fa-envelope"></i>
                    <div><span class="lang-id">Email Segera Hadir</span><span class="lang-en">Email Coming Soon</span></div>
                </div>
                <div class="contact-item">
                    <i class="fa-solid fa-phone"></i>
                    <div><span class="lang-id">Telepon Segera Hadir</span><span class="lang-en">Phone Coming Soon</span></div>
                </div>
            </div>
            <div class="footer-col">
                <div class="ready-box">
                    <h4><span class="lang-id">Siap Bergabung?</span><span class="lang-en">Ready to Join?</span></h4>
                    <p>
                        <span class="lang-id">Jadilah bagian dari jaringan vendor terpercaya <span class="brand-text">DNA <span class="brand-red">Advertising</span></span>.</span>
                        <span class="lang-en">Become a part of <span class="brand-text">DNA <span class="brand-red">Advertising</span></span>'s trusted vendor network.</span>
                    </p>
                    <a href="{{ route('vendor.register') }}" class="btn-gold"><span class="lang-id">Daftar Sekarang</span><span class="lang-en">Register Now</span> <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div>© {{ date('Y') }} DNA Advertising. <span class="lang-id">Semua hak dilindungi.</span><span class="lang-en">All rights reserved.</span></div>
            <div>Vendor Registration Portal</div>
        </div>
    </footer>
</div>

<!-- MODAL MOU -->
<div id="mouModal" class="mou-modal">
    <div class="mou-content">
        <div class="mou-watermark"></div>
        <div class="mou-header">
            <h3>MEMORANDUM OF UNDERSTANDING</h3>
            <button class="mou-close" onclick="closeMou()">&times;</button>
        </div>
        <div class="mou-body">
            
            <h4><span class="num">1</span> PASAL 1 – KETENTUAN UMUM</h4>
            <p>Memorandum Of Understanding (MOU) ini merupakan dasar dan ketentuan umum kerja sama antara <span class="brand-text" style="color:var(--navy)">PT. DNA <span class="brand-red">JAYA GROUP</span></span>, selanjutnya disebut PIHAK PERTAMA, dengan pihak vendor yang mendaftarkan dan menyatakan kesediannya untuk menjadi rekanan/vendor, selanjutnya disebut PIHAK KEDUA.</p>
            <p>MOU ini dibuat sebagai bentuk kesepahaman mengenai hak, kewajiban, tanggung jawab, serta ketentuan yang harus dipatuhi oleh PIHAK KEDUA selama menjalankan kerja sama dengan PIHAK PERTAMA.</p>
            
            <h4><span class="num">2</span> PASAL 2 – RUANG LINGKUP PEKERJAAN</h4>
            <p>(1) PIHAK PERTAMA memberikan pekerjaan terkait Advertising dan periklanan sesuai dengan spesifikasi dan lokasi yang sesuai dengan Surat Perintah Kerja yang turun kepada PIHAK KEDUA dan PIHAK KEDUA menyatakan telah sepakat untuk menerima dan akan melaksanakan pekerjaan tersebut.</p>
            <p>(2) Apabila PIHAK KEDUA merasa keberatan atau tidak mampu untuk mengerjakan Surat Perintah Yang turun dari PIHAK PERTAMA, maka PIHAK KEDUA diberikan waktu 1 x 24 Jam Masa Sanggah untuk membatalkan atau melakukan revisi atas Surat Perintah Kerja tersebut.</p>
            <p>(3) PIHAK KEDUA dengan ini menyatakan sanggup untuk menyediakan bahan baku dan jasa pemasangan sebagaimana dimaksud ketentuan Pasal 1 ayat (1) di perjanjian ini.</p>
            <p>(4) Pekerjaan-pekerjaan lainnya yang tidak dapat dirinci satu persatu namun menurut sifatnya menjadi tanggung jawab PIHAK KEDUA, sehingga hasil pekerjaan dapat diserahkan menurut kuantitas dan kualitas serta dalam jangka waktu yang ditetapkan dalam Perjanjian ini.</p>
            
            <h4><span class="num">3</span> PASAL 3 – KESELAMATAN DAN KESEHATAN KERJA (K3)</h4>
            <p>(1) PIHAK KEDUA berkewajiban untuk melaksanakan pekerjaan dengan mengutamakan aturan Keselamatan dan Kesehatan Kerja (K3).</p>
            <p>(2) PIHAK KEDUA wajib menugaskan tenaga kerja ahli yang sesuai dengan pekerjaan yang diberikan oleh PIHAK PERTAMA.</p>
            <p>(3) Jika PIHAK KEDUA mengalami Kecelakaan Kerja yang terjadi karena tidak dipatuhinya Aturan Kesehatan dan Keselamatan Kerja, maka PIHAK PERTAMA tidak bertanggung jawab atas Kerugian PIHAK KEDUA yang timbul akibat dari kecelakaan kerja tersebut.</p>
            <p>(4) Di lokasi pekerjaan harus ada wakil PIHAK KEDUA yang ditunjuk sebagai Pimpinan pelaksana yang mempunyai wewenang penuh untuk mewakili PIHAK KEDUA yang dapat menerima/memberikan/memutuskan segala urusan pekerjaan lapangan tidak terkecuali terhadap penanganan resiko kecelakaan kerja.</p>
            <p>(5) Semua yang berkaitan dengan persoalan dan tuntutan tenaga kerja yang menjadi beban dan tanggung jawab PIHAK KEDUA, baik di dalam maupun di luar pengadilan.</p>
            
            <h4><span class="num">4</span> PASAL 4 – KEWAJIBAN & TANGGUNG JAWAB</h4>
            <p>(1) PIHAK KEDUA berkewajiban melaksanakan pekerjaan berdasarkan Perjanjian ini dengan penuh tanggung jawab tetapi tidak terbatas pada tanggung jawab atas teknis pekerjaan saja, namun juga memperhatikan ketentuan hukum yang berlaku dan kelestarian lingkungan hidup.</p>
            <p>(2) Setiap pekerja PIHAK KEDUA wajib didaftarkan menjadi peserta BPJS Ketenagakerjaan oleh PIHAK KEDUA.</p>
            <p>(3) PIHAK KEDUA wajib memenuhi standar keamanan dan keselamatan kerja untuk keperluan pekerjaan yang beresiko tinggi bagi pekerjanya, meliputi: Sepatu, Body Harness, Helm, Sarung Tangan. PIHAK KEDUA wajib memprioritaskan keamanan dan keselamatan pekerja serta tidak merusak konstruksi Reklame itu sendiri kecuali dalam keadaan Darurat.</p>
            <p>(4) Apabila PIHAK KEDUA tidak dapat menyediakan alat keamanan keselamatan kerja di atas, maka PIHAK KEDUA dapat meminjam kepada PIHAK PERTAMA dan wajib mengembalikan sesuai kondisi semula.</p>
            <p>(5) Setiap Pekerja PIHAK KEDUA berkewajiban menggunakan alat-alat keselamatan kerja serta melaksanakan syarat-syarat keselamatan dan perlindungan pekerja.</p>
            <p>(6) Dalam melakukan Pekerjaan dari Pihak Pertama, PIHAK KEDUA wajib mematuhi waktu kerja yaitu dimulai dari Jam 07.00 dan selambat-lambatnya harus selesai Jam 17.00 Waktu Setempat.</p>
            <p>(7) PIHAK KEDUA bertanggung jawab terhadap barang yang ada di Lokasi pekerjaan.</p>
            <p>(8) Dalam melakukan pekerjaan terjadi hujan, angin kencang, masalah kabel Listrik PLN atau hal-hal yang berpotensi menimbulkan resiko tinggi, maka PIHAK KEDUA wajib melaporkan kepada PIHAK PERTAMA terkait kendala yang terjadi.</p>
            <p>(9) Segala resiko yang timbul yang menimpa PIHAK KEDUA berupa kecelakaan kerja, pekerja PIHAK KEDUA maupun pihak ketiga sehubungan dengan pelaksanaan Pekerjaan berdasarkan Perjanjian ini menjadi beban dan tanggung jawab PIHAK KEDUA sepenuhnya, namun PIHAK PERTAMA akan tetap memberikan bantuan berupa santunan kepada PIHAK KEDUA sesuai dengan kebijakan Perusahaan.</p>
            <p>(10) PIHAK KEDUA akan memberikan garansi kepada PIHAK PERTAMA atas konstruksi pekerjaan yang telah selesai:</p>
            <ul style="margin-left:1.5rem;">
                <li>Pekerjaan <strong>Permanen</strong>: masa garansi konstruksi <strong>1 Tahun</strong> sejak Pengiriman Foto kepada Pihak Pertama.</li>
                <li>Pekerjaan <strong>Insidentil</strong>: masa garansi konstruksi <strong>7 Hari</strong> sejak Pengiriman Foto kepada Pihak Pertama, kecuali diambil/disita oleh dinas terkait atau terjadi Force Majeur.</li>
            </ul>

            <h4><span class="num">5</span> PASAL 5 – CARA PEMBAYARAN</h4>
            <p>(1) Pembayaran harga pekerjaan dilaksanakan oleh PIHAK PERTAMA kepada PIHAK KEDUA dengan cara pembayaran sesuai dengan kesepakatan kedua belah Pihak sesuai dengan Surat Perintah Kerja.</p>
            <p>(2) PIHAK PERTAMA berhak untuk memotong pembayaran PPH (Pajak Penghasilan) sesuai dengan peraturan perundang-undangan yang berlaku.</p>
            <p>(3) Pembayaran harga pekerjaan dapat dilakukan secara transfer melalui Rekening PIHAK KEDUA yang telah dicantumkan.</p>

            <h4><span class="num">6</span> PASAL 6 – JANGKA WAKTU PERJANJIAN</h4>
            <p>(1) Jangka waktu perjanjian Kerjasama ini yakni sejak tanggal disetujui oleh PARA PIHAK dan tidak ditentukan jangka waktu berakhirnya.</p>
            <p>(2) Perjanjian ini akan tetap berlaku sepanjang PARA PIHAK masih melaksanakan kerja sama.</p>
            <p>(3) Dalam hal kerja sama tidak lagi digunakan, maka PIHAK PERTAMA dapat mengakhiri perjanjian ini dengan menyampaikan surat pemberitahuan penghentian kerja sama secara tertulis kepada PIHAK KEDUA.</p>
            <p>(4) Dalam hal PARA PIHAK sepakat untuk mengakhiri kerja sama yang akan dituangkan secara tertulis di dalam suatu dokumen yang disepakati oleh PARA PIHAK secara tersendiri.</p>

            <h4><span class="num">7</span> PASAL 7 – FORCE MAJEURE</h4>
            <p>(1) Dalam hal terjadi keadaan memaksa (Force Majeure) yang berada di luar kekuasaan PARA PIHAK, termasuk namun tidak terbatas kepada bencana alam, peperangan, revolusi, huru-hara, dan pemogokan massal, maka pihak yang terkena dampak harus memberitahukan kepada pihak lainnya secara tertulis selambat-lambatnya 3 (tiga) hari kalender setelah keadaan memaksa itu terjadi.</p>
            <p>(2) Apabila pihak yang terkena dampak tidak atau lalai memberitahukan kejadian tersebut dalam jangka waktu 3 (tiga) hari kalender tersebut, maka keadaan memaksa dianggap tidak pernah terjadi dan pihak yang mengalami keadaan memaksa tersebut tetap harus melaksanakan kewajibannya sebagaimana termaktub dalam Perjanjian ini.</p>
            <p>(3) Apabila pemberitahuan diberikan dalam batas waktu yang ditentukan, maka PARA PIHAK akan merundingkan kembali pelaksanaan Perjanjian ini berdasarkan musyawarah untuk mufakat.</p>

            <h4><span class="num">8</span> PASAL 8 – PENYELESAIAN PERSELISIHAN</h4>
            <p>(1) Segala perselisihan atau perbedaan pendapat yang timbul atas pelaksanaan dari Perjanjian ini terlebih dahulu akan dibicarakan dan diselesaikan oleh kedua belah pihak secara musyawarah untuk mufakat.</p>
            <p>(2) Apabila penyelesaian secara musyawarah tidak tercapai antara kedua belah pihak, maka PIHAK PERTAMA dan PIHAK KEDUA sepakat untuk memilih domisili hukum di Pengadilan Negeri Sidoarjo.</p>

            <h4><span class="num">9</span> PASAL 9 – KETENTUAN LAIN-LAIN</h4>
            <p>(1) Apabila terjadi perbedaan materi dan penafsiran antara Perjanjian ini dengan dokumen lampirannya, maka yang diberlakukan adalah ketentuan dalam Perjanjian ini.</p>
            <p>(2) Segala sesuatu mengenai jenis pekerjaan dalam Perjanjian ini, oleh PARA PIHAK akan diatur dalam perjanjian tersendiri yang merupakan satu kesatuan yang mengikat dan bagian yang tidak terpisahkan dari Perjanjian ini.</p>
            <p>(3) Apabila terdapat perbedaan isi antara Perjanjian ini dengan lampirannya, maka yang diberlakukan adalah yang tertuang dalam Perjanjian ini.</p>

            <h4><span class="num">10</span> PASAL 10 – PERSETUJUAN MELALUI WEBSITE</h4>
            <p>(1) PIHAK KEDUA menyatakan telah membaca dan memahami seluruh isi MOU ini sebelum melakukan persetujuan.</p>
            <p>(2) Dengan mencetang kolom persetujuan dan menekan tombol Submit/Kirim pada website, PIHAK KEDUA menyatakan bersedia menjadi Vendor <span class="brand-text" style="color:var(--navy)">PT. DNA <span class="brand-red">JAYA GROUP</span></span>.</p>
            <p>(3) Persetujuan melalui sistem/website merupakan bentuk pernyataan kesediaan PIHAK KEDUA untuk mematuhi dan menjalankan ketentuan yang tercantum dalam MOU ini.</p>
            <p>(4) Data dan informasi yang diberikan oleh PIHAK KEDUA melalui formulir pendaftaran vendor merupakan bagian dari proses administrasi kerja sama Vendor.</p>
            <p>(5) PIHAK KEDUA bertanggung jawab atas kebenaran data dan informasi yang disampaikan melalui sistem.</p>

            <h4><span class="num">11</span> PASAL 11 – PENUTUP</h4>
            <p>MOU ini dibuat sebagai dasar dan pedoman dalam pelaksanaan kerja sama <span class="brand-text" style="color:var(--navy)">PT. DNA <span class="brand-red">JAYA GROUP</span></span> dengan vendor. Dengan melakukan persetujuan melalui website, PIHAK KEDUA menyatakan telah membaca, memahami, menyetujui dan bersedia mematuhi seluruh ketentuan dalam MOU ini.</p>
            <p>MOU ini berlaku sebagai ketentuan umum kerja sama vendor sejak persetujuan dilakukan melalui sistem/website <span class="brand-text" style="color:var(--navy)">PT. DNA <span class="brand-red">JAYA GROUP</span></span>.</p>
            
        </div>
        <div class="mou-footer">
            <button class="btn-draft" onclick="closeMou()"><span class="lang-id">Tutup</span><span class="lang-en">Close</span></button>
            <button class="btn-next" onclick="acceptMou()"><i class="fa-solid fa-check"></i> <span class="lang-id">Saya Mengerti & Setuju</span><span class="lang-en">I Understand & Agree</span></button>
        </div>
    </div>
</div>

<script>
// ==================== LANGUAGE SWITCHER ====================
let currentLang = 'id'; // default
function switchLang(lang) {
    currentLang = lang;
    
    // Check if dropdown matches
    const select = document.querySelector('.lang-switcher-select');
    if(select && select.value !== lang) {
        select.value = lang;
    }
    
    // Show/hide texts
    if(lang === 'id') {
        document.querySelectorAll('.lang-en').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.lang-id').forEach(el => el.style.display = '');
    } else {
        document.querySelectorAll('.lang-id').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.lang-en').forEach(el => el.style.display = '');
    }
    
    // Update active step titles inside JS array so mobile renders correctly
    if(lang === 'en') {
        steps[0].title = 'Company Information'; steps[0].desc = 'General company data';
        steps[1].title = 'Contact & Documents'; steps[1].desc = 'Contact info & legal docs';
        steps[2].title = 'Review & Submit'; steps[2].desc = 'Review and send application';
    } else {
        steps[0].title = 'Informasi Perusahaan'; steps[0].desc = 'Data umum perusahaan';
        steps[1].title = 'Kontak & Dokumen'; steps[1].desc = 'Informasi kontak & dokumen';
        steps[2].title = 'Tinjau & Kirim'; steps[2].desc = 'Periksa dan kirim pendaftaran';
    }
    
    // Re-render mobile steps if on mobile
    goToStep(currentStepNumber);
}

// Initial hide of english classes to prevent FOUC (Flash of Unstyled Content)
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.lang-en').forEach(el => el.style.display = 'none');
    switchLang('id'); // initialize language
    goToStep(1);
});

// ==================== FILE UPLOAD INDICATORS ====================
function handleFile(inputElement, boxId, indicatorId) {
    const box = document.getElementById(boxId);
    const indicator = document.getElementById(indicatorId);
    
    if (inputElement.files && inputElement.files.length > 0) {
        box.classList.add('has-file');
        const fileName = inputElement.files[0].name;
        const fileSize = (inputElement.files[0].size / (1024 * 1024)).toFixed(2) + ' MB';
        indicator.innerHTML = `<i class="fa-solid fa-check"></i> Disimpan: ${fileName} (${fileSize})`;
    } else {
        box.classList.remove('has-file');
        indicator.innerHTML = '';
    }
}

function handleMultipleFiles(inputElement, boxId, indicatorId) {
    const box = document.getElementById(boxId);
    const indicator = document.getElementById(indicatorId);
    
    if (inputElement.files && inputElement.files.length > 0) {
        box.classList.add('has-file');
        indicator.innerHTML = `<i class="fa-solid fa-check"></i> Disimpan: ${inputElement.files.length} Foto Kantor`;
    } else {
        box.classList.remove('has-file');
        indicator.innerHTML = '';
    }
}

// ==================== STEP NAVIGATION ====================
const steps = [
    { id: 1, title: 'Informasi Perusahaan', desc: 'Data umum perusahaan' },
    { id: 2, title: 'Kontak & Dokumen', desc: 'Informasi kontak & dokumen' },
    { id: 3, title: 'Tinjau & Kirim', desc: 'Periksa dan kirim pendaftaran' }
];
let currentStepNumber = 1;

function goToStep(stepNumber) {
    currentStepNumber = stepNumber;
    
    // Hide all steps
    document.querySelectorAll('.step-content').forEach(el => el.classList.remove('active'));
    // Un-active all nav items
    document.querySelectorAll('.step-item').forEach(el => el.classList.remove('active'));
    
    // Show selected step
    let target = document.getElementById('step' + stepNumber);
    if(target) target.classList.add('active');
    
    // Update nav items
    for(let i = 1; i <= 3; i++) {
        let navItem = document.getElementById('nav-step' + i);
        if(!navItem) continue;
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
    if(mobileContainer) {
        let mobileHTML = `
            <div class="mobile-step-item active">
                <div class="step-number">${stepNumber}</div>
                <div class="step-text">
                    <h4>${steps[stepNumber-1].title}</h4>
                    <p>${steps[stepNumber-1].desc}</p>
                </div>
            </div>
        `;
        
        const nextStepsDiv = document.createElement('div');
        nextStepsDiv.className = 'mobile-steps';
        nextStepsDiv.style.marginTop = '1.5rem';
        let nextHTML = '';
        for(let i = stepNumber + 1; i <= 3; i++) {
            nextHTML += `
                <div class="mobile-step-item" style="opacity: 0.6;" onclick="goToStep(${i})">
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
        
        const oldNextSteps = document.getElementById('mobile-next-steps');
        if(oldNextSteps) oldNextSteps.remove();
        
        if(nextHTML !== '') {
            nextStepsDiv.id = 'mobile-next-steps';
            document.querySelector('.form-card').appendChild(nextStepsDiv);
        }
    }
    
    window.scrollTo({ top: document.querySelector('.form-layout').offsetTop - 100, behavior: 'smooth' });
}

// ==================== MOU MODAL ====================
function openMou() {
    document.getElementById('mouModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closeMou() {
    document.getElementById('mouModal').classList.remove('show');
    document.body.style.overflow = '';
}
function acceptMou() {
    document.getElementById('agreement').checked = true;
    closeMou();
}

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
        alert(currentLang === 'id' ? 'Mohon lengkapi semua file yang diperlukan pada langkah 2.' : 'Please complete all required files in step 2.');
        goToStep(2);
        return;
    }

    if (officePhotos.length < 2) {
        alert(currentLang === 'id' ? 'Minimal 2 foto kantor diperlukan pada langkah 2.' : 'Minimum 2 office photos required in step 2.');
        goToStep(2);
        return;
    }

    // Tampilkan loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> ' + (currentLang === 'id' ? 'Mengupload file...' : 'Uploading files...');

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
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> ' + (currentLang === 'id' ? 'Mengupload KTP...' : 'Uploading ID...');
        const idCardUrl = await uploadFile(idCardFile, 'id_cards');
        document.getElementById('id_card_url').value = idCardUrl;

        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> ' + (currentLang === 'id' ? 'Mengupload Buku Rekening...' : 'Uploading Bank Book...');
        const bankBookUrl = await uploadFile(bankBookFile, 'bank_books');
        document.getElementById('bank_book_url').value = bankBookUrl;

        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> ' + (currentLang === 'id' ? 'Mengupload NPWP...' : 'Uploading Tax ID...');
        const npwpUrl = await uploadFile(npwpFile, 'npwp');
        document.getElementById('npwp_file_url').value = npwpUrl;

        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> ' + (currentLang === 'id' ? 'Mengupload Foto Kantor...' : 'Uploading Photos...');
        const officePhotoUrls = [];
        for (const photo of officePhotos) {
            const url = await uploadFile(photo, 'office_photos');
            officePhotoUrls.push(url);
        }
        document.getElementById('office_photos_urls').value = JSON.stringify(officePhotoUrls);

        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> ' + (currentLang === 'id' ? 'Menyimpan data...' : 'Saving data...');

        // Disable file inputs so they don't upload directly to server
        form.querySelectorAll('input[type="file"]').forEach(input => {
            input.removeAttribute('name');
            input.disabled = true;
        });

        form.submit();

    } catch (err) {
        console.error(err);
        alert(currentLang === 'id' ? 'Terjadi kesalahan saat mengupload file: ' : 'Error uploading file: ' + err.message);
        submitBtn.disabled = false;
        submitBtn.innerHTML = currentLang === 'id' ? 'Kirim Pendaftaran' : 'Submit Registration';
    }
});
</script>
@endsection
