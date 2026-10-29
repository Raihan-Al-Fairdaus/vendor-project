@extends('layouts.public')

@section('title', 'List Billboard — DNA Advertising')

@section('content')
<style>
    /* ============================================================
       GLOBAL OVERRIDE & FONTS
    ============================================================ */
    html, body {
        font-family: 'Inter', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #0a1628 !important;
        min-height: 100dvh;
        overscroll-behavior-y: none;
}

    /* ============================================================
       DYNAMIC DISSOLVE FLOWING GRADIENT BACKGROUND
    ============================================================ */
    html, body {
        background-color: #1b3a60 !important;
        /* overscroll-behavior: none; */ /* Opsi jika ingin mematikan efek bounce, tapi biarkan saja default dan fix backgroundnya */
        overscroll-behavior-y: none;
}
    .bb-bg-container {
        position: fixed;
        top: -150px;
        bottom: -150px;
        left: -50px;
        right: -50px;
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
        min-height: 100dvh;
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
            url('/images/billboard-header.webp');
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
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex: 1 1 calc(50% - 1rem);
        min-width: 140px;
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

    .bb-card-thumb-wrap {
        padding: 12px 14px 0;
    }

    .bb-card-thumb {
        width: 100%;
        height: 110px;
        object-fit: cover;
        border-radius: 12px;
        display: block;
    }

    .bb-card-body {
        padding: 1.25rem 1.75rem 1.75rem;
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
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
    }

    .bb-btn-row {
        display: flex;
        gap: 0.5rem;
        align-items: stretch;
    }

    .bb-btn-row > * {
        flex: 1;
    }

    .bb-btn-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        width: 100%;
        padding: 0.85rem 1rem;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.9rem;
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

    .bb-btn-simulasi {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        width: 100%;
        padding: 0.85rem 1rem;
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.9rem;
        color: #ffffff !important;
        cursor: pointer;
        transition: all 0.25s ease;
        box-sizing: border-box;
    }

    .bb-btn-simulasi:hover {
        background: rgba(255, 255, 255, 0.22);
        border-color: rgba(255, 255, 255, 0.4);
        transform: translateY(-1px);
    }

    .bb-btn-wa {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 0.85rem 1rem;
        background: #25D366;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.9rem;
        color: #ffffff !important;
        text-decoration: none;
        letter-spacing: 0.01em;
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
        transition: all 0.25s ease;
        box-sizing: border-box;
    }

    .bb-btn-wa:hover {
        background: #20bd5a;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(37, 211, 102, 0.45);
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

    /* OPTIMASI: Matikan animasi background yang sangat berat di mobile agar backdrop-filter tidak menyebabkan frame drop */
    @media (max-width: 991px) {
        .bb-bg-gradient, .bb-glow-blob-1, .bb-glow-blob-2, .bb-wrapper {
            animation: none !important;
        }
        .bb-item-card, .bb-search-card {
            /* Fallback rendering filter jika GPU kesulitan */
            -webkit-backdrop-filter: none !important;
            backdrop-filter: none !important;
            background: rgba(30, 58, 138, 0.7) !important; /* warna fallback yang solid/gelap */
        }
    }

    /* ============================================================
       SIMULATION MODAL VIEWER (COMPACT FRAME & MOBILE OPTIMIZED)
    ============================================================ */
    .bb-sim-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(10, 20, 35, 0.78);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 99999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        box-sizing: border-box;
        opacity: 0;
        transition: opacity 0.22s ease;
    }
    .bb-sim-backdrop.is-active {
        display: flex;
        opacity: 1;
    }
    .bb-sim-frame {
        background: #1b3a60; /* Pinggiran bingkai #1b3a60 */
        border-radius: 12px;
        box-shadow: 0 20px 50px -10px rgba(0, 0, 0, 0.55), 0 0 0 1px rgba(255, 255, 255, 0.15);
        width: 90vw;
        max-width: 720px; /* Tidak terlalu besar / 1 layar penuh */
        height: auto;
        max-height: 85vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-sizing: border-box;
        animation: bbSimPop 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        margin: auto;
    }
    @keyframes bbSimPop {
        from {
            transform: scale(0.96) translateY(8px);
            opacity: 0;
        }
        to {
            transform: scale(1) translateY(0);
            opacity: 1;
        }
    }
    .bb-sim-header {
        background: #1b3a60;
        padding: 0.65rem 1.1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        color: #ffffff;
        user-select: none;
        flex-shrink: 0;
    }
    .bb-sim-header-left {
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 0;
        flex: 1;
    }
    .bb-sim-header-badge {
        background: rgba(245, 158, 11, 0.2);
        color: #fbbf24;
        font-weight: 700;
        font-size: 0.7rem;
        padding: 2px 7px;
        border-radius: 4px;
        letter-spacing: 0.04em;
        border: 1px solid rgba(245, 158, 11, 0.3);
        white-space: nowrap;
        flex-shrink: 0;
    }
    .bb-sim-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: #ffffff;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .bb-sim-header-right {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
    }
    .bb-sim-zoom-controls {
        display: flex;
        align-items: center;
        gap: 3px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 5px;
        padding: 2px 5px;
    }
    .bb-sim-zoom-btn {
        background: transparent;
        border: none;
        color: #ffffff;
        font-size: 1rem;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border-radius: 4px;
        transition: background 0.15s;
        line-height: 1;
    }
    .bb-sim-zoom-btn:hover {
        background: rgba(255, 255, 255, 0.18);
    }
    .bb-sim-zoom-label {
        font-size: 0.78rem;
        font-weight: 600;
        min-width: 40px;
        text-align: center;
        color: rgba(255, 255, 255, 0.9);
        cursor: pointer;
    }
    .bb-sim-page-badge {
        font-size: 0.8rem;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.95);
        background: rgba(255, 255, 255, 0.08);
        padding: 3px 8px;
        border-radius: 5px;
        white-space: nowrap;
    }
    .bb-sim-close-btn {
        background: transparent;
        border: none;
        color: #ffffff;
        font-size: 1.2rem;
        width: 28px;
        height: 28px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s;
        line-height: 1;
    }
    .bb-sim-close-btn:hover {
        background: rgba(239, 68, 68, 0.85);
    }
    .bb-sim-stage {
        background: #f8fafc; /* Tema terang meja baca */
        overflow: auto;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        position: relative;
        box-sizing: border-box;
        max-height: 430px;
        min-height: 200px;
    }
    .bb-sim-canvas-paper {
        background: #ffffff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12), 0 0 0 1px rgba(0, 0, 0, 0.06);
        border-radius: 4px;
        display: block;
        margin: auto;
    }
    .bb-sim-img-view {
        max-width: 100%;
        max-height: 400px;
        width: auto;
        object-fit: contain;
        background: #ffffff;
        border-radius: 4px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
        display: block;
        margin: auto;
        transition: transform 0.2s ease;
    }
    .bb-sim-video-view {
        max-width: 100%;
        max-height: 400px;
        border-radius: 8px;
        background: #000000;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        display: block;
        margin: auto;
    }
    .bb-sim-iframe-view {
        width: 100%;
        height: 380px;
        border: none;
        background: #ffffff;
        border-radius: 4px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
    }
    .bb-sim-spinner-wrap {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        color: #475569;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 2rem;
    }
    .bb-sim-spinner {
        width: 32px;
        height: 32px;
        border: 3px solid #cbd5e1;
        border-top-color: #1b3a60;
        border-radius: 50%;
        animation: bbSimSpin 0.8s linear infinite;
    }
    @keyframes bbSimSpin {
        to { transform: rotate(360deg); }
    }
    .bb-sim-footer {
        background: #1b3a60; /* Pinggiran footer #1b3a60 */
        padding: 0.75rem 1.1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        border-top: 1px solid rgba(255, 255, 255, 0.12);
        user-select: none;
        flex-shrink: 0;
    }
    .bb-sim-footer-loc {
        display: flex;
        align-items: center;
        gap: 6px;
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.8rem;
        max-width: 220px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .bb-sim-nav-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    /* Tombol navigasi putih teks hitam sesuai instruksi */
    .bb-sim-nav-btn {
        background: #ffffff !important;
        color: #000000 !important;
        font-weight: 600;
        font-size: 0.84rem;
        border: none;
        border-radius: 6px;
        padding: 0.45rem 1rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        transition: all 0.2s ease;
        white-space: nowrap;
    }
    .bb-sim-nav-btn:hover:not(:disabled) {
        background: #f8fafc !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
    }
    .bb-sim-nav-btn:disabled {
        opacity: 0.38 !important;
        cursor: not-allowed !important;
        transform: none !important;
        box-shadow: none !important;
    }
    .bb-sim-wa-btn {
        background: #25D366;
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.84rem;
        padding: 0.45rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        box-shadow: 0 2px 8px rgba(37, 211, 102, 0.35);
        transition: all 0.2s;
        white-space: nowrap;
    }
    .bb-sim-wa-btn:hover {
        background: #20bd5a;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.45);
    }

    /* ============================================================
       RESPONSIVE HP / MOBILE OPTIMIZATION
    ============================================================ */
    @media (max-width: 640px) {
        .bb-sim-backdrop {
            padding: 0.75rem 0.5rem;
        }
        .bb-sim-frame {
            width: 100%;
            max-width: 100%;
            max-height: 92vh;
            border-radius: 10px;
        }
        .bb-sim-header {
            padding: 0.55rem 0.75rem;
            gap: 6px;
        }
        .bb-sim-header-left {
            gap: 6px;
        }
        .bb-sim-header-badge {
            font-size: 0.65rem;
            padding: 2px 5px;
        }
        .bb-sim-title {
            font-size: 0.8rem;
            max-width: 130px;
        }
        .bb-sim-header-right {
            gap: 6px;
        }
        .bb-sim-zoom-controls {
            display: none; /* Hide micro zoom buttons on phone so header doesn't overflow */
        }
        .bb-sim-page-badge {
            font-size: 0.75rem;
            padding: 2px 6px;
        }
        .bb-sim-close-btn {
            width: 26px;
            height: 26px;
            font-size: 1.1rem;
        }
        .bb-sim-stage {
            padding: 0.6rem;
            max-height: 48vh;
            min-height: 180px;
        }
        .bb-sim-canvas-paper,
        .bb-sim-img-view,
        .bb-sim-video-view {
            max-height: 46vh;
        }
        .bb-sim-footer {
            padding: 0.65rem 0.75rem;
            flex-direction: column;
            gap: 8px;
        }
        .bb-sim-footer-loc {
            max-width: 100%;
            justify-content: center;
            font-size: 0.75rem;
            text-align: center;
        }
        .bb-sim-nav-group {
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        .bb-sim-nav-btn {
            width: 100%;
            padding: 0.5rem;
            font-size: 0.8rem;
        }
        .bb-sim-wa-btn {
            width: 100%;
            padding: 0.55rem;
            font-size: 0.82rem;
            box-sizing: border-box;
        }
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
                            <span class="bb-stat-value">{{ $billboardCount }}</span>
                            <span class="bb-stat-label">Billboard</span>
                        </div>
                    </div>
                    <div class="bb-stat-card">
                        <div class="bb-stat-icon" style="background: rgba(245,158,11,0.15); color: #f59e0b;">
                            <i class="fa-solid fa-tv"></i>
                        </div>
                        <div class="bb-stat-info">
                            <span class="bb-stat-value">{{ $midiboardCount }}</span>
                            <span class="bb-stat-label">Midiboard</span>
                        </div>
                    </div>
                    <div class="bb-stat-card">
                        <div class="bb-stat-icon">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div class="bb-stat-info">
                            <span class="bb-stat-value">{{ count($cityCounts) }}</span>
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
                        placeholder="Cari kode, alamat billboard..."
                        value="{{ request('search') }}"
                        class="bb-input"
                    >
                </div>
                <div class="bb-select-box">
                    <span class="bb-select-icon">📍</span>
                    <select name="city" onchange="this.form.submit()" class="bb-select">
                        <option value="">Semua Kota</option>
                        @foreach($cityCounts as $cityName => $counts)
                            @php
                                $bb = $counts['billboard'] ?? 0;
                                $mb = $counts['midiboard'] ?? 0;
                                $parts = [];
                                if ($bb > 0) $parts[] = $bb . ' BB';
                                if ($mb > 0) $parts[] = $mb . ' MB';
                                $label = strtoupper($cityName) . ' (' . implode(' · ', $parts) . ')';
                            @endphp
                            <option value="{{ $cityName }}" {{ request('city') === $cityName ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="bb-select-box">
                    <span class="bb-select-icon">📋</span>
                    <select name="jenis" onchange="this.form.submit()" class="bb-select">
                        <option value="">Semua Jenis</option>
                        <option value="billboard" {{ request('jenis') === 'billboard' ? 'selected' : '' }}>Billboard</option>
                        <option value="midiboard" {{ request('jenis') === 'midiboard' ? 'selected' : '' }}>Midiboard</option>
                    </select>
                </div>
                <div class="bb-select-box">
                    <span class="bb-select-icon">📐</span>
                    <select name="orientasi" onchange="this.form.submit()" class="bb-select">
                        <option value="">Semua Orientasi</option>
                        <option value="portrait" {{ request('orientasi') === 'portrait' ? 'selected' : '' }}>Portrait</option>
                        <option value="landscape" {{ request('orientasi') === 'landscape' ? 'selected' : '' }}>Landscape</option>
                    </select>
                </div>
                <div class="bb-action-group">
                    <button type="submit" class="bb-btn-search">Cari</button>
                    @if(request()->filled('search') || request()->filled('city') || request()->filled('jenis') || request()->filled('orientasi'))
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
                    @php
                        $photoMap = [
                            '#002-BAL-I' => 'billboard-1.jpg',
                            '#003-BAL-I' => 'billboard-2.jpg',
                            '#006-BDG-II' => 'billboard-3.jpg',
                            '#005-BDG-I' => 'billboard-4.jpg',
                            '#0012-BDG-I' => 'billboard-5.jpg',
                            '#007-BDG-I' => 'billboard-6.jpg',
                        ];
                        $photoName = $photoMap[$board->code] ?? ('billboard-' . ((($loop->iteration - 1) % 6) + 1) . '.jpg');
                    @endphp
                    <div class="bb-item-card">
                        <div class="bb-card-strip"></div>
                        <div class="bb-card-thumb-wrap">
                            <img src="{{ asset('images/billboards/' . $photoName) }}" alt="{{ $board->code }}" class="bb-card-thumb" loading="lazy">
                        </div>
                        <div class="bb-card-body">
                            {{-- Badges row --}}
                            <div class="bb-badge-row">
                                <span class="bb-available-badge">
                                    <span class="bb-available-dot"></span>
                                    Tersedia
                                </span>
                                <span class="bb-city-tag">🗺️ {{ $board->city }}</span>
                                <span class="bb-city-tag" style="background: rgba(245,158,11,0.15); color: #d97706;">
                                    {{ ucfirst($board->jenis ?? 'billboard') }}
                                </span>
                            </div>

                            {{-- Title: code as main identifier --}}
                            <h3 class="bb-card-name">{{ ucfirst($board->jenis ?? 'Billboard') }} {{ $board->code }}</h3>

                            <div class="bb-card-divider"></div>

                            {{-- Detail info --}}
                            @if($board->ukuran)
                            <div class="bb-info-line">
                                <span class="bb-info-line-icon">📐</span>
                                <span class="bb-info-line-text">{{ $board->ukuran }} · {{ ucfirst($board->orientasi ?? 'landscape') }}</span>
                            </div>
                            @endif

                            <div class="bb-info-line">
                                <span class="bb-info-line-icon">📍</span>
                                <span class="bb-info-line-text">{{ $board->address }}</span>
                            </div>

                            @if($board->kepemilikan)
                            <div class="bb-info-line">
                                <span class="bb-info-line-icon">🏢</span>
                                <span class="bb-info-line-text">{{ $board->kepemilikan }} · Sisi {{ $board->sisi ?? 1 }}</span>
                            </div>
                            @endif
                        </div>

                        {{-- Footer CTA buttons --}}
                        <div class="bb-card-footer">
                            <div class="bb-btn-row">
                                @if($board->google_maps_url)
                                    <a href="{{ $board->google_maps_url }}" target="_blank" rel="noopener noreferrer" class="bb-btn-link">
                                        Lihat Lokasi
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                    </a>
                                @else
                                    <span class="bb-no-gps-link">Lokasi belum diatur</span>
                                @endif

                                @if($board->simulation_path)
                                    <button type="button" class="bb-btn-simulasi" onclick="openSimulationModal('{{ addslashes($board->code) }}', '{{ addslashes($board->jenis ?? 'Billboard') }}', '{{ addslashes($board->address) }}', '{{ $board->simulation_file_url }}', '{{ $board->simulation_kind }}', '{{ rawurlencode('Halo DNA Advertising, saya ingin bertanya mengenai billboard dengan kode ' . $board->code . '. Mohon informasi mengenai harga sewa, ketersediaan, lokasi, dan detail billboard tersebut. Terima kasih.') }}')">
                                        <span>👁️</span> Lihat Simulasi
                                    </button>
                                @endif
                            </div>

                            @php
                                $waMessage = 'Halo DNA Advertising, saya ingin bertanya mengenai billboard dengan kode ' . $board->code . '. Mohon informasi mengenai harga sewa, ketersediaan, lokasi, dan detail billboard tersebut. Terima kasih.';
                                $waUrl = 'https://wa.me/6287798018647?text=' . rawurlencode($waMessage);
                            @endphp
                            <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="bb-btn-wa">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                <span>Tanya Billboard Ini via WhatsApp</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bb-pagination-wrap">
                <style>
                    .custom-pagination-container {
                        background: rgba(255, 255, 255, 0.95);
                        border-radius: 50px;
                        padding: 0.5rem 1.5rem;
                        display: flex;
                        max-width: 100%;
                        overflow-x: auto;
                        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                        margin-top: 2rem;
                        -ms-overflow-style: none;
                        scrollbar-width: none;
                    }
                    .custom-pagination-container::-webkit-scrollbar {
                        display: none;
                    }
                    .custom-pagination {
                        display: flex;
                        align-items: center;
                        gap: 1rem;
                        list-style: none;
                        margin: 0;
                        padding: 0;
                    }
                    .custom-pagination .mypill-item {
                        margin: 0;
                    }
                    .custom-pagination .mypill-link {
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        color: #1f2937;
                        font-weight: 600;
                        text-decoration: none;
                        border: none;
                        background: transparent;
                        min-width: 35px;
                        height: 35px;
                        border-radius: 50%;
                        transition: all 0.2s;
                        font-size: 0.95rem;
                        cursor: pointer;
                    }
                    .custom-pagination .mypill-link:hover {
                        color: #f59e0b;
                        background: rgba(245, 158, 11, 0.1);
                    }
                    .custom-pagination .mypill-item.active .mypill-link {
                        background: #f59e0b;
                        color: #ffffff;
                        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.4);
                    }
                    .custom-pagination .mypill-item.disabled .mypill-link {
                        color: #9ca3af;
                        cursor: not-allowed;
                        background: transparent;
                    }
                    .custom-pagination li:first-child .mypill-link,
                    .custom-pagination li:last-child .mypill-link {
                        border-radius: 20px;
                        padding: 0 0.75rem;
                        width: auto;
                        color: #f59e0b;
                    }
                    .custom-pagination li:first-child.disabled .mypill-link,
                    .custom-pagination li:last-child.disabled .mypill-link {
                        color: #9ca3af;
                    }
                </style>
                {{ $billboards->appends(request()->query())->links('admin.billboards.pagination') }}
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


    {{-- SIMULATION MODAL VIEWER --}}
    <div id="simBackdrop" class="bb-sim-backdrop" onclick="handleSimBackdropClick(event)">
        <div class="bb-sim-frame" role="dialog" aria-modal="true" aria-labelledby="simTitleText">
            {{-- Header --}}
            <div class="bb-sim-header">
                <div class="bb-sim-header-left">
                    <span class="bb-sim-header-badge">SIMULASI</span>
                    <span id="simTitleText" class="bb-sim-title">Billboard</span>
                </div>
                <div class="bb-sim-header-right">
                    <div class="bb-sim-zoom-controls">
                        <button type="button" class="bb-sim-zoom-btn" onclick="simZoomOut()" title="Perkecil">−</button>
                        <span id="simZoomLabel" class="bb-sim-zoom-label" onclick="simResetZoom()" title="Klik untuk Reset Zoom (100%)">100%</span>
                        <button type="button" class="bb-sim-zoom-btn" onclick="simZoomIn()" title="Perbesar">+</button>
                    </div>
                    <span id="simPageBadge" class="bb-sim-page-badge">1 / 1</span>
                    <button type="button" class="bb-sim-close-btn" onclick="closeSimulationModal()" title="Tutup Simulasi" aria-label="Tutup">✕</button>
                </div>
            </div>

            {{-- Body (Bright Desk Stage) --}}
            <div class="bb-sim-stage" id="simStage">
                <canvas id="simCanvas" class="bb-sim-canvas-paper" style="display: none;"></canvas>
                <div id="simMediaWrap" style="display: none;"></div>
                <div id="simLoader" class="bb-sim-spinner-wrap" style="display: none;">
                    <div class="bb-sim-spinner"></div>
                    <span>Memuat file simulasi...</span>
                </div>
            </div>

            {{-- Footer (Navigation & WA) --}}
            <div class="bb-sim-footer">
                <div class="bb-sim-footer-loc">
                    <span>📍</span>
                    <span id="simAddressText" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">-</span>
                </div>
                <div class="bb-sim-nav-group">
                    <button type="button" id="simPrevBtn" class="bb-sim-nav-btn" onclick="simGoPrev()">
                        ← Sebelumnya
                    </button>
                    <button type="button" id="simNextBtn" class="bb-sim-nav-btn" onclick="simGoNext()">
                        Selanjutnya →
                    </button>
                </div>
                <a href="#" id="simWaBtn" target="_blank" rel="noopener noreferrer" class="bb-sim-wa-btn">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                    </svg>
                    <span>Tanya via WhatsApp</span>
                </a>
            </div>
        </div>
    </div>

    {{-- PDF.js for rendering PDF documents directly with pagination --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
    if (typeof window.pdfjsLib !== 'undefined') {
        window.pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    }

    var currentPdf = null;
    var currentTotalPages = 1;
    var currentPageNum = 1;
    var currentZoom = 1.0;
    var isRendering = false;
    var pendingPageNum = null;
    var currentRenderTask = null;
    var currentMediaKind = 'image';
    var currentMediaUrl = '';

    function openSimulationModal(code, jenis, address, mediaUrl, mediaKind, waTextEncoded) {
        var modal = document.getElementById('simBackdrop');
        var title = document.getElementById('simTitleText');
        var addressEl = document.getElementById('simAddressText');
        var waBtn = document.getElementById('simWaBtn');
        var pageBadge = document.getElementById('simPageBadge');

        title.textContent = (jenis || 'Billboard') + ' ' + code;
        addressEl.textContent = address || 'Lokasi tidak tersedia';
        waBtn.href = 'https://wa.me/6287798018647?text=' + waTextEncoded;

        currentMediaKind = mediaKind || 'image';
        currentMediaUrl = mediaUrl;
        currentZoom = 1.0;
        updateZoomDisplay();

        // Reset stage elements
        var canvas = document.getElementById('simCanvas');
        if (canvas) {
            canvas.style.display = 'none';
            var ctx = canvas.getContext('2d');
            if (ctx) ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
        var mediaWrap = document.getElementById('simMediaWrap');
        mediaWrap.style.display = 'none';
        mediaWrap.innerHTML = '';
        var loader = document.getElementById('simLoader');
        loader.style.display = 'flex';

        modal.classList.add('is-active');
        document.body.style.overflow = 'hidden';

        if (currentMediaKind === 'pdf') {
            loadPdfDocument(mediaUrl);
        } else if (currentMediaKind === 'video') {
            loader.style.display = 'none';
            mediaWrap.style.display = 'flex';
            mediaWrap.innerHTML = '<video src="' + mediaUrl + '" class="bb-sim-video-view" controls autoplay playsinline></video>';
            currentTotalPages = 1;
            currentPageNum = 1;
            pageBadge.textContent = 'Video';
            updateNavControls();
        } else {
            // Image
            loader.style.display = 'none';
            mediaWrap.style.display = 'flex';
            var img = document.createElement('img');
            img.src = mediaUrl;
            img.alt = 'Simulasi ' + code;
            img.className = 'bb-sim-img-view';
            mediaWrap.appendChild(img);
            currentTotalPages = 1;
            currentPageNum = 1;
            pageBadge.textContent = '1 / 1';
            updateNavControls();
        }
    }

    function fallbackToIframe(url) {
        var loader = document.getElementById('simLoader');
        var canvas = document.getElementById('simCanvas');
        if (loader) loader.style.display = 'none';
        if (canvas) canvas.style.display = 'none';
        var mediaWrap = document.getElementById('simMediaWrap');
        if (mediaWrap) {
            mediaWrap.style.display = 'flex';
            mediaWrap.innerHTML = '<iframe src="' + url + '#toolbar=0" class="bb-sim-iframe-view"></iframe>';
        }
        var pageBadge = document.getElementById('simPageBadge');
        if (pageBadge) pageBadge.textContent = 'Dokumen PDF';
        updateNavControls();
    }

    function loadPdfDocument(url) {
        var loader = document.getElementById('simLoader');
        var canvas = document.getElementById('simCanvas');

        if (typeof pdfjsLib === 'undefined') {
            fallbackToIframe(url);
            return;
        }

        // Reset canvas state
        if (canvas) canvas.style.display = 'none';

        pdfjsLib.getDocument({
            url: url,
            cMapUrl: 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/cmaps/',
            cMapPacked: true
        }).promise.then(function(pdf) {
            currentPdf = pdf;
            currentTotalPages = pdf.numPages || 1;
            currentPageNum = 1;
            if (loader) loader.style.display = 'none';
            if (canvas) canvas.style.display = 'block';
            renderPdfPage(currentPageNum);
        }).catch(function(err) {
            console.error('PDF.js getDocument error, falling back to iframe:', err);
            fallbackToIframe(url);
        });
    }

    function renderPdfPage(num) {
        if (!currentPdf) return;

        // Batalkan render sebelumnya jika masih berjalan
        if (currentRenderTask) {
            try {
                currentRenderTask.cancel();
            } catch (e) {}
            currentRenderTask = null;
        }

        isRendering = true;

        currentPdf.getPage(num).then(function(page) {
            var stage = document.getElementById('simStage');
            var canvas = document.getElementById('simCanvas');
            if (!stage || !canvas) {
                isRendering = false;
                return;
            }
            var ctx = canvas.getContext('2d');

            // Ambil dimensi asli halaman PDF
            var unscaled = page.getViewport({ scale: 1.0 });

            // Hitung ukuran yang pas untuk stage
            var stageW = stage.clientWidth || 600;
            var availableW = Math.max(260, stageW - 32);
            var availableH = Math.max(200, 390);

            var scaleW = availableW / unscaled.width;
            var scaleH = availableH / unscaled.height;
            var baseScale = Math.min(scaleW, scaleH);
            var finalScale = Math.max(0.2, baseScale * currentZoom);

            var viewport = page.getViewport({ scale: finalScale });

            // Dukungan tampilan High-DPI / Retina & Smartphone
            var outputScale = window.devicePixelRatio || 1;
            canvas.width = Math.floor(viewport.width * outputScale);
            canvas.height = Math.floor(viewport.height * outputScale);
            canvas.style.width = Math.floor(viewport.width) + 'px';
            canvas.style.height = Math.floor(viewport.height) + 'px';

            var transform = outputScale !== 1
                ? [outputScale, 0, 0, outputScale, 0, 0]
                : null;

            var renderContext = {
                canvasContext: ctx,
                transform: transform,
                viewport: viewport
            };

            currentRenderTask = page.render(renderContext);
            currentRenderTask.promise.then(function() {
                currentRenderTask = null;
                isRendering = false;
                if (pendingPageNum !== null) {
                    var next = pendingPageNum;
                    pendingPageNum = null;
                    renderPdfPage(next);
                }
            }).catch(function(err) {
                currentRenderTask = null;
                isRendering = false;
                if (err && err.name === 'RenderingCancelledException') {
                    // Normal jika dibatalkan karena ganti halaman / zoom cepat
                    return;
                }
                console.error('Render page error:', err);
            });

            var pageBadge = document.getElementById('simPageBadge');
            if (pageBadge) {
                pageBadge.textContent = num + ' / ' + currentTotalPages;
            }
            updateNavControls();
        }).catch(function(err) {
            console.error('getPage error, fallback to iframe:', err);
            isRendering = false;
            fallbackToIframe(currentMediaUrl);
        });
    }

    function queueRenderPage(num) {
        if (isRendering) {
            pendingPageNum = num;
        } else {
            renderPdfPage(num);
        }
    }

    function simGoNext() {
        if (currentMediaKind === 'pdf' && currentPdf && currentPageNum < currentTotalPages) {
            currentPageNum++;
            queueRenderPage(currentPageNum);
        }
    }

    function simGoPrev() {
        if (currentMediaKind === 'pdf' && currentPdf && currentPageNum > 1) {
            currentPageNum--;
            queueRenderPage(currentPageNum);
        }
    }

    function updateNavControls() {
        var prevBtn = document.getElementById('simPrevBtn');
        var nextBtn = document.getElementById('simNextBtn');
        if (!prevBtn || !nextBtn) return;

        if (currentMediaKind !== 'pdf' || currentTotalPages <= 1) {
            prevBtn.disabled = true;
            nextBtn.disabled = true;
        } else {
            prevBtn.disabled = (currentPageNum <= 1);
            nextBtn.disabled = (currentPageNum >= currentTotalPages);
        }
    }

    function simZoomIn() {
        currentZoom = Math.min(2.5, Math.round((currentZoom + 0.2) * 10) / 10);
        applyZoom();
    }

    function simZoomOut() {
        currentZoom = Math.max(0.5, Math.round((currentZoom - 0.2) * 10) / 10);
        applyZoom();
    }

    function simResetZoom() {
        currentZoom = 1.0;
        applyZoom();
    }

    function applyZoom() {
        updateZoomDisplay();
        if (currentMediaKind === 'pdf' && currentPdf) {
            renderPdfPage(currentPageNum);
        } else if (currentMediaKind === 'image') {
            var img = document.querySelector('#simMediaWrap img');
            if (img) {
                img.style.transform = 'scale(' + currentZoom + ')';
            }
        }
    }

    function updateZoomDisplay() {
        var label = document.getElementById('simZoomLabel');
        if (label) {
            label.textContent = Math.round(currentZoom * 100) + '%';
        }
    }

    function closeSimulationModal() {
        var modal = document.getElementById('simBackdrop');
        var mediaWrap = document.getElementById('simMediaWrap');
        var video = mediaWrap ? mediaWrap.querySelector('video') : null;
        if (video) video.pause();
        if (mediaWrap) mediaWrap.innerHTML = '';
        if (currentRenderTask) {
            try { currentRenderTask.cancel(); } catch(e) {}
            currentRenderTask = null;
        }
        currentPdf = null;
        pendingPageNum = null;
        isRendering = false;
        if (modal) modal.classList.remove('is-active');
        document.body.style.overflow = '';
    }

    function handleSimBackdropClick(event) {
        if (event.target && event.target.id === 'simBackdrop') {
            closeSimulationModal();
        }
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' || event.key === 'Esc') {
            closeSimulationModal();
        } else if (event.key === 'ArrowRight') {
            simGoNext();
        } else if (event.key === 'ArrowLeft') {
            simGoPrev();
        }
    });
    </script>

</div>
@endsection

