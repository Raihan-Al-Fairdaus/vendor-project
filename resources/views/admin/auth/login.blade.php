@extends('layouts.public')

@section('title', 'Admin Login - DNA Vendor Portal')

@section('content')
<style>
    /* LATAR BELAKANG HALAMAN LOGIN */
    body, 
    .section-soft-blue,
    .login-wrapper {
        background: linear-gradient(135deg, #1b3a60 0%, #3a587d 50%, #899eb9 100%) !important;
        background-attachment: fixed !important;
    }

    .login-header-title {
        color: #ffffff !important;
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .login-header-subtitle {
        color: rgba(255, 255, 255, 0.85) !important;
    }

    .back-home-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: rgba(255, 255, 255, 0.8) !important;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .back-home-link:hover {
        color: #f59e0b !important;
        transform: translateX(-3px);
    }

    .login-footer-text {
        color: rgba(255, 255, 255, 0.7) !important;
        font-size: 0.8rem;
    }

    /* PERBAIKAN WARNA TEKS KETIKAN & LABEL DI DALAM KOTAK LOGIN */
    .card input.form-control,
    .card input[type="email"],
    .card input[type="password"],
    .card input[type="text"] {
        color: #1e293b !important; /* Warna teks ketikan menjadi hitam/biru tua gelap */
        background-color: #ffffff !important; /* Memastikan background input tetap putih netral */
        border: 1px solid #cbd5e1 !important;
    }

    .card input.form-control:focus {
        border-color: #1b3a60 !important;
        box-shadow: 0 0 0 3px rgba(27, 58, 96, 0.15) !important;
    }

    /* Warna label di atas input */
    .card .form-label, 
    .card label {
        color: #334155 !important;
        font-weight: 600;
    }
</style>

<div class="section-soft-blue" style="min-height: calc(100vh - 73px); display: flex; align-items: center; justify-content: center; padding: 2rem 1rem;">
    <div style="width: 100%; max-width: 420px;">

        {{-- Back to Home --}}
        <div style="margin-bottom: 1.5rem;">
            <a href="{{ route('home') }}" class="back-home-link">
                ← Back to Homepage
            </a>
        </div>

        {{-- Logo Header --}}
        <div class="text-center mb-8 animate-on-scroll">
            <div style="display: inline-flex; align-items: center; justify-content: center; width: 64px; height: 64px; background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(8px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: var(--radius-xl); margin-bottom: 1.25rem; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 36px; height: 36px; object-fit: contain;">
            </div>
            <h1 class="login-header-title">Welcome Back</h1>
            <p class="login-header-subtitle">Sign in to access the Admin Control Panel</p>
        </div>

        {{-- Login Card --}}
        <div class="card animate-on-scroll" style="transition-delay: 0.15s; border-radius: var(--radius-xl); padding: 2rem; background: #ffffff; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);">
            @if ($errors->any())
                <div style="background: var(--error-bg); border: 1px solid var(--error); border-radius: var(--radius-md); padding: 0.875rem 1rem; margin-bottom: 1.5rem; color: var(--error); font-size: 0.875rem; display: flex; align-items: center; gap: 0.5rem;">
                    ⚠️ {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST">
                @csrf
                <div class="form-group mb-4">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email') }}" required autofocus>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label d-flex justify-between">
                        Password
                        <a href="#" style="font-size: 0.8rem; font-weight: 500; color: #1b3a60;">Forgot Password?</a>
                    </label>
                    <div style="position: relative;">
                        <input type="password" name="password" id="password" class="form-control"
                               required style="padding-right: 45px;">
                        <button type="button" id="togglePassword" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; font-size: 1.1rem; padding: 0;">
                            👁️
                        </button>
                    </div>
                </div>

                <div class="form-group d-flex align-center gap-4 mb-4">
                    <input type="checkbox" name="remember" id="remember" style="accent-color: #1b3a60; width:16px; height:16px; cursor:pointer;">
                    <label for="remember" style="font-size: 0.875rem; color: #334155 !important; cursor:pointer;">Remember this device</label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.875rem; font-size: 0.95rem; margin-top: 0.5rem; background-color: #f59e0b; border-color: #f59e0b; color: #1e293b; font-weight: 600;">
                    Login to Dashboard →
                </button>
            </form>
        </div>

        <p class="text-center login-footer-text mt-4" style="transition-delay: 0.3s;">
            &copy; {{ date('Y') }} VendorConnect · Secure Admin Portal
        </p>
    </div>
</div>

<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function () {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.textContent = type === 'password' ? '👁️' : '👁️‍🗨️';
    });
</script>
@endsection