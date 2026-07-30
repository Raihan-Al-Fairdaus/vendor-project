<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'business_category',
        'company_address',
        'google_maps_link',
        'npwp',
        'npwp_file_path',
        'company_email',
        'company_phone',
        'pic_name',
        'id_card_path',
        'bank_book_path',
        'office_photos',
        'status',
    ];

    protected $casts = [
        'office_photos' => 'array',
    ];
}