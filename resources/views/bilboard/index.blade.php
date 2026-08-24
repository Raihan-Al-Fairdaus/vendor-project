@extends('layouts.public')

@section('title', 'List Billboard — DNA Advertising')

@section('content')
<style>
    /* ============================================================
       GLOBAL OVERRIDE & FONTS
    ============================================================ */
    body {
        font-family: 'Inter', sans-serif;
        margin: 0;
        padding: 0;
        background: #0a1628 !important;
    }

    /* ============================================================
       DYNAMIC DISSOLVE FLOWING GRADIENT BACKGROUND
    ============================================================ */
    .bb-bg-container {
        position: fixed;
        inset: 0;
        z-index: 0;
        overflow: hidden;
        background: #1b3a60; /* Fallback */
    }

    /* Wave gradient animation */
    .bb-bg-gradient {
        position: absolute;
        inset: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(
            -45deg,
            #1b3a60 0%,
            #234775 25%,
            #3a587d 50%,
            #567399 75%,
            #899eb9 100%
        );
        background-size: 400% 400%;
        animation: flowGradient 20s ease infinite;
    }

    /* Floating light circles (Glow blobs) */
    .bb-glow-blob-1 {
        position: absolute;
        width: 40vw;
        height: 40vw;
        max-width: 500px;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.18) 0%, transparent 70%);
        top: 10%;
        left: 5%;
        border-radius: 50%;
        animation: floatBlob1 15s infinite ease-in-out;
    }

    .bb-glow-blob-2 {
        position: absolute;
        width: 50vw;
        height: 50vw;
        max-width: 600px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.12) 0%, transparent 70%);
        bottom: 10%;
        right: 5%;
        border-radius: 50%;
        animation: floatBlob2 18s infinite ease-in-out alternate;
    }

    @keyframes flowGradient {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    @keyframes floatBlob1 {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(40px, -60px) scale(1.12); }
    }

    @keyframes floatBlob2 {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(-50px, 40px) scale(1.08); }
    }

    /* ============================================================
       WRAPPER & LAYOUT
    ============================================================ */
    .bb-wrapper {
        position: relative;
        z-index: 1;
        min-height: 100vh;
        padding-bottom: 80px;
    }

    /* ============================================================
       HEADER SECTION — full-bleed billboard background
    ============================================================ */
    .bb-header {
        padding: 95px 16px 80px;
        position: relative;
        color: #ffffff;
        background-image:
            linear-gradient(to right,
                #162d4e 0%,
                #1b3a60 30%,
                rgba(27, 58, 96, 0.80) 52%,
                rgba(27, 58, 96, 0.25) 72%,
                rgba(27, 58, 96, 0.05) 100%
            ),
            url('/images/billboard-header.jpg');
        background-size: cover;
        background-position: right center;
        background-repeat: no-repeat;
    }

    @media (min-width: 992px) {
        .bb-header {
            padding: 130px 24px 100px;
        }
    }

    .bb-header-content {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    @media (min-width: 992px) {
        .bb-header-content {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
    }

    .bb-header-text {
        flex: 1;
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
        color: #ffffff !important;
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
       OVERLAPPING SEARCH BAR (GLASSMORPHISM)
    ============================================================ */
    .bb-search-outer {
        max-width: 1200px;
        margin: -36px auto 0;
        padding: 0 24px;
        position: relative;
        z-index: 10;
    }

    .bb-search-card {
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 18px;
        padding: 1.25rem;
        box-shadow: 0 15px 35px rgba(0,0,0,0.25), inset 0 1px 0 rgba(255,255,255,0.15);
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
        color: #64748b;
        font-size: 1rem;
        z-index: 1;
    }

    .bb-input {
        width: 100% !important;
        padding: 0.95rem 1rem 0.95rem 3rem !important;
        background: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.5) !important;
        border-radius: 10px !important;
        color: #1e293b !important;
        font-size: 0.95rem !important;
        outline: none !important;
        box-sizing: border-box;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .bb-input::placeholder {
        color: #94a3b8 !important;
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
        color: #64748b;
        font-size: 1rem;
        pointer-events: none;
        z-index: 1;
    }

    .bb-select {
        width: 100% !important;
        padding: 0.95rem 2.5rem 0.95rem 3rem !important;
        background: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.5) !important;
        border-radius: 10px !important;
        color: #1e293b !important;
        font-size: 0.95rem !important;
        outline: none !important;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        box-sizing: border-box;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2.5'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: right 1.25rem center !important;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .bb-select option {
        background: #ffffff;
        color: #1e293b;
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
        background: rgba(255, 255, 255, 0.15);
        color: #ffffff;
        font-weight: 600;
        font-size: 0.95rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 10px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .bb-btn-reset:hover {
        background: rgba(255, 255, 255, 0.25);
    }

    /* Active filter tag */
    .bb-active-filter {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.75rem;
        flex-wrap: wrap;
    }

    .bb-filter-label {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.7);
    }

    .bb-filter-tag {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 50px;
        padding: 3px 10px;
        font-size: 0.75rem;
        color: #ffffff;
        font-weight: 600;
    }

    /* ============================================================
       CONTENT SECTION (DARK TRANSPARENT STYLE)
    ============================================================ */
    .bb-content {
        max-width: 1200px;
        margin: 60px auto 80px;
        padding: 0 24px;
    }

    .bb-section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .bb-section-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #ffffff;
        margin: 0;
    }

    .bb-results-info {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.7);
        margin-top: 0.25rem;
        display: block;
    }

    .bb-results-info strong {
        color: #f59e0b;
    }

    /* Cards Grid */
    .bb-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.75rem;
    }

    /* Glassmorphism Card Layout (NO THUMBNAIL) */
    .bb-item-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.35s cubic-bezier(0.25, 0.8, 0.25, 1);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .bb-card-strip {
        height: 4px;
        background: linear-gradient(90deg, #f59e0b, #fbbf24, #f59e0b);
        background-size: 200% auto;
    }

    .bb-item-card:hover {
        transform: translateY(-6px);
        border-color: rgba(245, 158, 11, 0.35);
        box-shadow:
            0 20px 40px rgba(0, 0, 0, 0.35),
            0 0 0 1px rgba(245, 158, 11, 0.15);
    }

    .bb-card-body {
        padding: 1.75rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    /* Badges row at the top */
    .bb-badge-row {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .bb-available-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: rgba(34, 197, 94, 0.12);
        border: 1px solid rgba(34, 197, 94, 0.3);
        border-radius: 50px;
        padding: 4px 10px;
        font-size: 0.72rem;
        font-weight: 700;
        color: #4ade80;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .bb-available-dot {
        width: 6px; height: 6px;
        background: #4ade80;
        border-radius: 50%;
        animation: pulseDot 2s infinite;
    }

    @keyframes pulseDot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(1.3); }
    }

    .bb-city-tag {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: rgba(255, 255, 255, 0.07);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 50px;
        padding: 4px 10px;
        font-size: 0.72rem;
        font-weight: 700;
        color: rgba(255, 255, 255, 0.85);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Card title spans 100% width, no squeezed lines on mobile */
    .bb-card-name {
        font-size: 1.25rem;
        font-weight: 800;
        color: #ffffff;
        line-height: 1.35;
        margin: 0 0 1rem;
        letter-spacing: -0.01em;
    }

    .bb-card-divider {
        height: 1px;
        background: rgba(255, 255, 255, 0.07);
        margin: 0 0 1.25rem;
    }

    /* Info Address */
    .bb-info-line {
        display: flex;
        align-items: flex-start;
        gap: 0.6rem;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .bb-info-line-icon {
        font-size: 1rem;
        min-width: 20px;
        text-align: center;
        color: #f59e0b;
    }

    .bb-info-line-text {
        color: rgba(255, 255, 255, 0.75);
    }

    /* Card Footer Button */
    .bb-card-footer {
        padding: 0 1.75rem 1.75rem;
    }

    .bb-btn-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        width: 100%;
        padding: 0.9rem 1rem;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.92rem;
        color: #0a1628 !important;
        text-decoration: none;
        letter-spacing: 0.02em;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);
        transition: all 0.25s ease;
        box-sizing: border-box;
    }

    .bb-btn-link:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(245, 158, 11, 0.4);
    }

    .bb-btn-link svg {
        transition: transform 0.2s;
    }

    .bb-btn-link:hover svg {
        transform: translateX(3px);
    }

    .bb-no-gps-link {
        display: block;
        text-align: center;
        padding: 0.9rem;
        border: 1px dashed rgba(255, 255, 255, 0.12);
        border-radius: 10px;
        color: rgba(255, 255, 255, 0.3);
        font-size: 0.85rem;
    }

    /* ============================================================
       EMPTY STATE
    ============================================================ */
    .bb-empty-box {
        text-align: center;
        padding: 5rem 1.5rem;
    }

    .bb-empty-icon {
        font-size: 3rem;
        color: rgba(255, 255, 255, 0.4);
        margin-bottom: 1rem;
    }

    .bb-empty-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 0.25rem;
    }

    .bb-empty-desc {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.6);
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
       FOOTER NAV
    ============================================================ */
    .bb-footer-nav {
        text-align: center;
        margin-top: 3.5rem;
    }

    .bb-back-home-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: rgba(255, 255, 255, 0.75) !important;
        font-size: 0.92rem;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.2s;
    }

    .bb-back-home-btn:hover {
        color: #fbbf24 !important;
    }
</style>

{{-- Animated Background Wrapper --}}
<div class="bb-bg-container">
    <div class="bb-bg-gradient"></div>
    <div class="bb-glow-blob-1"></div>
    <div class="bb-glow-blob-2"></div>
</div>

<div class="bb-wrapper">

    {{-- =========================================================
         HEADER SECTION
    ========================================================== --}}
    <div class="bb-header">
        <div class="bb-header-content">
            <div class="bb-header-text">
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
            </div>{{-- /.bb-header-text --}}
        </div>{{-- /.bb-header-content --}}
    </div>{{-- /.bb-header --}}


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
         CONTENT LIST (DARK BG STYLE)
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
                        <div class="bb-card-strip"></div>
                        <div class="bb-card-body">
                            {{-- Badges row at the top --}}
                            <div class="bb-badge-row">
                                <span class="bb-available-badge">
                                    <span class="bb-available-dot"></span>
                                    Tersedia
                                </span>
                                <span class="bb-city-tag">🗺️ {{ $board->city }}</span>
                            </div>

                            {{-- Title takes full width --}}
                            <h3 class="bb-card-name">{{ $board->name }}</h3>
                            
                            <div class="bb-card-divider"></div>

                            {{-- Address details --}}
                            <div class="bb-info-line">
                                <span class="bb-info-line-icon">📍</span>
                                <span class="bb-info-line-text">{{ $board->address }}</span>
                            </div>
                        </div>

                        {{-- Footer CTA button --}}
                        <div class="bb-card-footer">
                            @if($board->google_maps_url)
                                <a href="{{ $board->google_maps_url }}" target="_blank" rel="noopener" class="bb-btn-link">
                                    Lihat Lokasi
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                </a>
                            @else
                                <span class="bb-no-gps-link">Lokasi belum diatur</span>
                            @endif
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

</div>
@endsection
