<style>
.vc-modal{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.65);
    backdrop-filter:blur(5px);
    justify-content:center;
    align-items:center;
    z-index:99999;
    padding:20px;
}

.vc-modal.show{
    display:flex;
}

.vc-modal-box{
    width:100%;
    max-width:700px;
    background:#fff;
    border-radius:18px;
    overflow:hidden;
    animation:modalShow .3s ease;
    box-shadow:0 25px 60px rgba(0,0,0,.3);
    max-height:90vh;
    display:flex;
    flex-direction:column;
}

@keyframes modalShow{
    from{
        opacity:0;
        transform:translateY(20px) scale(.95);
    }
    to{
        opacity:1;
        transform:translateY(0) scale(1);
    }
}

.vc-modal-header{
    background:#1b3a60;
    color:#fff;
    padding:20px 24px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.vc-modal-header h3{
    margin:0;
    color:#fff;
}

.vc-modal-close{
    font-size:30px;
    cursor:pointer;
    font-weight:bold;
    line-height:1;
    min-width:36px;
    min-height:36px;
    display:flex;
    align-items:center;
    justify-content:center;
    flex-shrink:0;
    border-radius:50%;
    transition:background .2s;
    padding:4px;
}
.vc-modal-close:hover{
    background:rgba(255,255,255,0.2);
}

.vc-modal-body{
    padding:24px;
    overflow-y:auto;
    flex:1;
}

.vc-benefit{
    display:flex;
    gap:18px;
    margin-bottom:22px;
}

.vc-benefit:last-child{
    margin-bottom:0;
}

.vc-icon{
    width:55px;
    height:55px;
    border-radius:12px;
    background:#eef4fb;
    display:flex;
    justify-content:center;
    align-items:center;
    font-size:26px;
    flex-shrink:0;
}

.vc-benefit h4{
    margin:0 0 6px;
    color:#1b3a60;
}

.vc-benefit p{
    margin:0;
    color:#666;
    line-height:1.6;
}
.vc-modal-footer{
    padding:16px 24px;
    border-top:1px solid #eee;
    display:none;
    justify-content:center;
}
.vc-modal-close-btn{
    background:#1b3a60;
    color:#fff;
    border:none;
    padding:12px 40px;
    border-radius:50px;
    font-size:16px;
    font-weight:600;
    cursor:pointer;
    width:100%;
}
@media (max-width: 640px){
    .vc-modal{
        padding:12px;
        align-items:flex-end;
    }
    .vc-modal-box{
        border-bottom-left-radius:0;
        border-bottom-right-radius:0;
        max-height:85vh;
    }
    .vc-modal-footer{
        display:flex;
    }
    .vc-modal-header h3{
        font-size:1rem;
    }
}
</style>

<footer class="vc-footer">

    <div class="container">

        <div class="vc-footer-grid">

            <!-- Company -->
            <div class="vc-footer-company">

                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="DNA Vendor Portal"
                    class="footer-logo"
                >

                <h3>DNA Vendor Portal</h3>

                <p>
                    Secure Vendor Registration Platform for trusted business
                    partnerships with DNA Advertising. Register your company
                    quickly, securely, and transparently through our digital
                    vendor management system.
                </p>

            </div>

            <!-- Quick Links -->
           <div class="vc-footer-links">

    <h4>Quick Links</h4>

    <ul>

        <li>
            <a href="/">Home</a>
        </li>

        <li>
            <a href="{{ route('vendor.register') }}">
                Register Vendor
            </a>
        </li>

        <li>
            <a href="#" id="whyPartnerBtn">Why Partner With Us</a>
        </li>

        <li>
             <a href="{{ route('faq') }}">
                FAQ
            </a>
        </li>

    </ul>

</div>

            <!-- Contact -->
            <div class="vc-footer-contact">

                <h4>Contact Information</h4>

                <p>

                    <i class="fa-solid fa-location-dot"></i>

                    <span>
                        JL. Taman Dhika BL 6 No. 3A<br>
                        Sono, Sidokerto<br>
                        Buduran, Sidoarjo
                    </span>

                </p>

                <p>

                    <i class="fa-regular fa-clock"></i>

                    <span>
                        Monday – Friday<br>
                        08:00 – 17:00 WIB
                    </span>

                </p>

                <p>

                    <i class="fa-solid fa-envelope"></i>

                    <span>
                        Email Coming Soon
                    </span>

                </p>

                <p>

                    <i class="fa-solid fa-phone"></i>

                    <span>
                        Phone Coming Soon
                    </span>

                </p>

            </div>

            <!-- Help Card -->
            <div class="vc-footer-help">

                <div class="vc-help-card">

                    <div class="help-icon">
                        <i class="fa-solid fa-headset"></i>
                    </div>

                    <h4>Need Help?</h4>

                    <p>
                        Having trouble while registering your company?
                        Feel free to contact our team during working hours.
                        We are ready to assist your registration process.
                    </p>

                    <a
                        href="https://wa.me/6281228358630?text=Halo%20Admin,%20saya%20butuh%20bantuan%20terkait%20registrasi%20vendor."
                        target="_blank"
                        class="help-btn"
                    >
                        <i class="fa-solid fa-headset" style="margin-right: 6px;"></i> Contact Team
                    </a>

                </div>

            </div>

        </div>

        <hr class="vc-footer-divider">

        <div class="vc-footer-bottom">

            <span>

                © {{ date('Y') }}
                DNA Advertising.
                All rights reserved.

            </span>

            <span>

                Vendor Registration Portal

            </span>

        </div>

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

                    <p>
                        Kami membangun hubungan kerja sama yang
                        transparan, saling percaya, dan profesional
                        untuk jangka panjang.
                    </p>

                </div>

            </div>

            <div class="vc-benefit">

                <div class="vc-icon">🚀</div>

                <div>

                    <h4>Peluang Proyek Lebih Besar</h4>

                    <p>
                        Vendor yang lolos verifikasi berkesempatan
                        mengikuti berbagai kebutuhan pengadaan dari
                        DNA Advertising.
                    </p>

                </div>

            </div>

            <div class="vc-benefit">

                <div class="vc-icon">🛡️</div>

                <div>

                    <h4>Proses Seleksi Transparan</h4>

                    <p>
                        Seluruh proses evaluasi dilakukan secara
                        objektif berdasarkan kelengkapan dokumen
                        dan kualitas perusahaan.
                    </p>

                </div>

            </div>

            <div class="vc-benefit">

                <div class="vc-icon">⚡</div>

                <div>

                    <h4>Pendaftaran Mudah</h4>

                    <p>
                        Seluruh proses registrasi dilakukan secara
                        online sehingga lebih cepat dan efisien.
                    </p>

                </div>

            </div>

            <div class="vc-benefit">

                <div class="vc-icon">📈</div>

                <div>

                    <h4>Kesempatan Berkembang</h4>

                    <p>
                        Menjadi bagian dari jaringan vendor resmi
                        membuka peluang kerja sama yang
                        berkelanjutan.
                    </p>

                </div>

            </div>

        </div>

        {{-- Tombol tutup di bawah (untuk mobile) --}}
        <div class="vc-modal-footer">
            <button class="vc-modal-close-btn">✕ Tutup</button>
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
        modal.classList.remove("show");
        document.body.style.overflow = ""; // Unlock background scroll
    }

    if (btn && modal) {

        // Buka modal
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            modal.classList.add("show");
            document.body.style.overflow = "hidden"; // Lock background scroll
        });

        // Tutup modal saat klik X (header)
        if (closeBtn) {
            closeBtn.addEventListener("click", closeModal);
        }

        // Tutup modal saat klik tombol Tutup bawah (mobile)
        if (closeBtnBottom) {
            closeBtnBottom.addEventListener("click", closeModal);
        }

        // Tutup modal saat klik area luar
        window.addEventListener("click", function (e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Tutup modal dengan tombol ESC
        document.addEventListener("keydown", function (e) {
            if (e.key === "Escape") {
                closeModal();
            }
        });

    }

});
</script>