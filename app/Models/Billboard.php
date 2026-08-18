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
        'map_link',
        'status',
    ];

    const STATUS_TERSEDIA = 'tersedia';
    const STATUS_TERISI = 'terisi';

    /**
     * Accessor for map link.
     */
    public function getGoogleMapsUrlAttribute(): ?string
    {
        return $this->map_link;
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
