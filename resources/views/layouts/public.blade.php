<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DNA Vendor Portal')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    {{-- CSS khusus halaman login --}}
    @if (Request::routeIs('admin.login'))
        <link rel="stylesheet" href="{{ asset('css/admin-login.css') }}">
    @endif

    <link rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

    <link rel="stylesheet"
        href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css">

    <style>
        @media screen and (max-width:768px) {
            body {
                overflow-x: hidden;
                width: 100%;
                margin: 0;
                padding: 0;
            }

            .container {
                width: 100%;
                max-width: 100%;
                padding-left: 1rem;
                padding-right: 1rem;
                box-sizing: border-box;
            }

            img,
            video,
            canvas {
                max-width: 100%;
                height: auto;
            }

            .navbar-inner {
                flex-wrap: wrap;
                gap: .5rem;
            }
        }
    </style>

    <script>
        document.documentElement.setAttribute('data-theme', 'light');
    </script>

    @stack('styles')

</head>

<body>

    {{-- NAVBAR TIDAK DITAMPILKAN DI HALAMAN LOGIN --}}
    @unless(Request::routeIs('admin.login'))

    <nav class="navbar">

        <div class="container navbar-inner">

            <a href="{{ route('home') }}"
                class="brand"
                style="display:flex;align-items:center;gap:.5rem;text-decoration:none;">

                <img src="{{ asset('images/logo.png') }}"
                    alt="Logo"
                    style="width:50px;height:24px;object-fit:contain;">

                <span class="brand-text">
                    <span class="brand-dna">DNA</span>
                    <span class="brand-vendor">Vendor</span>
                    <span class="brand-portal">Portal</span>
                </span>

            </a>

            <div class="d-flex align-center gap-2">

                <button class="theme-toggle" title="Toggle theme">
                    🌙
                </button>

                {{-- Tombol Register dihapus karena route register tidak ada --}}

            </div>

        </div>

    </nav>

    @endunless

    <main>

        @yield('content')

        @unless(Request::routeIs('admin.login'))
            @include('layouts.partials.footer')
        @endunless

    </main>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    @stack('scripts')

</body>

</html>