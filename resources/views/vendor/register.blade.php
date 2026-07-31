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

        <form action="{{ route('vendor.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation">
            @csrf
            
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

            <div class="mb-8 animate-on-scroll hoverable" style="background-color: var(--surface); padding: 2rem; border-radius: var(--radius-md); border: 1px solid var(--border); transition-delay: 0.4s;">
                <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
                    <input type="checkbox" id="agreement" name="agreement" value="1" required style="margin-top: 0.25rem;">
                    <label for="agreement" style="font-size: 0.875rem; color: var(--text-main);">
                        I hereby declare that all the information provided above is true and accurate. I understand that any false information may lead to the rejection or revocation of my vendor status.
                        <br><br>
                        I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a> regarding the processing of business data.
                    </label>
                </div>
                
                <div class="d-flex justify-between align-center mt-4 mobile-col">
                    <a href="/" class="btn btn-outline" style="order: 2; border-color: #f87171 !important; color: #f87171 !important; background-color: rgba(248, 113, 113, 0.05);">Discard Draft</a>
                    <button type="submit" class="btn btn-primary" style="padding: 1rem 3rem; font-size: 1rem; order: 1; background-color: #f59e0b; border-color: #f59e0b; color: #1e293b; font-weight: 600;">Submit Registration</button>
                </div>
            </div>
        </form>
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

document.getElementById("searchLocationBtn").addEventListener("click", async function () {

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

</script>
@endsection