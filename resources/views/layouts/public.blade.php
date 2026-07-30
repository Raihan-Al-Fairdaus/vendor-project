<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DNA Vendor Portal')</title>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <link rel="stylesheet"
href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<link
rel="stylesheet"
href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css"/>

    {{-- CSS Mobile --}}
    <style>
        @media screen and (max-width:768px) {
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

            img,
            video,
            canvas {
                max-width: 100% !important;
                height: auto !important;
            }

            .navbar-inner {
                flex-wrap: wrap !important;
                gap: .5rem !important;
            }
        }
    </style>

    {{-- Theme --}}
   <script>
document.documentElement.setAttribute('data-theme', 'light');
</script>
</head>

<body>

    <!-- ================= NAVBAR ================= -->
    <nav class="navbar">
        <div class="container navbar-inner">

            <a href="/" class="brand"
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

                <a href="/register" class="btn btn-primary btn-sm">
                    Register Now
                </a>

            </div>

        </div>
    </nav>

    <!-- ================= CONTENT ================= -->

    <main>

        @yield('content')

        @include('layouts.partials.footer')

    </main>

    <!-- ================= JS ================= -->

    {{-- <script src="{{ asset('js/app.js') }}"></script> --}}

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
</body>

</html>