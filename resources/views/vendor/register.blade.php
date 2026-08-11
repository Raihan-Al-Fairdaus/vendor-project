@extends('layouts.public')

@section('title', 'Vendor Registration - VendorConnect')

@section('content')
<style>
    /* LATAR BELAKANG KHUSUS HALAMAN REGISTER (HAPUS SELEKTOR BODY AGAR TIDAK BOCOR KE DASHBOARD) */
    .register-page-wrapper {
        background: linear-gradient(135deg, #1b3a60 0%, #3a587d 50%, #899eb9 100%) !important;
        background-attachment: fixed !important;
        min-height: calc(100vh - 73px);
        padding: 4rem 1rem;
    }

    /* Subtitle Halaman Register */
    .text-register-subtitle {
        color: rgba(255, 255, 255, 0.85) !important;
        opacity: 1 !important;
    }

    /* PERBAIKAN WARNA TEKS KETIKAN DI DALAM INPUT */
    input.form-control, 
    select.form-control, 
    textarea.form-control {
        color: #1e293b !important;
    }

    /* ID CARD UPLOAD (SINGLE) */
    .single-drop-area {
        background-color: #faf8f5 !important;
        border: 2px dashed #c5a059 !important;
        border-radius: 14px !important;
        padding: 1.8rem 1.2rem !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        position: relative !important;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1) !important;
        min-height: 120px;
    }

    .single-drop-area:hover {
        background-color: #ffffff !important;
        border-color: #b38e46 !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 10px 20px -6px rgba(197, 160, 89, 0.35) !important;
    }

    .single-drop-area input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 5;
    }

    /* GRID FOTO OFFICE (MULTIPLE CARD SYSTEM) */
    .office-photo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        gap: 12px;
        width: 100%;
        margin-top: 8px;
    }

    /* KOTAK TOMBOL UPLOAD DINAMIS */
    .photo-upload-card {
        position: relative;
        aspect-ratio: 1 / 1;
        background-color: #faf8f5;
        border: 2px dashed #c5a059;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 10px;
        text-align: center;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .photo-upload-card:hover {
        background-color: #ffffff;
        border-color: #b38e46;
        transform: translateY(-2px);
    }

    .photo-upload-card input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 5;
    }

    /* KOTAK THUMBNAIL FOTO TERPOTONG RAPI */
    .photo-item-card {
        position: relative;
        aspect-ratio: 1 / 1;
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid #c5a059;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        background-color: #fff;
    }

    .photo-item-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }

    /* TOMBOL HAPUS (X) PER KOTAK FOTO */
    .btn-remove-photo {
        position: absolute;
        top: 6px;
        right: 6px;
        background: rgba(239, 68, 68, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        font-size: 14px;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        transition: background 0.2s;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .btn-remove-photo:hover {
        background: rgba(220, 38, 38, 1);
    }

    .photo-item-card .photo-tag {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.65);
        color: #fff;
        font-size: 11px;
        text-align: center;
        padding: 3px 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

/* ============================================================
   MOBILE-ONLY STYLES & ANIMATIONS (max-width: 640px)
   Desktop tidak tersentuh sama sekali
   ============================================================ */
@media (max-width: 640px) {

    /* --- Animasi Keyframes --- */
    @keyframes mob-slideUp {
        from { opacity: 0; transform: translateY(32px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes mob-fadeIn {
        from { opacity: 0; }
        to   { opacity: 1; }
    }
    @keyframes mob-shimmer {
        0%   { background-position: -400px 0; }
        100% { background-position: 400px 0; }
    }
    @keyframes mob-pulse-border {
        0%, 100% { border-color: #c5a059; box-shadow: 0 0 0 0 rgba(197,160,89,0); }
        50%       { border-color: #e6b93a; box-shadow: 0 0 0 6px rgba(197,160,89,0.18); }
    }
    @keyframes mob-float {
        0%, 100% { transform: translateY(0px); }
        50%       { transform: translateY(-5px); }
    }
    @keyframes mob-spin-badge {
        from { transform: rotate(0deg); }
        to   { transform: rotate(360deg); }
    }

    /* --- Hero Section --- */
    .register-page-wrapper {
        padding: 2rem 0.85rem 5rem !important;
    }
    .register-page-wrapper h1 {
        font-size: 1.75rem !important;
        line-height: 1.2 !important;
        animation: mob-slideUp 0.5s ease both;
    }
    .register-page-wrapper > .container > p {
        font-size: 0.92rem !important;
        animation: mob-slideUp 0.6s ease both;
        animation-delay: 0.1s;
    }

    /* --- Cards: Slide Up Staggered --- */
    .card.animate-on-scroll {
        border-radius: 20px !important;
        padding: 1.25rem !important;
        margin-bottom: 1.25rem !important;
        box-shadow: 0 8px 32px rgba(0,0,0,0.13) !important;
        animation: mob-slideUp 0.55s ease both;
        border: 1px solid rgba(255,255,255,0.08) !important;
    }
    .card.animate-on-scroll:nth-child(1) { animation-delay: 0.15s; }
    .card.animate-on-scroll:nth-child(2) { animation-delay: 0.25s; }
    .card.animate-on-scroll:nth-child(3) { animation-delay: 0.35s; }

    /* --- Card Header --- */
    .card h3.mb-4 {
        font-size: 1.05rem !important;
        margin-bottom: 1.1rem !important;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid rgba(0,0,0,0.07);
    }
    .card h3 span[style*="font-size: 1.5rem"] {
        font-size: 1.6rem !important;
        animation: mob-float 3s ease-in-out infinite;
        display: inline-block;
    }

    /* --- Grid 2-kolom → 1-kolom --- */
    .grid.grid-cols-2 {
        grid-template-columns: 1fr !important;
        gap: 0 !important;
    }

    /* --- Form Inputs: Lebih besar & mudah diketuk --- */
    .form-label {
        font-size: 0.78rem !important;
        font-weight: 600 !important;
        letter-spacing: 0.04em !important;
        text-transform: uppercase;
        color: #475569 !important;
        margin-bottom: 6px !important;
    }
    .form-control {
        min-height: 52px !important;
        font-size: 16px !important;   /* mencegah auto-zoom iOS */
        border-radius: 14px !important;
        padding: 0.85rem 1rem !important;
        border: 1.5px solid #d1d5db !important;
        transition: border-color 0.25s, box-shadow 0.25s !important;
        background: #f8fafc !important;
    }
    .form-control:focus {
        border-color: #1b3a60 !important;
        box-shadow: 0 0 0 3px rgba(27,58,96,0.12) !important;
        background: #fff !important;
    }
    select.form-control {
        appearance: auto !important;
    }
    textarea.form-control {
        min-height: 100px !important;
    }

    /* --- Upload Area: Pulse animasi & lebih compact --- */
    .single-drop-area {
        border-radius: 16px !important;
        padding: 1.4rem 1rem !important;
        min-height: 100px !important;
        animation: mob-pulse-border 2.5s ease-in-out infinite !important;
    }
    .single-drop-area .drop-icon {
        font-size: 2rem !important;
        margin-bottom: 6px !important;
        animation: mob-float 2.5s ease-in-out infinite;
        display: block;
    }
    .single-drop-area p {
        font-size: 0.9rem !important;
        margin-bottom: 2px !important;
    }
    .single-drop-area span {
        font-size: 0.78rem !important;
    }

    /* --- Office photo grid lebih kecil di mobile --- */
    .office-photo-grid {
        grid-template-columns: repeat(auto-fill, minmax(90px, 1fr)) !important;
        gap: 8px !important;
    }

    /* --- Agreement card --- */
    .agreement-card {
        border-radius: 18px !important;
        padding: 1.1rem !important;
        animation: mob-slideUp 0.55s ease 0.4s both;
    }
    .agreement-btn {
        border-radius: 14px !important;
        font-size: 0.9rem !important;
    }

    /* --- Checkbox area --- */
    .agreement-checkbox-wrapper {
        font-size: 0.88rem !important;
        line-height: 1.6 !important;
    }

    /* --- Action Buttons: Submit besar & menonjol --- */
    .mobile-col {
        flex-direction: column !important;
        gap: 0.75rem !important;
    }
    #submitBtn {
        width: 100% !important;
        order: 1 !important;
        padding: 1.1rem !important;
        font-size: 1.05rem !important;
        border-radius: 16px !important;
        font-weight: 700 !important;
        letter-spacing: 0.03em !important;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
        box-shadow: 0 6px 20px rgba(245,158,11,0.4) !important;
        transition: transform 0.2s, box-shadow 0.2s !important;
        position: relative;
        overflow: hidden;
    }
    #submitBtn:not(:disabled):active {
        transform: scale(0.97) !important;
    }
    /* Shimmer effect on submit button */
    #submitBtn:not(:disabled)::after {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: linear-gradient(90deg,
            rgba(255,255,255,0) 0%,
            rgba(255,255,255,0.3) 50%,
            rgba(255,255,255,0) 100%);
        background-size: 400px 100%;
        animation: mob-shimmer 2s infinite;
    }
    .btn.btn-outline[href="/"] {
        width: 100% !important;
        order: 2 !important;
        text-align: center !important;
        border-radius: 14px !important;
        padding: 0.9rem !important;
        font-size: 0.92rem !important;
    }

    /* --- Error messages --- */
    .card[style*="error-bg"] {
        border-radius: 14px !important;
        font-size: 0.88rem !important;
    }

    /* --- Smooth scroll untuk mobile --- */
    html { scroll-behavior: smooth; }
}
</style>

<div class="register-page-wrapper">
    <div class="container animate-on-scroll" style="max-width: 800px;">
        <h1 class="mb-4" style="color: #f59e0b !important; text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);">Become a Verified Partner</h1>
        <p class="text-muted mb-8 text-register-subtitle" style="font-size: 1.05rem;">
            Complete the form below to register your business in our procurement network. Professional verification takes approximately 2-3 business days.
        </p>

        @if ($errors->any())
            <div class="card mb-8" style="background-color: var(--error-bg); border-color: var(--error); padding: 1rem;">
                <ul style="color: var(--error); margin-left: 1rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('vendor.store') }}" method="POST" id="vendorForm" class="needs-validation">
            @csrf

            {{-- Hidden inputs untuk menyimpan URL file setelah upload ke Supabase --}}
            <input type="hidden" name="id_card_url" id="id_card_url">
            <input type="hidden" name="bank_book_url" id="bank_book_url">
            <input type="hidden" name="npwp_file_url" id="npwp_file_url">
            <input type="hidden" name="office_photos_urls" id="office_photos_urls">
            
            {{-- Input File Tersembunyi Khusus Mengirimkan Seluruh File Foto Kantor ke Laravel --}}
            <input type="file" name="office_photos[]" id="officePhotosInput" accept=".jpg,.jpeg,.png" multiple style="display: none;">

            <div class="card mb-8 animate-on-scroll hoverable" style="transition-delay: 0.1s;">
                <h3 class="mb-4 d-flex align-center gap-4"><span style="color: var(--primary); font-size: 1.5rem;">🏢</span> Business Identity</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="company_name" class="form-control" value="{{ old('company_name') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Business Category</label>
                        <select name="business_category" class="form-control" required>

    <option value="">Select Vendor Type</option>

    <option value="Badan"
        {{ old('business_category') == 'Badan' ? 'selected' : '' }}>
        Badan
    </option>

    <option value="Perorangan"
        {{ old('business_category') == 'Perorangan' ? 'selected' : '' }}>
        Perorangan
    </option>

</select>
                       
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Company Address</label>
                    <textarea name="company_address" class="form-control" rows="3" required>{{ old('company_address') }}</textarea>
                </div>

<div class="form-group mt-3">
    <label class="form-label">
        Share Location Google Maps
        <span class="required">*</span>
    </label>

    <input
        type="url"
        name="google_maps_link"
        class="form-control"
        placeholder="https://maps.app.goo.gl/xxxxxxxx"
        value="{{ old('google_maps_link') }}"
        required>

    <small style="
        display:block;
        margin-top:8px;
        color:#b8c7dc;
        font-size:13px;">
        📍 Open Google Maps → pilih lokasi perusahaan → Share → Copy Link → Paste di sini.
    </small>
</div>
         

                <div class="form-group">
    <label class="form-label">NPWP Number</label>
    <input
        type="text"
        name="npwp"
        class="form-control"
        placeholder="00.000.000.0-000.000"
        value="{{ old('npwp') }}"
        required
    >
</div>
            </div>

            <div class="card mb-8 animate-on-scroll hoverable" style="transition-delay: 0.2s;">
                <h3 class="mb-4 d-flex align-center gap-4"><span style="color: var(--primary); font-size: 1.5rem;">📞</span> Contact Information</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label"> Email</label>
                        <input type="email" name="company_email" class="form-control" value="{{ old('company_email') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="company_phone" class="form-control" value="{{ old('company_phone') }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Penanggung jawab</label>
                    <input type="text" name="pic_name" class="form-control" value="{{ old('pic_name') }}" required>
                </div>
            </div>

            <div class="card mb-8 animate-on-scroll hoverable" style="transition-delay: 0.3s;">
                <h3 class="mb-4 d-flex align-center gap-4"><span style="color: var(--primary); font-size: 1.5rem;">📄</span> Verification Documents</h3>
                
                <!-- ID Card Upload (Single) -->
                <div class="form-group mb-4">
                    <label class="form-label">Identity Card (KTP)</label>
                    <div class="single-drop-area" id="idCardDropArea">
                        <p id="idCardText" style="margin-bottom: 4px; font-weight: 600; color: #1e293b;">Drag and drop or click to upload ID card</p>
                        <span id="idCardSubText" style="color: #64748b; font-size: 0.85rem;">PNG, JPG, PDF up to 10MB</span>
                        <input type="file" name="id_card" id="idCardInput" accept=".jpg,.jpeg,.png,.pdf" required>
                    </div>
                </div>
                <div class="form-group mt-4">

   

    @error('bank_book')
        <small class="text-danger">{{ $message }}</small>
    @enderror

</div>

                <!-- Office Photos Upload (Multiple Cards Grid) -->
                <div class="form-group">
                    <label class="form-label">Office Photos (Min. 2 Photos)</label>
                    
                    <div class="office-photo-grid" id="officePhotosGrid">
                        <!-- Kotak Upload Utama Bawaan -->
                        <div class="photo-upload-card" id="mainUploadCard" style="grid-column: 1 / -1; aspect-ratio: auto; min-height: 120px;">
                            <p style="margin: 0; color: #1e293b; font-weight: 600;">Drag and drop or click to upload office photos</p>
                            <span style="color: #64748b; font-size: 0.8rem;">PNG, JPG up to 10MB each (Select 2 or more)</span>
                            <input type="file" accept=".jpg,.jpeg,.png" multiple id="initialOfficePhotosTrigger">
                        </div>
                    </div>
                </div>

                <!-- NPWP Document -->
<div class="form-group mt-4">

    <label class="form-label">
        Upload NPWP Document <span class="required"></span>
    </label>

    <div class="single-drop-area" id="npwpDropArea">

        <div class="drop-icon">📄</div>

        <p id="npwpText" style="color:#000;font-weight:600;">
            Drag & drop your NPWP document here
        </p>

        <span id="npwpSubText">
            JPG, PNG, PDF (Max 10MB)
        </span>

        <input
            type="file"
            id="npwpInput"
            name="npwp_file"
            accept=".jpg,.jpeg,.png,.pdf"
            required
        >

    </div>

    @error('npwp_file')
        <small class="text-danger">{{ $message }}</small>
    @enderror

</div>

                <!-- Bank Book -->
<div class="form-group mt-4">
    <label class="form-label">
      <div class="form-group">

    <label class="form-label">
        Upload Bank Account Book <span class="required"></span>
    </label>

    <div class="single-drop-area" id="bankBookDropArea">

        <div class="drop-icon">🏦</div>

        <p id="bankBookText"  style="color:#000;font-weight:600;">
            Drag & drop your Bank Account Book here
</p>

        <span id="bankBookSubText" >
            JPG, PNG, PDF (Max 10MB)
        </span>

        <input
            type="file"
            id="bankBookInput"
            name="bank_book"
            accept=".jpg,.jpeg,.png,.pdf"
            required
        >

    </div>

    @error('bank_book')
        <small class="text-danger">{{ $message }}</small>
    @enderror

</div>

            </div>

          <!-- =======================================================
    AGREEMENT SECTION
======================================================= -->

<div class="agreement-card">

    <div class="agreement-header">

        <div class="agreement-icon">
            📄
        </div>

        <div class="agreement-info">
            <h3>Memorandum Of Understanding (MOU)</h3>

            <p>
                Harap baca dan pahami seluruh isi MOU ini sebelum melanjutkan registrasi.
            </p>
        </div>

    </div>

    <button
        type="button"
        id="openAgreement"
        class="agreement-btn">

        <span class="agreement-btn-icon">
            📄
        </span>

        <span class="agreement-btn-text">
            Baca Memorandum Of Understanding
            <small>Wajib dibaca sebelum registrasi</small>
        </span>

        <span class="agreement-btn-arrow">
            →
        </span>

    </button>

    <div
        id="agreementStatus"
        class="agreement-status">

        Anda harus membaca dan menyetujui MOU sebelum melanjutkan.

    </div>

</div>


<!-- =======================================================
    AGREEMENT CHECKBOX
======================================================= -->

<div class="agreement-checkbox-wrapper">

    <input
        type="checkbox"
        id="agreement"
        name="agreement"
        value="1"
        disabled
        required>

    <label for="agreement">
        Saya telah membaca, memahami, dan menyetujui seluruh isi
        <strong>MOU Perjanjian Kerjasama Penunjukan Vendor PT. DNA JAYA GROUP</strong>
        serta bersedia menjadi Vendor dan mematuhi seluruh ketentuan yang tercantum di dalamnya.
    </label>

</div>


<!-- =======================================================
    ACTION BUTTONS
======================================================= -->

<div class="d-flex justify-between align-center mt-4 mobile-col">

    <a
        href="/"
        class="btn btn-outline"
        style="
            order:2;
            border-color:#f87171!important;
            color:#f87171!important;
            background:rgba(248,113,113,.05);
        ">

        Discard Draft

    </a>

    <button
        type="submit"
        id="submitBtn"
        class="btn btn-primary"
        disabled
        style="
            order:1;
            padding:1rem 3rem;
            font-size:1rem;
        ">

        Submit Registration

    </button>

</div>

</form>

</div>
</div>
</div>



<!-- =======================================================
    AGREEMENT MODAL
======================================================= -->

<div
    id="agreementModal"
    class="agreement-modal">

    <div class="agreement-modal-content">

        <div class="agreement-modal-header">

            <h2 style="margin:0;font-size:1rem;font-weight:bold;">MEMORANDUM OF UNDERSTANDING</h2>

            <button
                type="button"
                id="closeAgreement">

                ✕

            </button>

        </div>


        <div
            id="agreementScroll"
            class="agreement-body">

            <div style="text-align:center;margin-bottom:1.5rem;">
                <p style="font-size:0.85rem;opacity:0.7;">Harap baca dan pahami seluruh isi MOU ini sebelum melakukan persetujuan.</p>
            </div>

            <div class="agreement-title">
                <span class="agreement-number">1</span>
                <h3>PASAL 1 — KETENTUAN UMUM</h3>
            </div>
            <p>Memorandum Of Understanding (MOU) ini merupakan dasar dan ketentuan umum kerja sama antara <strong>PT. DNA JAYA GROUP</strong>, selanjutnya disebut <strong>PIHAK PERTAMA</strong>, dengan pihak vendor yang mendaftarkan dan menyatakan kesediannya untuk menjadi rekanan/vendor, selanjutnya disebut <strong>PIHAK KEDUA</strong>.</p>
            <p>MOU ini dibuat sebagai bentuk kesepahaman mengenai hak, kewajiban, tanggung jawab, serta ketentuan yang harus dipatuhi oleh PIHAK KEDUA selama menjalankan kerja sama dengan PIHAK PERTAMA.</p>

            <div class="agreement-title" style="margin-top:1.25rem;">
                <span class="agreement-number">2</span>
                <h3>PASAL 2 — RUANG LINGKUP PEKERJAAN</h3>
            </div>
            <p>(1) PIHAK PERTAMA memberikan pekerjaan terkait Advertising dan periklanan sesuai dengan spesifikasi dan lokasi yang sesuai dengan Surat Perintah Kerja yang turun kepada PIHAK KEDUA dan PIHAK KEDUA menyatakan telah sepakat untuk menerima dan akan melaksanakan pekerjaan tersebut.</p>
            <p>(2) Apabila PIHAK KEDUA merasa keberatan atau tidak mampu untuk mengerjakan Surat Perintah Yang turun dari PIHAK PERTAMA, maka PIHAK KEDUA diberikan waktu <strong>1 x 24 Jam</strong> Masa Sanggah untuk membatalkan atau melakukan revisi atas Surat Perintah Kerja tersebut.</p>
            <p>(3) PIHAK KEDUA dengan ini menyatakan sanggup untuk menyediakan bahan baku dan jasa pemasangan sebagaimana dimaksud ketentuan Pasal 1 ayat (1) di perjanjian ini.</p>
            <p>(4) Pekerjaan-pekerjaan lainnya yang tidak dapat dirinci satu persatu namun menurut sifatnya menjadi tanggung jawab PIHAK KEDUA, sehingga hasil pekerjaan dapat diserahkan menurut kuantitas dan kualitas serta dalam jangka waktu yang ditetapkan dalam Perjanjian ini.</p>

            <div class="agreement-title" style="margin-top:1.25rem;">
                <span class="agreement-number">3</span>
                <h3>PASAL 3 — KESELAMATAN DAN KESEHATAN KERJA (K3)</h3>
            </div>
            <p>(1) PIHAK KEDUA berkewajiban untuk melaksanakan pekerjaan dengan mengutamakan aturan Keselamatan dan Kesehatan Kerja (K3).</p>
            <p>(2) PIHAK KEDUA wajib menugaskan tenaga kerja ahli yang sesuai dengan pekerjaan yang diberikan oleh PIHAK PERTAMA.</p>
            <p>(3) Jika PIHAK KEDUA mengalami Kecelakaan Kerja yang terjadi karena tidak dipatuhinya Aturan Kesehatan dan Keselamatan Kerja, maka PIHAK PERTAMA tidak bertanggung jawab atas Kerugian PIHAK KEDUA yang timbul akibat dari kecelakaan kerja tersebut.</p>
            <p>(4) Di lokasi pekerjaan harus ada wakil PIHAK KEDUA yang ditunjuk sebagai Pimpinan pelaksana yang mempunyai wewenang penuh untuk mewakili PIHAK KEDUA yang dapat menerima/memberikan/memutuskan segala urusan pekerjaan lapangan tidak terkecuali terhadap penanganan resiko kecelakaan kerja.</p>
            <p>(5) Semua yang berkaitan dengan persoalan dan tuntutan tenaga kerja yang menjadi beban dan tanggung jawab PIHAK KEDUA, baik di dalam maupun di luar pengadilan.</p>

            <div class="agreement-title" style="margin-top:1.25rem;">
                <span class="agreement-number">4</span>
                <h3>PASAL 4 — KEWAJIBAN &amp; TANGGUNG JAWAB</h3>
            </div>
            <p>(1) PIHAK KEDUA berkewajiban melaksanakan pekerjaan berdasarkan Perjanjian ini dengan penuh tanggung jawab tetapi tidak terbatas pada tanggung jawab atas teknis pekerjaan saja, namun juga memperhatikan ketentuan hukum yang berlaku dan kelestarian lingkungan hidup.</p>
            <p>(2) Setiap pekerja PIHAK KEDUA wajib didaftarkan menjadi peserta BPJS Ketenagakerjaan oleh PIHAK KEDUA.</p>
            <p>(3) PIHAK KEDUA wajib memenuhi standar keamanan dan keselamatan kerja untuk keperluan pekerjaan yang beresiko tinggi bagi pekerjanya, meliputi: Sepatu, Body Harness, Helm, Sarung Tangan. PIHAK KEDUA wajib memprioritaskan keamanan dan keselamatan pekerja serta tidak merusak konstruksi Reklame itu sendiri kecuali dalam keadaan Darurat.</p>
            <p>(4) Apabila PIHAK KEDUA tidak dapat menyediakan alat keamanan keselamatan kerja di atas, maka PIHAK KEDUA dapat meminjam kepada PIHAK PERTAMA dan wajib mengembalikan sesuai kondisi semula.</p>
            <p>(5) Setiap Pekerja PIHAK KEDUA berkewajiban menggunakan alat-alat keselamatan kerja serta melaksanakan syarat-syarat keselamatan dan perlindungan pekerja.</p>
            <p>(6) Dalam melakukan Pekerjaan dari Pihak Pertama, PIHAK KEDUA wajib mematuhi waktu kerja yaitu dimulai dari Jam <strong>07.00</strong> dan selambat-lambatnya harus selesai Jam <strong>17.00</strong> Waktu Setempat.</p>
            <p>(7) PIHAK KEDUA bertanggung jawab terhadap barang yang ada di Lokasi pekerjaan.</p>
            <p>(8) Dalam melakukan pekerjaan terjadi hujan, angin kencang, masalah kabel Listrik PLN atau hal-hal yang berpotensi menimbulkan resiko tinggi, maka PIHAK KEDUA wajib melaporkan kepada PIHAK PERTAMA terkait kendala yang terjadi.</p>
            <p>(9) Segala resiko yang timbul yang menimpa PIHAK KEDUA berupa kecelakaan kerja, pekerja PIHAK KEDUA maupun pihak ketiga sehubungan dengan pelaksanaan Pekerjaan berdasarkan Perjanjian ini menjadi beban dan tanggung jawab PIHAK KEDUA sepenuhnya, namun PIHAK PERTAMA akan tetap memberikan bantuan berupa santunan kepada PIHAK KEDUA sesuai dengan kebijakan Perusahaan.</p>
            <p>(10) PIHAK KEDUA akan memberikan garansi kepada PIHAK PERTAMA atas konstruksi pekerjaan yang telah selesai:</p>
            <ul style="margin-left:1.5rem;margin-bottom:0.75rem;line-height:1.8;">
                <li>Pekerjaan <strong>Permanen</strong>: masa garansi konstruksi <strong>1 Tahun</strong> sejak Pengiriman Foto kepada Pihak Pertama.</li>
                <li>Pekerjaan <strong>Insidentil</strong>: masa garansi konstruksi <strong>7 Hari</strong> sejak Pengiriman Foto kepada Pihak Pertama, kecuali diambil/disita oleh dinas terkait atau terjadi Force Majeur.</li>
            </ul>

            <div class="agreement-title" style="margin-top:1.25rem;">
                <span class="agreement-number">5</span>
                <h3>PASAL 5 — CARA PEMBAYARAN</h3>
            </div>
            <p>(1) Pembayaran harga pekerjaan dilaksanakan oleh PIHAK PERTAMA kepada PIHAK KEDUA dengan cara pembayaran sesuai dengan kesepakatan kedua belah Pihak sesuai dengan Surat Perintah Kerja.</p>
            <p>(2) PIHAK PERTAMA berhak untuk memotong pembayaran PPH (Pajak Penghasilan) sesuai dengan peraturan perundang-undangan yang berlaku.</p>
            <p>(3) Pembayaran harga pekerjaan dapat dilakukan secara transfer melalui Rekening PIHAK KEDUA yang telah dicantumkan.</p>

            <div class="agreement-title" style="margin-top:1.25rem;">
                <span class="agreement-number">6</span>
                <h3>PASAL 6 — JANGKA WAKTU PERJANJIAN</h3>
            </div>
            <p>(1) Jangka waktu perjanjian Kerjasama ini yakni sejak tanggal disetujui oleh PARA PIHAK dan tidak ditentukan jangka waktu berakhirnya.</p>
            <p>(2) Perjanjian ini akan tetap berlaku sepanjang PARA PIHAK masih melaksanakan kerja sama.</p>
            <p>(3) Dalam hal kerja sama tidak lagi digunakan, maka PIHAK PERTAMA dapat mengakhiri perjanjian ini dengan menyampaikan surat pemberitahuan penghentian kerja sama secara tertulis kepada PIHAK KEDUA.</p>
            <p>(4) Dalam hal PARA PIHAK sepakat untuk mengakhiri kerja sama yang akan dituangkan secara tertulis di dalam suatu dokumen yang disepakati oleh PARA PIHAK secara tersendiri.</p>

            <div class="agreement-title" style="margin-top:1.25rem;">
                <span class="agreement-number">7</span>
                <h3>PASAL 7 — FORCE MAJEURE</h3>
            </div>
            <p>(1) Dalam hal terjadi keadaan memaksa (Force Majeure) yang berada di luar kekuasaan PARA PIHAK, termasuk namun tidak terbatas kepada bencana alam, peperangan, revolusi, huru-hara, dan pemogokan massal, maka pihak yang terkena dampak harus memberitahukan kepada pihak lainnya secara tertulis selambat-lambatnya <strong>3 (tiga) hari kalender</strong> setelah keadaan memaksa itu terjadi.</p>
            <p>(2) Apabila pihak yang terkena dampak tidak atau lalai memberitahukan kejadian tersebut dalam jangka waktu 3 (tiga) hari kalender tersebut, maka keadaan memaksa dianggap tidak pernah terjadi dan pihak yang mengalami keadaan memaksa tersebut tetap harus melaksanakan kewajibannya sebagaimana termaktub dalam Perjanjian ini.</p>
            <p>(3) Apabila pemberitahuan diberikan dalam batas waktu yang ditentukan, maka PARA PIHAK akan merundingkan kembali pelaksanaan Perjanjian ini berdasarkan musyawarah untuk mufakat.</p>

            <div class="agreement-title" style="margin-top:1.25rem;">
                <span class="agreement-number">8</span>
                <h3>PASAL 8 — PENYELESAIAN PERSELISIHAN</h3>
            </div>
            <p>(1) Segala perselisihan atau perbedaan pendapat yang timbul atas pelaksanaan dari Perjanjian ini terlebih dahulu akan dibicarakan dan diselesaikan oleh kedua belah pihak secara musyawarah untuk mufakat.</p>
            <p>(2) Apabila penyelesaian secara musyawarah tidak tercapai antara kedua belah pihak, maka PIHAK PERTAMA dan PIHAK KEDUA sepakat untuk memilih domisili hukum di <strong>Pengadilan Negeri Sidoarjo</strong>.</p>

            <div class="agreement-title" style="margin-top:1.25rem;">
                <span class="agreement-number">9</span>
                <h3>PASAL 9 — KETENTUAN LAIN-LAIN</h3>
            </div>
            <p>(1) Apabila terjadi perbedaan materi dan penafsiran antara Perjanjian ini dengan dokumen lampirannya, maka yang diberlakukan adalah ketentuan dalam Perjanjian ini.</p>
            <p>(2) Segala sesuatu mengenai jenis pekerjaan dalam Perjanjian ini, oleh PARA PIHAK akan diatur dalam perjanjian tersendiri yang merupakan satu kesatuan yang mengikat dan bagian yang tidak terpisahkan dari Perjanjian ini.</p>
            <p>(3) Apabila terdapat perbedaan isi antara Perjanjian ini dengan lampirannya, maka yang diberlakukan adalah yang tertuang dalam Perjanjian ini.</p>

            <div class="agreement-title" style="margin-top:1.25rem;">
                <span class="agreement-number">10</span>
                <h3>PASAL 10 — PERSETUJUAN MELALUI WEBSITE</h3>
            </div>
            <p>(1) PIHAK KEDUA menyatakan telah membaca dan memahami seluruh isi MOU ini sebelum melakukan persetujuan.</p>
            <p>(2) Dengan mencetang kolom persetujuan dan menekan tombol Submit/Kirim pada website, PIHAK KEDUA menyatakan bersedia menjadi Vendor PT. DNA JAYA GROUP.</p>
            <p>(3) Persetujuan melalui sistem/website merupakan bentuk pernyataan kesediaan PIHAK KEDUA untuk mematuhi dan menjalankan ketentuan yang tercantum dalam MOU ini.</p>
            <p>(4) Data dan informasi yang diberikan oleh PIHAK KEDUA melalui formulir pendaftaran vendor merupakan bagian dari proses administrasi kerja sama Vendor.</p>
            <p>(5) PIHAK KEDUA bertanggung jawab atas kebenaran data dan informasi yang disampaikan melalui sistem.</p>

            <div class="agreement-title" style="margin-top:1.25rem;">
                <span class="agreement-number">11</span>
                <h3>PASAL 11 — PENUTUP</h3>
            </div>
            <p>MOU ini dibuat sebagai dasar dan pedoman dalam pelaksanaan kerja sama <strong>PT. DNA JAYA GROUP</strong> dengan vendor. Dengan melakukan persetujuan melalui website, PIHAK KEDUA menyatakan telah membaca, memahami, menyetujui dan bersedia mematuhi seluruh ketentuan dalam MOU ini.</p>
            <p>MOU ini berlaku sebagai ketentuan umum kerja sama vendor sejak persetujuan dilakukan melalui sistem/website PT. DNA JAYA GROUP.</p>

            <div style="margin-top:1.5rem;padding:1rem;background:rgba(212,175,55,0.1);border:1px solid rgba(212,175,55,0.3);border-radius:8px;">
                <label style="display:flex;align-items:flex-start;gap:10px;cursor:pointer;">
                    <input
                        type="checkbox"
                        id="mouConfirm"
                        style="margin-top:3px;width:16px;height:16px;flex-shrink:0;accent-color:#d4af37;cursor:pointer;"
                    >
                    <span style="font-weight:600;color:#d4af37;font-size:0.88rem;line-height:1.5;">Saya telah membaca, memahami, dan menyetujui seluruh isi MOU Kerja Sama Vendor serta bersedia menjadi Vendor PT. DNA JAYA GROUP</span>
                </label>
            </div>

        </div>


        <div class="agreement-footer">

            <button
                type="button"
                id="acceptAgreement"
                class="agree-button"
                disabled>

                🔒 I Agree & Continue

            </button>

        </div>

    </div>

</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Business Category Toggle
       

        // Single ID Card Upload
        const idCardInput = document.getElementById('idCardInput');
        const idCardText = document.getElementById('idCardText');
        const idCardSubText = document.getElementById('idCardSubText');
        const bankBookInput = document.getElementById('bankBookInput');
        const bankBookText = document.getElementById('bankBookText');
        const npwpInput = document.getElementById('npwpInput');
        const npwpText = document.getElementById('npwpText');
        const npwpSubText = document.getElementById('npwpSubText');
        const bankBookSubText = document.getElementById('bankBookSubText');


        if (idCardInput) {
            idCardInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    idCardText.style.color = '#0d9488';
                    idCardText.textContent = '✓ ' + this.files[0].name;
                    idCardSubText.textContent = (this.files[0].size / (1024 * 1024)).toFixed(2) + ' MB';
                }
            });
        }

        if (npwpInput) {
    npwpInput.addEventListener('change', function () {
        if (this.files && this.files.length > 0) {
            npwpText.style.color = '#0d9488';
            npwpText.textContent = '✓ ' + this.files[0].name;
            npwpSubText.textContent =
                (this.files[0].size / (1024 * 1024)).toFixed(2) + ' MB';
        }
    });
}

      if (bankBookInput) {
    bankBookInput.addEventListener('change', function () {
        if (this.files && this.files.length > 0) {
            bankBookText.style.color = '#0d9488';
            bankBookText.textContent = '✓ ' + this.files[0].name;
            bankBookSubText.textContent =
                (this.files[0].size / (1024 * 1024)).toFixed(2) + ' MB';
        }
    });
}

        // Multiple Office Photos - Dynamic Grid System dengan Hapus Per Kotak
        const officePhotosInput = document.getElementById('officePhotosInput');
        const officePhotosGrid = document.getElementById('officePhotosGrid');
        const initialTrigger = document.getElementById('initialOfficePhotosTrigger');
        
        let selectedFiles = new DataTransfer();

        function syncInputFiles() {
            if (officePhotosInput) {
                officePhotosInput.files = selectedFiles.files;
            }
        }

        function renderPhotosGrid() {
            officePhotosGrid.innerHTML = ''; 

            if (selectedFiles.files.length === 0) {
                const mainBox = document.createElement('div');
                mainBox.className = 'photo-upload-card';
                mainBox.style.gridColumn = '1 / -1';
                mainBox.style.aspectRatio = 'auto';
                mainBox.style.minHeight = '120px';
                mainBox.innerHTML = `
                    <p style="margin: 0; color: #1e293b; font-weight: 600;">Drag and drop or click to upload office photos</p>
                    <span style="color: #64748b; font-size: 0.8rem;">PNG, JPG up to 10MB each (Select 2 or more)</span>
                    <input type="file" accept=".jpg,.jpeg,.png" multiple>
                `;
                
                const input = mainBox.querySelector('input');
                input.addEventListener('change', handleFileSelect);
                officePhotosGrid.appendChild(mainBox);
                syncInputFiles();
                return;
            }

            Array.from(selectedFiles.files).forEach((file, index) => {
                const card = document.createElement('div');
                card.className = 'photo-item-card';

                const reader = new FileReader();
                reader.onload = function(e) {
                    card.innerHTML = `
                        <button type="button" class="btn-remove-photo" data-index="${index}" title="Remove photo">&times;</button>
                        <img src="${e.target.result}" alt="Office Photo ${index + 1}">
                        <div class="photo-tag">Photo ${index + 1}</div>
                    `;

                    card.querySelector('.btn-remove-photo').addEventListener('click', function(evt) {
                        evt.stopPropagation();
                        removeFile(index);
                    });
                };
                reader.readAsDataURL(file);

                officePhotosGrid.appendChild(card);
            });

            const addMoreCard = document.createElement('div');
            addMoreCard.className = 'photo-upload-card';
            addMoreCard.innerHTML = `
                <span style="font-size: 1.5rem; color: #c5a059; line-height: 1;">+</span>
                <span style="font-size: 0.75rem; color: #1e293b; font-weight: 600; margin-top: 4px;">+ Add More</span>
                <input type="file" accept=".jpg,.jpeg,.png" multiple>
            `;

            const addInput = addMoreCard.querySelector('input');
            addInput.addEventListener('change', handleFileSelect);
            officePhotosGrid.appendChild(addMoreCard);

            syncInputFiles();
        }

        function handleFileSelect(event) {
            if (event.target.files && event.target.files.length > 0) {
                const maxSizeBytes = 10 * 1024 * 1024; // 10MB
                
                Array.from(event.target.files).forEach(file => {
                    if (!file.type.startsWith('image/')) {
                        alert(`File "${file.name}" bukan format gambar yang valid!`);
                        return;
                    }
                    if (file.size > maxSizeBytes) {
                        alert(`Ukuran file "${file.name}" terlalu besar! Maksimal 10MB per foto.`);
                        return;
                    }
                    selectedFiles.items.add(file);
                });

                renderPhotosGrid();
            }
        }

        function removeFile(index) {
            const dt = new DataTransfer();
            const files = selectedFiles.files;
            
            for (let i = 0; i < files.length; i++) {
                if (i !== index) {
                    dt.items.add(files[i]);
                }
            }
            
            selectedFiles = dt;
            renderPhotosGrid();
        }

        if (initialTrigger) {
            initialTrigger.addEventListener('change', handleFileSelect);
        }
    });



/* ===========================================
   SEARCH COMPANY LOCATION
=========================================== */

const searchLocationBtn = document.getElementById("searchLocationBtn");

if (searchLocationBtn) {

    searchLocationBtn.addEventListener("click", async function () {

        const keyword = document.getElementById("locationSearch").value.trim();

        if (keyword === "") {
            alert("Please enter a company location.");
            return;
        }

        try {

            const response = await fetch(
                `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(keyword)}`
            );

            const data = await response.json();

            if (data.length === 0) {
                alert("Alamat tidak ditemukan.\nSilakan klik lokasi perusahaan langsung pada peta.");
                return;
            }

            const lat = parseFloat(data[0].lat);
            const lng = parseFloat(data[0].lon);

            companyMap.setView([lat, lng], 16);

            if (companyMarker) {
                companyMap.removeLayer(companyMarker);
            }

            companyMarker = L.marker([lat, lng]).addTo(companyMap);

            document.getElementById("latitude").value = lat;
            document.getElementById("longitude").value = lng;

        } catch (e) {

            alert("Failed to search location.");
            console.error(e);

        }

    });

}

document.addEventListener("DOMContentLoaded", function () {

    const modal = document.getElementById("agreementModal");
    const openBtn = document.getElementById("openAgreement");
    const closeBtn = document.getElementById("closeAgreement");

    const scrollArea = document.getElementById("agreementScroll");
    const acceptBtn = document.getElementById("acceptAgreement");

    const agreement = document.getElementById("agreement");
    const submitBtn = document.getElementById("submitBtn");
    const agreementStatus = document.getElementById("agreementStatus");

    if (
        !modal ||
        !openBtn ||
        !closeBtn ||
        !scrollArea ||
        !acceptBtn ||
        !agreement ||
        !submitBtn ||
        !agreementStatus
    ) {
        console.error("Agreement modal element not found.");
        return;
    }

    // Open Modal
    openBtn.addEventListener("click", function () {

        modal.classList.add("show");

        scrollArea.scrollTop = 0;

        acceptBtn.disabled = true;
        acceptBtn.classList.remove("enabled");

    });

    // Close Button
    closeBtn.addEventListener("click", function () {

        modal.classList.remove("show");

    });

    // Click Backdrop
    modal.addEventListener("click", function (e) {

        if (e.target === modal) {
            modal.classList.remove("show");
        }

    });

    // Enable Agree Button only when MOU confirm checkbox is checked
    const mouConfirm = document.getElementById("mouConfirm");

    if (mouConfirm) {
        mouConfirm.addEventListener("change", function () {
            if (this.checked) {
                acceptBtn.disabled = false;
                acceptBtn.classList.add("enabled");
            } else {
                acceptBtn.disabled = true;
                acceptBtn.classList.remove("enabled");
            }
        });
    }

    // Disable scroll-based auto-enable (now requires checkbox)
    scrollArea.addEventListener("scroll", function () {
        // scroll tracking removed - button enabled by checkbox only
    });

    // Accept Agreement
    acceptBtn.addEventListener("click", function () {

        agreement.disabled = false;
        agreement.checked = true;
        agreement.disabled = false;

        submitBtn.disabled = false;

        agreementStatus.innerHTML =
            "✅ MOU Perjanjian Kerjasama Vendor telah disetujui.";

        agreementStatus.style.color = "#16a34a";

        modal.classList.remove("show");

    });

});

// ============================================================
// PRESIGNED UPLOAD - Upload langsung ke Supabase dari browser
// ============================================================
document.getElementById('vendorForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = this;
    const submitBtn = form.querySelector('button[type="submit"]');

    // Cek semua file sudah dipilih
    const idCardFile = document.getElementById('idCardInput').files[0];
    const bankBookFile = document.getElementById('bankBookInput').files[0];
    const npwpFile = document.getElementById('npwpInput').files[0];
    const officePhotos = Array.from(document.getElementById('officePhotosInput').files);

    if (!idCardFile || !bankBookFile || !npwpFile) {
        alert('Mohon lengkapi semua file yang diperlukan.');
        return;
    }

    if (officePhotos.length < 2) {
        alert('Minimal 2 foto kantor diperlukan.');
        return;
    }

    // Tampilkan loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '⏳ Mengupload file... Mohon tunggu';

    try {
        const csrfToken = document.querySelector('input[name="_token"]').value;

        // Fungsi upload satu file
        async function uploadFile(file, folder) {
            // Minta presigned URL dari Laravel
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

            // Upload langsung ke Supabase (bypass Vercel!)
            const uploadRes = await fetch(upload_url, {
                method: 'PUT',
                headers: { 'Content-Type': file.type },
                body: file,
            });

            if (!uploadRes.ok) throw new Error('Gagal mengupload file: ' + file.name);
            return public_url;
        }

        // Upload semua file secara bersamaan
        submitBtn.innerHTML = '⏳ Mengupload KTP...';
        const idCardUrl = await uploadFile(idCardFile, 'id_cards');
        document.getElementById('id_card_url').value = idCardUrl;

        submitBtn.innerHTML = '⏳ Mengupload Buku Rekening...';
        const bankBookUrl = await uploadFile(bankBookFile, 'bank_books');
        document.getElementById('bank_book_url').value = bankBookUrl;

        submitBtn.innerHTML = '⏳ Mengupload NPWP...';
        const npwpUrl = await uploadFile(npwpFile, 'npwp');
        document.getElementById('npwp_file_url').value = npwpUrl;

        submitBtn.innerHTML = '⏳ Mengupload Foto Kantor...';
        const officePhotoUrls = [];
        for (const photo of officePhotos) {
            const url = await uploadFile(photo, 'office_photos');
            officePhotoUrls.push(url);
        }
        document.getElementById('office_photos_urls').value = JSON.stringify(officePhotoUrls);

        // Semua file berhasil diupload, bersihkan file inputs dulu baru submit
        submitBtn.innerHTML = '⏳ Menyimpan data...';

        // Hapus name dan disable semua file input supaya tidak ikut terkirim ke Vercel
        form.querySelectorAll('input[type="file"]').forEach(input => {
            input.removeAttribute('name');
            input.disabled = true;
        });

        form.submit();

    } catch (err) {
        console.error(err);
        alert('Terjadi kesalahan saat mengupload file: ' + err.message);
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Submit Registration';
    }
});
</script>
@endsection