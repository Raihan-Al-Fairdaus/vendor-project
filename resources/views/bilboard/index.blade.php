@extends('layouts.public')

@section('title', 'List Billboard')

@section('content')
<style>
    /* Latar Belakang Gradasi */
    body {
        background: linear-gradient(135deg, #1b3a60 0%, #3a587d 50%, #899eb9 100%) !important;
        background-attachment: fixed !important;
    }

    /* Container utama agar turun di bawah fixed navbar */
    .billboard-section {
        padding-top: 120px; /* Jarak aman di bawah fixed navbar */
        padding-bottom: 60px;
        min-height: 80vh;
        width: 90%;
        max-width: 1200px;
        margin: 0 auto;
        box-sizing: border-box;
    }

    .billboard-title {
        color: #ffffff !important;
        font-size: 2.5rem;
        font-weight: 800;
        text-align: center;
        margin-bottom: 2.5rem;
        text-shadow: 0 4px 15px rgba(0,0,0,0.3);
        letter-spacing: 0.05em;
    }

    /* Search & Filter Wrapper */
    .search-filter-card {
        background: rgba(255, 255, 255, 0.12) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 3rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .filter-form {
        display: flex;
        flex-direction: row;
        gap: 1rem;
        align-items: center;
        width: 100%;
    }

    .input-group {
        flex: 2;
        position: relative;
    }

    .select-group {
        flex: 1;
    }

    .action-group {
        display: flex;
        gap: 0.5rem;
    }

    /* Custom Inputs */
    .custom-input, .custom-select {
        width: 100%;
        padding: 0.85rem 1.25rem;
        font-size: 1rem;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        background: rgba(255, 255, 255, 0.95);
        color: #1e293b;
        outline: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    .custom-input:focus, .custom-select:focus {
        border-color: #f59e0b;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.4);
    }

    /* Buttons */
    .btn-search {
        padding: 0.85rem 2rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        background: #f59e0b;
        color: #1e293b;
        box-shadow: 0 4px 10px rgba(245, 158, 11, 0.3);
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .btn-search:hover {
        background: #d97706;
        transform: translateY(-2px);
    }

    .btn-reset {
        padding: 0.85rem 1.5rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.4);
        cursor: pointer;
        background: rgba(255, 255, 255, 0.2);
        color: #ffffff;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
    }

    .btn-reset:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    /* Billboard Grid & Cards */
    .billboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 2rem;
    }

    .billboard-card {
        background: rgba(255, 255, 255, 0.95) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .billboard-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 35px rgba(0, 0, 0, 0.2) !important;
    }

    .billboard-card h2 {
        color: #1b3a60 !important;
        font-size: 1.4rem;
        font-weight: 700;
        margin-top: 0;
        margin-bottom: 0.75rem;
        line-height: 1.3;
    }

    .info-row {
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
        color: #475569;
    }

    .info-row strong {
        color: #1e293b;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: #dcfce7;
        color: #15803d;
        padding: 0.4rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-top: 0.5rem;
        margin-bottom: 1.5rem;
        width: fit-content;
    }

    .btn-maps {
        background: #f59e0b;
        color: #1e293b !important;
        border: none;
        border-radius: 10px;
        padding: 0.85rem 1rem;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        display: block;
        width: 100%;
        box-shadow: 0 4px 10px rgba(245, 158, 11, 0.2);
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    .btn-maps:hover {
        background: #d97706;
        transform: translateY(-2px);
    }

    .no-coordinates {
        color: #94a3b8;
        font-size: 0.9rem;
        text-align: center;
        padding: 0.85rem;
        border: 1px dashed #cbd5e1;
        border-radius: 10px;
    }

    /* Back Link */
    .back-container {
        text-align: center;
        margin-top: 4rem;
    }

    .back-link {
        color: #f59e0b !important;
        font-weight: 700;
        font-size: 1.1rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .back-link:hover {
        color: #ffffff !important;
        text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
    }

    /* Empty state */
    .empty-container {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-message {
        color: #ffffff;
        font-size: 1.25rem;
        text-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }

    /* Responsive styling */
    @media (max-width: 768px) {
        .billboard-section {
            padding-top: 100px;
            width: 95%;
        }

        .billboard-title {
            font-size: 2rem;
            margin-bottom: 2rem;
        }

        .filter-form {
            flex-direction: column;
            gap: 0.75rem;
        }

        .input-group, .select-group, .action-group {
            width: 100%;
        }

        .action-group {
            display: flex;
        }

        .btn-search, .btn-reset {
            flex: 1;
            text-align: center;
        }

        .billboard-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
    }
</style>

<div class="billboard-section">
    <h1 class="billboard-title">List Billboard</h1>

    {{-- Search and Filter Form --}}
    <div class="search-filter-card">
        <form action="{{ route('bilboard.index') }}" method="GET" class="filter-form">
            <div class="input-group">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Cari nama atau alamat billboard..." 
                    value="{{ request('search') }}" 
                    class="custom-input"
                >
            </div>
            <div class="select-group">
                <select name="city" onchange="this.form.submit()" class="custom-select">
                    <option value="">Semua Kota</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>
                            {{ $city }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="action-group">
                <button type="submit" class="btn-search">Cari</button>
                @if(request()->filled('search') || request()->filled('city'))
                    <a href="{{ route('bilboard.index') }}" class="btn-reset">Reset</a>
                @endif
            </div>
        </form>
    </div>

    @if($billboards->count())
        <div class="billboard-grid">
            @foreach($billboards as $board)
                <div class="billboard-card">
                    <div>
                        <h2>{{ $board->name }}</h2>
                        <div class="info-row">
                            <strong>Kota:</strong> {{ $board->city }}
                        </div>
                        <div class="info-row" style="margin-bottom: 1rem;">
                            <strong>Alamat:</strong> {{ $board->address }}
                        </div>
                        <div class="status-badge">
                            <span>🟢</span> Tersedia
                        </div>
                    </div>
                    <div>
                        @if($board->google_maps_url)
                            <a href="{{ $board->google_maps_url }}" target="_blank" class="btn-maps">
                                📍 Open Link
                            </a>
                        @else
                            <div class="no-coordinates">
                                Koordinat belum tersedia
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 3rem; display: flex; justify-content: center;">
            {{ $billboards->links() }}
        </div>
    @else
        <div class="empty-container">
            <p class="empty-message">Tidak ada billboard yang ditemukan.</p>
        </div>
    @endif

    <div class="back-container">
        <a href="{{ route('home') }}" class="back-link">← Kembali ke Beranda</a>
    </div>
</div>
@endsection
