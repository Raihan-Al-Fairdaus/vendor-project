<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;
use Aws\S3\S3MultiRegionClient;

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

    /**
     * Generate presigned URL untuk direct upload dari browser ke Supabase Storage
     */
    public function generatePresignedUrl(Request $request)
    {
        $request->validate([
            'filename' => 'required|string',
            'folder'   => 'required|string|in:id_cards,bank_books,npwp,office_photos',
            'type'     => 'required|string',
        ]);

        $extension = pathinfo($request->filename, PATHINFO_EXTENSION);
        $uniqueName = uniqid() . '_' . time() . '.' . $extension;
        $key = $request->folder . '/' . $uniqueName;

        $s3Client = new S3Client([
            'version'                 => 'latest',
            'region'                  => 'ap-southeast-1',
            'endpoint'                => 'https://kgwwmipiitbmhqroscqu.supabase.co/storage/v1/s3',
            'use_path_style_endpoint' => true,
            'credentials'             => [
                'key'    => env('SUPABASE_ACCESS_KEY_ID'),
                'secret' => env('SUPABASE_SECRET_ACCESS_KEY'),
            ],
        ]);

        $cmd = $s3Client->getCommand('PutObject', [
            'Bucket'      => 'vendors',
            'Key'         => $key,
            'ContentType' => $request->type,
        ]);

        $presignedRequest = $s3Client->createPresignedRequest($cmd, '+15 minutes');
        $presignedUrl = (string) $presignedRequest->getUri();

        $publicUrl = 'https://kgwwmipiitbmhqroscqu.supabase.co/storage/v1/object/public/vendors/' . $key;

        return response()->json([
            'upload_url' => $presignedUrl,
            'public_url' => $publicUrl,
            'key'        => $key,
        ]);
    }

    /**
     * Simpan data vendor - menerima URL file (bukan file langsung)
     */
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
            'google_maps_link'  => 'nullable|url',

            // Sekarang menerima URL string, bukan file upload
            'id_card_url'       => 'required|string|url',
            'bank_book_url'     => 'required|string|url',
            'npwp_file_url'     => 'required|string|url',
            'office_photos_urls'=> 'required|string', // JSON string of URLs

            'agreement'         => 'accepted',
        ]);

        $officePhotoUrls = json_decode($request->office_photos_urls, true);

        if (!is_array($officePhotoUrls) || count($officePhotoUrls) < 2) {
            return back()->withErrors(['office_photos_urls' => 'Minimal 2 foto kantor diperlukan.']);
        }

        Vendor::create([
            'company_name'      => $request->company_name,
            'business_category' => $request->business_category,
            'company_address'   => $request->company_address,
            'npwp'              => $request->npwp,
            'npwp_file_path'    => $request->npwp_file_url,
            'company_email'     => $request->company_email,
            'company_phone'     => $request->company_phone,
            'pic_name'          => $request->pic_name,
            'google_maps_link'  => $request->google_maps_link,
            'id_card_path'      => $request->id_card_url,
            'bank_book_path'    => $request->bank_book_url,
            'office_photos'     => json_encode($officePhotoUrls),
            'status'            => 'pending',
        ]);

        return redirect()->route('vendor.success');
    }

    public function success()
    {
        return view('vendor.success');
    }
}