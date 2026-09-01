@extends('layouts.public')
@section('title', 'Become Our Vendor Partner - DNA Vendor Portal')
@section('content')
<style>
/* Reset and Hide Defaults */
nav.navbar { display: none !important; }
footer.vc-footer { display: none !important; }
html, body { background-color: #f8fafc !important; font-family: 'Inter', sans-serif; margin: 0; padding: 0; }

/* Variables */
:root {
    --navy: #1b3a60;
    --navy-light: #244b7a;
    --gold: #f59e0b;
    --gold-hover: #d97706;
    --text-gray: #64748b;
}

/* NAVBAR */
.custom-nav {
    background: #ffffff;
    padding: 1rem 5%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    z-index: 100;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.nav-logo { display: flex; align-items: center; gap: 0.5rem; text-decoration: none; }
.nav-logo img { height: 28px; }
.nav-logo span { font-weight: 700; color: var(--navy); font-size: 1.2rem; }
.nav-logo span .text-red { color: #e11d48; }
.nav-links { display: flex; gap: 2rem; align-items: center; }
.nav-links a { text-decoration: none; color: var(--text-gray); font-weight: 600; font-size: 0.95rem; transition: 0.2s; position: relative; }
.nav-links a:hover, .nav-links a.active { color: var(--navy); }
.nav-links a.active::after { content: ''; position: absolute; bottom: -5px; left: 0; width: 100%; height: 2px; background: var(--navy); border-radius: 2px; }
.btn-nav-contact { background: var(--navy); color: #fff; padding: 0.6rem 1.25rem; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; transition: 0.2s; }
.btn-nav-contact:hover { background: var(--navy-light); }
.mobile-menu-btn { display: none; font-size: 1.5rem; color: var(--navy); background: none; border: none; cursor: pointer; }

/* HERO SECTION */
.hero-wrapper {
    background: var(--navy);
    padding: 4rem 5% 0;
    position: relative;
    overflow: hidden;
}
.hero-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
}
.hero-content { color: #fff; }
.eyebrow-pill {
    display: inline-block;
    border: 1px solid var(--gold);
    color: var(--gold);
    padding: 0.4rem 1rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.05em;
    margin-bottom: 1.5rem;
}
.hero-title { font-size: 3.5rem; font-weight: 800; line-height: 1.15; margin-bottom: 1.5rem; color: #fff; }
.hero-title .text-gold { color: var(--gold); }
.hero-desc { font-size: 1.05rem; color: rgba(255,255,255,0.8); line-height: 1.6; margin-bottom: 2.5rem; max-width: 90%; }
.hero-buttons { display: flex; gap: 1rem; }
.btn-primary { background: var(--gold); color: var(--navy); padding: 0.8rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: 0.2s; }
.btn-primary:hover { background: var(--gold-hover); }
.btn-outline { background: transparent !important; color: #ffffff !important; padding: 0.8rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600; border: 1px solid rgba(255,255,255,0.4) !important; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: 0.2s; }
.btn-outline:hover { background: rgba(255,255,255,0.15) !important; border-color: #ffffff !important; }
.hero-image { border-radius: 20px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.3); }
.hero-image img { width: 100%; display: block; object-fit: cover; }

/* FEATURES BAR */
.features-bar {
    max-width: 1200px;
    margin: 4rem auto 0;
    background: var(--navy-light);
    border-radius: 16px 16px 0 0;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    padding: 2.5rem;
    gap: 2rem;
    border-bottom: 1px solid rgba(255,255,255,0.05);
}
.feature-item { display: flex; align-items: center; gap: 1rem; color: #fff; }
.feature-icon { width: 48px; height: 48px; background: rgba(255,255,255,0.05); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; }
.feature-text h4 { margin: 0 0 0.25rem; font-size: 1.05rem; font-weight: 600; }
.feature-text p { margin: 0; font-size: 0.85rem; color: rgba(255,255,255,0.7); line-height: 1.4; }

/* LOGOS SECTION */
.logos-section { background: #fff; padding: 4rem 5%; text-align: center; }
.section-eyebrow { color: #3b82f6; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 0.5rem; display: block; }
.section-title { color: var(--navy); font-size: 2rem; font-weight: 700; margin-bottom: 3rem; }
.logos-slider-wrap { display: flex; align-items: center; gap: 1rem; max-width: 1100px; margin: 0 auto; position: relative; }
.slider-btn { width: 44px; height: 44px; border-radius: 50%; background: #fff; border: 1px solid #e2e8f0; color: var(--navy); display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.08); transition: 0.2s; flex-shrink: 0; z-index: 10; }
.slider-btn:hover { background: var(--navy); color: #fff; }
.logos-grid { display: flex; gap: 2rem; overflow-x: auto; scroll-behavior: smooth; padding: 1rem 0; -ms-overflow-style: none; scrollbar-width: none; flex: 1; align-items: center; }
.logos-grid::-webkit-scrollbar { display: none; }
.logo-box { padding: 1rem 1.5rem; background: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: center; height: 80px; width: 140px; flex-shrink: 0; }
.logo-box img { max-width: 100%; max-height: 100%; object-fit: contain; }

/* WHY PARTNER SECTION */
.why-section { background: #f8fafc; padding: 4rem 5% 6rem; text-align: center; }
.cards-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; max-width: 1200px; margin: 3rem auto 0; text-align: left; }
.card-item { background: #fff; padding: 2.5rem 2rem; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
.card-icon { width: 56px; height: 56px; background: #eff6ff; color: #3b82f6; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.5rem; }
.card-item h4 { color: var(--navy); font-size: 1.25rem; font-weight: 700; margin: 0 0 1rem; }
.card-item p { color: var(--text-gray); font-size: 0.95rem; line-height: 1.6; margin: 0; }

/* CTA SECTION */
.cta-section { max-width: 1200px; margin: -4rem auto 4rem; padding: 0 5%; position: relative; z-index: 10; }
.cta-box { background: var(--navy); border-radius: 16px; padding: 3rem; display: flex; justify-content: space-between; align-items: center; color: #fff; box-shadow: 0 20px 40px rgba(10, 22, 40, 0.15); }
.cta-content { display: flex; gap: 1.5rem; align-items: center; max-width: 70%; }
.cta-icon { font-size: 2.5rem; color: #fff; background: rgba(255,255,255,0.1); width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; border-radius: 50%; flex-shrink: 0; }
.cta-text h3 { margin: 0 0 0.5rem; font-size: 1.75rem; font-weight: 700; color: #fff; }
.cta-text p { margin: 0; color: rgba(255,255,255,0.8); line-height: 1.5; font-size: 0.95rem; }
.btn-white { background: #fff; color: var(--navy); padding: 0.8rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: 0.2s; white-space: nowrap; }
.btn-white:hover { background: #f1f5f9; }

/* CUSTOM FOOTER */
.custom-footer { background: var(--navy); color: #fff; padding: 4rem 5% 2rem; }
.footer-grid { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 2fr 1fr 1.5fr 1.5fr; gap: 3rem; }
.footer-col h4 { font-size: 1.1rem; font-weight: 600; margin: 0 0 1.5rem; color: #fff; }
.footer-logo-wrap { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; }
.footer-logo-wrap img { height: 24px; }
.footer-logo-wrap span { font-weight: 700; font-size: 1.1rem; }
.footer-logo-wrap span .text-red { color: #e11d48; }
.footer-col p { color: rgba(255,255,255,0.7); font-size: 0.9rem; line-height: 1.6; margin-bottom: 1.5rem; }
.social-links { display: flex; gap: 1rem; }
.social-links a { width: 36px; height: 36px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; text-decoration: none; transition: 0.2s; }
.social-links a:hover { background: var(--gold); }
.footer-links { list-style: none; padding: 0; margin: 0; }
.footer-links li { margin-bottom: 0.75rem; }
.footer-links a { color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.9rem; transition: 0.2s; }
.footer-links a:hover { color: var(--gold); }
.contact-item { display: flex; gap: 1rem; margin-bottom: 1rem; color: rgba(255,255,255,0.7); font-size: 0.9rem; }
.contact-item i { margin-top: 0.2rem; }
.footer-bottom { max-width: 1200px; margin: 3rem auto 0; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.1); display: flex; justify-content: space-between; color: rgba(255,255,255,0.5); font-size: 0.85rem; }

/* RESPONSIVE MOBILE */
@media (max-width: 992px) {
    .nav-links, .btn-nav-contact { display: none; }
    .custom-nav { padding: 1rem 1.5rem; }
    .nav-logo { margin: 0 auto; position: absolute; left: 50%; transform: translateX(-50%); }
    .mobile-menu-btn { display: block; z-index: 101; }
    
    .hero-wrapper { padding: 3rem 1.5rem 0; }
    .hero-grid { grid-template-columns: 1fr; gap: 2rem; }
    .hero-title { font-size: 2.25rem; }
    .hero-desc { max-width: 100%; }
    .hero-buttons { flex-direction: column; }
    .hero-image { height: 240px; }
    
    .features-bar { grid-template-columns: 1fr; border-radius: 12px; margin-top: 2rem; padding: 1.5rem; gap: 1.5rem; }
    
    .logos-section { padding: 3rem 1.5rem; }
    .section-title { font-size: 1.5rem; margin-bottom: 2rem; }
    .logo-box { width: 100px; height: 60px; padding: 0.5rem; }
    
    .why-section { padding: 3rem 1.5rem 5rem; }
    .cards-grid { grid-template-columns: 1fr; margin-top: 2rem; }
    
    .cta-section { margin-top: -3rem; padding: 0 1.5rem; }
    .cta-box { flex-direction: column; text-align: center; padding: 2rem 1.5rem; gap: 1.5rem; }
    .cta-content { flex-direction: column; align-items: center; max-width: 100%; gap: 1rem; }
    .btn-white { width: 100%; }
    
    .custom-footer { padding: 3rem 1.5rem 1.5rem; }
    .footer-grid { grid-template-columns: 1fr; gap: 2.5rem; }
    .footer-bottom { flex-direction: column; text-align: center; gap: 1rem; }
}

/* Modals */
.vc-modal{ display:none; position:fixed; inset:0; background:rgba(0,0,0,.65); backdrop-filter:blur(5px); justify-content:center; align-items:center; z-index:99999; padding:20px; }
.vc-modal.show{ display:flex; }
.vc-modal-box{ width:100%; max-width:600px; background:#fff; border-radius:18px; overflow:hidden; box-shadow:0 25px 60px rgba(0,0,0,.3); display:flex; flex-direction:column; }
.vc-modal-header{ background:#0a1628; color:#fff; padding:20px 24px; display:flex; justify-content:space-between; align-items:center; }
.vc-modal-header h3{ margin:0; color:#fff; font-size:1.1rem; }
.vc-modal-close{ font-size:24px; cursor:pointer; }
.vc-modal-body{ padding:24px; overflow-y:auto; }
.vc-benefit{ display:flex; gap:16px; margin-bottom:20px; }
.vc-icon{ width:40px; height:40px; border-radius:50%; background:#f59e0b; color:#fff; display:flex; justify-content:center; align-items:center; font-weight:bold; flex-shrink:0; }
.vc-benefit h4{ margin:0 0 4px; color:#0a1628; font-size:1rem; font-weight: 600; }
.vc-benefit p{ margin:0; color:#666; font-size:0.9rem; line-height:1.5; }
</style>

<!-- NAVBAR -->
<nav class="custom-nav">
    <button class="mobile-menu-btn"><i class="fa-solid fa-bars"></i></button>
    <a href="#" class="nav-logo">
        <img src="{{ asset('images/logo.png') }}" alt="Logo">
        <span>DNA <span class="text-red">Vendor</span> Portal</span>
    </a>
    <div class="nav-links">
        <a href="#" class="active">Home</a>
        <a href="{{ route('vendor.register') }}">Register Vendor</a>
        <a href="#why-partner">Why Partner With Us</a>
        <a href="{{ route('faq') }}">FAQ</a>
    </div>
    <a href="https://wa.me/6281228358630?text=Halo%20Admin,%20saya%20butuh%20bantuan%20terkait%20registrasi%20vendor." target="_blank" class="btn-nav-contact"><i class="fa-solid fa-headset"></i> Contact Team</a>
</nav>

<!-- HERO SECTION -->
<section class="hero-wrapper">
    <div class="hero-grid">
        <div class="hero-content">
            <div class="eyebrow-pill">EMPOWERING GLOBAL SUPPLY CHAINS</div>
            <h1 class="hero-title">Become Our<br><span class="text-gold">Vendor Partner</span></h1>
            <p class="hero-desc">Join our systematic procurement network. We provide a transparent, efficient platform for businesses to grow alongside our global enterprise requirements.</p>
            <div class="hero-buttons">
                <a href="{{ route('vendor.register') }}" class="btn-primary">Register as Vendor <i class="fa-solid fa-arrow-right"></i></a>
                <a href="#" id="howItWorksBtn" class="btn-outline"><i class="fa-regular fa-circle-play"></i> How it works</a>
            </div>
        </div>
        <div class="hero-image">
            <img src="{{ asset('images/kantor-project.jpg') }}" alt="Meeting">
        </div>
    </div>

    <!-- FEATURES BAR -->
    <div class="features-bar">
        <div class="feature-item">
            <div class="feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
            <div class="feature-text">
                <h4>Trusted Network</h4>
                <p>Verified vendors.<br>Secure collaboration.</p>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon"><i class="fa-solid fa-chart-column"></i></div>
            <div class="feature-text">
                <h4>Scalable Growth</h4>
                <p>Access to more opportunities<br>and long-term partnerships.</p>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon"><i class="fa-solid fa-bolt"></i></div>
            <div class="feature-text">
                <h4>Digital Efficiency</h4>
                <p>Smart procurement with<br>real-time transparency.</p>
            </div>
        </div>
    </div>
</section>

<!-- LOGOS SECTION -->
<section class="logos-section">
    <span class="section-eyebrow">TECHNOLOGY & INFRASTRUCTURE</span>
    <h2 class="section-title">Telah Dipercaya oleh Berbagai Mitra Bisnis</h2>
    <div class="logos-slider-wrap">
        <button class="slider-btn" id="logosPrev"><i class="fa-solid fa-chevron-left"></i></button>
        <div class="logos-grid" id="logosSlider">
            <div class="logo-box"><img src="{{ asset('images/partners/pertamina.png') }}" alt="Pertamina"></div>
            <div class="logo-box"><img src="{{ asset('images/partners/j&t.png') }}" alt="J&T Express"></div>
            <div class="logo-box"><img src="{{ asset('images/partners/indomie.png') }}" alt="Indomie"></div>
            <div class="logo-box"><img src="{{ asset('images/partners/gudang garam.png') }}" alt="Gudang Garam"></div>
            <div class="logo-box"><img src="{{ asset('images/partners/pln.png') }}" alt="PLN"></div>
            <div class="logo-box"><img src="{{ asset('images/partners/sosro.png') }}" alt="Sosro"></div>
            <div class="logo-box"><img src="{{ asset('images/partners/dua kelinci.png') }}" alt="Dua Kelinci"></div>
            <div class="logo-box"><img src="{{ asset('images/partners/unipin.png') }}" alt="UniPin"></div>
        </div>
        <button class="slider-btn" id="logosNext"><i class="fa-solid fa-chevron-right"></i></button>
    </div>
</section>

<!-- WHY PARTNER SECTION -->
<section class="why-section" id="why-partner">
    <span class="section-eyebrow">WHY PARTNER WITH US</span>
    <h2 class="section-title">Why Partner With Us?</h2>
    <div class="cards-grid">
        <div class="card-item">
            <div class="card-icon"><i class="fa-solid fa-shield-halved"></i></div>
            <h4>Built-in Trust</h4>
            <p>Our transparent vetting process ensures all partners meet global standards, creating a secure marketplace.</p>
        </div>
        <div class="card-item">
            <div class="card-icon"><i class="fa-solid fa-chart-column"></i></div>
            <h4>Scalable Growth</h4>
            <p>Gain access to massive procurement contracts and scale your operations through recurring business.</p>
        </div>
        <div class="card-item">
            <div class="card-icon"><i class="fa-solid fa-bolt"></i></div>
            <h4>Digital Efficiency</h4>
            <p>Eliminate manual paperwork with our automated documentation system and real-time tracking.</p>
        </div>
    </div>
</section>

<!-- CTA SECTION -->
<section class="cta-section">
    <div class="cta-box">
        <div class="cta-content">
            <div class="cta-icon"><i class="fa-solid fa-headset"></i></div>
            <div class="cta-text">
                <h3>Need Help?</h3>
                <p>Having trouble while registering your company? Feel free to contact our team during working hours. We are ready to assist your registration process.</p>
            </div>
        </div>
        <a href="https://wa.me/6281228358630?text=Halo%20Admin,%20saya%20butuh%20bantuan%20terkait%20registrasi%20vendor." target="_blank" class="btn-white"><i class="fa-solid fa-headset"></i> Contact Team</a>
    </div>
</section>

<!-- CUSTOM FOOTER -->
<footer class="custom-footer">
    <div class="footer-grid">
        <div class="footer-col">
            <div class="footer-logo-wrap">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
                <span>DNA <span class="text-red">Vendor</span> Portal</span>
            </div>
            <p>Secure vendor registration platform for trusted business partnerships with DNA Advertising.</p>
            <div class="social-links">
                <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                <a href="#"><i class="fa-brands fa-youtube"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>
        <div class="footer-col">
            <h4>Quick Links</h4>
            <ul class="footer-links">
                <li><a href="#">Home</a></li>
                <li><a href="{{ route('vendor.register') }}">Register Vendor</a></li>
                <li><a href="#why-partner">Why Partner With Us</a></li>
                <li><a href="{{ route('faq') }}">FAQ</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Contact Information</h4>
            <div class="contact-item">
                <i class="fa-solid fa-location-dot"></i>
                <div>Jl. Taman Dhika BL 6 No. 3A<br>Sono, Sidoarjo<br>Buduran, Sidoarjo</div>
            </div>
            <div class="contact-item">
                <i class="fa-regular fa-clock"></i>
                <div>Monday - Friday<br>08:00 - 17:00 WIB</div>
            </div>
            <div class="contact-item">
                <i class="fa-solid fa-envelope"></i>
                <div>Email Coming Soon</div>
            </div>
            <div class="contact-item">
                <i class="fa-solid fa-phone"></i>
                <div>Phone Coming Soon</div>
            </div>
        </div>
        <div class="footer-col">
            <h4>Ready to Get Started?</h4>
            <p>Join our network and grow your business with DNA Advertising.</p>
            <a href="{{ route('vendor.register') }}" class="btn-primary" style="display:inline-flex;">Register as Vendor <i class="fa-solid fa-arrow-right"></i></a>
        </div>
    </div>
    <div class="footer-bottom">
        <div>© {{ date('Y') }} DNA Advertising. All rights reserved.</div>
        <div>Vendor Registration Portal</div>
    </div>
</footer>

{{-- MODAL HOW IT WORKS --}}
<div id="howItWorksModal" class="vc-modal">
    <div class="vc-modal-box">
        <div class="vc-modal-header">
            <h3>Cara Kerja Pendaftaran Vendor</h3>
            <span class="vc-modal-close">&times;</span>
        </div>
        <div class="vc-modal-body">
            <div class="vc-benefit">
                <div class="vc-icon">1</div>
                <div>
                    <h4>Isi Form Data Perusahaan</h4>
                    <p>Klik tombol 'Register as Vendor' lalu lengkapi formulir dengan informasi dan dokumen legalitas perusahaan yang valid.</p>
                </div>
            </div>
            <div class="vc-benefit">
                <div class="vc-icon">2</div>
                <div>
                    <h4>Verifikasi oleh Tim Admin</h4>
                    <p>Tim admin kami akan mereview dan memvalidasi data serta dokumen yang telah dikirimkan secara internal.</p>
                </div>
            </div>
            <div class="vc-benefit">
                <div class="vc-icon">3</div>
                <div>
                    <h4>Akun Disetujui</h4>
                    <p>Setelah disetujui, akun perusahaan Anda akan didaftarkan ke sistem dan Anda dapat mulai berkolaborasi.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const btn = document.getElementById("howItWorksBtn");
    const modal = document.getElementById("howItWorksModal");
    const closeBtn = document.querySelector(".vc-modal-close");

    if (btn && modal) {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            modal.classList.add("show");
            document.body.style.overflow = "hidden";
        });
        if (closeBtn) {
            closeBtn.addEventListener("click", function() {
                modal.classList.remove("show");
                document.body.style.overflow = "";
            });
        }
        window.addEventListener("click", function (e) {
            if (e.target === modal) {
                modal.classList.remove("show");
                document.body.style.overflow = "";
            }
        });
    }

    // Slider Logic
    const logosSlider = document.getElementById("logosSlider");
    const btnPrev = document.getElementById("logosPrev");
    const btnNext = document.getElementById("logosNext");

    if (logosSlider && btnPrev && btnNext) {
        btnPrev.addEventListener("click", function() {
            logosSlider.scrollBy({ left: -200, behavior: 'smooth' });
        });
        btnNext.addEventListener("click", function() {
            logosSlider.scrollBy({ left: 200, behavior: 'smooth' });
        });
    }
});
</script>
@endsection
