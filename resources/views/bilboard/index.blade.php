@extends('layouts.public')

@section('title', 'List Billboard — DNA Advertising')

@section('content')
<style>
    /* ============================================================
       GLOBAL STYLE SETTINGS
    ============================================================ */
    body {
        background-color: #f8fafc !important;
        font-family: 'Inter', sans-serif;
        margin: 0;
        padding: 0;
        color: #1e293b;
    }

    /* ============================================================
       HEADER SECTION (CORPORATE NAVY BLUE)
    ============================================================ */
    .bb-header {
        background: linear-gradient(135deg, #163056 0%, #1e3a68 100%);
        padding: 120px 24px 80px;
        position: relative;
        color: #ffffff;
        overflow: hidden;
    }

    /* Subtle grid pattern background */
    .bb-header::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px);
        background-size: 24px 24px;
        opacity: 0.7;
        pointer-events: none;
    }

    /* Right-side masked billboard towers silhouette */
    .bb-header-illustration {
        position: absolute;
        right: 5%;
        bottom: 0;
        width: 380px;
        height: 260px;
        opacity: 0.15;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100' fill='%23ffffff'%3E%3Crect x='10' y='20' width='80' height='45' rx='3'/%3E%3Crect x='46' y='65' width='8' height='35'/%3E%3Cpath d='M40 65 L20 100 M60 65 L80 100' stroke='%23ffffff' stroke-width='2'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-size: contain;
        pointer-events: none;
        display: none;
    }

    @media (min-width: 992px) {
        .bb-header-illustration {
            display: block;
        }
    }

    .bb-header-content {
        max-width: 1200px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    .bb-eyebrow {
        color: #f59e0b;
        font-size: 0.78rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        margin-bottom: 0.75rem;
        display: block;
    }

    .bb-title {
        font-size: clamp(2.2rem, 5vw, 3.2rem);
        font-weight: 800;
        line-height: 1.2;
        margin: 0 0 1rem;
    }

    .bb-title span {
        color: #f59e0b;
    }

    .bb-subtitle {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.8);
        max-width: 600px;
        line-height: 1.6;
        margin: 0 0 2.5rem;
    }

    /* Stats Grid */
    .bb-stats-row {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .bb-stat-card {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.85rem;
        min-width: 160px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .bb-stat-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.12);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        color: #f59e0b;
    }

    .bb-stat-info {
        display: flex;
        flex-direction: column;
    }

    .bb-stat-value {
        font-size: 1.3rem;
        font-weight: 800;
        line-height: 1;
        color: #ffffff;
    }

    .bb-stat-label {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.65);
        margin-top: 0.2rem;
    }

    /* ============================================================
       OVERLAPPING SEARCH BAR
    ============================================================ */
    .bb-search-outer {
        max-width: 1200px;
        margin: -36px auto 0;
        padding: 0 24px;
        position: relative;
        z-index: 10;
    }

    .bb-search-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08), 0 1px 3px rgba(0, 0, 0, 0.02);
        border: 1px solid #e2e8f0;
    }

    .bb-form {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    @media (min-width: 769px) {
        .bb-form {
            flex-direction: row;
            align-items: center;
        }
    }

    .bb-search-box {
        flex: 2;
        position: relative;
    }

    .bb-search-icon {
        position: absolute;
        left: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 1rem;
    }

    .bb-input {
        width: 100% !important;
        padding: 0.95rem 1rem 0.95rem 3rem !important;
        background: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 10px !important;
        color: #1e293b !important;
        font-size: 0.95rem !important;
        outline: none !important;
        box-sizing: border-box;
        transition: border-color 0.2s;
    }

    .bb-input:focus {
        border-color: #1e3a68 !important;
        background: #ffffff !important;
    }

    .bb-select-box {
        flex: 1;
        position: relative;
    }

    .bb-select-icon {
        position: absolute;
        left: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 1rem;
        pointer-events: none;
    }

    .bb-select {
        width: 100% !important;
        padding: 0.95rem 2.5rem 0.95rem 3rem !important;
        background: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 10px !important;
        color: #1e293b !important;
        font-size: 0.95rem !important;
        outline: none !important;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        box-sizing: border-box;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.5'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: right 1.25rem center !important;
    }

    .bb-select:focus {
        border-color: #1e3a68 !important;
        background: #ffffff !important;
    }

    .bb-action-group {
        display: flex;
        gap: 0.5rem;
    }

    .bb-btn-search {
        padding: 0.95rem 2rem;
        background: #f59e0b;
        color: #ffffff;
        font-weight: 700;
        font-size: 0.95rem;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(245, 158, 11, 0.2);
        transition: all 0.2s;
        white-space: nowrap;
        flex: 1;
    }

    .bb-btn-search:hover {
        background: #d97706;
        transform: translateY(-1px);
    }

    .bb-btn-reset {
        padding: 0.95rem 1.25rem;
        background: #f1f5f9;
        color: #64748b;
        font-weight: 600;
        font-size: 0.95rem;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .bb-btn-reset:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    /* ============================================================
       CONTENT LIST & CARDS
    ============================================================ */
    .bb-content {
        max-width: 1200px;
        margin: 40px auto 80px;
        padding: 0 24px;
    }

    .bb-section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .bb-section-title {
        font-size: 1.4rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
    }

    .bb-results-info {
        font-size: 0.85rem;
        color: #64748b;
        margin-top: 0.25rem;
        display: block;
    }

    .bb-results-info strong {
        color: #0f172a;
    }

    /* Cards Grid */
    .bb-cards-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.25rem;
    }

    @media (min-width: 768px) {
        .bb-cards-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 1024px) {
        .bb-cards-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    /* Premium Horizontal / Compact Card Design matching mockup */
    .bb-item-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        display: flex;
        padding: 1rem;
        gap: 1rem;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }

    .bb-item-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 25px rgba(0, 0, 0, 0.08);
        border-color: #cbd5e1;
    }

    /* Left image thumbnail in card */
    .bb-thumb-container {
        width: 100px;
        height: 100px;
        min-width: 100px;
        border-radius: 12px;
        overflow: hidden;
        background-color: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        border: 1px solid #f1f5f9;
    }

    .bb-thumb-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Illustration placeholder if no image exists */
    .bb-thumb-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        color: #94a3b8;
        background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
    }

    /* Center info in card */
    .bb-card-details {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .bb-card-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 0.25rem;
        line-height: 1.3;
    }

    .bb-card-city {
        font-size: 0.82rem;
        color: #64748b;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        margin-bottom: 0.4rem;
    }

    .bb-card-address {
        font-size: 0.8rem;
        color: #64748b;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 0.5rem;
    }

    /* Right side status and action inside details */
    .bb-card-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: auto;
    }

    .bb-status-tag {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.72rem;
        font-weight: 700;
        color: #16a34a;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        padding: 2px 8px;
        border-radius: 50px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .bb-status-dot {
        width: 5px;
        height: 5px;
        background-color: #22c55e;
        border-radius: 50%;
        display: inline-block;
    }

    .bb-action-link {
        font-size: 0.82rem;
        color: #f59e0b;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 3px;
        transition: color 0.2s;
    }

    .bb-action-link:hover {
        color: #d97706;
    }

    .bb-action-link svg {
        transition: transform 0.2s;
    }

    .bb-action-link:hover svg {
        transform: translateX(2px);
    }

    .bb-no-gps-link {
        font-size: 0.78rem;
        color: #94a3b8;
        font-style: italic;
    }

    /* ============================================================
       EMPTY STATE
    ============================================================ */
    .bb-empty-box {
        text-align: center;
        padding: 4rem 1.5rem;
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
    }

    .bb-empty-icon {
        font-size: 3rem;
        color: #94a3b8;
        margin-bottom: 1rem;
    }

    .bb-empty-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .bb-empty-desc {
        font-size: 0.9rem;
        color: #64748b;
    }

    /* ============================================================
       PAGINATION
    ============================================================ */
    .bb-pagination-wrap {
        display: flex;
        justify-content: center;
        margin-top: 3rem;
    }

    /* ============================================================
       BACK TO HOME LINK
    ============================================================ */
    .bb-footer-nav {
        text-align: center;
        margin-top: 3.5rem;
    }

    .bb-back-home-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #64748b;
        font-size: 0.92rem;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.2s;
    }

    .bb-back-home-btn:hover {
        color: #1e3a68;
    }
</style>

{{-- =========================================================
     HEADER SECTION (NAVY BLUE)
========================================================== --}}
<div class="bb-header">
    <div class="bb-header-illustration"></div>
    <div class="bb-header-content">
        <span class="bb-eyebrow">DNA ADVERTISING NETWORK</span>
        <h1 class="bb-title">
            List Billboard <span>Tersedia</span>
        </h1>
        <p class="bb-subtitle">
            Temukan lokasi billboard strategis di berbagai kota. Semua billboard yang tampil di sini siap untuk dipesan.
        </p>

        {{-- Stats Row --}}
        @if($billboards->total() > 0)
        <div class="bb-stats-row">
            <div class="bb-stat-card">
                <div class="bb-stat-icon">
                    <i class="fa-solid fa-desktop"></i>
                </div>
                <div class="bb-stat-info">
                    <span class="bb-stat-value">{{ $billboards->total() }}</span>
                    <span class="bb-stat-label">Billboard</span>
                </div>
            </div>
            <div class="bb-stat-card">
                <div class="bb-stat-icon">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <div class="bb-stat-info">
                    <span class="bb-stat-value">{{ $cities->count() }}</span>
                    <span class="bb-stat-label">Lokasi</span>
                </div>
            </div>
            <div class="bb-stat-card">
                <div class="bb-stat-icon">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="bb-stat-info">
                    <span class="bb-stat-value">100%</span>
                    <span class="bb-stat-label">Siap Dipesan</span>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- =========================================================
     SEARCH & FILTER CARD (OVERLAPPING)
========================================================== --}}
<div class="bb-search-outer">
    <div class="bb-search-card">
        <form action="{{ route('bilboard.index') }}" method="GET" class="bb-form">
            <div class="bb-search-box">
                <span class="bb-search-icon">🔍</span>
                <input
                    type="text"
                    name="search"
                    placeholder="Cari nama atau alamat billboard..."
                    value="{{ request('search') }}"
                    class="bb-input"
                >
            </div>
            <div class="bb-select-box">
                <span class="bb-select-icon">📍</span>
                <select name="city" onchange="this.form.submit()" class="bb-select">
                    <option value="">Semua Kota</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>
                            {{ $city }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="bb-action-group">
                <button type="submit" class="bb-btn-search">Cari</button>
                @if(request()->filled('search') || request()->filled('city'))
                    <a href="{{ route('bilboard.index') }}" class="bb-btn-reset">✕ Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- =========================================================
     CONTENT LIST
========================================================== --}}
<div class="bb-content">

    <div class="bb-section-header">
        <div>
            <h2 class="bb-section-title">Daftar Billboard</h2>
            <span class="bb-results-info">
                Menampilkan <strong>{{ $billboards->count() }}</strong> dari <strong>{{ $billboards->total() }}</strong> billboard
            </span>
        </div>
    </div>

    @if($billboards->count())
        <div class="bb-cards-grid">
            @foreach($billboards as $board)
                <div class="bb-item-card">
                    {{-- Thumbnail on the left --}}
                    <div class="bb-thumb-container">
                        {{-- DNA billboard placeholder --}}
                        <div class="bb-thumb-placeholder">
                            <i class="fa-solid fa-mountain-sun" style="font-size: 1.8rem; color: #a1b0cb;"></i>
                        </div>
                    </div>

                    {{-- Details on the right --}}
                    <div class="bb-card-details">
                        <div>
                            <h3 class="bb-card-title">{{ $board->name }}</h3>
                            <div class="bb-card-city">
                                🏙️ {{ $board->city }}
                            </div>
                            <p class="bb-card-address">
                                {{ $board->address }}
                            </p>
                        </div>

                        <div class="bb-card-meta">
                            <span class="bb-status-tag">
                                <span class="bb-status-dot"></span>
                                Tersedia
                            </span>

                            @if($board->google_maps_url)
                                <a href="{{ $board->google_maps_url }}" target="_blank" rel="noopener" class="bb-action-link">
                                    Lihat Lokasi
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                </a>
                            @else
                                <span class="bb-no-gps-link">Lokasi belum diatur</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="bb-pagination-wrap">
            {{ $billboards->links() }}
        </div>
    @else
        <div class="bb-empty-box">
            <div class="bb-empty-icon">🔍</div>
            <h3 class="bb-empty-title">Tidak ada billboard ditemukan</h3>
            <p class="bb-empty-desc">Coba ubah kata kunci pencarian Anda atau pilih kota lain.</p>
        </div>
    @endif

    <div class="bb-footer-nav">
        <a href="{{ route('home') }}" class="bb-back-home-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
            Kembali ke Beranda
        </a>
    </div>

</div>
@endsection
