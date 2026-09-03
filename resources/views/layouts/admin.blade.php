<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Central - DNA Vendor Portal')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css" crossorigin="anonymous">

    <style>
        /* NProgress */
        #nprogress .bar { background: #d4a017 !important; height: 3px !important; }
        #nprogress .peg { box-shadow: 0 0 10px #d4a017, 0 0 5px #d4a017 !important; }
        #nprogress .spinner-icon { border-top-color: #d4a017 !important; border-left-color: #d4a017 !important; }

        /* ============================================================
           ADMIN THEME — FORCE OVERRIDE ALL style.css sidebar rules
           ============================================================ */
        body.admin-theme {
            background-color: #edf2f7 !important;
            font-family: 'Inter', sans-serif !important;
            color: #0f172a !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow: hidden !important;
        }

        /* Kill the old .sidebar rules from style.css */
        body.admin-theme .sidebar,
        body.admin-theme #admin-sidebar {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            height: 100vh !important;
            width: 200px !important;
            min-width: 200px !important;
            max-width: 200px !important;
            z-index: 100 !important;
            background: #1b3a60 !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
            border-right: none !important;
            transition: none !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            display: flex !important;
            flex-direction: column !important;
            padding: 0 !important;
            margin: 0 !important;
            box-sizing: border-box !important;
            box-shadow: none !important;
        }

        /* Kill hover expand */
        body.admin-theme .sidebar:hover,
        body.admin-theme #admin-sidebar:hover {
            width: 200px !important;
            min-width: 200px !important;
            max-width: 200px !important;
            background: #1b3a60 !important;
            box-shadow: none !important;
        }

        /* Kill the main-content shift on sidebar hover */
        body.admin-theme .sidebar:hover ~ .main-content {
            margin-left: 200px !important;
            width: calc(100% - 200px) !important;
        }

        /* Admin layout flex */
        body.admin-theme .admin-layout {
            display: flex !important;
            min-height: 100vh !important;
        }

        /* ---- SIDEBAR HEADER ---- */
        body.admin-theme .sidebar-header {
            padding: 1.25rem 1.25rem !important;
            border-bottom: 1px solid rgba(255,255,255,0.08) !important;
        }

        body.admin-theme .sidebar-brand {
            position: relative !important;
            display: flex !important;
            align-items: center !important;
            gap: 0.4rem !important;
            height: auto !important;
            width: auto !important;
            font-size: 1.15rem !important;
            font-weight: 700 !important;
            color: #ffffff !important;
        }

        body.admin-theme .sidebar-subtitle {
            font-size: 0.6rem !important;
            color: rgba(255,255,255,0.35) !important;
            text-transform: uppercase !important;
            letter-spacing: 0.06em !important;
            margin-top: 4px !important;
            padding-left: 0 !important;
        }

        /* ---- NAV SECTION LABEL ---- */
        body.admin-theme .nav-section-label {
            padding: 1.25rem 1.25rem 0.4rem !important;
            font-size: 0.65rem !important;
            font-weight: 700 !important;
            color: rgba(255,255,255,0.35) !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
        }

        /* ---- SIDEBAR NAV ---- */
        body.admin-theme .sidebar-nav {
            flex: 1 !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 0 !important;
            margin-top: 0 !important;
            padding-top: 0 !important;
        }

        /* ---- NAV ITEMS ---- */
        body.admin-theme .sidebar .nav-item,
        body.admin-theme #admin-sidebar .nav-item {
            position: relative !important;
            display: flex !important;
            align-items: center !important;
            gap: 0.75rem !important;
            padding: 0.65rem 1.25rem !important;
            color: rgba(255,255,255,0.75) !important;
            font-weight: 500 !important;
            font-size: 0.82rem !important;
            border-left: 3px solid transparent !important;
            text-decoration: none !important;
            background: transparent !important;
            height: auto !important;
            width: auto !important;
            transition: none !important;
        }

        body.admin-theme .sidebar .nav-item:hover,
        body.admin-theme #admin-sidebar .nav-item:hover {
            color: #ffffff !important;
            background-color: rgba(255,255,255,0.05) !important;
        }

        body.admin-theme .sidebar .nav-item.active,
        body.admin-theme #admin-sidebar .nav-item.active {
            color: #f6ad55 !important;
            background-color: rgba(246,173,85,0.08) !important;
            border-left-color: #f6ad55 !important;
            font-weight: 600 !important;
        }

        body.admin-theme .sidebar .nav-item.active .nav-item-icon,
        body.admin-theme #admin-sidebar .nav-item.active .nav-item-icon {
            color: #f6ad55 !important;
            left: auto !important;
        }

        /* ---- NAV ITEM ICON ---- */
        body.admin-theme .nav-item-icon {
            position: relative !important;
            left: auto !important;
            top: auto !important;
            width: 20px !important;
            height: auto !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 0.95rem !important;
            color: inherit !important;
        }

        /* ---- NAV TEXT (kill hide/show animation) ---- */
        body.admin-theme .sidebar .nav-text,
        body.admin-theme #admin-sidebar .nav-text {
            opacity: 1 !important;
            visibility: visible !important;
            position: relative !important;
            left: auto !important;
            top: auto !important;
            height: auto !important;
            display: inline !important;
            padding-left: 0 !important;
            white-space: nowrap !important;
            transition: none !important;
        }

        body.admin-theme .sidebar:hover .nav-text {
            opacity: 1 !important;
            visibility: visible !important;
        }

        /* ---- SIDEBAR FOOTER ---- */
        body.admin-theme .sidebar-footer {
            padding: 1rem 1.25rem !important;
            border-top: 1px solid rgba(255,255,255,0.08) !important;
            margin-top: auto !important;
        }

        body.admin-theme .user-profile-wrapper {
            position: relative !important;
            display: flex !important;
            align-items: center !important;
            gap: 0.6rem !important;
            margin-bottom: 0.75rem !important;
            height: auto !important;
            width: auto !important;
        }

        body.admin-theme .user-avatar {
            position: relative !important;
            left: auto !important;
            top: auto !important;
            width: 32px !important;
            height: 32px !important;
            border-radius: 50% !important;
            background-color: #f6ad55 !important;
            color: #1a202c !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-weight: 700 !important;
            font-size: 0.85rem !important;
        }

        body.admin-theme .user-avatar::after {
            display: none !important;
            content: none !important;
        }

        body.admin-theme .user-profile-wrapper .user-info {
            position: relative !important;
            left: auto !important;
            top: auto !important;
            height: auto !important;
            display: flex !important;
            flex-direction: column !important;
            padding-left: 0 !important;
        }

        body.admin-theme .user-name {
            font-weight: 700 !important;
            font-size: 0.8rem !important;
            color: #ffffff !important;
            line-height: 1.2 !important;
        }

        body.admin-theme .user-role {
            font-size: 0.65rem !important;
            color: rgba(255,255,255,0.5) !important;
            text-transform: uppercase !important;
            line-height: 1.2 !important;
        }

        /* ---- LOGOUT ---- */
        body.admin-theme .sidebar-footer form {
            position: relative !important;
            display: block !important;
            width: auto !important;
        }

        body.admin-theme .logout-btn {
            position: relative !important;
            display: flex !important;
            align-items: center !important;
            gap: 0.6rem !important;
            background: none !important;
            border: none !important;
            color: rgba(255,255,255,0.75) !important;
            font-weight: 500 !important;
            font-size: 0.82rem !important;
            cursor: pointer !important;
            width: auto !important;
            text-align: left !important;
            padding: 0 !important;
            height: auto !important;
        }

        body.admin-theme .logout-icon {
            position: relative !important;
            left: auto !important;
            top: auto !important;
            width: 20px !important;
            height: auto !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 0.95rem !important;
            color: inherit !important;
        }

        body.admin-theme .logout-icon i {
            position: relative !important;
            left: auto !important;
            top: auto !important;
            transform: none !important;
        }

        body.admin-theme .logout-btn .nav-text {
            position: relative !important;
            left: auto !important;
            top: auto !important;
            height: auto !important;
            display: inline !important;
            padding-left: 0 !important;
        }

        /* ---- MAIN CONTENT ---- */
        body.admin-theme .main-content {
            flex: 1 !important;
            margin-left: 200px !important;
            width: calc(100% - 200px) !important;
            background: #edf2f7 !important;
            height: 100vh !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            padding: 0 !important;
            box-sizing: border-box !important;
            transition: none !important;
            display: flex !important;
            flex-direction: column !important;
            color: #0f172a !important;
        }

        /* ---- STICKY PAGE HEADER ---- */
        body.admin-theme .admin-page-header {
            position: sticky !important;
            top: 0 !important;
            z-index: 50 !important;
        }

        /* Mobile bottom nav hide on desktop */
        body.admin-theme .mobile-bottom-nav {
            display: none !important;
        }

        /* ---- Brand icon override ---- */
        body.admin-theme .brand-icon {
            position: relative !important;
            left: auto !important;
            top: auto !important;
            width: auto !important;
            height: auto !important;
            display: inline !important;
        }

        body.admin-theme .sidebar-brand .nav-text {
            position: relative !important;
            left: auto !important;
            top: auto !important;
            height: auto !important;
            display: inline !important;
            padding-left: 0 !important;
            opacity: 1 !important;
            visibility: visible !important;
        }
    </style>
</head>
<body class="admin-theme">
    @if(Auth::check())
    <div class="admin-layout">
        <aside id="admin-sidebar" class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-brand">
                    <span style="color:#ef4444; font-weight:800;">DNA</span> <span style="color:#ffffff;">Portal</span>
                </div>
                <div class="sidebar-subtitle">ADMIN CONTROL PANEL</div>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section-label">MAIN</div>

                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="nav-item-icon"><i class="fa-solid fa-chart-pie"></i></span>
                    <span>Overview</span>
                </a>

                <a href="{{ route('admin.vendors.index') }}" class="nav-item {{ request()->routeIs('admin.vendors.*') ? 'active' : '' }}">
                    <span class="nav-item-icon"><i class="fa-solid fa-users"></i></span>
                    <span>Vendors</span>
                </a>

                <a href="{{ route('admin.billboards.index') }}" class="nav-item {{ request()->routeIs('admin.billboards.*') ? 'active' : '' }}">
                    <span class="nav-item-icon"><i class="fa-solid fa-map-pin"></i></span>
                    <span>Billboards</span>
                </a>

                <div class="nav-section-label">TOOLS</div>

                <a href="{{ route('admin.documents.index') }}" class="nav-item {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}">
                    <span class="nav-item-icon"><i class="fa-solid fa-file-lines"></i></span>
                    <span>Documents</span>
                </a>

                <a href="{{ route('admin.settings.index') }}" class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <span class="nav-item-icon"><i class="fa-solid fa-gear"></i></span>
                    <span>Settings</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="user-profile-wrapper">
                    <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                    <div class="user-info">
                        <div class="user-name">Admin DNA</div>
                        <div class="user-role">ADMINISTRATOR</div>
                    </div>
                </div>

                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn" title="Logout">
                        <span class="logout-icon"><i class="fa-solid fa-right-from-bracket"></i></span>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <main class="main-content">
            @if(session('success'))
                <div id="flash-message-success" style="position: fixed; top: 2rem; left: 50%; transform: translateX(-50%); z-index: 9999; background:#ffffff; border-left: 4px solid #10b981; border-radius:8px; padding:1rem 1.5rem; color:#0f172a; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.2); display:flex; align-items:center; gap:0.75rem; font-weight: 500; min-width: 300px; transition: opacity 0.5s ease;">
                    <div style="background: #dcfce7; color: #16a34a; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;"><i class="fa-solid fa-check" style="font-size: 0.8rem;"></i></div>
                    {{ session('success') }}
                </div>
                <script>setTimeout(() => { let el = document.getElementById('flash-message-success'); if(el) { el.style.opacity = '0'; setTimeout(()=>el.remove(), 500); } }, 4000);</script>
            @endif

            @if(session('error'))
                <div id="flash-message-error" style="position: fixed; top: 2rem; left: 50%; transform: translateX(-50%); z-index: 9999; background:#ffffff; border-left: 4px solid #ef4444; border-radius:8px; padding:1rem 1.5rem; color:#0f172a; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.2); display:flex; align-items:center; gap:0.75rem; font-weight: 500; min-width: 300px; transition: opacity 0.5s ease;">
                    <div style="background: #fee2e2; color: #dc2626; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;"><i class="fa-solid fa-xmark" style="font-size: 0.8rem;"></i></div>
                    {{ session('error') }}
                </div>
                <script>setTimeout(() => { let el = document.getElementById('flash-message-error'); if(el) { el.style.opacity = '0'; setTimeout(()=>el.remove(), 500); } }, 4000);</script>
            @endif

            @yield('content')
        </main>
    </div>
    @else
        @yield('content')
    @endif

    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js" crossorigin="anonymous"></script>
    <script>
    (function () {
        NProgress.configure({ showSpinner: false, speed: 300, minimum: 0.1, easing: 'ease' });
        document.addEventListener('click', function (e) {
            var link = e.target.closest('a[href]');
            if (!link) return;
            var href = link.getAttribute('href');
            if (href && !href.startsWith('#') && !href.startsWith('javascript') && !href.startsWith('mailto') && !href.startsWith('http') && link.target !== '_blank' && !e.ctrlKey && !e.metaKey && !e.shiftKey) {
                NProgress.start();
            }
        });
        document.addEventListener('submit', function () { NProgress.start(); });
        window.addEventListener('pageshow', function () { NProgress.done(); });
        var prefetched = new Set();
        document.addEventListener('mouseover', function (e) {
            var link = e.target.closest('a[href]');
            if (!link) return;
            var href = link.getAttribute('href');
            if (href && !href.startsWith('#') && !href.startsWith('javascript') && !href.startsWith('http') && !prefetched.has(href)) {
                prefetched.add(href);
                var el = document.createElement('link');
                el.rel = 'prefetch';
                el.href = href;
                document.head.appendChild(el);
            }
        });
    })();
    </script>
</body>
</html>