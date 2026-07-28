<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;

class DocumentController extends Controller
{
    public function index()
    {
        $vendors = Vendor::select(
            'id',
            'company_name',
            'pic_name',
            'business_category',
            'id_card_path',
            'bank_book_path',
            'google_maps_link',
            'office_photos',
            'status',
            'created_at'
        )
        ->orderBy('created_at', 'desc')
        ->get();

        // Total vendor upload KTP
        $withIdCard = $vendors->whereNotNull('id_card_path')->count();

        // Total vendor upload Buku Rekening
        $withBankBook = $vendors->whereNotNull('bank_book_path')->count();

        // Total seluruh office photos
        $withOfficePhotos = 0;

        foreach ($vendors as $vendor) {

            if (empty($vendor->office_photos)) {
                continue;
            }

            $photos = $vendor->office_photos;

            // Jika masih berupa JSON string
            if (is_string($photos)) {
                $photos = json_decode($photos, true);
            }

            if (is_array($photos)) {
                $withOfficePhotos += count($photos);
            }
        }

        return view('admin.documents.index', compact(
            'vendors',
            'withIdCard',
            'withBankBook',
            'withOfficePhotos'
        ));
    }
}