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
            'company_name'            => 'required|string|max:255',
            'business_category'       => 'required|string|max:255',
            'business_category_other' => 'required_if:business_category,Other|nullable|string|max:255',
            'company_address'         => 'required|string',
            'company_email'           => 'required|email|unique:vendors,company_email',
            'company_phone'           => 'required|string|max:50',
            'pic_name'                => 'required|string|max:255',
            
            // ID Card dinaikkan jadi 5MB (5120 KB)
            'id_card'                 => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
            
            // Validasi Office Photos (Minimal 2 foto, per foto MAKSIMAL 10MB / 10240 KB)
            'office_photos'           => 'required|array|min:2',
            'office_photos.*'         => 'image|mimes:jpeg,png,jpg|max:10240', 
            
            'agreement'               => 'accepted',
        ]);

        // Simpan file KTP
        $idCardPath = $request->file('id_card')->store('vendors/id_cards', 'public');
        
        // Simpan banyak foto kantor (looping)
        $officePhotosPaths = [];
        if ($request->hasFile('office_photos')) {
            foreach ($request->file('office_photos') as $photo) {
                $officePhotosPaths[] = $photo->store('vendors/office_photos', 'public');
            }
        }

        $category = $request->business_category;
        if ($category === 'Other' && $request->filled('business_category_other')) {
            $category = $request->business_category_other;
        }

     Vendor::create([
            'company_name'      => $request->company_name,
            'business_category' => $category,
            'company_address'   => $request->company_address,
            'company_email'     => $request->company_email,
            'company_phone'     => $request->company_phone,
            'pic_name'          => $request->pic_name,
            'id_card_path'      => $idCardPath,
            
            // Menyimpan array path foto kantor dalam format JSON
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