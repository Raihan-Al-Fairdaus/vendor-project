@extends('layouts.admin')

@section('page_title', 'Vendors')
@section('page_subtitle', 'Manage and verify vendor applications.')

@section('header_actions')
    <a href="{{ route('admin.vendors.export', 'excel') }}" class="btn btn-outline btn-sm">📊 Excel</a>
    <a href="{{ route('admin.vendors.export', 'word') }}" class="btn btn-outline btn-sm">📝 Word</a>
    <a href="{{ route('admin.vendors.export', 'pdf') }}" class="btn btn-outline btn-sm">📑 PDF</a>
@endsection

@section('content')

<style>
    table tbody tr td:first-child div {
        color: #1e293b !important;
    }
</style>

<div class="card mb-4" style="padding: 1rem 2rem;">
    <form action="{{ route('admin.vendors.index') }}" method="GET" class="d-flex align-center gap-4" id="filterForm">

        <div style="flex:1;">
            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search vendors, emails, or PIC..."
                value="{{ request('search') }}"
                onkeydown="if(event.key==='Enter'){this.form.submit();}"
            >
        </div>

        <div>
            <select
                name="category"
                class="form-control"
                onchange="document.getElementById('filterForm').submit()"
            >
                <option value="">All Categories</option>

                <option value="IT Services"
                    {{ request('category')=='IT Services' ? 'selected' : '' }}>
                    IT Services
                </option>

                <option value="Logistics"
                    {{ request('category')=='Logistics' ? 'selected' : '' }}>
                    Logistics
                </option>

                <option value="Manufacturing"
                    {{ request('category')=='Manufacturing' ? 'selected' : '' }}>
                    Manufacturing
                </option>

                <option value="Consulting"
                    {{ request('category')=='Consulting' ? 'selected' : '' }}>
                    Consulting
                </option>

                <option value="Other"
                    {{ request('category')=='Other' ? 'selected' : '' }}>
                    Other
                </option>
            </select>
        </div>

        <div>
            <select
                name="status"
                class="form-control"
                onchange="document.getElementById('filterForm').submit()"
            >
                <option value="">All Status</option>

                <option value="pending"
                    {{ request('status')=='pending' ? 'selected' : '' }}>
                    Pending
                </option>

                <option value="approved"
                    {{ request('status')=='approved' ? 'selected' : '' }}>
                    Approved
                </option>

                <option value="rejected"
                    {{ request('status')=='rejected' ? 'selected' : '' }}>
                    Rejected
                </option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">
            Search
        </button>

        <a href="{{ route('admin.vendors.index') }}" class="btn btn-outline">
            Reset
        </a>

    </form>
</div>

<div class="card animate-on-scroll" style="padding:0;overflow:hidden;">

    <div class="table-container">

        <table>

            <thead>
                <tr>
                    <th>VENDOR NAME</th>
                    <th>CATEGORY</th>
                    <th>NPWP</th>
                    <th>CONTACT</th>
                    <th>REGISTERED</th>
                    <th>STATUS</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>

            <tbody>

                @forelse($vendors as $vendor)

                <tr>

                    <td>
                        <div style="font-weight:600;color:var(--primary);">
                            {{ $vendor->company_name }}
                        </div>

                        <div style="font-size:.75rem;color:var(--text-muted);">
                            PIC: {{ $vendor->pic_name }}
                        </div>
                    </td>

                    <td>{{ $vendor->business_category }}</td>

<td>
    {{ $vendor->npwp ?? '-' }}
</td>

                    <td>
                        <div>{{ $vendor->company_email }}</div>

                        <div style="font-size:.75rem;color:var(--text-muted);">
                            {{ $vendor->company_phone }}
                        </div>
                    </td>

                    <td>{{ $vendor->created_at->format('M d, Y') }}</td>

                    <td>

                        @php
                            $statusBg = match($vendor->status){
                                'approved'=>'rgba(16,185,129,.2)',
                                'rejected'=>'rgba(239,68,68,.2)',
                                default=>'rgba(245,158,11,.2)'
                            };

                            $statusColor = match($vendor->status){
                                'approved'=>'#34d399',
                                'rejected'=>'#f87171',
                                default=>'#fbbf24'
                            };

                            $statusBorder = match($vendor->status){
                                'approved'=>'#10b981',
                                'rejected'=>'#ef4444',
                                default=>'#f59e0b'
                            };
                        @endphp

                        <span
                            style="
                                background:{{ $statusBg }};
                                border:1px solid {{ $statusBorder }};
                                color:{{ $statusColor }};
                                padding:.3rem .75rem;
                                border-radius:20px;
                                font-size:.75rem;
                                font-weight:600;
                                display:inline-flex;
                                align-items:center;
                                gap:.4rem;
                            "
                        >
                            <span
                                style="
                                    width:6px;
                                    height:6px;
                                    border-radius:50%;
                                    background:{{ $statusColor }};
                                "
                            ></span>

                            {{ ucfirst($vendor->status) }}
                        </span>

                    </td>

                    <td>
                        <a
                            href="{{ route('admin.vendors.show',$vendor->id) }}"
                            class="btn btn-outline btn-sm"
                        >
                            Review →
                        </a>
                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="7" class="text-center text-muted">
                        No vendors found matching criteria.
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-4">
        {{ $vendors->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>

</div>

@endsection