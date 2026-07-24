@extends('layouts.admin')

@section('title', 'Reports - VendorConnect')
@section('page_title', 'Reports & Analytics')
@section('page_subtitle', 'Comprehensive overview of vendor registration performance.')

@section('content')

{{-- KPI Cards --}}
<div class="grid grid-cols-4 gap-4 mb-8">
    <div class="stat-card animate-on-scroll hoverable" style="transition-delay:0.1s;">
        <div class="stat-icon stat-icon-blue">📊</div>
        <div>
            <div class="stat-label">Total Vendors</div>
            <div class="stat-value">{{ $totalVendors }}</div>
        </div>
    </div>
    <div class="stat-card animate-on-scroll hoverable" style="transition-delay:0.2s;">
        <div class="stat-icon stat-icon-green">✅</div>
        <div>
            <div class="stat-label">Approved</div>
            <div class="stat-value" style="color:var(--success);">{{ $approvedVendors }}</div>
        </div>
    </div>
    <div class="stat-card animate-on-scroll hoverable" style="transition-delay:0.3s;">
        <div class="stat-icon stat-icon-orange">⏳</div>
        <div>
            <div class="stat-label">Pending</div>
            <div class="stat-value" style="color:var(--warning);">{{ $pendingVendors }}</div>
        </div>
    </div>
    <div class="stat-card animate-on-scroll hoverable" style="transition-delay:0.4s;">
        <div class="stat-icon stat-icon-red">❌</div>
        <div>
            <div class="stat-label">Approval Rate</div>
            <div class="stat-value" style="color:var(--primary);">{{ $approvalRate }}%</div>
        </div>
    </div>
</div>

<div class="grid gap-4 mb-8" style="grid-template-columns: 2fr 1fr;">

    {{-- Monthly Bar Chart --}}
    <div class="card animate-on-scroll" style="transition-delay:0.5s;">
        <h3 class="mb-4" style="font-size:1rem;">Monthly Registrations (Last 12 Months)</h3>
        <div id="barChart" style="height:200px;display:flex;align-items:flex-end;gap:6px;padding-bottom:1rem;border-bottom:1px solid var(--border);">
            @php $maxVal = max(array_merge($monthValues, [1])); @endphp
            @foreach($monthValues as $i => $val)
            <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;">
                <span style="font-size:0.65rem;color:var(--text-muted);font-weight:600;">{{ $val > 0 ? $val : '' }}</span>
                <div style="width:100%;background:var(--primary);border-radius:4px 4px 0 0;height:{{ $maxVal > 0 ? round(($val/$maxVal)*160) : 4 }}px;min-height:4px;transition:height 0.5s ease;opacity:{{ $val > 0 ? '1' : '0.15' }};"
                     title="{{ $months[$i] }}: {{ $val }} registrations"></div>
            </div>
            @endforeach
        </div>
        <div style="display:flex;gap:6px;margin-top:0.5rem;overflow:hidden;">
            @foreach($months as $m)
            <div style="flex:1;text-align:center;font-size:0.6rem;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                {{ \Carbon\Carbon::parse($m)->format('M') }}
            </div>
            @endforeach
        </div>
    </div>

    {{-- Status Donut / Breakdown --}}
    <div class="card animate-on-scroll" style="transition-delay:0.6s;">
        <h3 class="mb-4" style="font-size:1rem;">Status Breakdown</h3>
        @foreach($statusData as $status => $count)
        @php
            $color = $status === 'approved' ? 'var(--success)' : ($status === 'pending' ? 'var(--warning)' : 'var(--error)');
            $bg    = $status === 'approved' ? 'var(--success-bg)' : ($status === 'pending' ? 'var(--warning-bg)' : 'var(--error-bg)');
            $pct   = $totalVendors > 0 ? round(($count / $totalVendors) * 100) : 0;
        @endphp
        <div style="margin-bottom:1rem;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.35rem;">
                <span style="font-size:0.8rem;font-weight:600;color:{{ $color }};text-transform:capitalize;">{{ $status }}</span>
                <span style="font-size:0.8rem;color:var(--text-muted);">{{ $count }} ({{ $pct }}%)</span>
            </div>
            <div style="height:8px;background:var(--border);border-radius:9999px;overflow:hidden;">
                <div style="height:100%;width:{{ $pct }}%;background:{{ $color }};border-radius:9999px;transition:width 0.8s ease;"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<div class="grid gap-4" style="grid-template-columns: 1fr 1fr;">

    {{-- Category Breakdown --}}
    <div class="card animate-on-scroll" style="transition-delay:0.7s;">
        <h3 class="mb-4" style="font-size:1rem;">Vendors by Category</h3>
        @forelse($categories as $cat)
        @php $catPct = $totalVendors > 0 ? round(($cat->total / $totalVendors) * 100) : 0; @endphp
        <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1rem;">
            <div style="flex:1;">
                <div style="display:flex;justify-content:space-between;margin-bottom:0.25rem;">
                    <span style="font-size:0.8rem;font-weight:500;color:var(--text-body);">{{ $cat->business_category }}</span>
                    <span style="font-size:0.8rem;color:var(--text-muted);">{{ $cat->total }}</span>
                </div>
                <div style="height:6px;background:var(--border);border-radius:9999px;overflow:hidden;">
                    <div style="height:100%;width:{{ $catPct }}%;background:var(--primary);border-radius:9999px;"></div>
                </div>
            </div>
        </div>
        @empty
        <p class="text-muted" style="font-size:0.875rem;">No data available yet.</p>
        @endforelse
    </div>

   {{-- Recent Registrations --}}
<div class="card animate-on-scroll" style="transition-delay:0.8s;">
    <h3 class="mb-4" style="font-size:1rem;">Recent Registrations</h3>

    @forelse($recentVendors as $v)
    <div style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem 0;border-bottom:1px solid var(--border);">

        <div style="
            width:38px;
            height:38px;
            background:var(--primary-muted);
            color:var(--primary);
            border-radius:var(--radius-md);
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:700;
            flex-shrink:0;
            font-size:0.875rem;
        ">
            {{ strtoupper(substr($v->company_name, 0, 2)) }}
        </div>

        <div style="
            flex:1;
            min-width:0;
        ">
            <div style="
                font-weight:600;
                font-size:0.875rem;
                color:#ffffff;
                white-space:nowrap;
                overflow:hidden;
                text-overflow:ellipsis;
            ">
                {{ $v->company_name }}
            </div>

            <div style="
                font-size:0.75rem;
                color:var(--text-muted);
            ">
                {{ $v->created_at->diffForHumans() }}
            </div>
        </div>

        <span class="badge badge-{{ $v->status }}">
            {{ ucfirst($v->status) }}
        </span>

    </div>
    @empty
        <p class="text-muted" style="font-size:0.875rem;">
            No vendors yet.
        </p>
    @endforelse
</div>
@endsection