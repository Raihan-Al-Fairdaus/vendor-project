@extends('layouts.public')

@section('title', 'FAQ - VendorConnect')

@section('content')


<style>

html,
body{
    margin:0;
    padding:0;
    overflow-x:hidden;
}

body{
    background:linear-gradient(
        180deg,
        #17375d 0%,
        #284d79 20%,
        #8ea3bf 50%,
        #284d79 80%,
        #17375d 100%
    );
    background-attachment:fixed;
    min-height:100vh;
}

/* Semua section transparan supaya mengikuti gradient body */

.hero,
.section-gray,
.section-white,
.faq-wrapper{
    background:transparent !important;
}

/* Hilangkan glow bawaan hero */

.hero::before,
.hero::after{
    display:none !important;
}

.faq-wrapper{
    padding:70px 0 90px;
}

/* ========================= */

.faq-search-card{

    max-width:850px;
    margin:0 auto 60px;
    padding:40px;

    background:#ffffff;

    border-radius:22px;

    box-shadow:
        0 18px 45px rgba(0,0,0,.18);

}

.faq-search-card input{

    width:100%;

    padding:16px 20px;

    border-radius:12px;

    border:1px solid #d6d6d6;

    font-size:16px;

    transition:.3s;

}

.faq-search-card input:focus{

    border-color:#2563eb;

    box-shadow:0 0 0 4px rgba(37,99,235,.15);

    outline:none;

}

/* ========================= */

.faq-list{

    max-width:900px;

    margin:auto;

}

/* ========================= */

.faq-item{

    background:#ffffff;

    border-radius:18px;

    overflow:hidden;

    margin-bottom:20px;

    box-shadow:0 10px 30px rgba(0,0,0,.15);

    transition:.3s;

}

.faq-item:hover{

    transform:translateY(-2px);

}

.faq-question{

    width:100%;

    border:none;

    background:#ffffff;

    cursor:pointer;

    padding:22px 28px;

    display:flex;

    justify-content:space-between;

    align-items:center;

    font-size:18px;

    font-weight:700;

    color:#17375d;

}

.faq-question span{

    font-size:30px;

    transition:.35s;

    color:#17375d;

}

.faq-answer{

    display:none;

    padding:0 28px 24px;

    line-height:1.8;

    color:#555;

}

.faq-item.active .faq-answer{

    display:block;

}

.faq-item.active .faq-question span{

    transform:rotate(45deg);

}

/* ===== FIX GAP SEBELUM FOOTER ===== */

main{
    display:flex;
    flex-direction:column;
    min-height:100vh;
}

.faq-wrapper{
    padding-bottom:0 !important;
    margin-bottom:0 !important;
}

.vc-footer,
.footer{
    margin-top:0 !important;
}
html,
body,
main{
    background:linear-gradient(
        180deg,
        #17375d 0%,
        #284d79 20%,
        #8ea3bf 50%,
        #284d79 80%,
        #17375d 100%
    ) !important;
}
</style>


{{-- HERO --}}
<div class="hero">

    <div class="container">

        <span style="color:#f59e0b;font-weight:700;text-transform:uppercase;">
            Help Center
        </span>

        <h1 class="hero-title">
            Frequently Asked Questions
        </h1>

        <p class="text-muted">
            Temukan jawaban dari pertanyaan yang paling sering diajukan
            mengenai proses pendaftaran Vendor DNA Advertising.
        </p>

    </div>

</div>


{{-- CONTENT --}}
<div class="faq-wrapper">

    <div class="container">

        <div class="faq-search-card">

            <h2 class="text-center">
                Cari Pertanyaan
            </h2>

            <p class="text-center text-muted mb-4">
                Ketik kata kunci untuk menemukan jawaban lebih cepat.
            </p>

            <input
                type="text"
                id="faqSearch"
                placeholder="Contoh: NPWP, Verifikasi, Buku Rekening..."
            >

        </div>

        <div class="faq-list">

            <div class="faq-item">
                <button class="faq-question">
                    Bagaimana cara mendaftar menjadi vendor?
                    <span>+</span>
                </button>
                <div class="faq-answer">
                    Klik tombol <b>Register Vendor</b>, lengkapi seluruh data perusahaan, unggah dokumen yang diminta, lalu kirim formulir pendaftaran.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    Apakah pendaftaran vendor dipungut biaya?
                    <span>+</span>
                </button>
                <div class="faq-answer">
                    Tidak. Seluruh proses pendaftaran vendor di DNA Advertising tidak dipungut biaya apapun.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    Siapa saja yang dapat mendaftar sebagai vendor?
                    <span>+</span>
                </button>
                <div class="faq-answer">
                    Perusahaan maupun badan usaha yang memiliki legalitas dan mampu menyediakan produk atau jasa sesuai kebutuhan perusahaan.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    Apakah usaha perseorangan dapat mendaftar?
                    <span>+</span>
                </button>
                <div class="faq-answer">
                    Ya. Usaha perseorangan tetap dapat mendaftar selama memenuhi seluruh persyaratan administrasi yang ditentukan.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    Dokumen apa saja yang diperlukan?
                    <span>+</span>
                </button>
                <div class="faq-answer">
                    Dokumen yang diperlukan meliputi KTP Penanggung Jawab, NPWP, Buku Rekening, Foto Kantor, Link Google Maps, dan data perusahaan.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    Berapa jumlah minimal foto kantor?
                    <span>+</span>
                </button>
                <div class="faq-answer">
                    Minimal dua foto kantor dengan kondisi yang jelas agar memudahkan proses verifikasi oleh tim admin.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    Berapa lama proses verifikasi?
                    <span>+</span>
                </button>
                <div class="faq-answer">
                    Proses verifikasi biasanya membutuhkan beberapa hari kerja tergantung kelengkapan dokumen yang dikirim.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    Bagaimana jika dokumen saya kurang lengkap?
                    <span>+</span>
                </button>
                <div class="faq-answer">
                    Tim admin akan melakukan evaluasi dan dapat meminta Anda melengkapi dokumen yang masih kurang.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    Apakah saya bisa mengubah data setelah dikirim?
                    <span>+</span>
                </button>
                <div class="faq-answer">
                    Setelah formulir dikirim, perubahan data hanya dapat dilakukan melalui bantuan admin apabila diperlukan.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    Bagaimana jika pendaftaran saya ditolak?
                    <span>+</span>
                </button>
                <div class="faq-answer">
                    Anda dapat melakukan perbaikan terhadap data atau dokumen yang diminta kemudian mengajukan kembali sesuai arahan admin.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    Apakah data perusahaan saya aman?
                    <span>+</span>
                </button>
                <div class="faq-answer">
                    Ya. Seluruh data vendor disimpan secara aman dan hanya digunakan untuk proses evaluasi dan administrasi internal perusahaan.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    Bagaimana saya mengetahui hasil verifikasi?
                    <span>+</span>
                </button>
                <div class="faq-answer">
                    Tim admin akan menghubungi perusahaan melalui email atau nomor telepon yang telah didaftarkan.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    Apakah menjadi vendor menjamin mendapatkan proyek?
                    <span>+</span>
                </button>
                <div class="faq-answer">
                    Tidak. Vendor yang lolos verifikasi akan memiliki kesempatan mengikuti proses pengadaan sesuai kebutuhan perusahaan.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    Apakah saya dapat mendaftar lebih dari satu perusahaan?
                    <span>+</span>
                </button>
                <div class="faq-answer">
                    Bisa, selama setiap perusahaan memiliki data dan dokumen legal yang berbeda dan valid.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    Siapa yang dapat saya hubungi jika mengalami kendala?
                    <span>+</span>
                </button>
                <div class="faq-answer">
                    Silakan menghubungi tim DNA Advertising melalui kontak yang tersedia pada website untuk mendapatkan bantuan lebih lanjut.
                </div>
            </div>

        </div>

    </div>

</div>

<script>

// ================= FAQ ACCORDION =================
document.querySelectorAll(".faq-question").forEach(button => {

    button.addEventListener("click", () => {

        button.parentElement.classList.toggle("active");

    });

});

// ================= FAQ SEARCH =================
document.getElementById("faqSearch").addEventListener("keyup", function () {

    let keyword = this.value.toLowerCase();

    document.querySelectorAll(".faq-item").forEach(item => {

        item.style.display = item.innerText.toLowerCase().includes(keyword)
            ? "block"
            : "none";

    });

});

</script>

@endsection