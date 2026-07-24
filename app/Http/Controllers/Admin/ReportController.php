<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Summary stats
        $totalVendors    = Vendor::count();
        $approvedVendors = Vendor::where('status', 'approved')->count();
        $pendingVendors  = Vendor::where('status', 'pending')->count();
        $rejectedVendors = Vendor::where('status', 'rejected')->count();

        $approvalRate = $totalVendors > 0
            ? round(($approvedVendors / $totalVendors) * 100, 1)
            : 0;

        // Monthly registrations for the last 12 months
        $monthlyData = Vendor::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Build 12-month labels and values
        $months = [];
        $monthValues = [];
        for ($i = 11; $i >= 0; $i--) {
            $date  = now()->subMonths($i);
            $label = $date->format('M Y');
            $value = $monthlyData->first(function ($item) use ($date) {
                return $item->year == $date->year && $item->month == $date->month;
            });
            $months[]      = $label;
            $monthValues[] = $value ? $value->total : 0;
        }

        // Category breakdown
        $categories = Vendor::select('business_category', DB::raw('COUNT(*) as total'))
            ->groupBy('business_category')
            ->orderByDesc('total')
            ->get();

        // Status breakdown for donut chart
        $statusData = [
            'approved' => $approvedVendors,
            'pending'  => $pendingVendors,
            'rejected' => $rejectedVendors,
        ];

        // Recent 5 registrations
        $recentVendors = Vendor::orderByDesc('created_at')->take(5)->get();

        return view('admin.reports.index', compact(
            'totalVendors', 'approvedVendors', 'pendingVendors', 'rejectedVendors',
            'approvalRate', 'months', 'monthValues', 'categories', 'statusData', 'recentVendors'
        ));
    }
}
