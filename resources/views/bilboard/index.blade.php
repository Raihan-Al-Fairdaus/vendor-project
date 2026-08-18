@extends('layouts.public')

@section('title', 'Billboards Tersedia')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #1b3a60 0%, #3a587d 50%, #899eb9 100%) !important;
        background-attachment: fixed !important;
    }
    .billboard-title {
        color: #ffffff !important;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }
    .billboard-card {
        background: rgba(255, 255, 255, 0.95) !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
        color: #1e293b !important;
        border-radius: 12px;
        padding: 1.5rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .billboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.2) !important;
    }
    .billboard-card h2 {
        color: #1b3a60 !important;
        font-weight: 700;
    }
    .billboard-card p {
        color: #475569 !important;
    }
    .back-link {
        color: #f59e0b !important;
        font-weight: 600;
        transition: color 0.2s;
    }
    .back-link:hover {
        color: #d97706 !important;
        text-decoration: underline;
    }
    .empty-message {
        color: rgba(255, 255, 255, 0.9) !important;
        font-size: 1.1rem;
        text-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
</style>

<div class="container mx-auto py-12 px-4" style="min-height: 60vh;">
    <h1 class="text-3xl font-bold mb-8 text-center billboard-title pt-10 md:pt-14">LIST BILBOARD</h1>

    {{-- Search and Filter Form --}}
    <div class="max-w-4xl mx-auto mb-10 px-2">
        <form action="{{ route('bilboard.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/20 shadow-md">
            <div class="flex-1">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Cari nama atau alamat billboard..." 
                    value="{{ request('search') }}" 
                    class="w-full px-4 py-3 rounded-lg bg-white text-gray-800 placeholder-gray-500 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-amber-500"
                >
            </div>
            <div class="w-full md:w-64">
                <select 
                    name="city" 
                    onchange="this.form.submit()"
                    class="w-full px-4 py-3 rounded-lg bg-white text-gray-800 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-amber-500"
                >
                    <option value="">Semua Kota</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>
                            {{ $city }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 md:flex-none px-6 py-3 bg-amber-500 hover:bg-amber-600 text-slate-900 font-semibold rounded-lg transition" style="background-color: #f59e0b; color: #1e293b;">
                    Cari
                </button>
                @if(request()->filled('search') || request()->filled('city'))
                    <a href="{{ route('bilboard.index') }}" class="px-4 py-3 bg-white/20 hover:bg-white/30 text-white font-semibold rounded-lg text-center transition flex items-center justify-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    @if($billboards->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($billboards as $board)
                <div class="billboard-card shadow">
                    <h2 class="text-xl font-semibold mb-2">{{ $board->name }}</h2>
                    <p style="font-size: 0.9rem; margin-bottom: 0.75rem; color:#64748b;">
                        <strong>Kota:</strong> {{ $board->city }}
                    </p>
                    <p class="mb-3" style="min-height: 48px;">
                        <strong>Alamat:</strong> {{ $board->address }}
                    </p>
                    <div class="mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                            🟢 Tersedia
                        </span>
                    </div>
                    @if($board->google_maps_url)
                        <a href="{{ $board->google_maps_url }}" target="_blank" class="btn btn-primary btn-sm text-center w-full" 
                           style="background-color: #1b3a60; border-color: #1b3a60; color: #ffffff; display: block; border-radius: 8px; padding: 0.5rem 1rem; font-weight: 500;">
                            📍 Lihat lokasi di Google Maps
                        </a>
                    @else
                        <span class="text-gray-400 text-sm">Koordinat belum tersedia</span>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $billboards->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <p class="empty-message">Tidak ada billboard yang tersedia saat ini.</p>
        </div>
    @endif

    <div class="mt-12 text-center">
        <a href="{{ route('home') }}" class="back-link">← Kembali ke Beranda</a>
    </div>
</div>
@endsection
