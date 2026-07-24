@extends('layouts.public')

@section('title', 'Registration Submitted - VendorConnect')

@section('content')
<style>
    /* Latar belakang gradient penuh konsisten */
    body {
        background: linear-gradient(135deg, #1b3a60 0%, #3a587d 50%, #899eb9 100%) !important;
        background-attachment: fixed !important;
    }

    /* Container area tengah */
    .success-container {
        min-height: calc(100vh - 200px); /* Menjaga jarak agar navbar & footer tetap pada tempatnya */
        padding: 4rem 1rem;
        display: block;
    }

    /* Card Box Utama */
    .success-card {
        background-color: #ffffff;
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
        padding: 3.5rem 2.5rem;
        max-width: 550px;
        margin: 0 auto; /* Supaya posisinya tepat di tengah layar */
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* Icon Checklist */
    .success-icon-wrapper {
        width: 80px;
        height: 80px;
        background-color: #dcfce7;
        color: #16a34a;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 8px 16px rgba(22, 163, 74, 0.15);
    }

    /* Judul Utama Kontras Jelas */
    .success-title {
        color: #0f172a !important;
        font-size: 1.85rem;
        font-weight: 700;
        margin-bottom: 1rem;
        line-height: 1.3;
    }

    /* Deskripsi Subtitle */
    .success-description {
        color: #475569 !important;
        font-size: 1.05rem;
        line-height: 1.6;
        margin-bottom: 2rem;
    }

    /* Tombol Return */
    .btn-return-home {
        background-color: #f59e0b;
        color: #1e293b;
        font-weight: 600;
        padding: 0.85rem 2.5rem;
        border-radius: 8px;
        border: none;
        text-decoration: none;
        display: inline-block;
        transition: all 0.25s ease;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .btn-return-home:hover {
        background-color: #d97706;
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(217, 119, 6, 0.4);
    }
</style>

<div class="success-container">
    <div class="success-card">
        <!-- Icon Centang -->
        <div class="success-icon-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>

        <!-- Judul Teks Sangat Jelas -->
        <h1 class="success-title">Registration Submitted Successfully</h1>

        <!-- Pesan Penjelasan -->
        <p class="success-description">
            Thank you for submitting your business details. Our verification team will review your application. This process typically takes <strong>2-3 business days</strong>. You will be notified via email once the review is complete.
        </p>

        <!-- Tombol Kembali -->
        <div>
            <a href="{{ url('/') }}" class="btn-return-home">
                Return to Home
            </a>
        </div>
    </div>
</div>
@endsection