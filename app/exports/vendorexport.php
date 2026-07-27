<?php

namespace App\Exports;

use App\Models\Vendor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class VendorExport implements
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithStyles
{
    public function collection()
    {
        return Vendor::select(
            'company_name',
            'business_category',
            'company_email',
            'company_phone',
            'pic_name',
            'npwp',
            'status',
            'created_at'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Company Name',
            'Business Category',
            'Email',
            'Phone',
            'PIC',
            'NPWP',
            'Status',
            'Registered At',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => [
                    'rgb' => 'FFFFFF',
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '1E40AF',
                ],
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
        ]);

        // Border semua data
        $lastRow = $sheet->getHighestRow();

        $sheet->getStyle("A1:H{$lastRow}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // Tinggi header
        $sheet->getRowDimension(1)->setRowHeight(25);

        return [];
    }
}