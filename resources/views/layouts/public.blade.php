<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VendorConnect')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    {{-- CSS Khusus Mobile (Mencegah overflow & merapikan elemen di layar HP) --}}
    <style>
        @media screen and (max-width: 768px) {
            body {
                overflow-x: hidden !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .container {
                width: 100% !important;
                max-width: 100% !important;
                padding-left: 1rem !important;
                padding-right: 1rem !important;
                box-sizing: border-box !important;
            }
            img, video, canvas {
                max-width: 100% !important;
                height: auto !important;
            }
            .navbar-inner {
                flex-wrap: wrap !important;
                gap: 0.5rem !important;
            }
        }
    </style>

    {{-- Apply theme immediately to avoid flash --}}
    <script>
        (function() {
            const saved = localStorage.getItem('vendorconnect-theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = saved || (prefersDark ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
</head>
<body>
    <nav class="navbar">
        <div class="container navbar-inner">
            <a href="/" class="brand" style="display: flex; align-items: center; gap: 0.5rem; text-decoration: none;">
                <!-- Logo kecil di sebelah kiri -->
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 50px; height: 24px; object-fit: contain;">
                
                <span style="font-size: 1.25rem; font-weight: bold;">Vendor<span>Connect</span></span>
            </a>
            <div class="d-flex align-center gap-2">
                <button class="theme-toggle" title="Toggle theme">🌙</button>
                <a href="/register" class="btn btn-primary btn-sm">Register Now</a>
                <a href="/admin/login" class="btn btn-outline btn-sm">Login</a>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} VendorConnect Procurement Systems. All rights reserved.</p>
        </div>
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>