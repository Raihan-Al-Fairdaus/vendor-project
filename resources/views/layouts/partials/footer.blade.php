<style>
/* CUSTOM FOOTER STYLES */
.custom-footer { background: #1b3a60; color: #fff; padding: 4rem 5% 2rem; font-family: 'Inter', sans-serif; position: relative; z-index: 10; }
.custom-footer .footer-grid { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 2fr 1fr 1.5fr 1.5fr; gap: 3rem; }
.custom-footer .footer-col h4 { font-size: 1.1rem; font-weight: 600; margin: 0 0 1.5rem; color: #fff; }
.custom-footer .footer-logo-wrap { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; }
.custom-footer .footer-logo-wrap img { height: 24px; }
.custom-footer .footer-logo-wrap span { font-weight: 700; font-size: 1.1rem; }
.custom-footer .footer-logo-wrap span .text-red { color: #e11d48; }
.custom-footer .footer-col p { color: rgba(255,255,255,0.7); font-size: 0.9rem; line-height: 1.6; margin-bottom: 1.5rem; }
.custom-footer .social-links { display: flex; gap: 1rem; }
.custom-footer .social-links a { width: 36px; height: 36px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; text-decoration: none; transition: 0.2s; }
.custom-footer .social-links a:hover { background: #f59e0b; }
.custom-footer .footer-links { list-style: none; padding: 0; margin: 0; }
.custom-footer .footer-links li { margin-bottom: 0.75rem; }
.custom-footer .footer-links a { color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.9rem; transition: 0.2s; }
.custom-footer .footer-links a:hover { color: #f59e0b; }
.custom-footer .contact-item { display: flex; gap: 1rem; margin-bottom: 1rem; color: rgba(255,255,255,0.7); font-size: 0.9rem; }
.custom-footer .contact-item i { margin-top: 0.2rem; }
.custom-footer .footer-bottom { max-width: 1200px; margin: 3rem auto 0; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.1); display: flex; justify-content: space-between; color: rgba(255,255,255,0.5); font-size: 0.85rem; }

.custom-footer .btn-primary { background: #f59e0b; color: #0a1628; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.2s; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; }
.custom-footer .btn-primary:hover { background: #d97706; }

/* RESPONSIVE MOBILE */
@media (max-width: 992px) {
    .custom-footer { padding: 3rem 1.5rem 1.5rem; }
    .custom-footer .footer-grid { grid-template-columns: 1fr; gap: 2.5rem; }
    .custom-footer .footer-bottom { flex-direction: column; text-align: center; gap: 1rem; }
}

/* MODAL STYLES */
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
.vc-modal-footer { padding: 15px 24px; text-align: right; border-top: 1px solid #eee; }
.vc-modal-close-btn { background: #f1f5f9; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; color: #475569; }
.vc-modal-close-btn:hover { background: #e2e8f0; }
</style>

<footer class="custom-footer">
    <div class="footer-grid">
        <div class="footer-col">
            <div class="footer-logo-wrap">
                <img src="{{ asset('images/logo.webp') }}" alt="Logo" loading="lazy">
                <span>DNA <span class="text-red">Vendor</span> Portal</span>
            </div>
            <p>Platform registrasi vendor yang aman untuk kemitraan bisnis terpercaya dengan DNA Advertising.</p>
            <div class="social-links">
                <a href="#"><svg width="1em" height="1em"  fill="currentColor" style="font-size:inherit;" class="" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"/></svg></a>
                <a href="https://wa.me/6281228358630" target="_blank"><svg width="1em" height="1em"  fill="currentColor" style="font-size:inherit;" class="" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg></a>
                <a href="https://www.instagram.com/dna.advofficial" target="_blank"><svg width="1em" height="1em"  fill="currentColor" style="font-size:inherit;" class="" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg></a>
            </div>
        </div>
        <div class="footer-col">
            <h4>Tautan Cepat</h4>
            <ul class="footer-links">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="{{ route('vendor.register') }}">Register Vendor</a></li>
                <li><a href="#" id="whyPartnerBtn">Why Partner With Us</a></li>
                <li><a href="{{ route('faq') }}">FAQ</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Informasi Kontak</h4>
            <div class="contact-item">
                <svg width="1em" height="1em"  fill="currentColor" style="font-size:inherit;margin-top:0.2rem;" class="" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"/></svg>
                <div>Jl. Taman Dhika BL 6 No. 3A<br>Sono, Sidoarjo<br>Buduran, Sidoarjo</div>
            </div>
            <div class="contact-item">
                <svg width="1em" height="1em"  fill="currentColor" style="font-size:inherit;margin-top:0.2rem;" class="" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M464 256A208 208 0 1 1 48 256a208 208 0 1 1 416 0zM0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM232 120l0 136c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2 280 120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"/></svg>
                <div>Senin - Jumat<br>08:00 - 17:00 WIB</div>
            </div>
            <div class="contact-item">
                <svg width="1em" height="1em"  fill="currentColor" style="font-size:inherit;margin-top:0.2rem;" class="" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48L48 64zM0 176L0 384c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-208L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z"/></svg>
                <div>Email Segera Hadir</div>
            </div>
            <div class="contact-item">
                <svg width="1em" height="1em"  fill="currentColor" style="font-size:inherit;margin-top:0.2rem;" class="" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M164.9 24.6c-7.7-18.6-28-28.5-47.4-23.2l-88 24C12.1 30.2 0 46 0 64C0 311.4 200.6 512 448 512c18 0 33.8-12.1 38.6-29.5l24-88c5.3-19.4-4.6-39.7-23.2-47.4l-96-40c-16.3-6.8-35.2-2.1-46.3 11.6L304.7 368C234.3 334.7 177.3 277.7 144 207.3L193.3 167c13.7-11.2 18.4-30 11.6-46.3l-40-96z"/></svg>
                <div>Telepon Segera Hadir</div>
            </div>
        </div>
        <div class="footer-col">
            <h4>Siap untuk Memulai?</h4>
            <p>Bergabunglah dengan jaringan kami dan kembangkan bisnis Anda bersama DNA Advertising.</p>
            <a href="{{ route('vendor.register') }}" class="btn-primary" style="display:inline-flex;">Register Vendor <svg width="1em" height="1em"  fill="currentColor" style="font-size:inherit;" class="" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/></svg></a>
        </div>
    </div>
    <div class="footer-bottom">
        <div>Ac {{ date('Y') }} DNA Advertising. Hak cipta dilindungi.</div>
        <div>Portal Registrasi Vendor</div>
    </div>
</footer>

{{-- WHY PARTNER MODAL --}}
<div id="whyPartnerModal" class="vc-modal">
    <div class="vc-modal-box">
        <div class="vc-modal-header">
            <h3>Mengapa Bermitra Dengan DNA Advertising?</h3>
            <span class="vc-modal-close">&times;</span>
        </div>
        <div class="vc-modal-body">
            <div class="vc-benefit">
                <div class="vc-icon">🤝</div>
                <div>
                    <h4>Kemitraan Profesional</h4>
                    <p>Kami membangun hubungan kerja sama yang transparan, saling percaya, dan profesional untuk jangka panjang.</p>
                </div>
            </div>
            <div class="vc-benefit">
                <div class="vc-icon">📈</div>
                <div>
                    <h4>Peluang Proyek Lebih Besar</h4>
                    <p>Vendor yang lolos verifikasi berkesempatan mengikuti berbagai kebutuhan pengadaan dari DNA Advertising.</p>
                </div>
            </div>
            <div class="vc-benefit">
                <div class="vc-icon">🔍</div>
                <div>
                    <h4>Proses Seleksi Transparan</h4>
                    <p>Seluruh proses evaluasi dilakukan secara objektif berdasarkan kelengkapan dokumen dan kualitas perusahaan.</p>
                </div>
            </div>
            <div class="vc-benefit">
                <div class="vc-icon">⚡</div>
                <div>
                    <h4>Pendaftaran Mudah</h4>
                    <p>Seluruh proses registrasi dilakukan secara online sehingga lebih cepat dan efisien.</p>
                </div>
            </div>
            <div class="vc-benefit">
                <div class="vc-icon">🚀</div>
                <div>
                    <h4>Kesempatan Berkembang</h4>
                    <p>Menjadi bagian dari jaringan vendor resmi membuka peluang kerja sama yang berkelanjutan.</p>
                </div>
            </div>
        </div>
        <div class="vc-modal-footer">
            <button class="vc-modal-close-btn">Tutup</button>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const btn = document.getElementById("whyPartnerBtn");
    const modal = document.getElementById("whyPartnerModal");
    const closeBtn = document.querySelector(".vc-modal-close");
    const closeBtnBottom = document.querySelector(".vc-modal-close-btn");

    function closeModal() {
        if(modal) modal.classList.remove("show");
        document.body.style.overflow = "";
    }

    if (btn && modal) {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            modal.classList.add("show");
            document.body.style.overflow = "hidden";
        });
        if (closeBtn) closeBtn.addEventListener("click", closeModal);
        if (closeBtnBottom) closeBtnBottom.addEventListener("click", closeModal);
        window.addEventListener("click", function (e) {
            if (e.target === modal) closeModal();
        });
        document.addEventListener("keydown", function (e) {
            if (e.key === "Escape") closeModal();
        });
    }
});
</script>
