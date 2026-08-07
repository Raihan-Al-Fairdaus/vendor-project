@extends('layouts.admin')

@section('page_title', 'System Overview')
@section('header_actions')
    <!-- Tombol Export Data dan + New Vendor sudah dihilangkan -->
@endsection

@section('content')
{{-- Greeting Banner on Mobile --}}
<div class="mobile-greeting-banner">
    <h2>Halo, {{ Auth::user()->name }}!</h2>
    <p>Role: Administrator • {{ now()->translatedFormat('l, d F Y') }}</p>
</div>

<div class="grid grid-cols-4 gap-4 mb-8 stat-cards-grid">
    <div class="card stat-card-new stat-total animate-on-scroll hoverable" style="transition-delay: 0.1s;">
        <div class="stat-card-icon-wrapper">
            <i class="fa-solid fa-users"></i>
        </div>
        <div class="stat-card-details">
            <span class="stat-card-label">Total Vendors</span>
            <span class="stat-card-value">{{ $totalVendors }}</span>
            <span class="stat-card-desc">Registered</span>
        </div>
    </div>
    <div class="card stat-card-new stat-pending animate-on-scroll hoverable" style="transition-delay: 0.2s;">
        <div class="stat-card-icon-wrapper">
            <i class="fa-solid fa-hourglass-half"></i>
        </div>
        <div class="stat-card-details">
            <span class="stat-card-label">Pending</span>
            <span class="stat-card-value">{{ $pendingVendors }}</span>
            <span class="stat-card-desc">Awaiting review</span>
        </div>
    </div>
    <div class="card stat-card-new stat-approved animate-on-scroll hoverable" style="transition-delay: 0.3s;">
        <div class="stat-card-icon-wrapper">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="stat-card-details">
            <span class="stat-card-label">Approved</span>
            <span class="stat-card-value">{{ $approvedVendors }}</span>
            <span class="stat-card-desc">Active partners</span>
        </div>
    </div>
    <div class="card stat-card-new stat-rejected animate-on-scroll hoverable" style="transition-delay: 0.4s;">
        <div class="stat-card-icon-wrapper">
            <i class="fa-solid fa-circle-xmark"></i>
        </div>
        <div class="stat-card-details">
            <span class="stat-card-label">Rejected</span>
            <span class="stat-card-value">{{ $rejectedVendors }}</span>
            <span class="stat-card-desc">Ineligible</span>
        </div>
    </div>
</div>

<div class="grid gap-4 mb-8 chart-grid">
    <!-- Chart Dinamis dengan Chart.js -->
    <div class="card animate-on-scroll" style="transition-delay: 0.5s;">
        <h3 class="mb-4">Monthly Registrations</h3>
        <div style="position: relative; height: 220px; width: 100%;">
            <canvas id="monthlyRegistrationsChart"></canvas>
        </div>
    </div>

    <div class="card animate-on-scroll hoverable" style="transition-delay: 0.6s;">
        <h3 class="mb-4">Vendor Categories</h3>
        <div>
            @foreach($categories as $category)
            <div class="d-flex justify-between mb-2">
                <span style="font-size:0.875rem;color:#ffffff;font-weight:500;">{{ $category->business_category }}</span>
                <span style="font-weight:600;color:#ffffff;">{{ $category->total }}</span>
            </div>
            @endforeach
            @if($categories->isEmpty())
                <p class="text-muted" style="font-size: 0.875rem;">No data available.</p>
            @endif
        </div>
    </div>
</div>

<div class="card animate-on-scroll" style="transition-delay: 0.7s;">
    <div class="d-flex justify-between align-center mb-4">
        <h3>Vendor Management</h3>
        <a href="{{ route('admin.vendors.index') }}"
   style="
        font-size:0.875rem;
        color:#ffffff;
        font-weight:600;
        text-decoration:none;
   ">
        View All →
</a>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>VENDOR NAME</th>
                    <th>CATEGORY</th>
                    <th>CONTACT EMAIL</th>
                    <th>REGISTERED</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentVendors as $vendor)
                <tr>
                    <td data-label="VENDOR NAME">
                        <a href="{{ route('admin.vendors.show', $vendor->id) }}" style="font-weight: 500; color: var(--primary);">{{ $vendor->company_name }}</a>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $vendor->pic_name }}</div>
                    </td>
                    <td data-label="CATEGORY">{{ $vendor->business_category }}</td>
                    <td data-label="CONTACT EMAIL">{{ $vendor->company_email }}</td>
                    <td data-label="REGISTERED">{{ $vendor->created_at->format('M d, Y') }}</td>
                    <td data-label="STATUS">
                        <span class="badge badge-{{ $vendor->status }}">
                            {{ ucfirst($vendor->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
                @if($recentVendors->isEmpty())
                <tr>
                    <td colspan="5" class="text-center text-muted">No vendors found.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

{{-- Script Chart.js untuk Monthly Registrations --}}
{{-- Script Chart.js untuk Monthly Registrations --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('monthlyRegistrationsChart').getContext('2d');
    
    // Menggunakan json_encode langsung dari PHP agar aman dari error sintaks Blade
    const monthlyCounts = {!! json_encode($chartData ?? [0,0,0,0,0,0,0,0,0,0,0,0]) !!};

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
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
                    ticks: { color: 'rgba(255, 255, 255, 0.7)', font: { size: 11 } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(255, 255, 255, 0.08)' },
                    ticks: { 
                        color: 'rgba(255, 255, 255, 0.7)',
                        stepSize: 1,
                        font: { size: 11 }
                    }
                }
            }
        }
    });
</script>
@endsection