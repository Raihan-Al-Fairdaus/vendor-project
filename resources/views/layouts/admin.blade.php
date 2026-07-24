<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Central - VendorConnect')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
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
        {{-- Mobile Sidebar Overlay --}}
        <div id="sidebar-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:39;transition:opacity 0.3s;"
             onclick="document.querySelector('.sidebar').classList.remove('open');this.classList.remove('active');"></div>

        {{-- Sidebar Presisi & Simetris --}}
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-brand">
                    <span class="brand-icon"><i class="fa-solid fa-bolt"></i></span>
                    <span class="nav-text">VendorConnect</span>
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
    </div>
    @else
        @yield('content')
    @endif

    <script src="{{ asset('js/app.js') }}"></script>

   
</body>
</html>