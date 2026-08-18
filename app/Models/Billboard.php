<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billboard extends Model
{
    use HasFactory;

    protected $fillable = [
        'city',
        'name',
        'address',
        'latitude',
        'longitude',
        'status',
    ];

    const STATUS_TERSEDIA = 'tersedia';
    const STATUS_TERISI = 'terisi';

    /**
     * Accessor for Google Maps URL based on coordinates.
     */
    public function getGoogleMapsUrlAttribute(): ?string
    {
        if ($this->latitude && $this->longitude) {
            return "https://www.google.com/maps/search/?api=1&query={$this->latitude},{$this->longitude}";
        }
        return null;
    }

    /**
     * Scope to filter available billboards.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_TERSEDIA);
    }
}
?>
