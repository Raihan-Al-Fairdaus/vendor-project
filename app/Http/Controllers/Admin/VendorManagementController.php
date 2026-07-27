<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use App\Exports\VendorExport;
use Maatwebsite\Excel\Facades\Excel;

class VendorManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Vendor::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
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
        $vendor->update([
            'status' => 'approved'
        ]);

        return back()->with('success', 'Vendor approved successfully.');
    }

    public function reject($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->update([
            'status' => 'rejected'
        ]);

        return back()->with('success', 'Vendor rejected successfully.');
    }

    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();

        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor deleted successfully.');
    }

    public function export($format)
    {
        $vendors = Vendor::all();

        // ==========================
        // WORD
        // ==========================
        if ($format === 'word') {

            $phpWord = new PhpWord();

            $phpWord->setDefaultFontName('Calibri');
            $phpWord->setDefaultFontSize(11);

            $section = $phpWord->addSection([
                'marginTop' => 700,
                'marginBottom' => 700,
                'marginLeft' => 700,
                'marginRight' => 700,
            ]);

            // Judul
            $section->addTitle('Vendor Report', 1);

            $section->addText(
                'Generated on : ' . now()->format('d F Y H:i'),
                ['italic' => true, 'color' => '666666']
            );

            $section->addTextBreak();

            $tableStyle = [
                'borderSize' => 6,
                'borderColor' => 'CCCCCC',
                'cellMargin' => 80,
            ];

            $firstRowStyle = [
                'bgColor' => '4472C4'
            ];

            $phpWord->addTableStyle(
                'VendorTable',
                $tableStyle,
                $firstRowStyle
            );

            $table = $section->addTable('VendorTable');

            $headerFont = [
                'bold' => true,
                'color' => 'FFFFFF'
            ];

            // HEADER
            $table->addRow();

            $table->addCell(3500)->addText('Company', $headerFont);
            $table->addCell(2500)->addText('Category', $headerFont);
            $table->addCell(4000)->addText('Email', $headerFont);
            $table->addCell(2500)->addText('Phone', $headerFont);
            $table->addCell(2500)->addText('PIC', $headerFont);
            $table->addCell(3000)->addText('NPWP', $headerFont);
            $table->addCell(1800)->addText('Status', $headerFont);

            foreach ($vendors as $vendor) {

                $table->addRow();

                $table->addCell(3500)->addText($vendor->company_name);
                $table->addCell(2500)->addText($vendor->business_category);
                $table->addCell(4000)->addText($vendor->company_email);
                $table->addCell(2500)->addText($vendor->company_phone);
                $table->addCell(2500)->addText($vendor->pic_name);
                $table->addCell(3000)->addText($vendor->npwp ?? '-');
                $table->addCell(1800)->addText(ucfirst($vendor->status));
            }

            $section->addTextBreak();

            $section->addText(
                'Total Vendors : ' . $vendors->count(),
                ['bold' => true]
            );

            $file = storage_path('app/vendors.docx');

            $writer = IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save($file);

            return response()->download($file)->deleteFileAfterSend(true);
        }

        // ==========================
        // PDF
        // ==========================
        elseif ($format === 'pdf') {

            $pdf = Pdf::loadView(
                'admin.vendors.pdf',
                compact('vendors')
            );

            return $pdf->download('vendors.pdf');
        }

        // ==========================
        // EXCEL
        // ==========================
        elseif ($format === 'excel') {

            return Excel::download(
                new VendorExport(),
                'vendors.xlsx'
            );
        }

        return back()->with('error', 'Invalid export format.');
    }
}