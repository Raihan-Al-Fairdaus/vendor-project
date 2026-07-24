<style>
    /* 1. CONTAINER SIDEBAR UTAMA */
    .animated-sidebar {
        position: relative;
        overflow: hidden !important; /* Agar elemen animasi tidak keluar dari sidebar */
        background-color: #0b1329 !important; /* Warna dasar gelap yang elegan */
        z-index: 1;
    }

    /* 2. ELEMEN LATAR BERGERAK (GLOWING ORBS) */
    .animated-sidebar::before,
    .animated-sidebar::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        filter: blur(50px);
        opacity: 0.25;
        z-index: -1;
        pointer-events: none;
    }

    /* Partikel Emas/Oren (Pojok Kiri Atas ke Bawah) */
    .animated-sidebar::before {
        width: 180px;
        height: 180px;
        background: #f59e0b;
        top: -20px;
        left: -30px;
        animation: floatOrb1 10s infinite alternate ease-in-out;
    }

    /* Partikel Biru/Cyan (Pojok Kanan Bawah ke Atas) */
    .animated-sidebar::after {
        width: 220px;
        height: 220px;
        background: #3b82f6;
        bottom: -40px;
        right: -40px;
        animation: floatOrb2 12s infinite alternate ease-in-out;
    }

    /* 3. TEKSTUR POLA GRID TRANSPARAN ENHANCEMENT */
    .sidebar-bg-pattern {
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255, 255, 255, 0.08) 1px, transparent 1px);
        background-size: 20px 20px;
        opacity: 0.6;
        z-index: -1;
        pointer-events: none;
    }

    /* 4. KEYFRAMES ANIMASI BERGERAK */
    @keyframes floatOrb1 {
        0% {
            transform: translate(0, 0) scale(1);
        }
        50% {
            transform: translate(60px, 120px) scale(1.2);
        }
        100% {
            transform: translate(20px, 250px) scale(0.9);
        }
    }

    @keyframes floatOrb2 {
        0% {
            transform: translate(0, 0) scale(1);
        }
        50% {
            transform: translate(-50px, -150px) scale(1.3);
        }
        100% {
            transform: translate(-30px, -300px) scale(1);
        }
    }

    /* 5. GAYA MENU ITEM SAAT HOVER/ACTIVE (GLASSMORPHISM) */
    .animated-sidebar .nav-link {
        transition: all 0.3s ease !important;
        border-radius: 10px;
    }

    .animated-sidebar .nav-link:hover {
        background: rgba(255, 255, 255, 0.1) !important;
        backdrop-filter: blur(5px);
        transform: translateX(5px);
    }

    .animated-sidebar .nav-link.active {
        background: linear-gradient(90deg, #1d4ed8 0%, #2563eb 100%) !important;
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);
    }
</style>

<aside class="sidebar animated-sidebar sticky-top vh-100 overflow-auto text-white p-3">
    <div class="sidebar-bg-pattern"></div>

    <div class="d-flex align-items-center mb-4 pt-2">
        <span class="fs-3 me-2">⚡</span>
        <div>
            <h4 class="fw-bold m-0 text-white" style="letter-spacing: 0.5px;">VendorConnect</h4>
            <small class="text-uppercase text-muted" style="font-size: 0.65rem; letter-spacing: 1px;">Admin Control Panel</small>
        </div>
    </div>

    <hr style="border-color: rgba(255, 255, 255, 0.15);">

    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-2">
            <a href="{{ route('admin.dashboard') }}" class="nav-link text-white {{ request()->routeIs('admin.dashboard*') ? 'active' : 'opacity-75' }}" data-turbo="false">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="{{ route('admin.documents.index') }}" class="nav-link text-white {{ request()->routeIs('admin.documents*') ? 'active' : 'opacity-75' }}" data-turbo="false">
                <i class="bi bi-file-earmark-text me-2"></i> Vendor Documents
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="{{ route('admin.vendors.index') }}" class="nav-link text-white {{ request()->routeIs('admin.vendors*') ? 'active' : 'opacity-75' }}" data-turbo="false">
                <i class="bi bi-people me-2"></i> Vendor List
            </a>
        </li>
    </ul>
</aside>