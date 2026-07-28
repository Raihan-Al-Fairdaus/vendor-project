@extends('layouts.admin')

@section('title', 'Documents - VendorConnect')
@section('page_title', 'Documents')
@section('page_subtitle', 'View and download all vendor verification documents.')

@section('content')

{{-- Summary Stats --}}
<div class="grid grid-cols-4 gap-4 mb-8">
    <div class="stat-card animate-on-scroll" style="transition-delay:0.1s;">
        <div class="stat-icon stat-icon-blue">📁</div>
        <div>
            <div class="stat-label">Total Vendors</div>
            <div class="stat-value">{{ $vendors->count() }}</div>
        </div>
    </div>

    <div class="stat-card animate-on-scroll" style="transition-delay:0.2s;">
        <div class="stat-icon stat-icon-green">🪪</div>
        <div>
            <div class="stat-label">ID Cards Uploaded</div>
            <div class="stat-value">{{ $withIdCard }}</div>
        </div>
    </div>

    <div class="stat-card animate-on-scroll" style="transition-delay:0.3s;">
        <div class="stat-icon stat-icon-purple">🏦</div>
        <div>
            <div class="stat-label">Bank Books Uploaded</div>
            <div class="stat-value">{{ $withBankBook }}</div>
        </div>
    </div>

    <div class="stat-card animate-on-scroll" style="transition-delay:0.4s;">
        <div class="stat-icon stat-icon-orange">🖼️</div>
        <div>
            <div class="stat-label">Office Photos Uploaded</div>
            <div class="stat-value">{{ $withOfficePhotos }}</div>
        </div>
    </div>
</div>

{{-- Documents Table --}}
<div class="card animate-on-scroll" style="padding:0;overflow:hidden;transition-delay:0.5s;">
    <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;">
        <h3 style="font-size:1rem;">All Vendor Documents</h3>
        <span class="badge" style="background:var(--primary-muted);color:var(--primary);">
            {{ $vendors->count() }} vendors
        </span>
    </div>

    <div class="table-container" style="border:none;border-radius:0;">
        <table>
            <thead>
                <tr>
                    <th>VENDOR</th>
                    <th>CATEGORY</th>
                    <th>STATUS</th>
                    <th>IDENTITY CARD (KTP)</th>
                    <th>BANK ACCOUNT BOOK</th>
                    <th>SHARE LOCATION</th>
                    <th>OFFICE PHOTOS</th>
                    <th>UPLOADED</th>
                </tr>
            </thead>

            <tbody>
                @forelse($vendors as $vendor)
                <tr>

                    <td>
                        <div style="font-weight:600;color:var(--primary);">
                            {{ $vendor->company_name }}
                        </div>

                        <div style="font-size:0.75rem;color:var(--text-muted);">
                            PIC: {{ $vendor->pic_name }}
                        </div>
                    </td>

                    <td>
                        <span style="font-size:0.8rem;background:var(--primary-muted);color:var(--primary);padding:0.2rem 0.6rem;border-radius:9999px;font-weight:500;">
                            {{ $vendor->business_category }}
                        </span>
                    </td>

                    <td>
                        <span class="badge badge-{{ $vendor->status }}">
                            {{ ucfirst($vendor->status) }}
                        </span>
                    </td>

                    {{-- KTP --}}
                    <td>
                        @if($vendor->id_card_path)
                            <a href="{{ Storage::url($vendor->id_card_path) }}"
                               target="_blank"
                               class="btn btn-outline btn-sm">
                                👁️ View KTP
                            </a>
                        @else
                            <span style="color:var(--text-muted);font-size:0.8rem;">
                                — Not uploaded
                            </span>
                        @endif
                    </td>

                    {{-- Bank Book --}}
                    <td>
                        @if($vendor->bank_book_path)
                            <a href="{{ Storage::url($vendor->bank_book_path) }}"
                               target="_blank"
                               class="btn btn-outline btn-sm">
                                🏦 View Bank Book
                            </a>
                        @else
                            <span style="color:var(--text-muted);font-size:0.8rem;">
                                — Not uploaded
                            </span>
                        @endif
                    </td>

                    {{-- Share Location --}}
                    <td>
                        @if($vendor->google_maps_link)
                            <a href="{{ $vendor->google_maps_link }}"
                               target="_blank"
                               class="btn btn-outline btn-sm">
                                📍 Open Maps
                            </a>
                        @else
                            <span style="color:var(--text-muted);font-size:0.8rem;">
                                — Not shared
                            </span>
                        @endif
                    </td>

                    {{-- Office Photos --}}
                    <td>

                        @php
                            $photos = json_decode($vendor->office_photos, true);
                        @endphp

                        @if(!empty($photos))

                            <div style="display:flex;gap:8px;flex-wrap:wrap;">

                                @foreach($photos as $photo)

                                    <a href="{{ Storage::url($photo) }}" target="_blank">
                                        <img
                                            src="{{ Storage::url($photo) }}"
                                            style="width:55px;height:55px;object-fit:cover;border-radius:8px;border:1px solid var(--border);">
                                    </a>

                                @endforeach

                            </div>

                        @else

                            <span style="color:var(--text-muted);font-size:0.8rem;">
                                — Not uploaded
                            </span>

                        @endif

                    </td>

                    <td style="color:var(--text-muted);font-size:0.8rem;">
                        {{ $vendor->created_at->format('d M Y') }}
                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="8" class="text-center" style="padding:3rem;color:var(--text-muted);">
                        <div style="font-size:2rem;margin-bottom:0.75rem;">📂</div>
                        <div>No documents found.</div>
                    </td>
                </tr>

                @endforelse

            </tbody>
        </table>
    </div>
</div>

@endsection