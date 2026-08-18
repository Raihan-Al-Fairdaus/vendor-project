@extends('layouts.public')

@section('title', 'List Billboard — DNA Advertising')

@section('content')
<style>
    /* ============================================================
       GLOBAL OVERRIDE & PREMIUM FONTS
    ============================================================ */
    body {
        background: #0a1628 !important;
        font-family: 'Inter', sans-serif;
    }

    /* ============================================================
       ANIMATED BACKGROUND WITH GRADIENT & GLOW
    ============================================================ */
    .bb-bg {
        position: fixed;
        inset: 0;
        z-index: 0;
        background:
            radial-gradient(ellipse 80% 60% at 20% 10%, rgba(30, 80, 160, 0.55) 0%, transparent 60%),
            radial-gradient(ellipse 60% 80% at 80% 80%, rgba(15, 50, 110, 0.45) 0%, transparent 60%),
            linear-gradient(160deg, #0a1628 0%, #122040 40%, #1a3060 70%, #0f1e3a 100%);
        overflow: hidden;
    }

    .bb-bg::before {
        content: '';
        position: absolute;
        width: 600px; height: 600px;
        top: -150px; right: -100px;
        background: radial-gradient(circle, rgba(245,158,11,0.07) 0%, transparent 70%);
        border-radius: 50%;
        animation: float1 12s ease-in-out infinite;
    }

    .bb-bg::after {
        content: '';
        position: absolute;
        width: 400px; height: 400px;
        bottom: -80px; left: -80px;
        background: radial-gradient(circle, rgba(59,130,246,0.09) 0%, transparent 70%);
        border-radius: 50%;
        animation: float2 15s ease-in-out infinite;
    }

    @keyframes float1 {
        0%, 100% { transform: translate(0,0) scale(1); }
        50%      { transform: translate(-40px, 40px) scale(1.08); }
    }
    @keyframes float2 {
        0%, 100% { transform: translate(0,0) scale(1); }
        50%      { transform: translate(30px, -30px) scale(1.05); }
    }

    /* ============================================================
       WRAPPER
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
        padding: 140px 24px 60px;
    }

    .bb-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(245, 158, 11, 0.12);
        border: 1px solid rgba(245, 158, 11, 0.35);
        border-radius: 50px;
        padding: 6px 18px;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #f59e0b;
        margin-bottom: 1.5rem;
    }

    .bb-eyebrow-dot {
        width: 6px; height: 6px;
        background: #f59e0b;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%      { opacity: 0.5; transform: scale(1.4); }
    }

    .bb-title {
        font-size: clamp(2.2rem, 5vw, 3.8rem);
        font-weight: 900;
        color: #ffffff;
        line-height: 1.1;
        margin: 0 auto 1.2rem;
        letter-spacing: -0.02em;
        text-shadow: 0 4px 30px rgba(0,0,0,0.3);
    }

    .bb-title span {
        background: linear-gradient(90deg, #f59e0b, #fbbf24, #f59e0b);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: shine 3s linear infinite;
    }

    @keyframes shine {
        0%   { background-position: 0% center; }
        100% { background-position: 200% center; }
    }

    .bb-subtitle {
        font-size: 1.05rem;
        color: rgba(255,255,255,0.55);
        max-width: 500px;
        margin: 0 auto 2.5rem;
        line-height: 1.7;
    }

    .bb-stats {
        display: flex;
        justify-content: center;
        gap: 3rem;
        margin-top: 2.5rem;
    }

    .bb-stat {
        text-align: center;
    }

    .bb-stat-num {
        font-size: 2rem;
        font-weight: 800;
        color: #f59e0b;
        display: block;
        line-height: 1;
    }

    .bb-stat-label {
        font-size: 0.75rem;
        color: rgba(255,255,255,0.45);
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-top: 0.3rem;
        display: block;
    }

    .bb-divider {
        width: 1px;
        background: rgba(255,255,255,0.12);
        align-self: stretch;
    }

    /* ============================================================
       SEARCH SECTION (GLASSMORPHISM)
    ============================================================ */
    .bb-search-wrap {
        max-width: 860px;
        margin: 0 auto;
        padding: 0 24px;
    }

    .bb-search-card {
        background: rgba(255,255,255,0.06);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3), inset 0 1px 0 rgba(255,255,255,0.08);
    }

    .bb-form {
        display: flex;
        gap: 0.75rem;
        align-items: stretch;
    }

    .bb-search-box {
        flex: 2;
        position: relative;
    }

    .bb-search-icon {
        position: absolute;
        left: 1.1rem;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(255,255,255,0.35);
        font-size: 1rem;
        pointer-events: none;
    }

    .bb-input {
        width: 100%;
        padding: 0.95rem 1rem 0.95rem 3rem;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 12px;
        color: #ffffff;
        font-size: 0.95rem;
        outline: none;
        transition: all 0.3s;
        box-sizing: border-box;
    }

    .bb-input::placeholder { color: rgba(255,255,255,0.35); }

    .bb-input:focus {
        background: rgba(255,255,255,0.12);
        border-color: rgba(245,158,11,0.6);
        box-shadow: 0 0 0 3px rgba(245,158,11,0.15);
    }

    .bb-select-box { flex: 1; }

    .bb-select {
        width: 100%;
        height: 100%;
        padding: 0.95rem 1rem;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 12px;
        color: #ffffff;
        font-size: 0.95rem;
        outline: none;
        cursor: pointer;
        transition: all 0.3s;
        appearance: none;
        box-sizing: border-box;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='rgba(255,255,255,0.5)' stroke-width='2'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        padding-right: 2.5rem;
    }

    .bb-select option { background: #0e1e38; color: #ffffff; }

    .bb-select:focus {
        border-color: rgba(245,158,11,0.6);
        box-shadow: 0 0 0 3px rgba(245,158,11,0.15);
    }

    .bb-btn-search {
        padding: 0.95rem 1.8rem;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border: none;
        border-radius: 12px;
        color: #0a1628;
        font-weight: 700;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s;
        white-space: nowrap;
        box-shadow: 0 4px 15px rgba(245,158,11,0.3);
        letter-spacing: 0.02em;
    }

    .bb-btn-search:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(245,158,11,0.45);
    }

    .bb-btn-reset {
        padding: 0.95rem 1.4rem;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 12px;
        color: rgba(255,255,255,0.7);
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s;
        white-space: nowrap;
    }

    .bb-btn-reset:hover {
        background: rgba(255,255,255,0.14);
        color: #ffffff;
    }

    /* Active filter tag */
    .bb-active-filter {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 1rem;
        flex-wrap: wrap;
    }

    .bb-filter-label {
        font-size: 0.78rem;
        color: rgba(255,255,255,0.45);
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .bb-filter-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(245,158,11,0.15);
        border: 1px solid rgba(245,158,11,0.3);
        border-radius: 50px;
        padding: 3px 12px 3px 10px;
        font-size: 0.8rem;
        color: #f59e0b;
        font-weight: 600;
    }

    /* ============================================================
       RESULTS SECTION
    ============================================================ */
    .bb-results {
        max-width: 1200px;
        margin: 3.5rem auto 0;
        padding: 0 24px;
    }

    .bb-results-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
    }

    .bb-results-count {
        font-size: 0.85rem;
        color: rgba(255,255,255,0.45);
        letter-spacing: 0.05em;
    }

    .bb-results-count strong {
        color: #f59e0b;
        font-weight: 700;
    }

    /* ============================================================
       CARD GRID
    ============================================================ */
    .bb-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(330px, 1fr));
        gap: 2rem;
    }

    /* Card Layout */
    .bb-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .bb-card::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 20px;
        background: linear-gradient(135deg, rgba(245,158,11,0.08) 0%, transparent 60%);
        opacity: 0;
        transition: opacity 0.35s;
        pointer-events: none;
    }

    .bb-card:hover {
        transform: translateY(-8px);
        border-color: rgba(245,158,11,0.35);
        box-shadow:
            0 25px 50px rgba(0,0,0,0.4),
            0 0 0 1px rgba(245,158,11,0.15),
            inset 0 1px 0 rgba(255,255,255,0.08);
    }

    .bb-card:hover::before { opacity: 1; }

    /* Top glowing strip */
    .bb-card-strip {
        height: 4px;
        background: linear-gradient(90deg, #f59e0b, #fbbf24, #f59e0b);
        background-size: 200% auto;
        animation: shine 3s linear infinite;
    }

    .bb-card-body {
        padding: 2rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .bb-card-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .bb-card-icon {
        width: 48px;
        height: 48px;
        min-width: 48px;
        background: linear-gradient(135deg, rgba(245,158,11,0.2), rgba(245,158,11,0.05));
        border: 1px solid rgba(245,158,11,0.25);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }

    .bb-card-name {
        font-size: 1.25rem;
        font-weight: 700;
        color: #ffffff;
        line-height: 1.35;
        flex: 1;
        margin: 0;
    }

    .bb-available-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: rgba(34,197,94,0.12);
        border: 1px solid rgba(34,197,94,0.3);
        border-radius: 50px;
        padding: 5px 12px;
        font-size: 0.72rem;
        font-weight: 700;
        color: #4ade80;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        white-space: nowrap;
    }

    .bb-available-dot {
        width: 5px; height: 5px;
        background: #4ade80;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    .bb-card-info {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        flex: 1;
    }

    .bb-info-line {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        font-size: 0.9rem;
        color: rgba(255,255,255,0.6);
        line-height: 1.5;
    }

    .bb-info-line-icon {
        font-size: 1rem;
        margin-top: 0.1rem;
        min-width: 20px;
        color: #f59e0b;
    }

    .bb-info-line-text {
        color: rgba(255,255,255,0.7);
    }

    .bb-card-divider {
        height: 1px;
        background: rgba(255,255,255,0.07);
        margin: 1.5rem 0;
    }

    .bb-card-footer {
        padding: 0 2rem 2rem;
    }

    .bb-btn-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 0.95rem 1rem;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.95rem;
        color: #0a1628 !important;
        text-decoration: none;
        letter-spacing: 0.02em;
        box-shadow: 0 4px 15px rgba(245,158,11,0.25);
        transition: all 0.3s;
    }

    .bb-btn-link:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(245,158,11,0.45);
        color: #0a1628 !important;
    }

    .bb-btn-link svg {
        transition: transform 0.3s;
    }
    .bb-btn-link:hover svg {
        transform: translateX(3px);
    }

    .bb-no-link {
        display: block;
        text-align: center;
        padding: 0.95rem;
        border: 1px dashed rgba(255,255,255,0.12);
        border-radius: 12px;
        color: rgba(255,255,255,0.25);
        font-size: 0.85rem;
    }

    /* ============================================================
       EMPTY STATE
    ============================================================ */
    .bb-empty {
        text-align: center;
        padding: 6rem 1rem;
    }

    .bb-empty-icon {
        font-size: 4rem;
        margin-bottom: 1.5rem;
        opacity: 0.35;
    }

    .bb-empty-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: rgba(255,255,255,0.5);
        margin-bottom: 0.5rem;
    }

    .bb-empty-sub {
        font-size: 0.95rem;
        color: rgba(255,255,255,0.25);
    }

    /* ============================================================
       PAGINATION
    ============================================================ */
    .bb-pagination {
        display: flex;
        justify-content: center;
        margin-top: 3.5rem;
    }

    /* ============================================================
       FOOTER LINK
    ============================================================ */
    .bb-back {
        text-align: center;
        margin-top: 4rem;
    }

    .bb-back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: rgba(255,255,255,0.4) !important;
        font-size: 0.95rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s;
    }

    .bb-back-link:hover {
        color: #f59e0b !important;
    }

    /* ============================================================
       FADE-IN ANIMATION
    ============================================================ */
    .bb-card {
        animation: fadeUp 0.6s ease both;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(30px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 768px) {
        .bb-hero { padding: 110px 20px 40px; }
        .bb-stats { gap: 1.5rem; }
        .bb-stat-num { font-size: 1.5rem; }
        .bb-form { flex-direction: column; }
        .bb-grid { grid-template-columns: 1fr; }
        .bb-results-header { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
    }
</style>

{{-- Animated Background --}}
<div class="bb-bg"></div>

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
            Temukan lokasi billboard strategis di berbagai kota. Semua billboard yang tampil di sini siap untuk dimanfaatkan.
        </p>

        {{-- Stats --}}
        @if($billboards->total() > 0)
        <div class="bb-stats">
            <div class="bb-stat">
                <span class="bb-stat-num">{{ $billboards->total() }}</span>
                <span class="bb-stat-label">Billboard Aktif</span>
            </div>
            <div class="bb-divider"></div>
            <div class="bb-stat">
                <span class="bb-stat-num">{{ $cities->count() }}</span>
                <span class="bb-stat-label">Kota Tercakup</span>
            </div>
            <div class="bb-divider"></div>
            <div class="bb-stat">
                <span class="bb-stat-num">100%</span>
                <span class="bb-stat-label">Tersedia</span>
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
                <button type="submit" class="bb-btn-search">Cari</button>
                @if(request()->filled('search') || request()->filled('city'))
                    <a href="{{ route('bilboard.index') }}" class="bb-btn-reset">✕ Reset</a>
                @endif
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
                            <div class="bb-card-header">
                                <div style="display:flex; align-items:flex-start; gap:0.85rem; flex:1;">
                                    <div class="bb-card-icon">🗺️</div>
                                    <h2 class="bb-card-name">{{ $board->name }}</h2>
                                </div>
                                <span class="bb-available-badge">
                                    <span class="bb-available-dot"></span>
                                    Tersedia
                                </span>
                            </div>

                            <div class="bb-card-info">
                                <div class="bb-info-line">
                                    <span class="bb-info-line-icon">🏙️</span>
                                    <span class="bb-info-line-text">{{ $board->city }}</span>
                                </div>
                                <div class="bb-info-line">
                                    <span class="bb-info-line-icon">📌</span>
                                    <span class="bb-info-line-text">{{ $board->address }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bb-card-divider" style="margin: 0 2rem;"></div>

                        <div class="bb-card-footer" style="padding-top:1.25rem;">
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
                <div class="bb-empty-sub">Coba ubah kata kunci atau pilih kota yang berbeda</div>
            </div>
        @endif

    </div>

    {{-- Back --}}
    <div class="bb-back">
        <a href="{{ route('home') }}" class="bb-back-link">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
            Kembali ke Beranda
        </a>
    </div>

</div>
@endsection
