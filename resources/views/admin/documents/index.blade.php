@extends('layouts.admin')

@section('title', 'Documents - VendorConnect')
@section('page_title', 'Documents')
@section('page_subtitle', 'View and download all vendor verification documents.')

@section('content')

{{-- Top header bar on dark navy bg --}}
<div class="admin-page-header" style="background-color: #1b3a60; padding: 1.75rem 2rem 1.5rem;">
    <h1 style="color: #ffffff; font-size: 1.5rem; font-weight: 700; margin: 0 0 0.35rem 0; line-height: 1.2;">Documents</h1>
    <p style="color: rgba(255,255,255,0.55); font-size: 0.85rem; margin: 0;">View and download all vendor verification documents.</p>
</div>

{{-- Summary Stats --}}
<div style="padding: 0 2rem 1.25rem; display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem;">
    <div style="background-color: #ffffff; border-radius: 10px; padding: 1.25rem; display: flex; align-items: center; gap: 0.75rem;">
        <div style="width: 44px; height: 44px; border-radius: 10px; background-color: #eff6ff; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
            📁
        </div>
        <div>
            <div style="font-size: 0.8rem; font-weight: 700; color: #1e3a8a; margin-bottom: 1px;">Total Vendors</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #0f172a; line-height: 1.2;">{{ $vendors->count() }}</div>
        </div>
    </div>

    <div style="background-color: #ffffff; border-radius: 10px; padding: 1.25rem; display: flex; align-items: center; gap: 0.75rem;">
        <div style="width: 44px; height: 44px; border-radius: 10px; background-color: #ecfdf5; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
            🪪
        </div>
        <div>
            <div style="font-size: 0.8rem; font-weight: 700; color: #10b981; margin-bottom: 1px;">ID Cards Uploaded</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #0f172a; line-height: 1.2;">{{ $withIdCard }}</div>
        </div>
    </div>

    <div style="background-color: #ffffff; border-radius: 10px; padding: 1.25rem; display: flex; align-items: center; gap: 0.75rem;">
        <div style="width: 44px; height: 44px; border-radius: 10px; background-color: #f5f3ff; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
            🏦
        </div>
        <div>
            <div style="font-size: 0.8rem; font-weight: 700; color: #8b5cf6; margin-bottom: 1px;">Bank Books Uploaded</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #0f172a; line-height: 1.2;">{{ $withBankBook }}</div>
        </div>
    </div>

    <div style="background-color: #ffffff; border-radius: 10px; padding: 1.25rem; display: flex; align-items: center; gap: 0.75rem;">
        <div style="width: 44px; height: 44px; border-radius: 10px; background-color: #fffbeb; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
            🖼️
        </div>
        <div>
            <div style="font-size: 0.8rem; font-weight: 700; color: #f59e0b; margin-bottom: 1px;">Office Photos Uploaded</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #0f172a; line-height: 1.2;">{{ $withOfficePhotos }}</div>
        </div>
    </div>
</div>

{{-- Documents Table --}}
<div style="padding: 0 2rem 2rem;">
    <div style="background-color: #ffffff; border-radius: 10px; overflow: hidden;">
        <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between;">
            <h3 style="font-size: 1rem; font-weight: 700; color: #0f172a; margin: 0;">All Vendor Documents</h3>
            <span style="background: #eff6ff; color: #1e3a8a; font-weight: 600; font-size: 0.75rem; padding: 0.35rem 0.75rem; border-radius: 20px; border: 1px solid #dbeafe;">
                {{ $vendors->count() }} vendors
            </span>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #e2e8f0; background-color: #ffffff;">
                        <th style="padding: 1rem 1.25rem; font-size: 0.7rem; color: #1e3a8a; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; white-space: nowrap;">VENDOR</th>
                        <th style="padding: 1rem 1.25rem; font-size: 0.7rem; color: #1e3a8a; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; white-space: nowrap;">CATEGORY</th>
                        <th style="padding: 1rem 1.25rem; font-size: 0.7rem; color: #1e3a8a; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; white-space: nowrap;">STATUS</th>
                        <th style="padding: 1rem 1.25rem; font-size: 0.7rem; color: #1e3a8a; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; white-space: nowrap;">IDENTITY CARD (KTP)</th>
                        <th style="padding: 1rem 1.25rem; font-size: 0.7rem; color: #1e3a8a; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; white-space: nowrap;">BANK ACCOUNT BOOK</th>
                        <th style="padding: 1rem 1.25rem; font-size: 0.7rem; color: #1e3a8a; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; white-space: nowrap;">SHARE LOCATION</th>
                        <th style="padding: 1rem 1.25rem; font-size: 0.7rem; color: #1e3a8a; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; white-space: nowrap;">OFFICE PHOTOS</th>
                        <th style="padding: 1rem 1.25rem; font-size: 0.7rem; color: #1e3a8a; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; white-space: nowrap;">UPLOADED</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($vendors as $vendor)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 0.85rem 1.25rem;">
                            <div style="font-weight: 700; color: #0f172a; font-size: 0.85rem;">
                                {{ $vendor->company_name }}
                            </div>
                            <div style="font-size: 0.75rem; color: #64748b; margin-top: 2px;">
                                PIC: {{ $vendor->pic_name }}
                            </div>
                        </td>

                        <td style="padding: 0.85rem 1.25rem;">
                            <span style="font-size: 0.75rem; background: #eff6ff; color: #1d4ed8; padding: 0.25rem 0.65rem; border-radius: 9999px; font-weight: 600; display: inline-block;">
                                {{ $vendor->business_category }}
                            </span>
                        </td>

                        <td style="padding: 0.85rem 1.25rem;">
                            @if($vendor->status == 'approved')
                                <span style="background-color: #d1fae5; color: #059669; padding: 0.3rem 0.65rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.3rem;"><span style="font-size:8px;">●</span> Approved</span>
                            @elseif($vendor->status == 'rejected')
                                <span style="background-color: #fee2e2; color: #dc2626; padding: 0.3rem 0.65rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.3rem;"><span style="font-size:8px;">●</span> Rejected</span>
                            @else
                                <span style="background-color: #fef3c7; color: #d97706; padding: 0.3rem 0.65rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.3rem;"><span style="font-size:8px;">●</span> {{ ucfirst($vendor->status) }}</span>
                            @endif
                        </td>

                        {{-- KTP --}}
                        <td style="padding: 0.85rem 1.25rem;">
                            @if($vendor->id_card_path)
                                <a href="{{ str_starts_with($vendor->id_card_path, 'http') ? $vendor->id_card_path : Storage::url($vendor->id_card_path) }}"
                                   target="_blank"
                                   style="border: 1px solid #e2e8f0; background-color: #f8fafc; color: #3b82f6; text-decoration: none; padding: 0.4rem 0.75rem; border-radius: 6px; font-size: 0.78rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.35rem; transition: all 0.15s ease-in-out;">
                                    👁️ View KTP
                                </a>
                            @else
                                <span style="color: #94a3b8; font-size: 0.8rem;">
                                    — Not uploaded
                                </span>
                            @endif
                        </td>

                        {{-- Bank Book --}}
                        <td style="padding: 0.85rem 1.25rem;">
                            @if($vendor->bank_book_path)
                                <a href="{{ str_starts_with($vendor->bank_book_path, 'http') ? $vendor->bank_book_path : Storage::url($vendor->bank_book_path) }}"
                                   target="_blank"
                                   style="border: 1px solid #e2e8f0; background-color: #f8fafc; color: #3b82f6; text-decoration: none; padding: 0.4rem 0.75rem; border-radius: 6px; font-size: 0.78rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.35rem; transition: all 0.15s ease-in-out;">
                                    🏦 View Bank Book
                                </a>
                            @else
                                <span style="color: #94a3b8; font-size: 0.8rem;">
                                    — Not uploaded
                                </span>
                            @endif
                        </td>

                        {{-- Share Location --}}
                        <td style="padding: 0.85rem 1.25rem;">
                            @if($vendor->google_maps_link)
                                <a href="{{ $vendor->google_maps_link }}"
                                   target="_blank"
                                   style="border: 1px solid #e2e8f0; background-color: #f8fafc; color: #3b82f6; text-decoration: none; padding: 0.4rem 0.75rem; border-radius: 6px; font-size: 0.78rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.35rem; transition: all 0.15s ease-in-out;">
                                    📍 Open Maps
                                </a>
                            @else
                                <span style="color: #94a3b8; font-size: 0.8rem;">
                                    — Not shared
                                </span>
                            @endif
                        </td>

                        {{-- Office Photos --}}
                        <td style="padding: 0.85rem 1.25rem;">
                            @php
                                $photos = json_decode($vendor->office_photos, true);
                            @endphp

                            @if(!empty($photos))
                                <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
                                    @foreach($photos as $photo)
                                        <a href="{{ str_starts_with($photo, 'http') ? $photo : Storage::url($photo) }}" target="_blank" style="display: inline-block; line-height: 0;">
                                            <img
                                                src="{{ str_starts_with($photo, 'http') ? $photo : Storage::url($photo) }}"
                                                alt="Office Photo"
                                                style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0; display: block; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <span style="color: #94a3b8; font-size: 0.8rem;">
                                    — Not uploaded
                                </span>
                            @endif
                        </td>

                        <td style="padding: 0.85rem 1.25rem; color: #64748b; font-size: 0.82rem; white-space: nowrap;">
                            {{ $vendor->created_at->format('d M Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="padding: 3rem 1.25rem; text-align: center; color: #64748b;">
                            <div style="font-size: 2rem; margin-bottom: 0.5rem;">📂</div>
                            <div style="font-size: 0.9rem; font-weight: 500;">No documents found.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection