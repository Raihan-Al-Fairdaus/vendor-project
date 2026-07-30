@extends('layouts.admin')

@section('page_title', $vendor->company_name)
@section('page_subtitle', 'Vendor ID: #VND-' . str_pad($vendor->id, 5, '0', STR_PAD_LEFT) . ' • Registration Date: ' . $vendor->created_at->format('M d, Y'))

@section('header_actions')
    <a href="{{ route('admin.vendors.index') }}" class="btn" style="background: rgba(27, 58, 96, 0.9); border: 1px solid #d4af37; color: #fff; padding: 0.4rem 0.8rem; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.85rem;">← Back to List</a>
@endsection

@section('content')
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.25rem; align-items: start;" id="vendor-dashboard-layout">
    
    {{-- KOLOM KIRI (PERUSAHAAN DI ATAS, PIC DI BAWAHNYA) --}}
    <div style="display: flex; flex-direction: column; gap: 1.25rem;">
        
        {{-- Card: Company Profile --}}
        <div class="card" style="margin: 0; padding: 1.5rem; background: linear-gradient(135deg, rgba(27, 58, 96, 0.6) 0%, rgba(15, 32, 54, 0.85) 100%);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 1rem;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    @if($vendor->company_logo_path)
                        <img src="{{ Storage::url($vendor->company_logo_path) }}" alt="Logo" style="width: 55px; height: 55px; object-fit: contain; border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; background: rgba(0,0,0,0.2);">
                    @else
                        <div style="width: 55px; height: 55px; background: rgba(212,175,55,0.1); border: 1px solid #d4af37; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; border-radius: 10px; color: #d4af37; flex-shrink: 0;">🏢</div>
                    @endif
                    <div>
                        <div style="font-size: 0.75rem; color: #d4af37; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">Company Profile</div>
                        <h2 style="margin: 0.1rem 0 0 0; color: #fff; font-size: 1.3rem; font-weight: 700;">{{ $vendor->company_name }}</h2>
                        <div style="font-size: 0.85rem; color: rgba(255,255,255,0.7); margin-top: 0.2rem;"><i class="fa-solid fa-briefcase" style="margin-right: 0.4rem; color: #d4af37;"></i> {{ $vendor->business_category ?? 'IT Services' }}</div>
                    </div>
                </div>

                <div>
                    @php
                        $statusBg = match($vendor->status) {
                            'approved' => 'rgba(16, 185, 129, 0.2)',
                            'rejected' => 'rgba(239, 68, 68, 0.2)',
                            default => 'rgba(245, 158, 11, 0.2)'
                        };
                        $statusColor = match($vendor->status) {
                            'approved' => '#34d399',
                            'rejected' => '#f87171',
                            default => '#fbbf24'
                        };
                        $statusBorder = match($vendor->status) {
                            'approved' => '#10b981',
                            'rejected' => '#ef4444',
                            default => '#f59e0b'
                        };
                    @endphp
                    <span style="background: {{ $statusBg }}; border: 1px solid {{ $statusBorder }}; color: {{ $statusColor }}; padding: 0.35rem 0.85rem; border-radius: 20px; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; display: inline-flex; align-items: center; gap: 0.4rem;">
                        <span style="width: 6px; height: 6px; border-radius: 50%; background: {{ $statusColor }}; display: inline-block;"></span>
                        {{ ucfirst($vendor->status) }}
                    </span>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
               <div>
    <div style="font-size: 0.7rem; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.3rem;">
        Office Address
    </div>

    <p style="color: #fff; margin: 0; font-size: 0.95rem; line-height: 1.5;">
        <i class="fa-solid fa-location-dot" style="color: #d4af37; margin-right: 0.4rem;"></i>
        {{ $vendor->company_address ?? '-' }}
    </p>

    @if($vendor->google_maps_link)
        <a href="{{ $vendor->google_maps_link }}"
           target="_blank"
           style="
                display:inline-flex;
                align-items:center;
                gap:.45rem;
                margin-top:12px;
                padding:.55rem .9rem;
                background:#1b3a60;
                border:1px solid #d4af37;
                color:#fff;
                border-radius:8px;
                text-decoration:none;
                font-size:.82rem;
                font-weight:600;">
            <i class="fa-solid fa-location-dot"></i>
            Open Google Maps
        </a>
    @endif
</div>
            </div>
        </div>

        {{-- Card: Person in Charge (PIC) Information --}}
        <div class="card" style="margin: 0; padding: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 0.75rem; margin-bottom: 1.25rem;">
                <div style="width: 32px; height: 32px; background: rgba(212,175,55,0.15); border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #d4af37; font-size: 0.9rem;">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
                <h3 style="margin: 0; color: #d4af37; font-size: 1.05rem; font-weight: 700;">Person in Charge (PIC) Information</h3>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; align-items: center;">
                <div style="display: flex; align-items: center; gap: 0.75rem; background: rgba(0,0,0,0.2); padding: 0.75rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                    <div style="width: 38px; height: 38px; background: #d4af37; color: #1b3a60; display: flex; align-items: center; justify-content: center; font-size: 0.95rem; border-radius: 50%; font-weight: 700; flex-shrink: 0;">
                        {{ substr($vendor->pic_name ?? $vendor->name ?? 'A', 0, 1) }}
                    </div>
                    <div style="overflow: hidden;">
                        <div style="font-size: 0.65rem; color: rgba(255,255,255,0.5); text-transform: uppercase;">Full Name</div>
                        <div style="font-weight: 600; color: #fff; font-size: 0.85rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $vendor->pic_name ?? $vendor->name ?? '-' }}</div>
                    </div>
                </div>

                <div style="background: rgba(0,0,0,0.2); padding: 0.75rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                    <div style="font-size: 0.65rem; color: rgba(255,255,255,0.5); text-transform: uppercase;">Email Address</div>
                    <div style="color: #fff; font-size: 0.8rem; margin-top: 0.15rem; word-break: break-all; font-weight: 500;">{{ $vendor->company_email ?? $vendor->email ?? '-' }}</div>
                </div>

                <div style="background: rgba(0,0,0,0.2); padding: 0.75rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                    <div style="font-size: 0.65rem; color: rgba(255,255,255,0.5); text-transform: uppercase;">Phone Number</div>
                    <div style="color: #fff; font-size: 0.8rem; margin-top: 0.15rem; font-weight: 500;">{{ $vendor->company_phone ?? $vendor->phone ?? '-' }}</div>
                </div>
            </div>
        </div>

    </div>

    {{-- KOLOM KANAN: REVIEW ACTIONS, DOCUMENTS, & DANGER ZONE --}}
    <div style="display: flex; flex-direction: column; gap: 1.25rem;">
        
        {{-- Card: Review Actions (Hanya muncul jika status masih pending/selain approved & rejected) --}}
        @if($vendor->status !== 'approved' && $vendor->status !== 'rejected')
        <div class="card" style="margin: 0; padding: 1.25rem; border-color: rgba(212, 175, 55, 0.5); background: rgba(27, 58, 96, 0.4);">
            <h3 style="margin: 0 0 0.75rem 0; font-size: 0.9rem; color: #d4af37; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 0.4rem;">Review Actions</h3>
            
            <div style="display: flex; flex-direction: column; gap: 0.65rem;">
                <form action="{{ route('admin.vendors.approve', $vendor->id) }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" style="width: 100%; background-color: #10b981; border: 1px solid #059669; color: #ffffff; padding: 0.65rem; border-radius: 6px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; font-size: 0.85rem;">
                        <i class="fa-solid fa-check"></i> Approve Vendor
                    </button>
                </form>
                
                <form action="{{ route('admin.vendors.reject', $vendor->id) }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" style="width: 100%; background-color: #ef4444; border: 1px solid #dc2626; color: #ffffff; padding: 0.65rem; border-radius: 6px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; font-size: 0.85rem;">
                        <i class="fa-solid fa-xmark"></i> Reject Registration
                    </button>
                </form>
            </div>
        </div>
        @else
        <div class="card" style="margin: 0; padding: 1.1rem 1.25rem; border-color: rgba(16, 185, 129, 0.4); background: rgba(16, 185, 129, 0.05);">
            <h3 style="margin: 0 0 0.3rem 0; font-size: 0.85rem; color: #34d399; text-transform: uppercase; letter-spacing: 0.05em;">Review Status</h3>
            <p style="margin: 0; color: rgba(255,255,255,0.8); font-size: 0.85rem;">This vendor has been <strong>{{ $vendor->status }}</strong>. Action buttons are locked.</p>
        </div>
        @endif

        {{-- Card: Documents --}}
<div class="card" style="margin: 0; padding: 1.25rem;">

    <h3 style="margin: 0 0 0.75rem 0; font-size: 0.9rem; color: #d4af37; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 0.4rem;">
        Documents
    </h3>

    {{-- Identity Card --}}
    <div style="margin-bottom:1rem;">

        <div style="font-size:0.65rem;color:rgba(255,255,255,.5);text-transform:uppercase;margin-bottom:.3rem;">
            Verification Document
        </div>

        <div style="display:flex;align-items:center;justify-content:space-between;background:rgba(0,0,0,.25);padding:.7rem .85rem;border-radius:8px;border:1px solid rgba(255,255,255,.05);">

            <div style="display:flex;align-items:center;gap:.6rem;overflow:hidden;">

                <span style="font-size:1.3rem;">📄</span>

                <div style="overflow:hidden;">
                    <div style="font-weight:600;color:#fff;font-size:.85rem;">
                        Identity Card (KTP)
                    </div>

                    <div style="font-size:.7rem;color:#34d399;">
                        Verified Secure File
                    </div>
                </div>

            </div>

            @if($vendor->id_card_path)

                <a href="{{ Storage::url($vendor->id_card_path) }}"
                   target="_blank"
                   class="btn"
                   style="background:#1b3a60;border:1px solid #d4af37;color:#fff;padding:.3rem .7rem;border-radius:6px;font-size:.8rem;text-decoration:none;">
                    View
                </a>

            @else

                <span style="font-size:.8rem;color:rgba(255,255,255,.4);">
                    Not Uploaded
                </span>

            @endif

        </div>

    </div>

    {{-- NPWP Document --}}
<div style="margin-bottom:1rem;">

    <div style="font-size:0.65rem;color:rgba(255,255,255,.5);text-transform:uppercase;margin-bottom:.3rem;">
        Tax Document
    </div>

    <div style="display:flex;align-items:center;justify-content:space-between;background:rgba(0,0,0,.25);padding:.7rem .85rem;border-radius:8px;border:1px solid rgba(255,255,255,.05);">

        <div style="display:flex;align-items:center;gap:.6rem;overflow:hidden;">

            <span style="font-size:1.3rem;">🧾</span>

            <div style="overflow:hidden;">

                <div style="font-weight:600;color:#fff;font-size:.85rem;">
                    NPWP Document
                </div>

                <div style="font-size:.7rem;color:#34d399;">
                    Tax Identification File
                </div>

            </div>

        </div>

        @if($vendor->npwp_file_path)

            <a href="{{ Storage::url($vendor->npwp_file_path) }}"
               target="_blank"
               class="btn"
               style="background:#1b3a60;border:1px solid #d4af37;color:#fff;padding:.3rem .7rem;border-radius:6px;font-size:.8rem;text-decoration:none;">
                View
            </a>

        @else

            <span style="font-size:.8rem;color:rgba(255,255,255,.4);">
                Not Uploaded
            </span>

        @endif

    </div>

</div>

    {{-- Bank Account Book --}}
    <div>

        <div style="font-size:0.65rem;color:rgba(255,255,255,.5);text-transform:uppercase;margin-bottom:.3rem;">
            Supporting Document
        </div>

        <div style="display:flex;align-items:center;justify-content:space-between;background:rgba(0,0,0,.25);padding:.7rem .85rem;border-radius:8px;border:1px solid rgba(255,255,255,.05);">

            <div style="display:flex;align-items:center;gap:.6rem;overflow:hidden;">

                <span style="font-size:1.3rem;">🏦</span>

                <div style="overflow:hidden;">
                    <div style="font-weight:600;color:#fff;font-size:.85rem;">
                        Bank Account Book
                    </div>

                    <div style="font-size:.7rem;color:#34d399;">
                        Supporting Verification File
                    </div>
                </div>

            </div>

            @if($vendor->bank_book_path)

                <a href="{{ Storage::url($vendor->bank_book_path) }}"
                   target="_blank"
                   class="btn"
                   style="background:#1b3a60;border:1px solid #d4af37;color:#fff;padding:.3rem .7rem;border-radius:6px;font-size:.8rem;text-decoration:none;">
                    View
                </a>

            @else

                <span style="font-size:.8rem;color:rgba(255,255,255,.4);">
                    Not Uploaded
                </span>

            @endif

        </div>

    </div>

</div>

        {{-- Card: Danger Zone --}}
        <div class="card" style="margin: 0; padding: 1.1rem 1.25rem; border-color: rgba(239, 68, 68, 0.4); background: rgba(239, 68, 68, 0.03);">
            <h3 style="margin: 0 0 0.5rem 0; font-size: 0.85rem; color: #f87171; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid rgba(239, 68, 68, 0.2); padding-bottom: 0.3rem;">Danger Zone</h3>
            <form action="{{ route('admin.vendors.destroy', $vendor->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this vendor?');" style="margin: 0;">
                @csrf
                @method('DELETE')
                <button type="submit" style="width: 100%; background-color: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; color: #f87171; padding: 0.55rem; border-radius: 6px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; font-size: 0.85rem;">
                    <i class="fa-solid fa-trash"></i> Delete Vendor Profile
                </button>
            </form>
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