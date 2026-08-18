<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Central - DNA Vendor Portal')</title>

    {{-- Preconnect ke external resources biar load font/icon lebih cepat --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">

    {{-- Font: hanya yang dipakai, dengan display=swap supaya teks langsung muncul --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Font Awesome via CDN dengan crossorigin untuk cache lebih baik --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">

    {{-- Main CSS --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    {{-- NProgress: loading bar tipis di atas halaman --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css" crossorigin="anonymous">
    <style>
        /* Override warna NProgress agar sesuai brand */
        #nprogress .bar { background: #d4a017 !important; height: 3px !important; }
        #nprogress .peg { box-shadow: 0 0 10px #d4a017, 0 0 5px #d4a017 !important; }
        #nprogress .spinner-icon {
            border-top-color: #d4a017 !important;
            border-left-color: #d4a017 !important;
        }
    </style>
    
    <!-- TAMBAHKAN SCRIPT INI DI SINI -->
    <script>
(function() {
    const saved = localStorage.getItem('vendorconnect-theme');
    const theme = saved || 'light';
    document.documentElement.setAttribute('data-theme', theme);
})();
</script>
</head>
<body>
    @if(Auth::check())
    <div class="admin-layout">
        {{-- Mobile Top Bar --}}
        <div class="mobile-top-bar">
            <div class="mobile-top-brand">
                <img src="{{ asset('images/logo.png') }}" alt="DNA" class="mobile-brand-logo">
                <div class="mobile-brand-text">
                    <span class="mobile-brand-name">DNA Portal</span>
                    <span class="mobile-brand-desc">Admin Panel</span>
                </div>
            </div>
            <div class="mobile-top-right">
                <button class="theme-toggle mobile-theme-toggle" title="Toggle theme">🌙</button>
                <div class="mobile-user-avatar" onclick="window.location.href='{{ route('admin.settings.index') }}'">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            </div>
        </div>

        {{-- Mobile Sidebar Overlay --}}
        <div id="sidebar-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:39;transition:opacity 0.3s;"
             onclick="document.querySelector('.sidebar').classList.remove('open');this.classList.remove('active');"></div>

        {{-- Sidebar Presisi & Simetris --}}
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-brand">
                    <span class="brand-icon"><img src="{{ asset('images/logo.png') }}" alt="DNA" style="width:28px;height:28px;object-fit:contain;"></span>
                    <span class="nav-text">DNA Portal</span>
                </div>
                <div class="sidebar-subtitle nav-text">Admin Control Panel</div>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section-label nav-text">Main</div>
                
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" title="Overview">
                    <span class="nav-item-icon"><i class="fa-solid fa-chart-pie"></i></span> 
                    <span class="nav-text">Overview</span>
                </a>
                
                <a href="{{ route('admin.vendors.index') }}" class="nav-item {{ request()->routeIs('admin.vendors.*') ? 'active' : '' }}" title="Vendors">
                    <span class="nav-item-icon"><i class="fa-solid fa-users"></i></span> 
                    <span class="nav-text">Vendors</span>
                </a>
                
                <a href="{{ route('admin.billboards.index') }}" class="nav-item {{ request()->routeIs('admin.billboards.*') ? 'active' : '' }}" title="Billboards">
                    <span class="nav-item-icon"><i class="fa-solid fa-map-pin"></i></span> 
                    <span class="nav-text">Billboards</span>
                </a>
                
                <div class="nav-section-label nav-text">Tools</div>
                
                <a href="{{ route('admin.documents.index') }}" class="nav-item {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}" title="Documents">
                    <span class="nav-item-icon"><i class="fa-solid fa-file-lines"></i></span> 
                    <span class="nav-text">Documents</span>
                </a>
                
                <a href="{{ route('admin.reports.index') }}" class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" title="Reports">
                    <span class="nav-item-icon"><i class="fa-solid fa-chart-line"></i></span> 
                    <span class="nav-text">Reports</span>
                </a>
                
                <a href="{{ route('admin.settings.index') }}" class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" title="Settings">
                    <span class="nav-item-icon"><i class="fa-solid fa-gear"></i></span> 
                    <span class="nav-text">Settings</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="user-profile-wrapper">
                    <div class="user-avatar" data-initial="{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}"></div>
                    <div class="nav-text user-info">
                        <div class="user-name">{{ Auth::user()->name }}</div>
                        <div class="user-role">Administrator</div>
                    </div>
                </div>

                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn" title="Logout">
                        <span class="logout-icon"><i class="fa-solid fa-right-from-bracket"></i></span>
                        <span class="nav-text">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="main-content">
            <div class="d-flex align-center gap-4 mb-8">
                <button id="mobile-sidebar-toggle" class="mobile-nav-toggle" aria-label="Toggle sidebar">☰</button>
                <div style="flex:1;">
                    <h1 class="dashboard-title">@yield('page_title', 'Dashboard')</h1>
                    <p class="dashboard-subtitle">@yield('page_subtitle', 'System overview and vendor activity monitoring.')</p>
                </div>
                <div class="d-flex gap-2 align-center">
                    <button class="theme-toggle" title="Toggle theme">🌙</button>
                    @yield('header_actions')
                </div>
            </div>

            @if(session('success'))
                <div style="background:var(--success-bg);border:1px solid var(--success);border-radius:var(--radius-md);padding:1rem;color:var(--success);margin-bottom:1.5rem;display:flex;align-items:center;gap:0.75rem;">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="background:var(--error-bg);border:1px solid var(--error);border-radius:var(--radius-md);padding:1rem;color:var(--error);margin-bottom:1.5rem;display:flex;align-items:center;gap:0.75rem;">
                    ❌ {{ session('error') }}
                </div>
            @endif


            @yield('content')
        </main>

        {{-- Mobile Bottom Tab Navigation --}}
        <div class="mobile-bottom-nav">
            <a href="{{ route('admin.dashboard') }}" class="mobile-nav-tab {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="mobile-nav-icon"><i class="fa-solid fa-chart-pie"></i></span>
                <span class="mobile-nav-label">Beranda</span>
            </a>
            <a href="{{ route('admin.vendors.index') }}" class="mobile-nav-tab {{ request()->routeIs('admin.vendors.*') ? 'active' : '' }}">
                <span class="mobile-nav-icon"><i class="fa-solid fa-users"></i></span>
                <span class="mobile-nav-label">Vendors</span>
            </a>
            <a href="{{ route('admin.documents.index') }}" class="mobile-nav-tab {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}">
                <span class="mobile-nav-icon"><i class="fa-solid fa-file-lines"></i></span>
                <span class="mobile-nav-label">Docs</span>
            </a>
            <a href="{{ route('admin.reports.index') }}" class="mobile-nav-tab {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <span class="mobile-nav-icon"><i class="fa-solid fa-chart-line"></i></span>
                <span class="mobile-nav-label">Reports</span>
            </a>
            <a href="{{ route('admin.settings.index') }}" class="mobile-nav-tab {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <span class="mobile-nav-icon"><i class="fa-solid fa-gear"></i></span>
                <span class="mobile-nav-label">Settings</span>
            </a>
        </div>
    </div>
    @else
        @yield('content')
    @endif

    <script src="{{ asset('js/app.js') }}" defer></script>

    {{-- NProgress & prefetch script --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js" crossorigin="anonymous"></script>
    <script>
    (function () {
        // Konfigurasi NProgress
        NProgress.configure({
            showSpinner: false,
            speed: 300,
            minimum: 0.1,
            easing: 'ease',
        });

        // Mulai progress bar saat klik link navigasi
        document.addEventListener('click', function (e) {
            const link = e.target.closest('a[href]');
            if (!link) return;
            const href = link.getAttribute('href');
            // Hanya untuk link internal (bukan anchor, bukan external, bukan target blank)
            if (
                href &&
                !href.startsWith('#') &&
                !href.startsWith('javascript') &&
                !href.startsWith('mailto') &&
                !href.startsWith('http') &&
                link.target !== '_blank' &&
                !e.ctrlKey && !e.metaKey && !e.shiftKey
            ) {
                NProgress.start();
            }
        });

        // Form submit juga tampilkan progress
        document.addEventListener('submit', function () {
            NProgress.start();
        });

        // Stop progress bar saat halaman selesai load
        window.addEventListener('pageshow', function () {
            NProgress.done();
        });

        // Prefetch halaman saat hover link navigasi
        // (browser mulai download sebelum diklik)
        const prefetched = new Set();
        document.addEventListener('mouseover', function (e) {
            const link = e.target.closest('a[href]');
            if (!link) return;
            const href = link.getAttribute('href');
            if (
                href &&
                !href.startsWith('#') &&
                !href.startsWith('javascript') &&
                !href.startsWith('http') &&
                !prefetched.has(href)
            ) {
                prefetched.add(href);
                const el = document.createElement('link');
                el.rel = 'prefetch';
                el.href = href;
                document.head.appendChild(el);
            }
        });
    })();
    </script>

</body>
</html>