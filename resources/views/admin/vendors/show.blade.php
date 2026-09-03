@extends('layouts.admin')

@section('page_title', 'Detail Vendor: ' . $vendor->company_name)

@section('content')

{{-- Top header bar --}}
<div class="admin-page-header" style="background-color: #1b3a60; padding: 1.75rem 2rem 1.5rem; display: flex; justify-content: space-between; align-items: flex-start;">
    <div>
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.35rem;">
            <a href="{{ route('admin.vendors.index') }}" style="color: rgba(255,255,255,0.7); text-decoration: none; display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 6px; background: rgba(255,255,255,0.1); transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.2)'; this.style.color='#fff'" onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.color='rgba(255,255,255,0.7)'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            </a>
            <h1 style="color: #ffffff; font-size: 1.5rem; font-weight: 700; margin: 0; line-height: 1.2;">{{ $vendor->company_name }}</h1>
        </div>
        <p style="color: rgba(255,255,255,0.55); font-size: 0.85rem; margin: 0; padding-left: 2.75rem;">
            Vendor ID: #VND-{{ str_pad($vendor->id, 5, '0', STR_PAD_LEFT) }} &bull; Registration Date: {{ $vendor->created_at->format('M d, Y') }}
        </p>
    </div>
    
    <div>
        @php
            $statusBg = match($vendor->status) {
                'approved' => '#dcfce7',
                'rejected' => '#fee2e2',
                default => '#fef3c7'
            };
            $statusColor = match($vendor->status) {
                'approved' => '#166534',
                'rejected' => '#991b1b',
                default => '#92400e'
            };
            $statusBorder = match($vendor->status) {
                'approved' => '#bbf7d0',
                'rejected' => '#fecaca',
                default => '#fde68a'
            };
            $statusDot = match($vendor->status) {
                'approved' => '#22c55e',
                'rejected' => '#ef4444',
                default => '#f59e0b'
            };
        @endphp
        <span style="background: {{ $statusBg }}; border: 1px solid {{ $statusBorder }}; color: {{ $statusColor }}; padding: 0.4rem 1rem; border-radius: 20px; font-weight: 600; font-size: 0.8rem; text-transform: uppercase; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <span style="width: 8px; height: 8px; border-radius: 50%; background: {{ $statusDot }}; display: inline-block;"></span>
            {{ ucfirst($vendor->status) }}
        </span>
    </div>
</div>

<div style="padding: 0 2rem 2rem;">
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; align-items: stretch;" id="vendor-dashboard-layout">
        
        {{-- KOLOM KIRI --}}
        <div style="display: flex; flex-direction: column; gap: 1.5rem; height: 100%;">
            
            {{-- Company Profile --}}
            <div style="background-color: #ffffff; border-radius: 10px; padding: 1.5rem;">
                <div style="display: flex; align-items: flex-start; gap: 1.25rem; margin-bottom: 1.5rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1.25rem;">
                    @if($vendor->company_logo_path)
                        <img src="{{ str_starts_with($vendor->company_logo_path, 'http') ? $vendor->company_logo_path : Storage::url($vendor->company_logo_path) }}" alt="Logo" style="width: 64px; height: 64px; object-fit: contain; border: 1px solid #e2e8f0; border-radius: 12px; background: #f8fafc; flex-shrink: 0;">
                    @else
                        <div style="width: 64px; height: 64px; background: #eff6ff; border: 1px solid #bfdbfe; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; border-radius: 12px; color: #3b82f6; flex-shrink: 0;">🏢</div>
                    @endif
                    
                    <div style="flex: 1;">
                        <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; margin-bottom: 0.2rem;">Company Profile</div>
                        <h2 style="margin: 0 0 0.25rem 0; color: #0f172a; font-size: 1.5rem; font-weight: 700;">{{ $vendor->company_name }}</h2>
                        <div style="font-size: 0.9rem; color: #475569; display: flex; align-items: center; gap: 0.4rem;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #64748b;"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                            {{ $vendor->business_category ?? 'IT Services' }}
                        </div>
                    </div>
                </div>

                <div>
                    <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; font-weight: 600;">
                        Office Address
                    </div>
                    <p style="color: #0f172a; margin: 0 0 1rem 0; font-size: 0.95rem; line-height: 1.5; display: flex; gap: 0.5rem; align-items: flex-start;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #ef4444; flex-shrink: 0; margin-top: 0.2rem;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        <span>{{ $vendor->company_address ?? '-' }}</span>
                    </p>

                    @if($vendor->google_maps_link)
                        <a href="{{ $vendor->google_maps_link }}" target="_blank" style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.5rem 1rem; background:#f8fafc; border:1px solid #cbd5e1; color:#0f172a; border-radius:6px; text-decoration:none; font-size:0.85rem; font-weight:600; transition: all 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"></polygon><line x1="9" y1="3" x2="9" y2="21"></line><line x1="15" y1="3" x2="15" y2="21"></line></svg>
                            Buka di Google Maps
                        </a>
                    @endif
                </div>
            </div>

            {{-- PIC Information --}}
            <div style="background-color: #ffffff; border-radius: 10px; padding: 1.5rem; flex: 1; display: flex; flex-direction: column;">
                <div style="display: flex; align-items: center; gap: 0.75rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem; margin-bottom: 1.25rem;">
                    <div style="width: 32px; height: 32px; background: #eff6ff; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #3b82f6;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </div>
                    <h3 style="margin: 0; color: #0f172a; font-size: 1.1rem; font-weight: 700;">Person in Charge (PIC)</h3>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; align-items: start; flex: 1; align-content: center;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; background: #f8fafc; padding: 1rem; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <div style="width: 40px; height: 40px; background: #e0e7ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; border-radius: 50%; font-weight: 700; flex-shrink: 0;">
                            {{ strtoupper(substr($vendor->pic_name ?? $vendor->name ?? 'A', 0, 1)) }}
                        </div>
                        <div style="overflow: hidden;">
                            <div style="font-size: 0.7rem; color: #64748b; text-transform: uppercase; font-weight: 600;">Full Name</div>
                            <div style="font-weight: 700; color: #0f172a; font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $vendor->pic_name ?? $vendor->name ?? '-' }}</div>
                        </div>
                    </div>

                    <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <div style="font-size: 0.7rem; color: #64748b; text-transform: uppercase; font-weight: 600; margin-bottom: 0.2rem;">Email Address</div>
                        <div style="color: #0f172a; font-size: 0.9rem; word-break: break-all; font-weight: 600;">{{ $vendor->company_email ?? $vendor->email ?? '-' }}</div>
                    </div>

                    <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <div style="font-size: 0.7rem; color: #64748b; text-transform: uppercase; font-weight: 600; margin-bottom: 0.2rem;">Phone Number</div>
                        <div style="color: #0f172a; font-size: 0.9rem; font-weight: 600;">{{ $vendor->company_phone ?? $vendor->phone ?? '-' }}</div>
                    </div>
                </div>
            </div>

        </div>

        {{-- KOLOM KANAN --}}
        <div style="display: flex; flex-direction: column; gap: 1.5rem; height: 100%;">
            
            {{-- Review Actions --}}
            @if($vendor->status !== 'approved' && $vendor->status !== 'rejected')
            <div style="background-color: #ffffff; border-radius: 10px; padding: 1.5rem; border-top: 4px solid #f59e0b; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                <h3 style="margin: 0 0 1rem 0; font-size: 1rem; color: #0f172a; font-weight: 700;">Tindakan Review</h3>
                
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <form action="{{ route('admin.vendors.approve', $vendor->id) }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" style="width: 100%; background-color: #10b981; border: none; color: #ffffff; padding: 0.75rem; border-radius: 6px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; font-size: 0.9rem; transition: background 0.2s;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            Approve Vendor
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.vendors.reject', $vendor->id) }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" style="width: 100%; background-color: #ef4444; border: none; color: #ffffff; padding: 0.75rem; border-radius: 6px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; font-size: 0.9rem; transition: background 0.2s;" onmouseover="this.style.background='#dc2626'" onmouseout="this.style.background='#ef4444'">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            Reject Registration
                        </button>
                    </form>
                </div>
            </div>
            @else
            <div style="background-color: #f8fafc; border-radius: 10px; padding: 1.25rem; border: 1px solid #e2e8f0;">
                <h3 style="margin: 0 0 0.5rem 0; font-size: 0.9rem; color: #0f172a; font-weight: 700;">Status Review</h3>
                <p style="margin: 0; color: #64748b; font-size: 0.85rem; line-height: 1.4;">Vendor ini sudah berstatus <strong style="color: #0f172a;">{{ ucfirst($vendor->status) }}</strong>. Tindakan review dikunci.</p>
            </div>
            @endif

            {{-- Documents --}}
            <div style="background-color: #ffffff; border-radius: 10px; padding: 1.5rem; flex: 1; display: flex; flex-direction: column;">
                <h3 style="margin: 0 0 1rem 0; font-size: 1rem; color: #0f172a; font-weight: 700; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.75rem;">Dokumen Terlampir</h3>

                <div style="flex: 1; display: flex; flex-direction: column; justify-content: space-around;">
                    {{-- KTP --}}
                    <div>
                        <div style="font-size: 0.7rem; color: #64748b; text-transform: uppercase; font-weight: 600; margin-bottom: 0.4rem;">Dokumen Verifikasi</div>
                        <div style="display: flex; align-items: center; justify-content: space-between; background: #f8fafc; padding: 0.85rem 1rem; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <span style="font-size: 1.5rem;">📄</span>
                                <div>
                                    <div style="font-weight: 700; color: #0f172a; font-size: 0.9rem;">KTP (Identity Card)</div>
                                    <div style="font-size: 0.75rem; color: #10b981; font-weight: 500;">Verified Secure File</div>
                                </div>
                            </div>
                            @if($vendor->id_card_path)
                                <a href="{{ str_starts_with($vendor->id_card_path, 'http') ? $vendor->id_card_path : Storage::url($vendor->id_card_path) }}" target="_blank" style="background: #ffffff; border: 1px solid #cbd5e1; color: #0f172a; padding: 0.4rem 0.75rem; border-radius: 6px; font-size: 0.85rem; text-decoration: none; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#ffffff'">View</a>
                            @else
                                <span style="font-size: 0.8rem; color: #94a3b8; font-style: italic;">Not Uploaded</span>
                            @endif
                        </div>
                    </div>

                    {{-- NPWP --}}
                    <div>
                        <div style="font-size: 0.7rem; color: #64748b; text-transform: uppercase; font-weight: 600; margin-bottom: 0.4rem;">Dokumen Pajak</div>
                        <div style="display: flex; align-items: center; justify-content: space-between; background: #f8fafc; padding: 0.85rem 1rem; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <span style="font-size: 1.5rem;">🧾</span>
                                <div>
                                    <div style="font-weight: 700; color: #0f172a; font-size: 0.9rem;">NPWP</div>
                                    <div style="font-size: 0.75rem; color: #10b981; font-weight: 500;">Tax Identification File</div>
                                </div>
                            </div>
                            @if($vendor->npwp_file_path)
                                <a href="{{ str_starts_with($vendor->npwp_file_path, 'http') ? $vendor->npwp_file_path : Storage::url($vendor->npwp_file_path) }}" target="_blank" style="background: #ffffff; border: 1px solid #cbd5e1; color: #0f172a; padding: 0.4rem 0.75rem; border-radius: 6px; font-size: 0.85rem; text-decoration: none; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#ffffff'">View</a>
                            @else
                                <span style="font-size: 0.8rem; color: #94a3b8; font-style: italic;">Not Uploaded</span>
                            @endif
                        </div>
                    </div>

                    {{-- Bank Book --}}
                    <div>
                        <div style="font-size: 0.7rem; color: #64748b; text-transform: uppercase; font-weight: 600; margin-bottom: 0.4rem;">Dokumen Pendukung</div>
                        <div style="display: flex; align-items: center; justify-content: space-between; background: #f8fafc; padding: 0.85rem 1rem; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <span style="font-size: 1.5rem;">🏦</span>
                                <div>
                                    <div style="font-weight: 700; color: #0f172a; font-size: 0.9rem;">Buku Tabungan</div>
                                    <div style="font-size: 0.75rem; color: #10b981; font-weight: 500;">Bank Account Book</div>
                                </div>
                            </div>
                            @if($vendor->bank_book_path)
                                <a href="{{ str_starts_with($vendor->bank_book_path, 'http') ? $vendor->bank_book_path : Storage::url($vendor->bank_book_path) }}" target="_blank" style="background: #ffffff; border: 1px solid #cbd5e1; color: #0f172a; padding: 0.4rem 0.75rem; border-radius: 6px; font-size: 0.85rem; text-decoration: none; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#ffffff'">View</a>
                            @else
                                <span style="font-size: 0.8rem; color: #94a3b8; font-style: italic;">Not Uploaded</span>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            {{-- Danger Zone --}}
            <div style="background-color: #ffffff; border-radius: 10px; padding: 1.5rem; border: 1px solid #fecaca;">
                <h3 style="margin: 0 0 0.75rem 0; font-size: 0.9rem; color: #dc2626; font-weight: 700;">Danger Zone</h3>
                <form action="{{ route('admin.vendors.destroy', $vendor->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus vendor ini secara permanen?');" style="margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="width: 100%; background-color: #fff1f2; border: 1px solid #fecaca; color: #e11d48; padding: 0.75rem; border-radius: 6px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; font-size: 0.85rem; transition: all 0.2s;" onmouseover="this.style.background='#ffe4e6'" onmouseout="this.style.background='#fff1f2'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                        Hapus Vendor Permanen
                    </button>
                </form>
            </div>

        </div>

    </div>
</div>

<style>
    @media (max-width: 1024px) {
        #vendor-dashboard-layout {
            grid-template-columns: 1fr !important;
        }
    }
</style>

@endsection