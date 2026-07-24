<?php

namespace App\Exports;

use App\Models\Vendor;
use Maatwebsite\Excel\Concerns\FromCollection;

class VendorExport implements FromCollection
{
    public function collection()
    {
        return Vendor::select(
            'company_name',
            'business_category',
            'company_email',
            'company_phone',
            'pic_name',
            'status',
            'created_at'
        )->get();
    }
}