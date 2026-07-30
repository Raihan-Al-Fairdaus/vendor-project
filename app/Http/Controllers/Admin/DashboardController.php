<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;

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

        // Mengambil data jumlah pendaftaran vendor per bulan pada tahun berjalan (2026)
        $monthlyData = Vendor::select(
                DB::raw('COUNT(id) as total'),
                DB::raw('MONTH(created_at) as month')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Siapkan array untuk 12 bulan (Jan - Dec)
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyData[$i] ?? 0;
        }

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
            'recentVendors',
            'chartData'
        ));
    }
}