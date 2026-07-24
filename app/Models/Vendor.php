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
        'company_email',
        'company_phone',
        'pic_name',
        'id_card_path',
        'office_photos',
        'status',
    ];

    protected $casts = [
        'office_photos' => 'array',
    ];
}