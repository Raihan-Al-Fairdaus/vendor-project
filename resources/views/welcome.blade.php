@extends('layouts.public')

@section('title', 'Become Our Vendor - VendorConnect')

@section('content')

{{-- 1. BAGIAN CSS (Di dalam tag <style>) --}}
<style>
    /* Latar Belakang Gradasi */
    body, 
    .hero, 
    .section-white, 
    .section-gray {
        background: linear-gradient(135deg, #1b3a60 0%, #3a587d 50%, #899eb9 100%) !important;
        background-attachment: fixed !important;
    }
    html {
        background-color: #899eb9; /* Mencegah warna putih saat scroll (menyesuaikan ujung bawah gradasi) */
    }

    /* Warna Teks */
    .hero p, 
    .section-white p, 
    .section-gray p,
    .text-muted {
        color: rgba(255, 255, 255, 0.85) !important;
    }

    .section-white h2, 
    .section-gray h2, 
    .section-gray h3 {
        color: #ffffff !important;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
    }

    /* Card Styling */
    .section-white .card,
    .section-gray .card {
        background: rgba(255, 255, 255, 0.95) !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    }

    .section-white .card h2,
    .section-white .card p,
    .section-gray .card h3,
    .section-gray .card p {
        color: #1e293b !important;
    }

    .section-gray .card p {
        color: #64748b !important;
    }

    /* MARQUEE STYLING */
    .marquee-section {
        padding: 3.5rem 0 2rem 0;
        overflow: hidden;
        width: 100%;
    }

    .marquee-wrapper {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        width: 100%;
    }

    .marquee-container {
        display: flex;
        overflow: hidden;
        user-select: none;
        width: 100%;
        mask-image: linear-gradient(to right, transparent 0%, black 10%, black 90%, transparent 100%);
        -webkit-mask-image: linear-gradient(to right, transparent 0%, black 10%, black 90%, transparent 100%);
    }

    .marquee-track {
        display: flex;
        gap: 3.5rem;
        align-items: center;
        white-space: nowrap;
        will-change: transform;
        flex-shrink: 0;
    }

    .track-left {
        animation: marquee-left 25s linear infinite;
    }

    .track-right {
        animation: marquee-right 25s linear infinite;
    }

    .marquee-container:hover .marquee-track {
        animation-play-state: paused;
    }

   .marquee-item{
    flex:0 0 auto;
    display:flex;
    justify-content:center;
    align-items:center;
    width:120px;
}

.marquee-item img{
    display:block;
    width:auto !important;
    height:36px !important;
    max-width:100px !important;
    max-height:36px !important;
    object-fit:contain;
}

.marquee-item img:hover {
    transform: scale(1.2);
}

    /* KEYFRAMES ANIMASI */
    @keyframes marquee-left {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }

    @keyframes marquee-right {
        0% { transform: translateX(-50%); }
        100% { transform: translateX(0%); }
    }

    /* ========================================================
       MODAL HOW IT WORKS STYLING
       ======================================================== */
    .hiw-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(5px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .hiw-modal.active {
        display: flex !important;
    }

    .hiw-modal-content {
        background: #ffffff;
        color: #1b3a60;
        width: 90%;
        max-width: 450px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        overflow: hidden;
        animation: hiwScaleUp 0.3s ease;
        text-align: left;
    }

    @keyframes hiwScaleUp {
        from { transform: scale(0.9); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .hiw-modal-header {
        padding: 1.25rem 1.5rem;
        background: #1b3a60;
        color: #ffffff;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .hiw-modal-header h3 {
        margin: 0;
        font-size: 1.1rem;
        color: #ffffff !important;
        text-shadow: none !important;
    }

    .hiw-close-btn {
        font-size: 1.5rem;
        cursor: pointer;
        color: rgba(255,255,255,0.8);
    }

    .hiw-close-btn:hover {
        color: #ffffff;
    }

    .hiw-modal-body {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .hiw-step {
        display: flex;
        gap: 1rem;
        align-items: flex-start;
    }

    .hiw-number {
        background: #f59e0b;
        color: #1b3a60;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        flex-shrink: 0;
    }

    .hiw-step h4 {
        margin: 0 0 0.25rem 0;
        font-size: 0.95rem;
        color: #1b3a60 !important;
        text-shadow: none !important;
    }

    .hiw-step p {
        margin: 0;
        font-size: 0.85rem;
        color: #555555 !important;
    }
    /* ===========================
   MOBILE RESPONSIVE
=========================== */

@media (max-width:768px){

    .marquee-track{
        gap:16px;
    }

    .marquee-item{
        width:80px;
    }

    .marquee-item img{
        width:auto !important;
        height:20px !important;
        max-width:70px !important;
        max-height:20px !important;
    }

}
/* ===========================
   MOBILE HERO
=========================== */

@media (max-width:768px){

    .grid.grid-cols-2{
        grid-template-columns:1fr !important;
    }

    .hero{
        padding:40px 0 !important;
    }

    .hero .container{
        display:flex !important;
        flex-direction:column !important;
    }

    .hero .card{
        height:220px !important;
        margin-top:20px;
    }

    .hero img{
        width:100% !important;
        height:220px !important;
        object-fit:cover !important;
    }

    .hero-title{
        font-size:2rem !important;
    }

    .mobile-col{
        flex-direction:column !important;
    }

    .mobile-col .btn{
        width:100%;
    }

}
</style>

{{-- 2. BAGIAN HTML / TAMPILAN --}}
<div class="hero">
    <div class="container grid grid-cols-2 align-center gap-4">
        <div class="animate-on-scroll">
            <span style="color: #f59e0b; font-weight: 700; margin-bottom: 1rem; display: block; letter-spacing: 0.05em; text-transform: uppercase; font-size: 0.875rem;">Empowering Global Supply Chains</span>
            
            <h1 class="hero-title" style="color: #f59e0b; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">Become Our Vendor</h1>
            
            <p class="text-muted mb-4" style="font-size: 1.125rem;">
                Join our systematic procurement network. We provide a transparent, efficient platform for businesses to grow alongside our global enterprise requirements.
            </p>
            <div class="d-flex gap-4 mobile-col">
                <a href="{{ route('vendor.register') }}" class="btn btn-primary" style="background-color: #f59e0b; border-color: #f59e0b; color: #1e293b; font-weight: 600;">Register as Vendor</a>
                {{-- Tombol How It Works yang sudah diberi id="howItWorksBtn" --}}
                <a href="#" id="howItWorksBtn" class="btn btn-outline" style="border-color: rgba(255,255,255,0.4); color: #ffffff;">How it works</a>
            </div>
        </div>
        <div class="animate-on-scroll" style="transition-delay: 0.2s;">
            <div class="card" style="padding: 0 !important; overflow: hidden; border: none; height: 360px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); background: transparent;">
                <img src="{{ asset('images/kantor-project.jpg') }}" alt="VendorConnect Office" style="width: 100%; height: 100%; object-fit: cover; display: block;">
            </div>
        </div>
    </div>
</div>

<div class="section-white">
    <div class="container">
        <div class="card d-flex align-center justify-between mobile-col animate-on-scroll hoverable">
            <div style="flex: 1; padding-right: 2rem; margin-bottom: 1rem;">
                <h2 style="margin-bottom: 1rem;">Streamlined Procurement Ecosystem</h2>
                <p>Our vendor program is designed to eliminate bureaucratic friction. We leverage high-performance data verification to ensure that qualified partners can begin collaboration within days, not months.</p>
            </div>
            <div style="background: linear-gradient(135deg, #142642, #0d1a2d); color: white; padding: 2rem; border-radius: var(--radius-md); width: 100%; max-width: 320px; text-align: center; box-shadow: 0 8px 20px rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1);">
                <div style="display: flex; justify-content: center; margin-bottom: 1rem;">
                    <div style="background: rgba(255,255,255,0.15); width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">🤝</div>
                </div>
                <h3 style="font-size: 2.25rem; margin-bottom: 0.5rem; font-weight: 700; color: #ffffff !important;">Trusted</h3>
                <p style="font-size: 0.95rem; opacity: 0.9; color: #ffffff !important;">Business Network</p>
            </div>
        </div>
    </div>
</div>

{{-- SECTION LOGO MARQUEE --}}
<div class="marquee-section">
    <div class="container text-center mb-6">
        <span style="color: #f59e0b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; font-size: 0.8rem; background: rgba(245, 158, 11, 0.15); padding: 5px 16px; border-radius: 20px; border: 1px solid rgba(245, 158, 11, 0.3);">
            Technology & Infrastructure
        </span>
        <h3 style="color: #ffffff; font-size: 1.35rem; font-weight: 700; margin-top: 0.85rem; letter-spacing: 0.02em;">
           TELAH DIPERCAYA OLEH BERBAGAI MITRA BISNIS
        </h3>
    </div>

    <div class="marquee-wrapper">
        <!-- BARIS 1: JALAN KE KIRI -->
        <div class="marquee-container">
            <div class="marquee-track track-left">
                <!-- Group 1 -->
                 <div class="marquee-item"><img src="{{ asset('images/partners/adhi persada.png') }}" alt="Adhi Persada"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/dua kelinci.png') }}" alt="Dua Kelinci"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/gudang garam.png') }}" alt="Gudang Garam"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/indofood.png') }}" alt="Indofood"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/indomie.png') }}" alt="Indomie"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/j&t.png') }}" alt="J&T Express"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/pertamina.png') }}" alt="Pertamina"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/pln.png') }}" alt="PLN"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/sosro.png') }}" alt="Sosro"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/unipin.png') }}" alt="UniPin"></div>
                <!-- Group 2 -->
                <div class="marquee-item"><img src="{{ asset('images/partners/adhi persada.png') }}" alt="Adhi Persada"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/dua kelinci.png') }}" alt="Dua Kelinci"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/gudang garam.png') }}" alt="Gudang Garam"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/indofood.png') }}" alt="Indofood"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/indomie.png') }}" alt="Indomie"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/j&t.png') }}" alt="J&T Express"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/pertamina.png') }}" alt="Pertamina"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/pln.png') }}" alt="PLN"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/sosro.png') }}" alt="Sosro"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/unipin.png') }}" alt="UniPin"></div>
                <!-- Group 3 -->
                <div class="marquee-item"><img src="{{ asset('images/partners/adhi persada.png') }}" alt="Adhi Persada"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/dua kelinci.png') }}" alt="Dua Kelinci"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/gudang garam.png') }}" alt="Gudang Garam"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/indofood.png') }}" alt="Indofood"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/indomie.png') }}" alt="Indomie"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/j&t.png') }}" alt="J&T Express"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/pertamina.png') }}" alt="Pertamina"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/pln.png') }}" alt="PLN"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/sosro.png') }}" alt="Sosro"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/unipin.png') }}" alt="UniPin"></div>
                <!-- Group 4 -->
                 <div class="marquee-item"><img src="{{ asset('images/partners/adhi persada.png') }}" alt="Adhi Persada"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/dua kelinci.png') }}" alt="Dua Kelinci"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/gudang garam.png') }}" alt="Gudang Garam"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/indofood.png') }}" alt="Indofood"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/indomie.png') }}" alt="Indomie"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/j&t.png') }}" alt="J&T Express"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/pertamina.png') }}" alt="Pertamina"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/pln.png') }}" alt="PLN"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/sosro.png') }}" alt="Sosro"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/unipin.png') }}" alt="UniPin"></div>
            </div>
        </div>

        <!-- BARIS 2: JALAN KE KANAN -->
        <div class="marquee-container">
            <div class="marquee-track track-right">
                <!-- Group 1 -->
                <div class="marquee-item"><img src="{{ asset('images/partners/dua kelinci.png') }}" alt="Dua Kelinci"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/gudang garam.png') }}" alt="Gudang Garam"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/indofood.png') }}" alt="Indofood"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/indomie.png') }}" alt="Indomie"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/j&t.png') }}" alt="J&T Express"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/pertamina.png') }}" alt="Pertamina"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/pln.png') }}" alt="PLN"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/sosro.png') }}" alt="Sosro"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/unipin.png') }}" alt="UniPin"></div>
                <!-- Group 2 -->
             <div class="marquee-item"><img src="{{ asset('images/partners/dua kelinci.png') }}" alt="Dua Kelinci"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/gudang garam.png') }}" alt="Gudang Garam"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/indofood.png') }}" alt="Indofood"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/indomie.png') }}" alt="Indomie"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/j&t.png') }}" alt="J&T Express"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/pertamina.png') }}" alt="Pertamina"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/pln.png') }}" alt="PLN"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/sosro.png') }}" alt="Sosro"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/unipin.png') }}" alt="UniPin"></div>
                <!-- Group 3 -->
               <div class="marquee-item"><img src="{{ asset('images/partners/dua kelinci.png') }}" alt="Dua Kelinci"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/gudang garam.png') }}" alt="Gudang Garam"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/indofood.png') }}" alt="Indofood"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/indomie.png') }}" alt="Indomie"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/j&t.png') }}" alt="J&T Express"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/pertamina.png') }}" alt="Pertamina"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/pln.png') }}" alt="PLN"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/sosro.png') }}" alt="Sosro"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/unipin.png') }}" alt="UniPin"></div>
                <!-- Group 4 -->
               <div class="marquee-item"><img src="{{ asset('images/partners/dua kelinci.png') }}" alt="Dua Kelinci"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/gudang garam.png') }}" alt="Gudang Garam"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/indofood.png') }}" alt="Indofood"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/indomie.png') }}" alt="Indomie"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/j&t.png') }}" alt="J&T Express"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/pertamina.png') }}" alt="Pertamina"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/pln.png') }}" alt="PLN"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/sosro.png') }}" alt="Sosro"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/unipin.png') }}" alt="UniPin"></div>
                <!-- Group 5 -->
               <div class="marquee-item"><img src="{{ asset('images/partners/dua kelinci.png') }}" alt="Dua Kelinci"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/gudang garam.png') }}" alt="Gudang Garam"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/indofood.png') }}" alt="Indofood"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/indomie.png') }}" alt="Indomie"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/j&t.png') }}" alt="J&T Express"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/pertamina.png') }}" alt="Pertamina"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/pln.png') }}" alt="PLN"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/sosro.png') }}" alt="Sosro"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/unipin.png') }}" alt="UniPin"></div>
                <!-- Group 6 -->
               <div class="marquee-item"><img src="{{ asset('images/partners/dua kelinci.png') }}" alt="Dua Kelinci"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/gudang garam.png') }}" alt="Gudang Garam"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/indofood.png') }}" alt="Indofood"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/indomie.png') }}" alt="Indomie"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/j&t.png') }}" alt="J&T Express"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/pertamina.png') }}" alt="Pertamina"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/pln.png') }}" alt="PLN"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/sosro.png') }}" alt="Sosro"></div>
    <div class="marquee-item"><img src="{{ asset('images/partners/unipin.png') }}" alt="UniPin"></div>
            </div>
        </div>
    </div>
</div>

<div class="section-gray">
    <div class="container">
        <h2 class="text-center mb-8 animate-on-scroll">Why Partner With Us?</h2>
        <div class="grid grid-cols-3 gap-4">
            <div class="card text-center animate-on-scroll hoverable" style="transition-delay: 0.1s;">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">🛡️</div>
                <h3 class="mb-4">Built-in Trust</h3>
                <p>Our transparent vetting process ensures all partners meet global standards, creating a secure marketplace.</p>
            </div>
            <div class="card text-center animate-on-scroll hoverable" style="transition-delay: 0.2s;">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">📈</div>
                <h3 class="mb-4">Scalable Growth</h3>
                <p>Gain access to massive procurement contracts and scale your operations through recurring business.</p>
            </div>
            <div class="card text-center animate-on-scroll hoverable" style="transition-delay: 0.3s;">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">⚡</div>
                <h3 class="mb-4">Digital Efficiency</h3>
                <p>Eliminate manual paperwork with our automated documentation system and real-time tracking.</p>
            </div>
        </div>
    </div>
</div>

{{-- MODAL HOW IT WORKS (POP-UP) --}}
<div id="howItWorksModal" class="hiw-modal">
    <div class="hiw-modal-content">
        <div class="hiw-modal-header">
            <h3>Cara Kerja Pendaftaran Vendor</h3>
            <span class="hiw-close-btn">&times;</span>
        </div>
        <div class="hiw-modal-body">
            <div class="hiw-step">
                <span class="hiw-number">1</span>
                <div>
                    <h4>Isi Form Data Perusahaan</h4>
                    <p>Klik tombol 'Register as Vendor' lalu lengkapi formulir dengan informasi dan dokumen legalitas perusahaan yang valid.</p>
                </div>
            </div>
            <div class="hiw-step">
                <span class="hiw-number">2</span>
                <div>
                    <h4>Verifikasi oleh Tim Admin</h4>
                    <p>Tim admin kami akan mereview dan memvalidasi data serta dokumen yang telah dikirimkan secara internal.</p>
                </div>
            </div>
            <div class="hiw-step">
                <span class="hiw-number">3</span>
                <div>
                    <h4>Akun Disetujui</h4>
                    <p>Setelah disetujui, akun perusahaan Anda akan didaftarkan ke sistem dan Anda dapat mulai berkolaborasi.</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- JAVASCRIPT MODAL --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('howItWorksBtn');
        const modal = document.getElementById('howItWorksModal');
        const closeBtn = document.querySelector('.hiw-close-btn');

        if (btn && modal) {
            // Buka modal saat diklik
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                modal.classList.add('active');
                document.body.style.overflow = 'hidden'; // Lock background scroll
            });

            // Tutup modal saat tombol silang (X) diklik
            closeBtn.addEventListener('click', function() {
                modal.classList.remove('active');
                document.body.style.overflow = ''; // Unlock background scroll
            });

            // Tutup modal saat area luar kotak diklik
            window.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.remove('active');
                    document.body.style.overflow = ''; // Unlock background scroll
                }
            });
        }
    });
</script>

@endsection