<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\VendorController;
use App\Models\Vendor;
use App\Exports\VendorExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\VendorManagementController;

/*
|--------------------------------------------------------------------------
| Web Routes - VendorConnect
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. ROUTE PUBLIC & VENDOR
// ==========================================
Route::get('/', [VendorController::class, 'index'])->name('home');

// Direct & Alias Registrasi Vendor
Route::get('/vendor/register', [VendorController::class, 'create'])->name('vendor.register');
Route::get('/register', function () {
    return redirect()->route('vendor.register');
});

Route::post('/vendor/register', [VendorController::class, 'store'])->name('vendor.store');
Route::get('/vendor/success', [VendorController::class, 'success'])->name('vendor.success');


// ==========================================
// 2. ROUTE LOGIN ADMIN
// ==========================================
Route::get('/admin/login', function () {
    return view('admin.auth.login');
})->name('admin.login');

Route::post('/admin/login', function (Request $request) {
    $credentials = $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->has('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended(route('admin.dashboard'));
    }

    return back()->withErrors([
        'email' => 'Email atau password yang dimasukkan salah.',
    ])->onlyInput('email');
})->name('admin.login.post');


// ==========================================
// 3. ROUTE AREA DASHBOARD & NAVIGASI ADMIN
// ==========================================
Route::middleware('auth')->group(function () {

    // --- DASHBOARD UTAMA ---
    Route::get('/admin/dashboard', function () {
        $user            = Auth::user();
        $vendors         = Vendor::latest()->paginate(10);
        $totalVendors    = Vendor::count();
        $pendingVendors  = Vendor::where('status', 'pending')->count();
        $approvedVendors = Vendor::where('status', 'approved')->count();
        $rejectedVendors = Vendor::where('status', 'rejected')->count();
        $approvalRate    = $totalVendors > 0 ? round(($approvedVendors / $totalVendors) * 100, 1) : 0;

        $statusData = [
            'pending'  => $pendingVendors,
            'approved' => $approvedVendors,
            'rejected' => $rejectedVendors,
        ];

        $categories = Vendor::select('business_category', DB::raw('count(*) as total'))
            ->groupBy('business_category')
            ->get();

        $categoryLabels = $categories->pluck('business_category')->toArray();
        $categoryValues = $categories->pluck('total')->toArray();

        $recentVendors = Vendor::latest()->take(5)->get();

        $months      = [];
        $monthLabels = [];
        $monthValues = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subMonths($i);
            $label = $date->format('M Y');
            
            $months[]      = $label;
            $monthLabels[] = $label;
            $monthValues[] = Vendor::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        return view('admin.dashboard', compact(
            'user',
            'vendors',
            'totalVendors',
            'pendingVendors',
            'approvedVendors',
            'rejectedVendors',
            'approvalRate',
            'statusData',
            'categories',
            'categoryLabels',
            'categoryValues',
            'recentVendors',
            'months',
            'monthLabels',
            'monthValues'
        ));
    })->name('admin.dashboard');

    // --- MANAJEMEN VENDOR ---
    Route::get('/admin/vendors', [VendorManagementController::class, 'index'])
    ->name('admin.vendors.index');

    // EXPORT DATA VENDOR (CSV DOWNLOAD)
  Route::get('/admin/vendors/export/{type}', function ($type) {

    $vendors = Vendor::all();

    if ($type == 'excel') {

        return Excel::download(
            new VendorExport(),
            'vendors.xlsx'
        );

    }

    if ($type == 'csv') {

        return Excel::download(
            new VendorExport(),
            'vendors.csv',
            \Maatwebsite\Excel\Excel::CSV
        );

    }

    if ($type == 'pdf') {

        $pdf = Pdf::loadView(
            'admin.vendors.pdf',
            compact('vendors')
        );

        return $pdf->download('vendors.pdf');

    }

    abort(404);

})->name('admin.vendors.export');

    // Detail, Approve, Reject & Delete Vendor
    Route::get('/admin/vendors/{id}', function ($id) {
        $user   = Auth::user();
        $vendor = Vendor::findOrFail($id);
        $recentVendors = Vendor::latest()->take(5)->get();
        return view('admin.vendors.show', compact('user', 'vendor', 'recentVendors'));
    })->name('admin.vendors.show');

    Route::post('/admin/vendors/{id}/approve', function ($id) {
        $vendor = Vendor::findOrFail($id);
        $vendor->update(['status' => 'approved']);
        return back()->with('success', 'Vendor berhasil disetujui (Approved).');
    })->name('admin.vendors.approve');

    Route::post('/admin/vendors/{id}/reject', function ($id) {
        $vendor = Vendor::findOrFail($id);
        $vendor->update(['status' => 'rejected']);
        return back()->with('success', 'Vendor telah ditolak (Rejected).');
    })->name('admin.vendors.reject');

    Route::delete('/admin/vendors/{id}', function ($id) {
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();
        return back()->with('success', 'Vendor berhasil dihapus.');
    })->name('admin.vendors.destroy');

  // --- MANAJEMEN DOKUMEN ---
Route::get('/admin/documents', [DocumentController::class, 'index'])
    ->name('admin.documents.index');
    // --- LAPORAN ---
    Route::get('/admin/reports', function () {
        $user            = Auth::user();
        $vendors         = Vendor::latest()->paginate(10);
        $totalVendors    = Vendor::count();
        $pendingVendors  = Vendor::where('status', 'pending')->count();
        $approvedVendors = Vendor::where('status', 'approved')->count();
        $rejectedVendors = Vendor::where('status', 'rejected')->count();
        $approvalRate    = $totalVendors > 0 ? round(($approvedVendors / $totalVendors) * 100, 1) : 0;

        $statusData = [
            'pending'  => $pendingVendors,
            'approved' => $approvedVendors,
            'rejected' => $rejectedVendors,
        ];

        $categories = Vendor::select('business_category', DB::raw('count(*) as total'))
            ->groupBy('business_category')
            ->get();

        $categoryLabels = $categories->pluck('business_category')->toArray();
        $categoryValues = $categories->pluck('total')->toArray();

        $recentVendors = Vendor::latest()->take(5)->get();

        $months      = [];
        $monthLabels = [];
        $monthValues = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subMonths($i);
            $label = $date->format('M Y');

            $months[]      = $label;
            $monthLabels[] = $label;
            $monthValues[] = Vendor::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        return view('admin.reports.index', compact(
            'user',
            'vendors',
            'totalVendors',
            'pendingVendors',
            'approvedVendors',
            'rejectedVendors',
            'approvalRate',
            'statusData',
            'categories',
            'categoryLabels',
            'categoryValues',
            'recentVendors',
            'months',
            'monthLabels',
            'monthValues'
        ));
    })->name('admin.reports.index');

    // --- SETTINGS (VIEW & PROCESS) ---
    Route::get('/admin/settings', function () {
        $user            = Auth::user();
        $totalVendors    = Vendor::count();
        $approvedVendors = Vendor::where('status', 'approved')->count();
        $approvalRate    = $totalVendors > 0 ? round(($approvedVendors / $totalVendors) * 100, 1) : 0;
        $recentVendors   = Vendor::latest()->take(5)->get();

        return view('admin.settings.index', compact('user', 'totalVendors', 'approvalRate', 'recentVendors'));
    })->name('admin.settings.index');

    Route::get('/admin/settings/profile', function () {
        $user            = Auth::user();
        $totalVendors    = Vendor::count();
        $approvedVendors = Vendor::where('status', 'approved')->count();
        $approvalRate    = $totalVendors > 0 ? round(($approvedVendors / $totalVendors) * 100, 1) : 0;
        $recentVendors   = Vendor::latest()->take(5)->get();

        if (view()->exists('admin.settings.profile')) {
            return view('admin.settings.profile', compact('user', 'totalVendors', 'approvalRate', 'recentVendors'));
        }
        return view('admin.settings.index', compact('user', 'totalVendors', 'approvalRate', 'recentVendors'));
    })->name('admin.settings.profile');

    // Action Update Profile
    Route::post('/admin/settings/profile', function (Request $request) {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Profile updated successfully!');
    });

    Route::get('/admin/settings/password', function () {
        $user            = Auth::user();
        $totalVendors    = Vendor::count();
        $approvedVendors = Vendor::where('status', 'approved')->count();
        $approvalRate    = $totalVendors > 0 ? round(($approvedVendors / $totalVendors) * 100, 1) : 0;
        $recentVendors   = Vendor::latest()->take(5)->get();

        if (view()->exists('admin.settings.password')) {
            return view('admin.settings.password', compact('user', 'totalVendors', 'approvalRate', 'recentVendors'));
        }
        return view('admin.settings.index', compact('user', 'totalVendors', 'approvalRate', 'recentVendors'));
    })->name('admin.settings.password');

    // Action Update Password
    Route::post('/admin/settings/password', function (Request $request) {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak cocok.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully!');
    });

    Route::get('/admin/profile', function () {
        return redirect()->route('admin.settings.profile');
    })->name('admin.profile');

    // --- LOGOUT ACTION ---
    Route::post('/admin/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    })->name('admin.logout');

});