@extends('layouts.admin')

@section('page_title', 'Vendors')

@section('content')

{{-- Top header bar: title + export buttons on dark navy bg matching sidebar --}}
<div class="admin-page-header" style="background-color: #1b3a60; padding: 1.75rem 2rem 1.5rem; display: flex; justify-content: space-between; align-items: flex-start;">
    <div>
        <h1 style="color: #ffffff; font-size: 1.5rem; font-weight: 700; margin: 0 0 0.35rem 0; line-height: 1.2;">Vendors</h1>
        <p style="color: rgba(255,255,255,0.55); font-size: 0.85rem; margin: 0;">Kelola dan verifikasi aplikasi vendor dengan mudah.</p>
    </div>
    <div style="display: flex; gap: 0.5rem;">
        <a href="{{ route('admin.vendors.export', 'excel') }}" style="background-color: #ffffff; color: #1e293b; text-decoration: none; border: none; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; font-size: 0.8rem; display: flex; align-items: center; gap: 0.4rem; cursor: pointer;"><i class="fa-solid fa-file-excel" style="color:#10b981;"></i> Excel</a>
        <a href="{{ route('admin.vendors.export', 'word') }}" style="background-color: #ffffff; color: #1e293b; text-decoration: none; border: none; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; font-size: 0.8rem; display: flex; align-items: center; gap: 0.4rem; cursor: pointer;"><i class="fa-solid fa-file-word" style="color:#3b82f6;"></i> Word</a>
        <a href="{{ route('admin.vendors.export', 'pdf') }}" style="background-color: #ffffff; color: #1e293b; text-decoration: none; border: none; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; font-size: 0.8rem; display: flex; align-items: center; gap: 0.4rem; cursor: pointer;"><i class="fa-solid fa-file-pdf" style="color:#ef4444;"></i> PDF</a>
    </div>
</div>

{{-- Search / Filter bar --}}
<div style="padding: 0 2rem 1.25rem;">
    <form action="{{ route('admin.vendors.index') }}" method="GET" style="background-color: #ffffff; border-radius: 10px; padding: 0.85rem 1rem; display: flex; gap: 0.75rem; align-items: center;">
        <div style="flex:1; display: flex; align-items: center; gap: 0.5rem; border: 1px solid #e2e8f0; border-radius: 6px; padding: 0.5rem 0.75rem;">
            <i class="fa-solid fa-search" style="color:#94a3b8; font-size:0.85rem;"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari vendor, email, atau PIC..." style="border:none; outline:none; width:100%; color:#0f172a; background:transparent; font-size:0.85rem;" onkeydown="if(event.key==='Enter'){this.form.submit();}">
        </div>
        <select name="category" style="border: 1px solid #e2e8f0; border-radius: 6px; padding: 0.5rem 0.75rem; color:#0f172a; outline:none; background:#fff; cursor:pointer; font-size:0.85rem;" onchange="this.form.submit()">
            <option value="">Semua Tipe Vendor</option>
            <option value="Badan" {{ request('category') == 'Badan' ? 'selected' : '' }}>Badan</option>
            <option value="Perorangan" {{ request('category') == 'Perorangan' ? 'selected' : '' }}>Perorangan</option>
        </select>
        <select name="status" style="border: 1px solid #e2e8f0; border-radius: 6px; padding: 0.5rem 0.75rem; color:#0f172a; outline:none; background:#fff; cursor:pointer; font-size:0.85rem;" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
        <button type="submit" style="background-color: #1b3a60; color:#fff; border:none; border-radius:6px; padding:0.55rem 1.25rem; font-weight:600; display:flex; align-items:center; gap:0.4rem; cursor:pointer; font-size:0.85rem;"><i class="fa-solid fa-search"></i> Cari</button>
        <a href="{{ route('admin.vendors.index') }}" style="background:#fff; border:1px solid #e2e8f0; color:#0f172a; border-radius:6px; padding:0.5rem 1.25rem; font-weight:600; display:flex; align-items:center; gap:0.4rem; cursor:pointer; text-decoration:none; font-size:0.85rem;"><i class="fa-solid fa-rotate-right" style="color:#64748b;"></i> Reset</a>
    </form>
</div>

{{-- Data Table --}}
<div style="padding: 0 2rem 1.25rem;">
    <div style="background-color: #ffffff; border-radius: 10px; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 1rem 1.25rem; font-size: 0.7rem; color: #1e3a8a; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">VENDOR</th>
                    <th style="padding: 1rem 1.25rem; font-size: 0.7rem; color: #1e3a8a; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">KATEGORI</th>
                    <th style="padding: 1rem 1.25rem; font-size: 0.7rem; color: #1e3a8a; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">NPWP</th>
                    <th style="padding: 1rem 1.25rem; font-size: 0.7rem; color: #1e3a8a; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">KONTAK</th>
                    <th style="padding: 1rem 1.25rem; font-size: 0.7rem; color: #1e3a8a; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">TANGGAL DAFTAR</th>
                    <th style="padding: 1rem 1.25rem; font-size: 0.7rem; color: #1e3a8a; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">STATUS</th>
                    <th style="padding: 1rem 1.25rem; font-size: 0.7rem; color: #1e3a8a; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vendors as $vendor)
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 0.85rem 1.25rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 38px; height: 38px; border-radius: 8px; background-color: {{ $vendor->business_category == 'Badan' ? '#10b981' : '#6366f1' }}; color: #ffffff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem; flex-shrink:0;">
                                {{ strtoupper(substr($vendor->company_name, 0, 2)) }}
                            </div>
                            <div>
                                <div style="font-weight: 700; color: #0f172a; font-size: 0.85rem;">{{ $vendor->company_name }}</div>
                                <div style="font-size: 0.75rem; color: #64748b; margin-top: 1px;">PIC: {{ $vendor->pic_name }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 0.85rem 1.25rem; color: #0f172a; font-size: 0.85rem;">{{ $vendor->business_category }}</td>
                    <td style="padding: 0.85rem 1.25rem; color: #0f172a; font-size: 0.85rem;">{{ $vendor->npwp ?? '-' }}</td>
                    <td style="padding: 0.85rem 1.25rem;">
                        <div style="color: #0f172a; font-size: 0.85rem;">{{ $vendor->company_email }}</div>
                        <div style="font-size: 0.75rem; color: #64748b; margin-top: 1px;">{{ $vendor->company_phone }}</div>
                    </td>
                    <td style="padding: 0.85rem 1.25rem; color: #0f172a; font-size: 0.85rem;">{{ $vendor->created_at->format('d M Y') }}</td>
                    <td style="padding: 0.85rem 1.25rem;">
                        @if($vendor->status == 'approved')
                            <span style="background-color: #d1fae5; color: #059669; padding: 0.3rem 0.65rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.3rem;"><span style="font-size:8px;">●</span> Approved</span>
                        @else
                            <span style="background-color: #fef3c7; color: #d97706; padding: 0.3rem 0.65rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.3rem;"><span style="font-size:8px;">●</span> Pending</span>
                        @endif
                    </td>
                    <td style="padding: 0.85rem 1.25rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <a href="{{ route('admin.vendors.show', $vendor->id) }}" style="border: 1px solid #e2e8f0; color: #3b82f6; text-decoration: none; padding: 0.4rem 0.7rem; border-radius: 6px; font-size: 0.8rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.35rem;"><i class="fa-regular fa-eye"></i> Review <i class="fa-solid fa-chevron-down" style="font-size:0.6rem; color:#94a3b8;"></i></a>
                            <button style="border:none; background:none; color:#64748b; cursor:pointer; padding:0.4rem;"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 2.5rem; text-align: center; color: #64748b; font-size: 0.9rem;">Belum ada data vendor.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div style="padding: 0.85rem 1.25rem; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #e2e8f0;">
            <div style="color: #64748b; font-size: 0.8rem;">Menampilkan 1 - {{ $vendors->count() }} dari {{ $vendors->count() }} data</div>
            <div style="display: flex; gap: 0.35rem;">
                <button style="width: 30px; height: 30px; border-radius: 50%; border: 1px solid #e2e8f0; background: #ffffff; color: #64748b; display: flex; align-items: center; justify-content: center; cursor: pointer;"><i class="fa-solid fa-chevron-left" style="font-size:0.65rem;"></i></button>
                <button style="width: 30px; height: 30px; border-radius: 50%; border: none; background: #1e3a8a; color: #ffffff; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 0.8rem; font-weight: 700;">1</button>
                <button style="width: 30px; height: 30px; border-radius: 50%; border: 1px solid #e2e8f0; background: #ffffff; color: #64748b; display: flex; align-items: center; justify-content: center; cursor: pointer;"><i class="fa-solid fa-chevron-right" style="font-size:0.65rem;"></i></button>
            </div>
        </div>
    </div>
</div>

{{-- Stats Cards --}}
<div style="padding: 0 2rem 2rem; display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem;">
    <div style="background-color: #ffffff; border-radius: 10px; padding: 1.25rem; display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; gap: 0.75rem; align-items: center;">
            <div style="width: 44px; height: 44px; border-radius: 10px; background-color: #eff6ff; color: #3b82f6; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <div style="font-size: 0.8rem; font-weight: 700; color: #1e3a8a; margin-bottom: 1px;">Total Vendor</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #0f172a; line-height: 1.2;">{{ \App\Models\Vendor::count() }}</div>
                <div style="font-size: 0.72rem; color: #64748b; margin-top: 1px;">Semua vendor terdaftar</div>
            </div>
        </div>
        <svg width="70" height="35" viewBox="0 0 70 35"><path d="M0,30 L15,25 L30,30 L50,12 L70,4" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </div>
    <div style="background-color: #ffffff; border-radius: 10px; padding: 1.25rem; display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; gap: 0.75rem; align-items: center;">
            <div style="width: 44px; height: 44px; border-radius: 10px; background-color: #fffbeb; color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                <i class="fa-regular fa-clock"></i>
            </div>
            <div>
                <div style="font-size: 0.8rem; font-weight: 700; color: #f59e0b; margin-bottom: 1px;">Pending Review</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #0f172a; line-height: 1.2;">{{ \App\Models\Vendor::where('status', 'pending')->count() }}</div>
                <div style="font-size: 0.72rem; color: #64748b; margin-top: 1px;">Menunggu verifikasi</div>
            </div>
        </div>
        <svg width="70" height="35" viewBox="0 0 70 35"><path d="M0,30 L15,20 L30,25 L50,8 L70,4" fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </div>
    <div style="background-color: #ffffff; border-radius: 10px; padding: 1.25rem; display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; gap: 0.75rem; align-items: center;">
            <div style="width: 44px; height: 44px; border-radius: 10px; background-color: #ecfdf5; color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                <i class="fa-solid fa-check-circle"></i>
            </div>
            <div>
                <div style="font-size: 0.8rem; font-weight: 700; color: #10b981; margin-bottom: 1px;">Approved</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #0f172a; line-height: 1.2;">{{ \App\Models\Vendor::where('status', 'approved')->count() }}</div>
                <div style="font-size: 0.72rem; color: #64748b; margin-top: 1px;">Vendor disetujui</div>
            </div>
        </div>
        <svg width="70" height="35" viewBox="0 0 70 35"><path d="M0,30 L15,25 L30,18 L50,20 L70,4" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </div>
</div>

@endsection