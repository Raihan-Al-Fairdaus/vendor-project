<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Memaksa browser dan server agar tidak menyimpan cache halaman admin ini sama sekali
        $this->middleware(function ($request, $next) {
            $response = $next($request);
            return $response->header('Cache-Control', 'nocache, no-store, max-age=0, must-revalidate')
                            ->header('Pragma', 'no-cache')
                            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
        });
    }

    public function index()
    {
        $totalVendors = Vendor::count();
        $pendingVendors = Vendor::where('status', 'pending')->count();
        $approvedVendors = Vendor::where('status', 'approved')->count();
        $rejectedVendors = Vendor::where('status', 'rejected')->count();

        // Optional: Get data for a simple chart (e.g. grouped by category)
        $categories = Vendor::selectRaw('business_category, count(*) as total')
                            ->groupBy('business_category')
                            ->get();

        $recentVendors = Vendor::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalVendors',
            'pendingVendors',
            'approvedVendors',
            'rejectedVendors',
            'categories',
            'recentVendors'
        ));
    }
}