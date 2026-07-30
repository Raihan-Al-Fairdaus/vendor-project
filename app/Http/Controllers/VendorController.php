<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VendorController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function create()
    {
        return view('vendor.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name'      => 'required|string|max:255',
            'business_category' => 'required|in:Badan,Perorangan',
            'company_address'   => 'required|string',
            'npwp'              => 'required|string|max:30',
            'company_email'     => 'required|email|unique:vendors,company_email',
            'company_phone'     => 'required|string|max:50',
            'pic_name'          => 'required|string|max:255',

            // Share Location Google Maps
            'google_maps_link'  => 'nullable|url',

            // ID Card
            'id_card' => 'required|file|mimes:jpeg,png,jpg,pdf|max:10240',

            // Buku Rekening
            'bank_book' => 'required|file|mimes:jpeg,png,jpg,pdf|max:10240',

            // file NPWP
            'npwp_file' => 'required|file|mimes:jpeg,png,jpg,pdf|max:10240',


            // Office Photos
            'office_photos'   => 'required|array|min:2',
            'office_photos.*' => 'image|mimes:jpeg,png,jpg|max:10240',

            'agreement' => 'accepted',
        ]);

        // Simpan KTP
        $idCardPath = $request->file('id_card')->store(
            'vendors/id_cards',
            'public'
        );

        // Simpan Buku Rekening
        $bankBookPath = $request->file('bank_book')->store(
            'vendors/bank_books',
            'public'
        );

        // simpan file NPWP
        $npwpPath = $request->file('npwp_file')->store(
            'vendors/npwp',
            'public'
        );

        // Simpan Foto Kantor
        $officePhotosPaths = [];

        if ($request->hasFile('office_photos')) {

            foreach ($request->file('office_photos') as $photo) {

                $officePhotosPaths[] = $photo->store(
                    'vendors/office_photos',
                    'public'
                );

            }
        }

        Vendor::create([
            'company_name'      => $request->company_name,
            'business_category' => $request->business_category,
            'company_address'   => $request->company_address,
            'npwp'              => $request->npwp,
            'npwp_file_path'    => $npwpPath,
            'company_email'     => $request->company_email,
            'company_phone'     => $request->company_phone,
            'pic_name'          => $request->pic_name,

            // Share Location
            'google_maps_link'  => $request->google_maps_link,

            'id_card_path'      => $idCardPath,
            'bank_book_path'    => $bankBookPath,
            'office_photos'     => json_encode($officePhotosPaths),
            'status'            => 'pending',
        ]);

        return redirect()->route('vendor.success');
    }

    public function success()
    {
        return view('vendor.success');
    }
}