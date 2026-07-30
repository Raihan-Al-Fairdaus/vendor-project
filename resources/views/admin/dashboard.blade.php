@extends('layouts.admin')

@section('page_title', 'System Overview')
@section('header_actions')
    <!-- Tombol Export Data dan + New Vendor sudah dihilangkan -->
@endsection

@section('content')
<div class="grid grid-cols-4 gap-4 mb-8">
    <div class="card text-center animate-on-scroll hoverable" style="padding: 1.5rem; transition-delay: 0.1s;">
        <div style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 0.5rem; text-transform: uppercase;">Total Vendors</div>
        <div style="font-size: 2.5rem; font-weight: 700; color: var(--accent);">{{ $totalVendors }}</div>
    </div>
    <div class="card text-center animate-on-scroll hoverable" style="padding: 1.5rem; transition-delay: 0.2s;">
        <div style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 0.5rem; text-transform: uppercase;">Pending</div>
        <div style="font-size: 2.5rem; font-weight: 700; color: var(--warning);">{{ $pendingVendors }}</div>
    </div>
    <div class="card text-center animate-on-scroll hoverable" style="padding: 1.5rem; transition-delay: 0.3s;">
        <div style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 0.5rem; text-transform: uppercase;">Approved</div>
        <div style="font-size: 2.5rem; font-weight: 700; color: var(--success);">{{ $approvedVendors }}</div>
    </div>
    <div class="card text-center animate-on-scroll hoverable" style="padding: 1.5rem; transition-delay: 0.4s;">
        <div style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 0.5rem; text-transform: uppercase;">Rejected</div>
        <div style="font-size: 2.5rem; font-weight: 700; color: var(--error);">{{ $rejectedVendors }}</div>
    </div>
</div>

<div class="grid gap-4 mb-8" style="grid-template-columns: 2fr 1fr;">
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
                    <td>
                        <a href="{{ route('admin.vendors.show', $vendor->id) }}" style="font-weight: 500; color: var(--primary);">{{ $vendor->company_name }}</a>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $vendor->pic_name }}</div>
                    </td>
                    <td>{{ $vendor->business_category }}</td>
                    <td>{{ $vendor->company_email }}</td>
                    <td>{{ $vendor->created_at->format('M d, Y') }}</td>
                    <td>
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