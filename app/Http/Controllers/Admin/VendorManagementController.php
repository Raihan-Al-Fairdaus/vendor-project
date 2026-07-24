<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
// use Maatwebsite\Excel\Facades\Excel; // If we create a VendorExport class

class VendorManagementController extends Controller
{
    public function index(Request $request)
    
    {
        $query = Vendor::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('company_email', 'like', "%{$search}%")
                  ->orWhere('pic_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('business_category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $vendors = $query->latest()->paginate(10);
        return view('admin.vendors.index', compact('vendors'));
    }

    public function show($id)
    {
        $vendor = Vendor::findOrFail($id);
        return view('admin.vendors.show', compact('vendor'));
    }

    public function approve($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->update(['status' => 'approved']);
        return back()->with('success', 'Vendor approved successfully.');
    }

    public function reject($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->update(['status' => 'rejected']);
        return back()->with('success', 'Vendor rejected successfully.');
    }

    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();
        return redirect()->route('admin.vendors.index')->with('success', 'Vendor deleted successfully.');
    }

    public function export($format)
    {
        $vendors = Vendor::all();

        if ($format === 'csv') {
            $filename = "vendors_export.csv";
            $handle = fopen($filename, 'w+');
            fputcsv($handle, ['ID', 'Company Name', 'Category', 'Email', 'Phone', 'PIC', 'Status', 'Registered At']);
            foreach($vendors as $row) {
                fputcsv($handle, [$row->id, $row->company_name, $row->business_category, $row->company_email, $row->company_phone, $row->pic_name, $row->status, $row->created_at]);
            }
            fclose($handle);
            return response()->download($filename)->deleteFileAfterSend(true);
        } elseif ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.vendors.pdf', compact('vendors'));
            return $pdf->download('vendors_export.pdf');
        } elseif ($format === 'excel') {
            // As a fallback without creating a separate Export class, we can return CSV labeled as XLS
            // A true implementation would use: return Excel::download(new VendorExport, 'vendors.xlsx');
            // For now, generating a CSV but with .xlsx might be problematic. Let's just output CSV style if class doesn't exist.
            $filename = "vendors_export.csv";
            $handle = fopen($filename, 'w+');
            fputcsv($handle, ['ID', 'Company Name', 'Category', 'Email', 'Phone', 'PIC', 'Status', 'Registered At']);
            foreach($vendors as $row) {
                fputcsv($handle, [$row->id, $row->company_name, $row->business_category, $row->company_email, $row->company_phone, $row->pic_name, $row->status, $row->created_at]);
            }
            fclose($handle);
            return response()->download($filename, 'vendors_export.csv')->deleteFileAfterSend(true);
        }

        return back()->with('error', 'Invalid format');
    }
}
