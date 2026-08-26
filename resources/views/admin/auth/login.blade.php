<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - DNA Advertising</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Preconnect: koneksi awal ke CDN sebelum browser butuh resource --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>

    {{-- Preload: muat CSS login & foto billboard lebih awal --}}
    <link rel="preload" href="{{ asset('css/login.css') }}?v=2.1" as="style">
    <link rel="preload" href="{{ asset('images/billboard-header.jpg') }}" as="image">

    {{-- Fonts & Icons --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/login.css') }}?v=2.1">
</head>

<body>

<div class="login-page">

    <!-- Background Pattern -->
    <div class="bg-gradient"></div>
    <div class="bg-circle circle-1"></div>
    <div class="bg-circle circle-2"></div>

    <div class="login-container">

        <!-- LEFT PANEL -->
        <div class="login-left">
            <div class="left-illustration"></div>
            
            <div class="left-header-wrap">
                <div class="brand">
                    <img src="{{ asset('images/logo.png') }}" class="logo" alt="DNA Advertising">
                    <h1><span>DNA</span> Advertising</h1>
                    <p>Secure Vendor Management Portal</p>
                </div>
                <div class="gold-accent-line"></div>
            </div>

            <div class="left-features-wrap desktop-only">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div class="feature-text">
                        <h4>Secure Login</h4>
                        <p>Protected authentication system for administrator access.</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fa-solid fa-award"></i>
                    </div>
                    <div class="feature-text">
                        <h4>Premium Experience</h4>
                        <p>Elegant & modern interface for better productivity.</p>
                    </div>
                </div>
            </div>

            <div class="left-copyright">
                © {{ date('Y') }} DNA Advertising. All Rights Reserved.
            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div class="login-right">
            <div class="login-box">
                
                <a href="{{ route('home') }}" class="back-home-link">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
                    Back to Homepage
                </a>

                <div class="form-heading">
                    <h2>Login</h2>
                    <p>Please login using your administrator account.</p>
                </div>

                @if ($errors->any())
                    <div class="alert-box-error">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('admin.login.post') }}" method="POST">
                    @csrf

                    <div class="input-form-group">
                        <label>Email Address</label>
                        <div class="input-container-box">
                            <span class="field-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                            </span>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="Enter your email"
                                required
                                autofocus
                            >
                        </div>
                    </div>

                    <div class="input-form-group">
                        <label>Password</label>
                        <div class="input-container-box">
                            <span class="field-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            </span>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Enter your password"
                                required
                            >
                            <button type="button" id="togglePassword" class="eye-toggle-btn">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="remember-device-row">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember">
                            <span>Remember this device</span>
                        </label>
                    </div>

                    <button type="submit" class="submit-login-btn">
                        Login to Dashboard
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
const togglePassword = document.getElementById("togglePassword");
const password = document.getElementById("password");

togglePassword.addEventListener("click", () => {
    const icon = togglePassword.querySelector("i");
    if (password.type === "password") {
        password.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        password.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
});
</script>

</body>
</html>
