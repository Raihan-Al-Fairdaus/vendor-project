@extends('layouts.public')
@section('title', 'Register Vendor - DNA Vendor Portal')
@section('content')
<style>
/* Reset and Defaults */
nav.navbar { display: none !important; }
*, *::before, *::after { box-sizing: border-box; }
html, body { margin: 0; padding: 0; font-family: 'Inter', sans-serif; background-color: #f8fafc; overflow-x: hidden; max-width: 100vw; }

:root {
    --navy: #1b3a60;
    --navy-light: #2a5a8f;
    --navy-dark: #0c1a2c;
    --gold: #f59e0b;
    --gold-hover: #d97706;
    --text-gray: #64748b;
}

/* BRANDING TEXT */
.brand-text { font-weight: 700; color: inherit; }
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
.nav-logo img { height: 35px; } 
.nav-logo span { font-weight: 700; color: #fff; font-size: 1.2rem; }
.nav-logo span .text-red { color: #e11d48; }

.nav-right-container {
    display: flex;
    align-items: center;
    gap: 2rem;
}

.nav-links { display: flex; gap: 2rem; align-items: center; }
.nav-links a { text-decoration: none; color: #ffffff !important; font-weight: 600; font-size: 0.95rem; transition: 0.2s; opacity: 0.9; }
.nav-links a:hover, .nav-links a.active { color: var(--gold) !important; opacity: 1; }
.nav-links a.active { border-bottom: 2px solid var(--gold); padding-bottom: 4px; }
.btn-nav-contact { background: transparent; border: 1px solid rgba(255,255,255,0.4); color: #ffffff !important; padding: 0.5rem 1.25rem; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; transition: 0.2s; font-size: 0.9rem; }
.btn-nav-contact:hover { background: rgba(255,255,255,0.15); }
.mobile-menu-btn { display: none; font-size: 1.5rem; color: #fff; background: none; border: none; cursor: pointer; }

/* LANG SWITCHER */
.lang-switcher {
    display: flex;
    background: rgba(255,255,255,0.15);
    border-radius: 30px;
    padding: 3px;
    border: 1px solid rgba(255,255,255,0.3);
}
.lang-btn {
    background: transparent;
    border: none;
    color: #fff;
    padding: 6px 16px;
    font-size: 0.85rem;
    font-weight: 700;
    border-radius: 25px;
    cursor: pointer;
    transition: 0.3s;
}
.lang-btn.active {
    background: #fff;
    color: var(--navy);
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

/* LANGUAGE LOGIC */
.lang-id { display: inline; }
.lang-en { display: none; }
body.lang-en-active .lang-id { display: none; }
body.lang-en-active .lang-en { display: inline; }
div.lang-id, p.lang-id, h1.lang-id, h2.lang-id, h3.lang-id, h4.lang-id { display: block; }
body.lang-en-active div.lang-en, body.lang-en-active p.lang-en, body.lang-en-active h1.lang-en, body.lang-en-active h2.lang-en, body.lang-en-active h3.lang-en, body.lang-en-active h4.lang-en { display: block; }

/* HERO SECTION - SECTION 1 (NAVY) */
.hero-wrapper {
    background-color: var(--navy); /* #1b3a60 */
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
.eyebrow-pill { color: var(--gold) !important; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 1rem; }
.hero-title { font-size: 3.2rem; font-weight: 800; line-height: 1.15; margin-bottom: 1.5rem; }
.hero-desc { font-size: 0.95rem; color: #ffffff !important; line-height: 1.6; margin-bottom: 3rem; max-width: 90%; }
.hero-features { display: flex; gap: 2rem; }
.hf-item { display: flex; align-items: center; gap: 0.75rem; color: #ffffff !important; }
.hf-icon { color: var(--gold); font-size: 1.25rem; }
.hf-text { font-size: 0.8rem; line-height: 1.3; color: #ffffff !important; }
.hf-text strong { display: block; font-weight: 600; margin-bottom: 2px; color: #ffffff !important; }

/* FORM SECTION - SECTION 2 (LIGHT GRAY) */
.form-section {
    background-color: #f8fafc; /* light gray requested by user */
    padding: 4rem 0;
}

.form-layout {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 5%;
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 2rem;
    position: relative;
    z-index: 10;
}

/* SIDEBAR STEPS (Now on light background) */
.sidebar-steps {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}
.steps-box {
    background: #ffffff;
    border-radius: 16px;
    padding: 1.5rem;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
}
.steps-title { color: var(--navy) !important; font-size: 1rem; font-weight: 700; margin-bottom: 1.5rem; }
.step-item { display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem; padding: 0.75rem 1rem; border-radius: 12px; cursor: pointer; transition: 0.3s; border: 1px solid transparent; color: #64748b !important; }
.step-item.active { background: #f8fafc; color: var(--navy) !important; border: 1px solid #e2e8f0; }
.step-number { width: 32px; height: 32px; border-radius: 50%; border: 1px solid #cbd5e1; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; font-weight: 700; flex-shrink: 0; }
.step-item.active .step-number { background: #eff6ff; border-color: #3b82f6; color: #3b82f6; }
.step-item.completed .step-number { background: #10b981; border-color: #10b981; color: #fff; }
.step-text h4 { margin: 0 0 2px; font-size: 0.9rem; font-weight: 700; color: #475569 !important; }
.step-text p { margin: 0; font-size: 0.75rem; color: #94a3b8 !important; }
.step-item.active .step-text h4 { color: var(--navy) !important; }
.step-item.active .step-text p { color: #64748b !important; }
.step-item.completed .step-text h4 { color: var(--navy) !important; }

.help-box {
    background: #ffffff;
    border-radius: 16px;
    padding: 1.5rem;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
.help-box .icon { font-size: 1.5rem; color: var(--navy) !important; margin-bottom: -0.5rem; }
.help-box h4 { color: var(--navy) !important; margin: 0; font-size: 0.95rem; font-weight: 600; }
.help-box p { color: #64748b !important; font-size: 0.8rem; margin: 0; line-height: 1.5; }
.btn-help { background: var(--navy) !important; color: #ffffff !important; padding: 0.6rem 1rem; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.85rem; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; }
.btn-help:hover { background: var(--navy-dark) !important; }

/* RIGHT CONTENT (FORM) */
.form-card {
    background: #ffffff; /* White so it pops against #f8fafc */
    border-radius: 20px;
    padding: 2.5rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.08);
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
.form-header .icon { width: 48px; height: 48px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--navy); font-size: 1.25rem; }
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
.help-text { display: block; font-size: 0.75rem; color: #64748b; margin-top: 0.5rem; line-height: 1.5; }
.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }

/* File Upload specific styles */
.file-upload-box {
    border: 2px dashed #94a3b8;
    border-radius: 8px;
    padding: 1.5rem;
    text-align: center;
    background: #f8fafc; 
    cursor: pointer;
    transition: 0.2s;
    position: relative;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}
.file-upload-box.has-file { border-color: #10b981; background: #d1fae5; }
.file-upload-box.error { border-color: #ef4444; background: #fee2e2; }
.file-upload-box:hover { border-color: #3b82f6; background: #eff6ff; }
.file-upload-box input[type="file"] { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
.file-upload-box i { font-size: 1.5rem; color: #64748b; margin-bottom: 0.5rem; transition: 0.2s; }
.file-upload-box.has-file i { color: #10b981; }
.file-upload-box.error i { color: #ef4444; }
.file-upload-box p { margin: 0; font-size: 0.85rem; color: var(--navy); font-weight: 600; }
.file-upload-box span { font-size: 0.75rem; color: #475569; display: block; margin-top: 4px; }
.file-indicator { font-size: 0.75rem; font-weight: 700; margin-top: 8px; display: none; }
.file-indicator.success { color: #10b981; display: block; }
.file-indicator.error { color: #ef4444; display: block; }

/* Agreement styling */
.agreement-box { background: rgba(27, 58, 96, 0.05); border: 1px solid #cbd5e1; border-radius: 12px; padding: 1.5rem; }
.agreement-box label { display: flex; gap: 1rem; align-items: flex-start; cursor: pointer; opacity: 0.7; transition: 0.3s; }
.agreement-box label.enabled { opacity: 1; }
.agreement-box input[type="checkbox"] { margin-top: 4px; width: 18px; height: 18px; pointer-events: none; } 
.agreement-text { font-size: 0.85rem; color: #334155; line-height: 1.6; }
.btn-mou { background: #3b82f6; color: #fff; padding: 0.6rem 1.2rem; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem; margin-top: 1rem; border: none; cursor: pointer; }
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


.ready-box { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); padding: 1.5rem; border-radius: 12px; }
.ready-box h4 { margin-bottom: 0.5rem; font-size: 1rem; font-weight: 700; color: #fff; }
.ready-box p { font-size: 0.8rem; margin-bottom: 1.25rem; color: rgba(255,255,255,0.8); line-height: 1.5; }
.btn-gold { background: var(--gold); color: var(--navy); padding: 0.6rem 1rem; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 0.85rem; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; transition: 0.2s; }
.btn-gold:hover { background: var(--gold-hover); }

/* MODAL MOU - FORMAT COMPACT & PROFESSIONAL */
.mou-modal { display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.75); z-index: 9999; align-items: center; justify-content: center; padding: 1rem 0.75rem; backdrop-filter: blur(5px); -webkit-backdrop-filter: blur(5px); }
.mou-modal.show { display: flex; }
.mou-content { background: #ffffff; width: 100%; max-width: 780px; border-radius: 14px; display: flex; flex-direction: column; max-height: 94vh; position: relative; overflow: hidden; box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.35); border: 1px solid rgba(255,255,255,0.2); }
.mou-watermark { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 55%; height: 55%; background-image: url('{{ asset("images/logo.webp") }}'); background-repeat: no-repeat; background-position: center; background-size: contain; opacity: 0.035; pointer-events: none; z-index: 0; }

.mou-header { padding: 0.75rem 1.25rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 2; background: #ffffff; }
.mou-header-title { width: 100%; text-align: center; }
.mou-header h3 { margin: 0; color: var(--navy); font-size: 1.05rem; font-weight: 800; letter-spacing: 0.02em; }
.mou-sub { margin: 2px 0 0; font-size: 0.72rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; }
.mou-close { position: absolute; right: 0.85rem; top: 0.75rem; background: #f1f5f9; border: none; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; cursor: pointer; color: #64748b; transition: all 0.2s; line-height: 1; }
.mou-close:hover { background: #fee2e2; color: #ef4444; }

/* Reading Progress & Timer HUD (Super Compact 1 baris) */
.mou-progress-strip { height: 3px; background: #e2e8f0; width: 100%; position: relative; z-index: 3; }
.mou-progress-bar-fill { height: 100%; width: 0%; background: linear-gradient(to right, #3b82f6, #10b981); transition: width 0.12s ease; }
.mou-reading-hud { background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 0.35rem 1.25rem; display: flex; justify-content: space-between; align-items: center; font-size: 0.76rem; font-weight: 600; color: #475569; position: relative; z-index: 2; }
.mou-hud-left { display: flex; align-items: center; gap: 0.35rem; color: var(--navy); }
.mou-hud-right { display: flex; align-items: center; gap: 0.35rem; color: #d97706; }
.mou-hud-right.complete { color: #10b981; }

.mou-body { padding: 1rem 1.35rem; overflow-y: auto; color: #1e293b; font-size: 0.88rem; line-height: 1.55; position: relative; z-index: 1; text-align: justify; scroll-behavior: smooth; }
.mou-body p { margin-bottom: 0.5rem; }

/* Artikel & Pasal Format Rapi & Rapat (No big boxes, compact spacing) */
.mou-article { margin-bottom: 1.1rem; padding-bottom: 0.85rem; border-bottom: 1px solid #f1f5f9; }
.mou-article:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
.mou-article-header { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.35rem; padding-bottom: 0.2rem; border-bottom: 1.5px solid rgba(27, 58, 96, 0.12); }
.mou-article-label { color: var(--navy); font-weight: 800; font-size: 0.92rem; letter-spacing: 0.03em; }
.mou-article-sep { color: #cbd5e1; font-weight: 300; }
.mou-article-title { color: #0f172a; font-weight: 700; font-size: 0.92rem; margin: 0; text-transform: uppercase; letter-spacing: 0.02em; }

/* Highlight Warna Kalimat Penting Langsung di Teks */
.mou-danger { color: #dc2626; font-weight: 700; background: rgba(239, 68, 68, 0.08); padding: 1px 4px; border-radius: 3px; }
.mou-warning { color: #b45309; font-weight: 700; background: rgba(245, 158, 11, 0.1); padding: 1px 4px; border-radius: 3px; }
.mou-primary { color: #1e40af; font-weight: 700; background: rgba(59, 130, 246, 0.08); padding: 1px 4px; border-radius: 3px; }

.mou-footer { padding: 0.65rem 1.25rem; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; align-items: center; gap: 0.75rem; background: #f8fafc; position: relative; z-index: 2; }
.mou-footer .btn-draft { padding: 0.5rem 1rem; font-size: 0.84rem; }

/* Tombol Persetujuan MOU Berstatus Dinamis */
.btn-accept-mou { padding: 0.55rem 1.25rem; border-radius: 7px; font-weight: 700; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.45rem; border: none; cursor: pointer; transition: all 0.2s ease; }
.btn-accept-mou.disabled { background: #94a3b8 !important; color: #ffffff !important; opacity: 0.65; cursor: not-allowed; pointer-events: auto; }
.btn-accept-mou.unlocked { background: #10b981 !important; color: #ffffff !important; box-shadow: 0 3px 10px rgba(16, 185, 129, 0.35); transform: scale(1.01); }
.btn-accept-mou.unlocked:hover { background: #059669 !important; }

/* Submit Form Locked State */
.btn-submit.btn-locked { background: #94a3b8 !important; color: #ffffff !important; opacity: 0.65; cursor: not-allowed; box-shadow: none !important; }
.btn-submit.btn-locked:hover { background: #94a3b8 !important; }

/* MOBILE RESPONSIVE */
@media (max-width: 992px) {
    .nav-links { display: none; }
    .mobile-menu-btn { display: none !important; }
    .btn-nav-contact { display: none !important; }
    .hero-wrapper { padding: 5.5rem 1.25rem 3rem; flex-direction: column; position: relative; }
    .hero-bg-image {
        width: 100%;
        height: 100%;
        opacity: 0.22;
        background-size: 100% auto;
        background-repeat: no-repeat;
        background-position: center 25px;
        mask-image: linear-gradient(to bottom, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.3) 75%, transparent 100%);
        -webkit-mask-image: linear-gradient(to bottom, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.3) 75%, transparent 100%);
    }
    .hero-features { flex-direction: column; gap: 1rem; }
    .hero-title { font-size: 2.25rem; }
    .form-layout { grid-template-columns: 1fr; padding: 0 1rem; }
    .form-card { padding: 1.25rem; width: 100%; max-width: 100%; }
    .custom-footer { padding: 3rem 1.5rem 2rem; }
    .footer-grid { grid-template-columns: 1fr; gap: 2rem; }
    .grid-2 { grid-template-columns: 1fr; }
    
    .sidebar-steps { display: none; } 
    .mobile-steps { display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1.5rem; }
    .mobile-step-item { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1rem; display: flex; align-items: center; gap: 1rem; color: #64748b; }
    .mobile-step-item.active { background: #f8fafc; color: var(--navy); }
    .mobile-step-item .step-number { width: 32px; height: 32px; border-radius: 50%; border: 1px solid #cbd5e1; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; font-weight: 700; flex-shrink: 0; }
    .mobile-step-item.active .step-number { background: #eff6ff; color: #3b82f6; border-color: #3b82f6; }
    .mobile-step-item .step-text h4 { margin: 0; font-size: 0.9rem; font-weight: 700; color: #475569 !important; }
    .mobile-step-item .step-text p { margin: 0; font-size: 0.75rem; color: #94a3b8 !important; }
    .mobile-step-item.active .step-text h4 { color: var(--navy) !important; }
    .mobile-step-item.active .step-text p { color: #64748b !important; }
    
    .mobile-help { display: flex; margin-top: 1.5rem; }
}
@media (min-width: 993px) {
    .mobile-steps, .mobile-help { display: none; }
}
</style>

<!-- NAVBAR -->
<nav class="custom-nav">
    <button class="mobile-menu-btn"><i class="fa-solid fa-bars"></i></button>
    <a href="#" class="nav-logo">
        <img src="{{ asset('images/logo.webp') }}" alt="Logo">
        <span>DNA <span class="text-red">Vendor</span> Portal</span>
    </a>
    
    <div class="nav-right-container">
        <div class="nav-links">
            <a href="{{ url('/') }}">
                <span class="lang-id">Beranda</span>
                <span class="lang-en">Home</span>
            </a>
            <a href="#" class="active">
                <span class="lang-id">Daftar Vendor</span>
                <span class="lang-en">Register Vendor</span>
            </a>
            <a href="{{ url('/') }}#why-partner">
                <span class="lang-id">Mengapa Bermitra</span>
                <span class="lang-en">Why Partner</span>
            </a>
            <a href="{{ route('faq') }}">FAQ</a>
        </div>
        
        <div class="lang-switcher">
            <button class="lang-btn active" onclick="switchLang('id')">ID</button>
            <button class="lang-btn" onclick="switchLang('en')">EN</button>
        </div>

        <a href="https://wa.me/6288221002100?text=Halo%20Admin,%20saya%20butuh%20bantuan%20terkait%20registrasi%20vendor." target="_blank" class="btn-nav-contact">
            <i class="fa-regular fa-user"></i> 
            <span class="lang-id">Hubungi Kami</span>
            <span class="lang-en">Contact Us</span>
        </a>
    </div>
</nav>

<!-- HERO SECTION -->
<section class="hero-wrapper">
    <div class="hero-bg-image"></div>
    <div class="hero-content">
        <div class="eyebrow-pill">
            <span class="lang-id">GABUNG BERSAMA KAMI</span>
            <span class="lang-en">JOIN US TODAY</span>
        </div>
        <h1 class="hero-title">
            <span class="lang-id" style="color: #ffffff !important;">Menjadi Mitra<br><span style="color: var(--gold) !important;">Terpercaya</span></span>
            <span class="lang-en" style="color: #ffffff !important;">Become a Trusted<br><span style="color: var(--gold) !important;">Partner</span></span>
        </h1>
        <p class="hero-desc">
            <span class="lang-id">Lengkapi data perusahaan Anda untuk bergabung dalam jaringan vendor terverifikasi <span class="brand-text">DNA <span class="brand-red">Advertising</span></span>. Proses verifikasi kami hanya memakan waktu 2-3 hari kerja.</span>
            <span class="lang-en">Complete your company profile to join <span class="brand-text">DNA <span class="brand-red">Advertising</span></span>'s verified vendor network. Our verification process only takes 2-3 working days.</span>
        </p>
        <div class="hero-features">
            <div class="hf-item">
                <i class="fa-solid fa-shield-halved hf-icon"></i>
                <div class="hf-text">
                    <strong>
                        <span class="lang-id">Proses Aman</span>
                        <span class="lang-en">Secure Process</span>
                    </strong>
                    <span class="lang-id">dan Terverifikasi</span>
                    <span class="lang-en">and Verified</span>
                </div>
            </div>
            <div class="hf-item">
                <i class="fa-regular fa-clock hf-icon"></i>
                <div class="hf-text">
                    <strong>
                        <span class="lang-id">Verifikasi Cepat</span>
                        <span class="lang-en">Fast Verification</span>
                    </strong>
                    <span class="lang-id">2-3 Hari Kerja</span>
                    <span class="lang-en">2-3 Work Days</span>
                </div>
            </div>
            <div class="hf-item">
                <i class="fa-solid fa-users hf-icon"></i>
                <div class="hf-text">
                    <strong>
                        <span class="lang-id">Jaringan Mitra</span>
                        <span class="lang-en">Partner Network</span>
                    </strong>
                    <span class="lang-id">Terpercaya</span>
                    <span class="lang-en">Trusted Globally</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FORM SECTION -->
<section class="form-section">
    <div class="form-layout">
        
        <!-- LEFT SIDEBAR -->
        <div class="sidebar-steps">
            <div class="steps-box">
                <div class="steps-title">
                    <span class="lang-id">Langkah Pendaftaran</span>
                    <span class="lang-en">Registration Steps</span>
                </div>
                
                <div class="step-item active" id="nav-step1" onclick="goToStep(1)">
                    <div class="step-number">1</div>
                    <div class="step-text">
                        <h4>
                            <span class="lang-id">Informasi Perusahaan</span>
                            <span class="lang-en">Company Information</span>
                        </h4>
                        <p>
                            <span class="lang-id">Data umum perusahaan</span>
                            <span class="lang-en">General company data</span>
                        </p>
                    </div>
                </div>
                <div class="step-item" id="nav-step2" onclick="goToStep(2)">
                    <div class="step-number">2</div>
                    <div class="step-text">
                        <h4>
                            <span class="lang-id">Kontak & Dokumen</span>
                            <span class="lang-en">Contact & Documents</span>
                        </h4>
                        <p>
                            <span class="lang-id">Informasi kontak & dokumen</span>
                            <span class="lang-en">Contact info & legal docs</span>
                        </p>
                    </div>
                </div>
                <div class="step-item" id="nav-step3" onclick="goToStep(3)">
                    <div class="step-number">3</div>
                    <div class="step-text">
                        <h4>
                            <span class="lang-id">Tinjau & Kirim</span>
                            <span class="lang-en">Review & Submit</span>
                        </h4>
                        <p>
                            <span class="lang-id">Periksa dan kirim pendaftaran</span>
                            <span class="lang-en">Review and send application</span>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="help-box">
                <i class="fa-solid fa-headset icon"></i>
                <h4>
                    <span class="lang-id">Butuh Bantuan?</span>
                    <span class="lang-en">Need Help?</span>
                </h4>
                <p>
                    <span class="lang-id">Tim kami siap membantu Anda selama proses pendaftaran.</span>
                    <span class="lang-en">Our team is ready to assist you during the registration process.</span>
                </p>
                <div style="font-size: 0.8rem; margin: 0.6rem 0 0.8rem; opacity: 0.95; line-height: 1.6; text-align: left;">
                    <div><i class="fa-solid fa-phone" style="width: 16px;"></i> <strong>0882-2100-2100</strong></div>
                    <div style="word-break: break-all;"><i class="fa-solid fa-envelope" style="width: 16px;"></i> <strong>workshop@dnajayagroup.co.id</strong></div>
                </div>
                <a href="https://wa.me/6288221002100?text=Halo%20Admin,%20saya%20butuh%20bantuan%20terkait%20registrasi%20vendor." target="_blank" class="btn-help">
                    <i class="fa-brands fa-whatsapp"></i> 
                    <span class="lang-id">Hubungi via WhatsApp</span>
                    <span class="lang-en">Contact via WhatsApp</span>
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
                
                <!-- MOBILE STEPS -->
                <div class="mobile-steps" id="mobile-steps-container"></div>

                <!-- STEP 1 -->
                <div class="step-content active" id="step1">
                    <div class="form-header">
                        <div class="icon"><i class="fa-regular fa-building"></i></div>
                        <h2>
                            <span class="lang-id">Informasi Perusahaan</span>
                            <span class="lang-en">Company Information</span>
                        </h2>
                    </div>
                    
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">
                                <span class="lang-id">Nama Perusahaan</span>
                                <span class="lang-en">Company Name</span> 
                                <span class="req">*</span>
                            </label>
                            <input type="text" name="company_name" class="form-control" placeholder="..." value="{{ old('company_name') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <span class="lang-id">Kategori Vendor</span>
                                <span class="lang-en">Vendor Category</span> 
                                <span class="req">*</span>
                            </label>
                            <select name="business_category" class="form-control" required>
                                <option value="" disabled selected class="lang-id">Pilih kategori vendor</option>
                                <option value="" disabled selected class="lang-en">Select category</option>
                                <option value="Perorangan" {{ old('business_category') == 'Perorangan' ? 'selected' : '' }}>Perorangan / Individual</option>
                                <option value="Badan" {{ old('business_category') == 'Badan' ? 'selected' : '' }}>Badan / Enterprise</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            <span class="lang-id">Alamat Perusahaan</span>
                            <span class="lang-en">Company Address</span> 
                            <span class="req">*</span>
                        </label>
                        <textarea name="company_address" class="form-control" rows="3" placeholder="..." required>{{ old('company_address') }}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            <span class="lang-id">Lokasi di Google Maps</span>
                            <span class="lang-en">Google Maps Location</span> 
                            <span class="req">*</span>
                        </label>
                        <input type="url" name="google_maps_link" class="form-control" placeholder="https://maps.app.goo.gl/xxxxxxxxxxxx" value="{{ old('google_maps_link') }}" required>
                        <span class="help-text">
                            <span class="lang-id">Buka Google Maps → pilih lokasi → Bagikan → Salin link → Tempel di sini.</span>
                            <span class="lang-en">Open Google Maps → select location → Share → Copy link → Paste here.</span>
                        </span>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            <span class="lang-id">Nomor NPWP</span>
                            <span class="lang-en">Tax ID (NPWP)</span> 
                            <span class="req">*</span>
                        </label>
                        <input type="text" name="npwp" class="form-control" placeholder="00.000.000.0-000.000" value="{{ old('npwp') }}" required>
                        <span class="help-text">
                            <span class="lang-id">Masukkan NPWP perusahaan Anda</span>
                            <span class="lang-en">Enter your company's Tax ID</span>
                        </span>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn-draft">
                            <span class="lang-id">Simpan Draf</span>
                            <span class="lang-en">Save Draft</span>
                        </button>
                        <button type="button" class="btn-next" onclick="goToStep(2)">
                            <span class="lang-id">Selanjutnya</span>
                            <span class="lang-en">Next Step</span> 
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- STEP 2 -->
                <div class="step-content" id="step2">
                    <div class="form-header">
                        <div class="icon"><i class="fa-regular fa-id-badge"></i></div>
                        <h2>
                            <span class="lang-id">Kontak & Dokumen</span>
                            <span class="lang-en">Contact & Documents</span>
                        </h2>
                    </div>
                    
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">
                                <span class="lang-id">Email Perusahaan</span>
                                <span class="lang-en">Company Email</span> 
                                <span class="req">*</span>
                            </label>
                            <input type="email" name="company_email" class="form-control" placeholder="email@perusahaan.com" value="{{ old('company_email') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <span class="lang-id">Nomor Telepon</span>
                                <span class="lang-en">Phone Number</span> 
                                <span class="req">*</span>
                            </label>
                            <input type="text" name="company_phone" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('company_phone') }}" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            <span class="lang-id">Nama PIC / Penanggung Jawab</span>
                            <span class="lang-en">PIC / Person in Charge Name</span> 
                            <span class="req">*</span>
                        </label>
                        <input type="text" name="pic_name" class="form-control" placeholder="..." value="{{ old('pic_name') }}" required>
                    </div>
                    
                    <hr style="border:0; border-top: 1px solid #e2e8f0; margin: 2rem 0;">
                    
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">
                                <span class="lang-id">Upload KTP</span>
                                <span class="lang-en">Upload ID Card (KTP)</span> 
                                <span class="req">*</span>
                            </label>
                            <div class="file-upload-box" id="box-ktp" onclick="document.getElementById('idCardInput').click()">
                                <i class="fa-solid fa-id-card"></i>
                                <p>
                                    <span class="lang-id">Pilih file KTP</span>
                                    <span class="lang-en">Select ID file</span>
                                </p>
                                <span>JPG, PNG, PDF max 5MB</span>
                                <div class="file-indicator" id="ind-ktp"></div>
                                <input type="file" name="id_card" id="idCardInput" accept=".jpg,.jpeg,.png,.pdf" required onchange="handleFile(this, 'box-ktp', 'ind-ktp')">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <span class="lang-id">Upload NPWP</span>
                                <span class="lang-en">Upload Tax ID (NPWP)</span> 
                                <span class="req">*</span>
                            </label>
                            <div class="file-upload-box" id="box-npwp" onclick="document.getElementById('npwpInput').click()">
                                <i class="fa-solid fa-file-invoice-dollar"></i>
                                <p>
                                    <span class="lang-id">Pilih file NPWP</span>
                                    <span class="lang-en">Select Tax file</span>
                                </p>
                                <span>JPG, PNG, PDF max 5MB</span>
                                <div class="file-indicator" id="ind-npwp"></div>
                                <input type="file" name="npwp_file" id="npwpInput" accept=".jpg,.jpeg,.png,.pdf" required onchange="handleFile(this, 'box-npwp', 'ind-npwp')">
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">
                                <span class="lang-id">Upload Buku Rekening</span>
                                <span class="lang-en">Upload Bank Book</span> 
                                <span class="req">*</span>
                            </label>
                            <div class="file-upload-box" id="box-bank" onclick="document.getElementById('bankBookInput').click()">
                                <i class="fa-solid fa-book"></i>
                                <p>
                                    <span class="lang-id">Pilih file Rekening</span>
                                    <span class="lang-en">Select Bank Book</span>
                                </p>
                                <span>
                                    <span class="lang-id">Halaman depan</span>
                                    <span class="lang-en">Front page</span>
                                </span>
                                <div class="file-indicator" id="ind-bank"></div>
                                <input type="file" name="bank_book" id="bankBookInput" accept=".jpg,.jpeg,.png,.pdf" required onchange="handleFile(this, 'box-bank', 'ind-bank')">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <span class="lang-id">Upload Foto Kantor</span>
                                <span class="lang-en">Upload Office Photos</span> 
                                <span class="req">*</span>
                            </label>
                            <div class="file-upload-box" id="box-office" onclick="document.getElementById('officePhotosInput').click()">
                                <i class="fa-solid fa-images"></i>
                                <p>
                                    <span class="lang-id">Pilih Foto Kantor</span>
                                    <span class="lang-en">Select Office Photos</span>
                                </p>
                                <span>
                                    <span class="lang-id">Tampak depan & dalam (Min 2)</span>
                                    <span class="lang-en">Front & inside view (Min 2)</span>
                                </span>
                                <div class="file-indicator" id="ind-office"></div>
                                <input type="file" id="officePhotosInput" accept=".jpg,.jpeg,.png" multiple onchange="handleMultipleFiles(this, 'box-office', 'ind-office')">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn-prev" onclick="goToStep(1)">
                            <span class="lang-id">Kembali</span>
                            <span class="lang-en">Back</span>
                        </button>
                        <button type="button" class="btn-next" onclick="goToStep(3)">
                            <span class="lang-id">Selanjutnya</span>
                            <span class="lang-en">Next Step</span> 
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- STEP 3 -->
                <div class="step-content" id="step3">
                    <div class="form-header">
                        <div class="icon"><i class="fa-solid fa-check-double"></i></div>
                        <h2>
                            <span class="lang-id">Tinjau & Kirim</span>
                            <span class="lang-en">Review & Submit</span>
                        </h2>
                    </div>
                    
                    <p style="color: var(--text-gray); font-size: 0.9rem; margin-bottom: 2rem;">
                        <span class="lang-id">Silakan tinjau kembali data yang telah Anda masukkan pada langkah sebelumnya. Jika sudah benar, setujui syarat dan ketentuan untuk mengirimkan pendaftaran.</span>
                        <span class="lang-en">Please review the data you entered in the previous steps. If everything is correct, agree to the terms and conditions to submit your application.</span>
                    </p>
                    
                    <div class="agreement-box" id="agreementBoxWrapper">
                        <label id="agreementLabel" onclick="handleAgreementClick(event)">
                            <input type="checkbox" name="agreement" id="agreement" required>
                            <div class="agreement-text">
                                <strong>
                                    <span class="lang-id">Saya menyetujui Syarat dan Ketentuan</span>
                                    <span class="lang-en">I agree to the Terms and Conditions</span>
                                </strong><br>
                                <span class="lang-id">Dengan mencentang kotak ini, saya menyatakan bahwa data yang saya berikan adalah benar dan dapat dipertanggungjawabkan. Saya setuju untuk terikat oleh MOU Perjanjian Kerjasama Vendor <span class="brand-text" style="color:var(--navy)">DNA <span class="brand-red">Advertising</span></span>.</span>
                                <span class="lang-en">By checking this box, I declare that the data provided is true and accountable. I agree to be bound by the Vendor Cooperation MOU of <span class="brand-text" style="color:var(--navy)">DNA <span class="brand-red">Advertising</span></span>.</span>
                                
                                <br>
                                <button type="button" class="btn-mou" onclick="openMou()">
                                    <i class="fa-solid fa-file-contract"></i> 
                                    <span class="lang-id">Baca Dokumen MOU Dulu</span>
                                    <span class="lang-en">Read MOU Document First</span>
                                </button>
                            </div>
                        </label>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn-prev" onclick="goToStep(2)">
                            <span class="lang-id">Kembali</span>
                            <span class="lang-en">Back</span>
                        </button>
                        <button type="button" class="btn-submit btn-locked" id="submitBtn" onclick="submitVendorForm()" title="Harap baca dan setujui MOU terlebih dahulu" disabled>
                            <i class="fa-solid fa-lock" id="submitLockIcon"></i>
                            <span class="lang-id">Kirim Pendaftaran</span>
                            <span class="lang-en">Submit Registration</span>
                        </button>
                    </div>
                </div>
                
            </form>
            
            <!-- MOBILE HELP -->
            <div class="help-box mobile-help">
                <i class="fa-solid fa-headset icon"></i>
                <h4>
                    <span class="lang-id">Butuh Bantuan?</span>
                    <span class="lang-en">Need Help?</span>
                </h4>
                <p>
                    <span class="lang-id">Tim kami siap membantu Anda selama proses pendaftaran.</span>
                    <span class="lang-en">Our team is ready to assist you during the registration process.</span>
                </p>
                <div style="font-size: 0.8rem; margin: 0.6rem 0 0.8rem; opacity: 0.95; line-height: 1.6; text-align: left;">
                    <div><i class="fa-solid fa-phone" style="width: 16px;"></i> <strong>0882-2100-2100</strong></div>
                    <div style="word-break: break-all;"><i class="fa-solid fa-envelope" style="width: 16px;"></i> <strong>workshop@dnajayagroup.co.id</strong></div>
                </div>
                <a href="https://wa.me/6288221002100?text=Halo%20Admin,%20saya%20butuh%20bantuan%20terkait%20registrasi%20vendor." target="_blank" class="btn-help">
                    <i class="fa-brands fa-whatsapp"></i> 
                    <span class="lang-id">Hubungi via WhatsApp</span>
                    <span class="lang-en">Contact via WhatsApp</span>
                </a>
            </div>
            
        </div>
    </div>
</section>

<!-- MODAL MOU RESMI DNA ADVERTISING -->
<div id="mouModal" class="mou-modal">
    <div class="mou-content">
        <div class="mou-watermark"></div>
        
        <!-- Header Dokumen Ringkas -->
        <div class="mou-header">
            <div class="mou-header-title">
                <h3>MEMORANDUM OF UNDERSTANDING (MOU)</h3>
                <p class="mou-sub">STANDAR KETENTUAN KERJA SAMA KEMITRAAN VENDOR &bull; PT. DNA JAYA GROUP</p>
            </div>
            <button type="button" class="mou-close" onclick="closeMou()" title="Tutup Modal">&times;</button>
        </div>

        <!-- Progress Bar & Reading Status HUD (Compact) -->
        <div class="mou-progress-strip">
            <div class="mou-progress-bar-fill" id="mouProgressFill"></div>
        </div>
        <div class="mou-reading-hud">
            <div class="mou-hud-left">
                <i class="fa-solid fa-book-open"></i>
                <span id="mouScrollStatus">Progres: 0%</span>
            </div>
            <div class="mou-hud-right" id="mouTimerHud">
                <i class="fa-regular fa-clock"></i>
                <span id="mouTimeRemaining">Wajib Baca: 15 dtk</span>
            </div>
        </div>

        <!-- Isi Naskah MOU Rapi, Rapat, dengan Penekanan Warna pada Teks Penting -->
        <div class="mou-body" id="mouBody">
            
            <!-- PASAL 1 -->
            <div class="mou-article">
                <div class="mou-article-header">
                    <span class="mou-article-label">PASAL 1</span>
                    <span class="mou-article-sep">—</span>
                    <h4 class="mou-article-title">KETENTUAN UMUM</h4>
                </div>
                <p>(1) Memorandum Of Understanding (MOU) ini merupakan dasar dan ketentuan umum kerja sama antara <span class="mou-primary">PT. DNA JAYA GROUP</span> (selanjutnya disebut <strong>PIHAK PERTAMA</strong>) dengan pihak vendor yang mendaftarkan diri menjadi rekanan resmi (selanjutnya disebut <strong>PIHAK KEDUA</strong>).</p>
                <p>(2) MOU ini dibuat sebagai bentuk kesepahaman mengenai hak, kewajiban, tanggung jawab, serta ketentuan yang <span class="mou-primary">wajib dipatuhi dan mengikat secara hukum</span> oleh PIHAK KEDUA selama menjalankan kerja sama dengan PIHAK PERTAMA.</p>
            </div>

            <!-- PASAL 2 -->
            <div class="mou-article">
                <div class="mou-article-header">
                    <span class="mou-article-label">PASAL 2</span>
                    <span class="mou-article-sep">—</span>
                    <h4 class="mou-article-title">RUANG LINGKUP PEKERJAAN & SPK</h4>
                </div>
                <p>(1) PIHAK PERTAMA memberikan pekerjaan advertising/periklanan sesuai spesifikasi dan lokasi yang tercantum dalam <span class="mou-primary">Surat Perintah Kerja (SPK) resmi</span> yang diterbitkan kepada PIHAK KEDUA, dan PIHAK KEDUA menyatakan telah sepakat untuk melaksanakan pekerjaan tersebut.</p>
                <p>(2) <span class="mou-warning">Masa Sanggah 1 x 24 Jam:</span> Apabila PIHAK KEDUA merasa keberatan atau tidak mampu mengerjakan SPK yang turun dari PIHAK PERTAMA, maka PIHAK KEDUA diberikan waktu <span class="mou-warning">maksimal 1 x 24 Jam Masa Sanggah</span> untuk membatalkan atau merevisi SPK. Lewat dari 24 jam, SPK dianggap disetujui penuh dan <span class="mou-danger">dilarang membatalkan atau meninggalkan pekerjaan secara sepihak</span>.</p>
                <p>(3) PIHAK KEDUA menyatakan sanggup untuk menyediakan bahan baku bermutu standar tinggi dan jasa pemasangan yang rapi dan profesional.</p>
                <p>(4) Seluruh pekerjaan menjadi tanggung jawab PIHAK KEDUA dan wajib diserahkan tepat waktu sesuai kuantitas, kualitas, serta tenggat waktu (deadline) yang ditetapkan.</p>
            </div>

            <!-- PASAL 3 -->
            <div class="mou-article">
                <div class="mou-article-header">
                    <span class="mou-article-label">PASAL 3</span>
                    <span class="mou-article-sep">—</span>
                    <h4 class="mou-article-title">KESELAMATAN & KESEHATAN KERJA (K3)</h4>
                </div>
                <p>(1) PIHAK KEDUA berkewajiban mutlak untuk melaksanakan pekerjaan dengan mematuhi aturan <span class="mou-primary">Keselamatan dan Kesehatan Kerja (K3)</span>.</p>
                <p>(2) PIHAK KEDUA wajib menugaskan tenaga kerja ahli yang kompeten dan berpengalaman sesuai jenis pekerjaan.</p>
                <p>(3) <span class="mou-danger">Pelepasan Tanggung Jawab atas Kelalaian K3:</span> Jika terjadi kecelakaan kerja akibat ketidakpatuhan PIHAK KEDUA terhadap aturan K3, maka <span class="mou-danger">PIHAK PERTAMA TIDAK BERTANGGUNG JAWAB</span> atas segala kerugian yang timbul. Seluruh akibat hukum dan biaya perawatan menjadi beban dan tanggung jawab PIHAK KEDUA sepenuhnya.</p>
                <p>(4) Di lokasi pekerjaan <span class="mou-primary">wajib ada perwakilan PIHAK KEDUA yang ditunjuk sebagai Pimpinan Pelaksana</span> dengan wewenang penuh mengambil tindakan di lapangan termasuk penanganan risiko keselamatan kerja.</p>
                <p>(5) Semua persoalan dan tuntutan tenaga kerja sepenuhnya menjadi beban dan tanggung jawab PIHAK KEDUA, baik di dalam maupun di luar pengadilan.</p>
            </div>

            <!-- PASAL 4 -->
            <div class="mou-article">
                <div class="mou-article-header">
                    <span class="mou-article-label">PASAL 4</span>
                    <span class="mou-article-sep">—</span>
                    <h4 class="mou-article-title">KEWAJIBAN, STANDAR APD & GARANSI</h4>
                </div>
                <p>(1) PIHAK KEDUA berkewajiban melaksanakan pekerjaan dengan penuh tanggung jawab teknis, mematuhi hukum yang berlaku, dan menjaga kelestarian lingkungan hidup.</p>
                <p>(2) <span class="mou-primary">Wajib BPJS Ketenagakerjaan:</span> Setiap pekerja PIHAK KEDUA <span class="mou-primary">wajib didaftarkan menjadi peserta aktif BPJS Ketenagakerjaan</span> oleh PIHAK KEDUA.</p>
                <p>(3) <span class="mou-primary">Standar APD Wajib Ketinggian:</span> Setiap pekerja wajib mengenakan <span class="mou-primary">Sepatu Safety, Full Body Harness, Helm Pengaman, dan Sarung Tangan Kerja</span>. Dilarang keras merusak konstruksi reklame kecuali dalam keadaan darurat resmi.</p>
                <p>(4) Apabila PIHAK KEDUA belum memiliki alat pelindung di atas, dapat meminjam kepada PIHAK PERTAMA dan wajib mengembalikannya sesuai kondisi semula.</p>
                <p>(5) <span class="mou-warning">Batas Jam Operasional:</span> PIHAK KEDUA wajib mematuhi jam kerja operasional di lapangan yaitu <span class="mou-warning">dimulai Jam 07.00 dan selambat-lambatnya selesai Jam 17.00 Waktu Setempat</span>.</p>
                <p>(6) Dalam kondisi hujan lebat, angin kencang, atau potensi bahaya kabel listrik PLN, pekerjaan wajib segera dihentikan dan dilaporkan kepada PIHAK PERTAMA.</p>
                <p>(7) <span class="mou-warning">Masa Garansi Konstruksi:</span> PIHAK KEDUA wajib memberikan garansi hasil pekerjaan yang telah selesai:</p>
                <ul style="margin: 0 0 0.5rem 1.25rem; padding-left: 0; line-height: 1.5;">
                    <li>Pekerjaan <strong>Permanen</strong>: Masa garansi konstruksi minimal <span class="mou-warning">1 (Satu) Tahun</span> sejak pengiriman foto selesai pekerjaan.</li>
                    <li>Pekerjaan <strong>Insidentil</strong>: Masa garansi konstruksi minimal <span class="mou-warning">7 (Tujuh) Hari</span> sejak pengiriman foto selesai pekerjaan (kecuali penertiban dinas atau force majeure).</li>
                </ul>
                <p>Kerusakan fisik dalam masa garansi <span class="mou-danger">wajib diperbaiki kembali oleh PIHAK KEDUA tanpa memungut biaya tambahan</span>.</p>
            </div>

            <!-- PASAL 5 -->
            <div class="mou-article">
                <div class="mou-article-header">
                    <span class="mou-article-label">PASAL 5</span>
                    <span class="mou-article-sep">—</span>
                    <h4 class="mou-article-title">TATA CARA PEMBAYARAN & PAJAK</h4>
                </div>
                <p>(1) Pembayaran harga pekerjaan dilakukan oleh PIHAK PERTAMA kepada PIHAK KEDUA sesuai nominal dan termin pada Surat Perintah Kerja (SPK) serta Berita Acara Serah Terima (BAST).</p>
                <p>(2) <span class="mou-primary">Pemotongan Pajak PPh:</span> PIHAK PERTAMA berhak <span class="mou-primary">memotong pembayaran Pajak Penghasilan (PPh)</span> sesuai peraturan perundang-undangan perpajakan yang berlaku.</p>
                <p>(3) Pembayaran dicairkan secara nontunai melalui <span class="mou-primary">Transfer Bank resmi</span> ke rekening PIHAK KEDUA yang telah divalidasi pada sistem.</p>
            </div>

            <!-- PASAL 6 -->
            <div class="mou-article">
                <div class="mou-article-header">
                    <span class="mou-article-label">PASAL 6</span>
                    <span class="mou-article-sep">—</span>
                    <h4 class="mou-article-title">JANGKA WAKTU PERJANJIAN</h4>
                </div>
                <p>(1) Jangka waktu perjanjian kerja sama ini berlaku sejak disetujui melalui sistem website dan berlaku seterusnya selama kedua belah pihak menjalin kemitraan.</p>
                <p>(2) PIHAK PERTAMA berhak mengakhiri kerja sama sepihak seketika apabila PIHAK KEDUA melakukan wanprestasi, pemalsuan data, atau pelanggaran berat terhadap SOP keselamatan kerja.</p>
            </div>

            <!-- PASAL 7 -->
            <div class="mou-article">
                <div class="mou-article-header">
                    <span class="mou-article-label">PASAL 7</span>
                    <span class="mou-article-sep">—</span>
                    <h4 class="mou-article-title">KEADAAN MEMAKSA (FORCE MAJEURE)</h4>
                </div>
                <p>(1) Meliputi bencana alam, gempa bumi, banjir bandang, peperangan, huru-hara, dan pemogokan massal di luar kekuasaan PARA PIHAK.</p>
                <p>(2) Pihak terdampak <span class="mou-warning">wajib memberitahukan secara tertulis selambat-lambatnya 3 (tiga) hari kalender</span> setelah kejadian. Kelalaian melapor dalam batas 3 hari kalender menyebabkan keadaan memaksa dianggap gugur dan pekerjaan tetap wajib diselesaikan.</p>
            </div>

            <!-- PASAL 8 -->
            <div class="mou-article">
                <div class="mou-article-header">
                    <span class="mou-article-label">PASAL 8</span>
                    <span class="mou-article-sep">—</span>
                    <h4 class="mou-article-title">PENYELESAIAN PERSELISIHAN</h4>
                </div>
                <p>(1) Setiap perselisihan diselesaikan terlebih dahulu secara musyawarah untuk mencapai mufakat.</p>
                <p>(2) Apabila penyelesaian musyawarah tidak tercapai, PARA PIHAK sepakat memilih domisili hukum yang tetap di <span class="mou-primary">Pengadilan Negeri Sidoarjo</span>.</p>
            </div>

            <!-- PASAL 9 -->
            <div class="mou-article">
                <div class="mou-article-header">
                    <span class="mou-article-label">PASAL 9</span>
                    <span class="mou-article-sep">—</span>
                    <h4 class="mou-article-title">KETENTUAN LAIN-LAIN</h4>
                </div>
                <p>(1) Apabila terjadi perbedaan penafsiran antara Perjanjian ini dengan lampirannya, maka yang diberlakukan adalah ketentuan utama dalam Perjanjian ini.</p>
                <p>(2) Ketentuan teknis detail setiap proyek diatur terpisah dalam SPK yang merupakan satu kesatuan mengikat dari MOU ini.</p>
            </div>

            <!-- PASAL 10 -->
            <div class="mou-article">
                <div class="mou-article-header">
                    <span class="mou-article-label">PASAL 10</span>
                    <span class="mou-article-sep">—</span>
                    <h4 class="mou-article-title">KEABSAHAN PERSETUJUAN WEBSITE</h4>
                </div>
                <p>(1) PIHAK KEDUA menyatakan telah membaca dengan teliti dan memahami seluruh isi MOU ini sebelum memberikan persetujuan.</p>
                <p>(2) Persetujuan digital melalui sistem ini <span class="mou-primary">sah, berkekuatan hukum penuh, dan mengikat setara tanda tangan basah di atas meterai</span>.</p>
                <p>(3) PIHAK KEDUA <span class="mou-danger">bertanggung jawab penuh secara hukum</span> atas kebenaran seluruh dokumen legalitas (KTP, NPWP, Buku Rekening, Foto Kantor) yang disampaikan.</p>
            </div>

            <!-- PASAL 11 -->
            <div class="mou-article">
                <div class="mou-article-header">
                    <span class="mou-article-label">PASAL 11</span>
                    <span class="mou-article-sep">—</span>
                    <h4 class="mou-article-title">PENUTUP</h4>
                </div>
                <p>MOU ini berlaku sebagai ketentuan umum kerja sama kemitraan vendor sejak persetujuan dilakukan melalui sistem pendaftaran website <strong>PT. DNA JAYA GROUP</strong>.</p>
            </div>
            
        </div>

        <!-- Footer Modal Ringkas -->
        <div class="mou-footer">
            <button type="button" class="btn-draft" onclick="closeMou()">
                <span class="lang-id">Tutup Dulu</span>
                <span class="lang-en">Close</span>
            </button>
            
            <button type="button" id="btnAcceptMou" class="btn-accept-mou disabled" onclick="handleAcceptMouClick()" disabled>
                <i class="fa-solid fa-lock" id="iconAcceptMou"></i> 
                <span id="textAcceptMou">Harap Baca Seluruh MOU (15 dtk)</span>
            </button>
        </div>
    </div>
</div>

<script>
// ==================== LANGUAGE SWITCHER ====================
let currentLang = 'id'; // default

function switchLang(lang) {
    currentLang = lang;
    
    // Update button visual state
    document.querySelectorAll('.lang-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelector(`.lang-btn[onclick="switchLang('${lang}')"]`).classList.add('active');
    
    // Toggle body class which controls CSS display
    if(lang === 'en') {
        document.body.classList.add('lang-en-active');
    } else {
        document.body.classList.remove('lang-en-active');
    }
    
    // Update step data
    if(lang === 'en') {
        steps[0].title = 'Company Information'; steps[0].desc = 'General company data';
        steps[1].title = 'Contact & Documents'; steps[1].desc = 'Contact info & legal docs';
        steps[2].title = 'Review & Submit'; steps[2].desc = 'Review and send application';
    } else {
        steps[0].title = 'Informasi Perusahaan'; steps[0].desc = 'Data umum perusahaan';
        steps[1].title = 'Kontak & Dokumen'; steps[1].desc = 'Informasi kontak & dokumen';
        steps[2].title = 'Tinjau & Kirim'; steps[2].desc = 'Periksa dan kirim pendaftaran';
    }
    
    goToStep(currentStepNumber);
}

// ==================== FILE UPLOAD INDICATORS ====================
function handleFile(inputElement, boxId, indicatorId) {
    const box = document.getElementById(boxId);
    const indicator = document.getElementById(indicatorId);
    
    if (inputElement.files && inputElement.files.length > 0) {
        box.classList.remove('error');
        box.classList.add('has-file');
        const fileName = inputElement.files[0].name;
        const fileSize = (inputElement.files[0].size / (1024 * 1024)).toFixed(2) + ' MB';
        indicator.className = 'file-indicator success';
        indicator.innerHTML = `<i class="fa-solid fa-check"></i> Disimpan: ${fileName} (${fileSize})`;
    } else {
        box.classList.remove('has-file');
        indicator.innerHTML = '';
    }
}

let accumulatedOfficePhotos = [];

function handleMultipleFiles(inputElement, boxId, indicatorId) {
    const box = document.getElementById(boxId);
    const indicator = document.getElementById(indicatorId);
    
    if (inputElement.files && inputElement.files.length > 0) {
        // Tambahkan file baru ke dalam array global
        for (let i = 0; i < inputElement.files.length; i++) {
            accumulatedOfficePhotos.push(inputElement.files[i]);
        }
        
        // Reset input supaya bisa klik lagi untuk nambah file
        inputElement.value = '';
        
        if (accumulatedOfficePhotos.length >= 2) {
            box.classList.remove('error');
            box.classList.add('has-file');
            indicator.className = 'file-indicator success';
            indicator.innerHTML = `<i class="fa-solid fa-check"></i> Berhasil: ${accumulatedOfficePhotos.length} Foto Kantor disimpan`;
        } else {
            box.classList.remove('has-file');
            box.classList.add('error');
            indicator.className = 'file-indicator error';
            indicator.innerHTML = `<i class="fa-solid fa-triangle-exclamation"></i> Baru ${accumulatedOfficePhotos.length} Foto! (Klik kotak ini lagi untuk tambah foto)`;
        }
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
    
    document.querySelectorAll('.step-content').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.step-item').forEach(el => el.classList.remove('active'));
    
    let target = document.getElementById('step' + stepNumber);
    if(target) target.classList.add('active');
    
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
    
    // Update Mobile
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
                <div class="mobile-step-item" style="opacity: 0.8;" onclick="goToStep(${i})">
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
    
    window.scrollTo({ top: document.querySelector('.form-section').offsetTop - 20, behavior: 'smooth' });
}

// ==================== SISTEM VERIFIKASI MEMBACA MOU KETAT ====================
let hasReadMou = false;
let mouTimer = null;
let mouSecondsLeft = 15;
let hasScrolledToBottom = false;
let isMouTimeFinished = false;
let isMouTrackingInitialized = false;

function handleAgreementClick(event) {
    if (!hasReadMou) {
        event.preventDefault(); 
        openMou();
    }
}

function openMou() {
    const modal = document.getElementById('mouModal');
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';

    // Inisialisasi pelacakan membaca jika belum selesai
    if (!hasReadMou) {
        initMouReadingTracking();
    }
}

function closeMou() {
    document.getElementById('mouModal').classList.remove('show');
    document.body.style.overflow = '';
}

function initMouReadingTracking() {
    const body = document.getElementById('mouBody');
    const progressBar = document.getElementById('mouProgressFill');
    const scrollStatus = document.getElementById('mouScrollStatus');
    const timerHud = document.getElementById('mouTimerHud');
    const timeRemaining = document.getElementById('mouTimeRemaining');

    if (!isMouTrackingInitialized) {
        isMouTrackingInitialized = true;
        
        // Listener Scroll untuk memantau kemajuan pembacaan
        body.addEventListener('scroll', function() {
            const scrollTop = body.scrollTop;
            const maxScroll = body.scrollHeight - body.clientHeight;
            let percent = 0;
            if (maxScroll > 0) {
                percent = Math.min(100, Math.round((scrollTop / maxScroll) * 100));
            }
            
            if (progressBar) progressBar.style.width = percent + '%';
            if (scrollStatus) {
                if (percent >= 95) {
                    scrollStatus.innerHTML = '<i class="fa-solid fa-circle-check" style="color:#10b981;"></i> Selesai (100%)';
                } else {
                    scrollStatus.innerHTML = '<i class="fa-solid fa-book-open"></i> Progres: ' + percent + '%';
                }
            }

            // Batas minimal scroll mencapai bagian paling bawah (>= 94%)
            if (percent >= 94) {
                hasScrolledToBottom = true;
            }
            updateMouButtonState();
        }, { passive: true });
    }

    // Mulai hitung mundur waktu membaca wajib jika belum selesai
    if (!mouTimer && !isMouTimeFinished) {
        mouTimer = setInterval(function() {
            if (mouSecondsLeft > 0) {
                mouSecondsLeft--;
                if (timeRemaining) {
                    timeRemaining.textContent = 'Wajib Baca: ' + mouSecondsLeft + ' dtk';
                }
                updateMouButtonState();
            } else {
                isMouTimeFinished = true;
                clearInterval(mouTimer);
                mouTimer = null;
                if (timerHud) timerHud.classList.add('complete');
                if (timeRemaining) {
                    timeRemaining.innerHTML = '<i class="fa-solid fa-circle-check" style="color:#10b981;"></i> Waktu Terpenuhi';
                }
                updateMouButtonState();
            }
        }, 1000);
    }
}

function updateMouButtonState() {
    const btn = document.getElementById('btnAcceptMou');
    const icon = document.getElementById('iconAcceptMou');
    const text = document.getElementById('textAcceptMou');
    if (!btn || hasReadMou) return;

    // KONDISI 1: Belum scroll ke bawah sama sekali
    if (!hasScrolledToBottom) {
        btn.disabled = true;
        btn.className = 'btn-accept-mou disabled';
        icon.className = 'fa-solid fa-arrow-down';
        text.textContent = isMouTimeFinished 
            ? 'Gulir ke Bawah Hingga Selesai' 
            : 'Gulir & Baca MOU (' + mouSecondsLeft + 's)';
        return;
    }

    // KONDISI 2: Sudah scroll ke bawah tetapi waktu baca belum habis (mencegah scroll kilat)
    if (!isMouTimeFinished) {
        btn.disabled = true;
        btn.className = 'btn-accept-mou disabled';
        icon.className = 'fa-solid fa-hourglass-half';
        text.textContent = 'Harap Pahami Isi Ketentuan (' + mouSecondsLeft + 's)';
        return;
    }

    // KONDISI 3: DUA SYARAT TERPENUHI (Sudah scroll habis DAN waktu baca selesai)
    btn.disabled = false;
    btn.className = 'btn-accept-mou unlocked';
    icon.className = 'fa-solid fa-check-double';
    text.textContent = currentLang === 'id' 
        ? 'Saya Telah Membaca & Menyetujui MOU' 
        : 'I Have Read & Agree to MOU';
}

function handleAcceptMouClick() {
    if (!hasScrolledToBottom || !isMouTimeFinished) {
        alert(currentLang === 'id' 
            ? 'Harap gulir dokumen sampai bagian bawah dan luangkan waktu untuk membaca seluruh ketentuan hingga selesai.' 
            : 'Please scroll to the bottom and take time to read all terms thoroughly.');
        return;
    }
    acceptMou();
}

function acceptMou() {
    hasReadMou = true; 
    
    const checkbox = document.getElementById('agreement');
    const label = document.getElementById('agreementLabel');
    const mouBtn = document.querySelector('.btn-mou');
    const submitBtn = document.getElementById('submitBtn');
    const submitLockIcon = document.getElementById('submitLockIcon');
    
    // Otomatis centang checkbox persetujuan
    checkbox.checked = true;
    checkbox.style.pointerEvents = 'auto'; 
    label.classList.add('enabled'); 
    
    // Update tombol MOU pada form menjadi hijau centang
    if (currentLang === 'id') {
        mouBtn.innerHTML = '<i class="fa-solid fa-circle-check"></i> MOU Telah Dibaca & Disetujui';
    } else {
        mouBtn.innerHTML = '<i class="fa-solid fa-circle-check"></i> MOU Read & Accepted';
    }
    mouBtn.style.background = '#10b981'; 
    
    // AKTIFKAN TOMBOL SUBMIT PENDAFTARAN VENDOR!
    if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.classList.remove('btn-locked');
        submitBtn.title = 'Kirim Pendaftaran Vendor';
    }
    if (submitLockIcon) {
        submitLockIcon.className = 'fa-solid fa-paper-plane';
    }
    
    closeMou();
}

// ==================== UPLOAD ====================
async function submitVendorForm() {
    const form = document.getElementById('vendorForm');
    
    // Manual validation for all fields including hidden ones
    if (!form.checkValidity()) {
        for (let i = 0; i < form.elements.length; i++) {
            const field = form.elements[i];
            if (!field.checkValidity()) {
                let stepElement = field.closest('.step-content');
                if (stepElement) {
                    let stepId = stepElement.id.replace('step', '');
                    goToStep(parseInt(stepId));
                    setTimeout(() => field.reportValidity(), 100);
                    return;
                }
            }
        }
        return;
    }

    // CEK KELENGKAPAN BACA MOU
    if (!hasReadMou) {
        alert(currentLang === 'id' 
            ? 'PERHATIAN: Anda wajib membaca seluruh isi Dokumen MOU sampai selesai terlebih dahulu sebelum dapat mengirimkan pendaftaran vendor.' 
            : 'ATTENTION: You must read the entire MOU document to the end before submitting your vendor registration.');
        openMou();
        return;
    }

    const submitBtn = document.getElementById('submitBtn');

    const idCardFile = document.getElementById('idCardInput').files[0];
    const bankBookFile = document.getElementById('bankBookInput').files[0];
    const npwpFile = document.getElementById('npwpInput').files[0];
    const officePhotos = accumulatedOfficePhotos;

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

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> ' + (currentLang === 'id' ? 'Mengupload file...' : 'Uploading files...');

    try {
        const csrfToken = document.querySelector('input[name="_token"]').value;

        // Fungsi kompresi gambar (klien-side)
        async function compressImage(file, maxWidth = 1600, quality = 0.7) {
            // Hanya proses file gambar (abaikan PDF)
            if (!file.type.startsWith('image/')) return file;

            return new Promise((resolve) => {
                const img = new Image();
                img.src = URL.createObjectURL(file);
                img.onload = () => {
                    URL.revokeObjectURL(img.src);
                    let width = img.width;
                    let height = img.height;

                    if (width > maxWidth) {
                        height = Math.round((height * maxWidth) / width);
                        width = maxWidth;
                    }

                    const canvas = document.createElement('canvas');
                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    canvas.toBlob((blob) => {
                        if (blob) {
                            const newName = file.name.replace(/\.[^/.]+$/, "") + ".jpg";
                            resolve(new File([blob], newName, { type: 'image/jpeg', lastModified: Date.now() }));
                        } else {
                            resolve(file); // fallback
                        }
                    }, 'image/jpeg', quality);
                };
                img.onerror = () => resolve(file);
            });
        }

        async function uploadFile(file, folder) {
            // Kompres file sebelum diupload (jika gambar)
            const fileToUpload = await compressImage(file);
            
            const presignRes = await fetch('/upload/presign', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ filename: fileToUpload.name, folder: folder, type: fileToUpload.type })
            });

            if (!presignRes.ok) {
                const errData = await presignRes.json();
                throw new Error(errData.message || 'Gagal mendapatkan URL upload dari server');
            }
            const { upload_url, public_url } = await presignRes.json();

            const uploadRes = await fetch(upload_url, { method: 'PUT', headers: { 'Content-Type': fileToUpload.type }, body: fileToUpload });
            if (!uploadRes.ok) throw new Error('Gagal mengupload file: ' + await uploadRes.text());
            return public_url;
        }

        const idCardUrl = await uploadFile(idCardFile, 'id_cards');
        document.getElementById('id_card_url').value = idCardUrl;

        const bankBookUrl = await uploadFile(bankBookFile, 'bank_books');
        document.getElementById('bank_book_url').value = bankBookUrl;

        const npwpUrl = await uploadFile(npwpFile, 'npwp');
        document.getElementById('npwp_file_url').value = npwpUrl;

        const officePhotoUrls = [];
        for (const photo of officePhotos) {
            const url = await uploadFile(photo, 'office_photos');
            officePhotoUrls.push(url);
        }
        document.getElementById('office_photos_urls').value = JSON.stringify(officePhotoUrls);

        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> ' + (currentLang === 'id' ? 'Menyimpan data...' : 'Saving data...');

        form.querySelectorAll('input[type="file"]').forEach(input => { input.removeAttribute('name'); input.disabled = true; });

        form.submit();

    } catch (err) {
        console.error(err);
        const prefix = currentLang === 'id' ? 'Terjadi kesalahan saat mengupload file: ' : 'Error uploading file: ';
        alert(prefix + err.message);
        submitBtn.disabled = false;
        submitBtn.innerHTML = currentLang === 'id' ? 'Kirim Pendaftaran' : 'Submit Registration';
    }
}
</script>
@endsection
