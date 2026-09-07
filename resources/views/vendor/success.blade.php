@extends('layouts.public')

@section('title', 'Pendaftaran Berhasil - DNA Vendor Portal')

@section('content')
<style>
    /* RESET DEFAULT NAVBAR */
    nav.navbar { display: none !important; }
    
    /* Variables matching the design system */
    :root {
        --navy: #0a1628;
        --navy-light: #1b3a60;
        --gold: #f59e0b;
        --gold-hover: #d97706;
        --text-gray: #64748b;
    }

    body {
        background-color: var(--navy-light) !important;
        font-family: 'Inter', sans-serif;
        margin: 0;
        padding: 0;
    }

    /* CUSTOM NAV (Transparent over dark blue) */
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

    /* Container area tengah */
    .success-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 8rem 1.5rem 6rem;
    }

    /* Card Box Utama */
    .success-card {
        background-color: #ffffff;
        border-radius: 20px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
        padding: 4rem 3rem;
        max-width: 550px;
        width: 100%;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    /* Decorative top border */
    .success-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, #10b981, #059669);
    }

    /* Icon Checklist */
    .success-icon-wrapper {
        width: 90px;
        height: 90px;
        background: #ecfdf5;
        color: #10b981;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        box-shadow: 0 0 0 10px rgba(16, 185, 129, 0.1);
    }
    .success-icon-wrapper svg {
        width: 45px;
        height: 45px;
    }

    /* Judul Utama Kontras Jelas */
    .success-title {
        color: #0f172a;
        font-size: 1.85rem;
        font-weight: 800;
        margin-bottom: 1rem;
        line-height: 1.3;
        letter-spacing: -0.02em;
    }

    /* Deskripsi Subtitle */
    .success-description {
        color: #475569;
        font-size: 1rem;
        line-height: 1.7;
        margin-bottom: 2.5rem;
    }
    
    .success-description strong {
        color: #0f172a;
        font-weight: 700;
    }

    /* Tombol Return */
    .btn-return-home {
        background-color: var(--gold);
        color: var(--navy);
        font-weight: 700;
        font-size: 1.05rem;
        padding: 1rem 2.5rem;
        border-radius: 10px;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.25s ease;
        width: 100%;
    }

    .btn-return-home:hover {
        background-color: var(--gold-hover);
        transform: translateY(-3px);
        box-shadow: 0 10px 20px -10px rgba(217, 119, 6, 0.5);
    }

    /* MOBILE RESPONSIVE */
    @media (max-width: 768px) {
        .custom-nav { padding: 1.5rem; justify-content: center; }
        .success-card { padding: 3rem 1.5rem; }
        .success-title { font-size: 1.6rem; }
    }
</style>

<!-- NAVBAR -->
<nav class="custom-nav">
    <a href="{{ url('/') }}" class="nav-logo">
        <img src="{{ asset('images/logo.png') }}" alt="Logo">
        <span>DNA <span class="text-red">Vendor</span> Portal</span>
    </a>
</nav>

<div class="success-wrapper">
    <div class="success-card">
        <!-- Icon Centang -->
        <div class="success-icon-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>

        <!-- Judul Teks Sangat Jelas -->
        <h1 class="success-title">Pendaftaran Berhasil!</h1>

        <!-- Pesan Penjelasan -->
        <p class="success-description">
            Terima kasih telah mendaftarkan perusahaan Anda. Tim verifikasi kami akan meninjau aplikasi Anda.<br><br>
            Proses ini biasanya memakan waktu <strong>2-3 hari kerja</strong>. Anda akan menerima pemberitahuan via email setelah proses review selesai.
        </p>

        <!-- Tombol Kembali -->
        <div>
            <a href="{{ url('/') }}" class="btn-return-home">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection