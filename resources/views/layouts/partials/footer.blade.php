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
.custom-footer .contact-item { 
    display: flex; 
    align-items: flex-start;
    gap: 0.85rem; 
    margin-bottom: 0.85rem; 
    color: rgba(255,255,255,0.75); 
    font-size: 0.9rem; 
    line-height: 1.5;
    padding: 0.45rem 0.65rem;
    border-radius: 8px;
    border: 1px solid transparent;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}
.custom-footer .contact-item i { 
    margin-top: 0.2rem; 
    font-size: 0.95rem;
    color: rgba(255,255,255,0.6);
    transition: all 0.25s ease;
    flex-shrink: 0;
}
.custom-footer .contact-link { 
    text-decoration: none; 
    cursor: pointer; 
    background: rgba(255, 255, 255, 0.03);
}
.custom-footer .contact-link:hover { 
    color: #ffffff !important; 
    background: rgba(245, 158, 11, 0.12);
    border-color: rgba(245, 158, 11, 0.4);
    transform: translateX(6px); 
    box-shadow: 0 4px 15px rgba(245, 158, 11, 0.15);
}
.custom-footer .contact-link:hover i { 
    color: #f59e0b; 
    transform: scale(1.25); 
}
.custom-footer .contact-link:hover div { 
    color: #f59e0b; 
    font-weight: 600;
}
.custom-footer .contact-link:active {
    transform: translateX(3px) scale(0.98);
}
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
                <a href="https://wa.me/6288221002100" target="_blank" title="WhatsApp 0882-2100-2100"><i class="fa-brands fa-whatsapp"></i></a>
                <a href="https://www.instagram.com/dna.advofficial" target="_blank" title="Instagram @dna.advofficial"><i class="fa-brands fa-instagram"></i></a>
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
            <a href="https://www.google.com/maps/search/?api=1&query=Jl.+Taman+Dhika+BL+6+No.+3A+Sono+Buduran+Sidoarjo" target="_blank" class="contact-item contact-link" title="Buka Lokasi Kantor di Google Maps">
                <i class="fa-solid fa-location-dot"></i>
                <div>Jl. Taman Dhika BL 6 No. 3A<br>Sono, Sidoarjo<br>Buduran, Sidoarjo</div>
            </a>
            <div class="contact-item">
                <i class="fa-regular fa-clock"></i>
                <div>Senin - Jumat<br>08:00 - 17:00 WIB</div>
            </div>
            <a href="https://mail.google.com/mail/?view=cm&fs=1&to=workshop@dnajayagroup.co.id&su=Informasi%20Kemitraan%20Vendor%20DNA" target="_blank" rel="noopener noreferrer" onclick="event.preventDefault(); window.open('https://mail.google.com/mail/?view=cm&fs=1&to=workshop@dnajayagroup.co.id&su=Informasi%20Kemitraan%20Vendor%20DNA', '_blank');" class="contact-item contact-link" title="Buka Gmail untuk Tulis Pesan ke workshop@dnajayagroup.co.id">
                <i class="fa-solid fa-envelope"></i>
                <div>workshop@dnajayagroup.co.id</div>
            </a>
            <a href="https://wa.me/6288221002100?text=Halo%20Admin,%20saya%20butuh%20informasi%20terkait%20vendor%20portal." target="_blank" class="contact-item contact-link" title="Chat WhatsApp / Telepon 0882-2100-2100">
                <i class="fa-solid fa-phone"></i>
                <div>0882-2100-2100</div>
            </a>
        </div>
        <div class="footer-col">
            <h4>Siap untuk Memulai?</h4>
            <p>Bergabunglah dengan jaringan kami dan kembangkan bisnis Anda bersama DNA Advertising.</p>
            <a href="{{ route('vendor.register') }}" class="btn-primary" style="display:inline-flex;">Register Vendor <i class="fa-solid fa-arrow-right"></i></a>
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
