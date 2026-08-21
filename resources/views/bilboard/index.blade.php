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
       HERO SECTION
    ============================================================ */
    .bb-hero {
        text-align: center;
        padding: 95px 16px 40px; /* Spasi pas di bawah navbar mobile */
    }

    @media (min-width: 769px) {
        .bb-hero {
            padding: 130px 24px 50px;
        }
    }

    .bb-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(245, 158, 11, 0.15);
        border: 1px solid rgba(245, 158, 11, 0.4);
        border-radius: 50px;
        padding: 6px 16px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #fbbf24;
        margin-bottom: 1.25rem;
    }

    .bb-eyebrow-dot {
        width: 6px; height: 6px;
        background: #f59e0b;
        border-radius: 50%;
        animation: pulseDot 2s infinite;
    }

    @keyframes pulseDot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(1.3); }
    }

    .bb-title {
        font-size: clamp(2rem, 6vw, 3.5rem);
        font-weight: 900;
        color: #ffffff;
        line-height: 1.15;
        margin: 0 auto 1rem;
        letter-spacing: -0.01em;
        text-shadow: 0 4px 15px rgba(0,0,0,0.25);
    }

    .bb-title span {
        background: linear-gradient(90deg, #f59e0b, #fbbf24, #f59e0b);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: shineText 3s linear infinite;
    }

    @keyframes shineText {
        0% { background-position: 0% center; }
        100% { background-position: 200% center; }
    }

    .bb-subtitle {
        font-size: 0.98rem;
        color: rgba(255, 255, 255, 0.85);
        max-width: 550px;
        margin: 0 auto 2rem;
        line-height: 1.6;
    }

    /* Stats Grid */
    .bb-stats {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin-top: 2rem;
    }

    .bb-stat {
        text-align: center;
    }

    .bb-stat-num {
        font-size: 1.8rem;
        font-weight: 800;
        color: #fbbf24;
        display: block;
        line-height: 1.1;
    }

    .bb-stat-label {
        font-size: 0.7rem;
        color: rgba(255, 255, 255, 0.75);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-top: 0.25rem;
        display: block;
    }

    .bb-divider {
        width: 1px;
        background: rgba(255, 255, 255, 0.25);
        align-self: stretch;
    }

    /* ============================================================
       SEARCH & FILTER BAR
    ============================================================ */
    .bb-search-wrap {
        max-width: 860px;
        margin: 0 auto;
        padding: 0 16px;
    }

    .bb-search-card {
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 18px;
        padding: 1.25rem;
        box-shadow: 0 15px 35px rgba(0,0,0,0.2), inset 0 1px 0 rgba(255,255,255,0.15);
    }

    .bb-form {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    @media (min-width: 769px) {
        .bb-form {
            flex-direction: row;
            align-items: stretch;
        }
    }

    .bb-search-box {
        flex: 2;
        position: relative;
    }

    .bb-search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        font-size: 0.95rem;
        pointer-events: none;
    }

    .bb-input {
        width: 100% !important;
        padding: 0.9rem 1rem 0.9rem 2.8rem !important;
        background: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.5) !important;
        border-radius: 10px !important;
        color: #1e293b !important;
        font-size: 0.92rem !important;
        outline: none !important;
        box-sizing: border-box;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .bb-input::placeholder {
        color: #94a3b8 !important;
    }

    .bb-select-box {
        flex: 1;
    }

    .bb-select {
        width: 100% !important;
        height: 100% !important;
        padding: 0.9rem 2.5rem 0.9rem 1rem !important;
        background: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.5) !important;
        border-radius: 10px !important;
        color: #1e293b !important;
        font-size: 0.92rem !important;
        outline: none !important;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        box-sizing: border-box;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2.5'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: right 1rem center !important;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .bb-select option {
        background: #ffffff;
        color: #1e293b;
    }

    .bb-action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .bb-btn-search {
        flex: 1;
        padding: 0.9rem 1.5rem;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border: none;
        border-radius: 10px;
        color: #0a1628;
        font-weight: 700;
        font-size: 0.92rem;
        cursor: pointer;
        transition: all 0.25s;
        box-shadow: 0 4px 10px rgba(245,158,11,0.25);
    }

    .bb-btn-search:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(245,158,11,0.35);
    }

    .bb-btn-reset {
        padding: 0.9rem 1.2rem;
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 10px;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.25s;
    }

    .bb-btn-reset:hover {
        background: rgba(255, 255, 255, 0.3);
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
       RESULTS GRID
    ============================================================ */
    .bb-results {
        max-width: 1200px;
        margin: 2.5rem auto 0;
        padding: 0 16px;
    }

    .bb-results-header {
        margin-bottom: 1.25rem;
        text-align: left;
    }

    .bb-results-count {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.8);
    }

    .bb-results-count strong {
        color: #fbbf24;
    }

    /* Grid */
    .bb-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
        gap: 1.5rem;
    }

    /* ============================================================
       PREMIUM LIGHT-THEME GLASSMORMISM CARD
    ============================================================ */
    .bb-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 20px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        position: relative;
    }

    /* Top golden border highlight */
    .bb-card-strip {
        height: 5px;
        background: linear-gradient(90deg, #f59e0b, #fbbf24, #f59e0b);
    }

    .bb-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 35px rgba(0, 0, 0, 0.18);
        border-color: rgba(245, 158, 11, 0.45);
    }

    .bb-card-body {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    /* Badges row at the top */
    .bb-badge-row {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.85rem;
        flex-wrap: wrap;
    }

    .bb-available-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.25);
        border-radius: 50px;
        padding: 4px 10px;
        font-size: 0.7rem;
        font-weight: 700;
        color: #15803d;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .bb-available-dot {
        width: 6px; height: 6px;
        background: #22c55e;
        border-radius: 50%;
        animation: pulseDot 2s infinite;
    }

    .bb-city-tag {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: rgba(27, 58, 96, 0.07);
        border: 1px solid rgba(27, 58, 96, 0.15);
        border-radius: 50px;
        padding: 4px 10px;
        font-size: 0.7rem;
        font-weight: 700;
        color: #1b3a60;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Card title spans 100% width, no squeezed lines on mobile */
    .bb-card-name {
        font-size: 1.25rem;
        font-weight: 800;
        color: #1b3a60;
        line-height: 1.3;
        margin: 0 0 1rem;
        letter-spacing: -0.01em;
    }

    .bb-card-divider {
        height: 1px;
        background: rgba(0, 0, 0, 0.06);
        margin: 0 0 1rem;
    }

    /* Info Address */
    .bb-info-line {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        font-size: 0.88rem;
        line-height: 1.45;
    }

    .bb-info-line-icon {
        font-size: 1rem;
        min-width: 20px;
        text-align: center;
    }

    .bb-info-line-text {
        color: #475569;
        font-weight: 500;
    }

    /* Card Footer Button */
    .bb-card-footer {
        padding: 0 1.5rem 1.5rem;
    }

    .bb-btn-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        width: 100%;
        padding: 0.85rem 1rem;
        background: linear-gradient(135deg, #1b3a60, #284c78);
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.9rem;
        color: #ffffff !important;
        text-decoration: none;
        letter-spacing: 0.02em;
        box-shadow: 0 4px 10px rgba(27, 58, 96, 0.15);
        transition: all 0.25s ease;
        box-sizing: border-box;
    }

    .bb-btn-link:hover {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #0a1628 !important;
        box-shadow: 0 6px 18px rgba(245, 158, 11, 0.35);
        transform: translateY(-1px);
    }

    .bb-btn-link svg {
        transition: transform 0.2s;
    }

    .bb-btn-link:hover svg {
        transform: translateX(3px);
    }

    .bb-no-link {
        display: block;
        text-align: center;
        padding: 0.85rem;
        border: 1px dashed rgba(0, 0, 0, 0.12);
        border-radius: 10px;
        color: #94a3b8;
        font-size: 0.85rem;
    }

    /* ============================================================
       EMPTY STATE
    ============================================================ */
    .bb-empty {
        text-align: center;
        padding: 5rem 1rem;
    }

    .bb-empty-icon {
        font-size: 3.5rem;
        margin-bottom: 1.25rem;
        opacity: 0.7;
    }

    .bb-empty-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 0.5rem;
    }

    .bb-empty-sub {
        font-size: 0.9rem;
        color: rgba(255,255,255,0.7);
    }

    /* ============================================================
       PAGINATION
    ============================================================ */
    .bb-pagination {
        display: flex;
        justify-content: center;
        margin-top: 3rem;
    }

    /* ============================================================
       FOOTER LINK
    ============================================================ */
    .bb-back {
        text-align: center;
        margin-top: 3.5rem;
    }

    .bb-back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: rgba(255, 255, 255, 0.75) !important;
        font-size: 0.92rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.25s;
    }

    .bb-back-link:hover {
        color: #fbbf24 !important;
    }

    /* ============================================================
       FADE-IN ANIMATION FOR CARDS
    ============================================================ */
    .bb-card {
        animation: fadeUpCard 0.5s ease both;
    }

    @keyframes fadeUpCard {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
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
         HERO
    ========================================================== --}}
    <div class="bb-hero">
        <div class="bb-eyebrow">
            <span class="bb-eyebrow-dot"></span>
            DNA Advertising Network
        </div>
        <h1 class="bb-title">
            List <span>Billboard</span><br>Tersedia
        </h1>
        <p class="bb-subtitle">
            Temukan lokasi billboard strategis di berbagai kota. Semua billboard yang tampil di sini siap untuk dipesan.
        </p>

        {{-- Stats --}}
        @if($billboards->total() > 0)
        <div class="bb-stats">
            <div class="bb-stat">
                <span class="bb-stat-num">{{ $billboards->total() }}</span>
                <span class="bb-stat-label">Billboard</span>
            </div>
            <div class="bb-divider"></div>
            <div class="bb-stat">
                <span class="bb-stat-num">{{ $cities->count() }}</span>
                <span class="bb-stat-label">Kota</span>
            </div>
            <div class="bb-divider"></div>
            <div class="bb-stat">
                <span class="bb-stat-num">100%</span>
                <span class="bb-stat-label">Ready</span>
            </div>
        </div>
        @endif
    </div>

    {{-- =========================================================
         SEARCH & FILTER
    ========================================================== --}}
    <div class="bb-search-wrap">
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
                    <select name="city" onchange="this.form.submit()" class="bb-select">
                        <option value="">Semua Kota</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>
                                {{ $city }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="bb-action-buttons">
                    <button type="submit" class="bb-btn-search">Cari</button>
                    @if(request()->filled('search') || request()->filled('city'))
                        <a href="{{ route('bilboard.index') }}" class="bb-btn-reset">✕ Reset</a>
                    @endif
                </div>
            </form>

            {{-- Active Filter Tags --}}
            @if(request()->filled('search') || request()->filled('city'))
            <div class="bb-active-filter">
                <span class="bb-filter-label">Filter aktif:</span>
                @if(request()->filled('city'))
                    <span class="bb-filter-tag">📍 {{ request('city') }}</span>
                @endif
                @if(request()->filled('search'))
                    <span class="bb-filter-tag">🔍 "{{ request('search') }}"</span>
                @endif
            </div>
            @endif
        </div>
    </div>

    {{-- =========================================================
         RESULTS
    ========================================================== --}}
    <div class="bb-results">

        @if($billboards->count())
            <div class="bb-results-header">
                <span class="bb-results-count">
                    Menampilkan <strong>{{ $billboards->count() }}</strong>
                    dari <strong>{{ $billboards->total() }}</strong> billboard
                </span>
            </div>

            <div class="bb-grid">
                @foreach($billboards as $i => $board)
                    <div class="bb-card" style="animation-delay: {{ $i * 0.05 }}s">
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
                            <h2 class="bb-card-name">{{ $board->name }}</h2>
                            
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
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                </a>
                            @else
                                <span class="bb-no-link">Link lokasi belum tersedia</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bb-pagination">
                {{ $billboards->links() }}
            </div>

        @else
            <div class="bb-empty">
                <div class="bb-empty-icon">🔍</div>
                <div class="bb-empty-title">Tidak ada billboard ditemukan</div>
                <div class="bb-empty-sub">Coba ubah kata kunci atau pilih kota yang berbeda.</div>
            </div>
        @endif

    </div>

    {{-- Back Link --}}
    <div class="bb-back">
        <a href="{{ route('home') }}" class="bb-back-link">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
            Kembali ke Beranda
        </a>
    </div>

</div>
@endsection
