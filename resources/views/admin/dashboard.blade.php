@extends('layouts.admin')

@section('page_title', 'Overview')

@section('content')

{{-- Top Header --}}
<div class="admin-page-header" style="background-color: #1b3a60; padding: 1.75rem 2rem 1.5rem;">
    <h1 style="color: #ffffff; font-size: 1.5rem; font-weight: 700; margin: 0 0 0.35rem 0; line-height: 1.2;">Overview</h1>
    <p style="color: rgba(255,255,255,0.55); font-size: 0.85rem; margin: 0;">Ringkasan data dan aktivitas sistem vendor portal.</p>
</div>

{{-- Stat Cards --}}
<div style="padding: 0 2rem 1.25rem; display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem;">
    <div style="background-color: #ffffff; border-radius: 10px; padding: 1.25rem; display: flex; align-items: center; gap: 0.75rem;">
        <div style="width: 44px; height: 44px; border-radius: 10px; background-color: #eff6ff; color: #3b82f6; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
            <i class="fa-solid fa-users"></i>
        </div>
        <div>
            <div style="font-size: 0.8rem; font-weight: 700; color: #1e3a8a; margin-bottom: 1px;">Total Vendors</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #0f172a; line-height: 1.2;">{{ $totalVendors }}</div>
            <div style="font-size: 0.72rem; color: #64748b; margin-top: 1px;">Registered</div>
        </div>
    </div>

    <div style="background-color: #ffffff; border-radius: 10px; padding: 1.25rem; display: flex; align-items: center; gap: 0.75rem;">
        <div style="width: 44px; height: 44px; border-radius: 10px; background-color: #fffbeb; color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
            <i class="fa-solid fa-hourglass-half"></i>
        </div>
        <div>
            <div style="font-size: 0.8rem; font-weight: 700; color: #f59e0b; margin-bottom: 1px;">Pending</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #0f172a; line-height: 1.2;">{{ $pendingVendors }}</div>
            <div style="font-size: 0.72rem; color: #64748b; margin-top: 1px;">Awaiting review</div>
        </div>
    </div>

    <div style="background-color: #ffffff; border-radius: 10px; padding: 1.25rem; display: flex; align-items: center; gap: 0.75rem;">
        <div style="width: 44px; height: 44px; border-radius: 10px; background-color: #ecfdf5; color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div>
            <div style="font-size: 0.8rem; font-weight: 700; color: #10b981; margin-bottom: 1px;">Approved</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #0f172a; line-height: 1.2;">{{ $approvedVendors }}</div>
            <div style="font-size: 0.72rem; color: #64748b; margin-top: 1px;">Active partners</div>
        </div>
    </div>

    <div style="background-color: #ffffff; border-radius: 10px; padding: 1.25rem; display: flex; align-items: center; gap: 0.75rem;">
        <div style="width: 44px; height: 44px; border-radius: 10px; background-color: #fef2f2; color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
            <i class="fa-solid fa-circle-xmark"></i>
        </div>
        <div>
            <div style="font-size: 0.8rem; font-weight: 700; color: #ef4444; margin-bottom: 1px;">Rejected</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #0f172a; line-height: 1.2;">{{ $rejectedVendors }}</div>
            <div style="font-size: 0.72rem; color: #64748b; margin-top: 1px;">Ineligible</div>
        </div>
    </div>
</div>

{{-- Chart + Categories --}}
<div style="padding: 0 2rem 1.25rem; display: grid; grid-template-columns: 2fr 1fr; gap: 1.25rem;">
    {{-- Monthly Registrations Chart --}}
    <div style="background-color: #ffffff; border-radius: 10px; padding: 1.25rem;">
        <h3 style="margin: 0 0 1rem 0; font-size: 1rem; font-weight: 700; color: #0f172a;">Monthly Registrations</h3>
        <div style="position: relative; height: 220px; width: 100%;">
            <canvas id="monthlyRegistrationsChart"></canvas>
        </div>
    </div>

    {{-- Vendor Categories --}}
    <div style="background-color: #ffffff; border-radius: 10px; padding: 1.25rem; display: flex; flex-direction: column;">
        <h3 style="margin: 0 0 1rem 0; font-size: 1rem; font-weight: 700; color: #0f172a;">Vendor Categories</h3>
        <div style="display: flex; flex-direction: column; gap: 0.5rem; flex: 1;">
            @foreach($categories as $category)
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.4rem 0; border-bottom: 1px solid #f1f5f9;">
                <span style="font-size: 0.875rem; color: #0f172a; font-weight: 500;">{{ $category->business_category }}</span>
                <span style="font-weight: 600; color: #0f172a; font-size: 0.85rem; background-color: #f1f5f9; padding: 0.2rem 0.6rem; border-radius: 6px;">{{ $category->total }}</span>
            </div>
            @endforeach
            @if($categories->isEmpty())
                <p style="font-size: 0.875rem; color: #64748b; margin: 0;">No data available.</p>
            @endif
        </div>
    </div>
</div>

{{-- Vendor Table --}}
<div style="padding: 0 2rem 2rem;">
    <div style="background-color: #ffffff; border-radius: 10px; overflow: hidden;">
        <div style="padding: 1.25rem 1.25rem 1rem; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 1rem; font-weight: 700; color: #0f172a;">Vendor Management</h3>
            <a href="{{ route('admin.vendors.index') }}" style="font-size: 0.875rem; color: #3b82f6; font-weight: 600; text-decoration: none;">
                View All →
            </a>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #e2e8f0; border-top: 1px solid #f1f5f9;">
                        <th style="padding: 0.85rem 1.25rem; font-size: 0.7rem; color: #1e3a8a; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">VENDOR NAME</th>
                        <th style="padding: 0.85rem 1.25rem; font-size: 0.7rem; color: #1e3a8a; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">CATEGORY</th>
                        <th style="padding: 0.85rem 1.25rem; font-size: 0.7rem; color: #1e3a8a; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">CONTACT EMAIL</th>
                        <th style="padding: 0.85rem 1.25rem; font-size: 0.7rem; color: #1e3a8a; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">REGISTERED</th>
                        <th style="padding: 0.85rem 1.25rem; font-size: 0.7rem; color: #1e3a8a; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentVendors as $vendor)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 0.85rem 1.25rem;">
                            <a href="{{ route('admin.vendors.show', $vendor->id) }}" style="font-weight: 600; color: #3b82f6; text-decoration: none; font-size: 0.85rem;">{{ $vendor->company_name }}</a>
                            <div style="font-size: 0.75rem; color: #64748b; margin-top: 1px;">{{ $vendor->pic_name }}</div>
                        </td>
                        <td style="padding: 0.85rem 1.25rem; color: #0f172a; font-size: 0.85rem;">{{ $vendor->business_category }}</td>
                        <td style="padding: 0.85rem 1.25rem; color: #0f172a; font-size: 0.85rem;">{{ $vendor->company_email }}</td>
                        <td style="padding: 0.85rem 1.25rem; color: #0f172a; font-size: 0.85rem;">{{ $vendor->created_at->format('M d, Y') }}</td>
                        <td style="padding: 0.85rem 1.25rem;">
                            @if($vendor->status == 'approved')
                                <span style="background-color: #d1fae5; color: #059669; padding: 0.3rem 0.65rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.3rem;"><span style="font-size:8px;">●</span> Approved</span>
                            @elseif($vendor->status == 'rejected')
                                <span style="background-color: #fee2e2; color: #dc2626; padding: 0.3rem 0.65rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.3rem;"><span style="font-size:8px;">●</span> Rejected</span>
                            @else
                                <span style="background-color: #fef3c7; color: #d97706; padding: 0.3rem 0.65rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.3rem;"><span style="font-size:8px;">●</span> Pending</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding: 2.5rem; text-align: center; color: #64748b; font-size: 0.9rem;">No vendors found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Script Chart.js untuk Monthly Registrations --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('monthlyRegistrationsChart').getContext('2d');
    
    // Menggunakan json_encode langsung dari PHP agar aman dari error sintaks Blade
    const monthlyCounts = {!! json_encode($monthValues ?? [0,0,0,0,0,0,0,0,0,0,0,0]) !!};
    const monthlyLabels = {!! json_encode($monthLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']) !!};

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'registrations',
                data: monthlyCounts,
                backgroundColor: '#3b82f6',
                borderRadius: 6,
                barThickness: 'flex',
                maxBarThickness: 30,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' registrations';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748b', font: { size: 11 } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: { 
                        color: '#64748b',
                        stepSize: 1,
                        font: { size: 11 }
                    }
                }
            }
        }
    });
</script>
@endsection