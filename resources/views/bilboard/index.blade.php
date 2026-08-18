@extends('layouts.public')

@section('title', 'Billboards Tersedia')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6 text-center">Billboards Tersedia</h1>

    @if($billboards->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($billboards as $board)
                <div class="border rounded-lg p-4 bg-white shadow">
                    <h2 class="text-xl font-semibold mb-2">{{ $board->name }} — {{ $board->city }}</h2>
                    <p class="text-gray-700 mb-2"><strong>Alamat:</strong> {{ $board->address }}</p>
                    <p class="mb-2">
                        <span class="inline-flex items-center px-2 py-1 rounded text-sm font-medium 
                            {{ $board->status === 'tersedia' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $board->status === 'tersedia' ? '🟢 Tersedia' : '🔴 Terisi' }}
                        </span>
                    </p>
                    @if($board->google_maps_url)
                        <a href="{{ $board->google_maps_url }}" target="_blank" class="text-blue-600 hover:underline">
                            📍 Lihat lokasi di Google Maps
                        </a>
                    @else
                        <span class="text-gray-500">Koordinat belum tersedia</span>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $billboards->links() }}
        </div>
    @else
        <p class="text-center text-gray-600">Tidak ada billboard yang tersedia saat ini.</p>
    @endif

    <div class="mt-8 text-center">
        <a href="{{ route('home') }}" class="text-indigo-600 hover:underline">← Kembali ke Beranda</a>
    </div>
</div>
@endsection
