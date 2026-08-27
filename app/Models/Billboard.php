<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billboard extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'jenis',
        'city',
        'name',
        'address',
        'ukuran',
        'orientasi',
        'kepemilikan',
        'sisi',
        'map_link',
        'status',
    ];

    const STATUS_TERSEDIA = 'tersedia';
    const STATUS_TERISI = 'terisi';

    const JENIS_BILLBOARD = 'billboard';
    const JENIS_MIDIBOARD = 'midiboard';

    // ─── City Abbreviation Map ──────────────────────────────
    protected static $cityMap = [
        'SIDOARJO'     => 'SDA',
        'SURABAYA'     => 'SBY',
        'GRESIK'       => 'GRK',
        'JAKARTA'      => 'JKT',
        'MALANG'       => 'MLG',
        'BANDUNG'      => 'BDG',
        'SEMARANG'     => 'SMG',
        'YOGYAKARTA'   => 'YGY',
        'SOLO'         => 'SLO',
        'MEDAN'        => 'MDN',
        'MAKASSAR'     => 'MKS',
        'DENPASAR'     => 'DPS',
        'MOJOKERTO'    => 'MJK',
        'KEDIRI'       => 'KDR',
        'JOMBANG'      => 'JBG',
        'LAMONGAN'     => 'LMG',
        'TUBAN'        => 'TBN',
        'PASURUAN'     => 'PSR',
        'PROBOLINGGO'  => 'PBL',
        'BANYUWANGI'   => 'BWI',
        'JEMBER'       => 'JBR',
        'BLITAR'       => 'BLT',
        'MADIUN'       => 'MDU',
        'NGANJUK'      => 'NGK',
        'BOJONEGORO'   => 'BJN',
        'PONOROGO'     => 'PNG',
        'TULUNGAGUNG'  => 'TLA',
        'TRENGGALEK'   => 'TGL',
        'PACITAN'      => 'PCT',
        'MAGETAN'      => 'MGT',
        'NGAWI'        => 'NGW',
        'LUMAJANG'     => 'LMJ',
        'SITUBONDO'    => 'STB',
        'BONDOWOSO'    => 'BDW',
        'SAMPANG'      => 'SPG',
        'PAMEKASAN'    => 'PMK',
        'SUMENEP'      => 'SMP',
        'BANGKALAN'    => 'BKL',
        'BEKASI'       => 'BKS',
        'TANGERANG'    => 'TNG',
        'DEPOK'        => 'DPK',
        'BOGOR'        => 'BGR',
        'CIREBON'      => 'CRB',
    ];

    // ─── Accessors ──────────────────────────────────────────

    public function getGoogleMapsUrlAttribute(): ?string
    {
        return $this->map_link;
    }

    /**
     * Display label: "Billboard #001-SDA-I" atau "Midiboard #012-SBY-II"
     */
    public function getDisplayLabelAttribute(): string
    {
        $jenis = ucfirst($this->jenis ?? 'billboard');
        return "{$jenis} {$this->code}";
    }

    // ─── Scopes ─────────────────────────────────────────────

    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_TERSEDIA);
    }

    // ─── Code Generation ────────────────────────────────────

    /**
     * Generate 3-letter city abbreviation from city name.
     */
    public static function getCityAbbreviation(string $city): string
    {
        $upper = strtoupper(trim($city));

        // Check known mapping first
        if (isset(static::$cityMap[$upper])) {
            return static::$cityMap[$upper];
        }

        // Fallback: ambil konsonan pertama 3 huruf
        $clean = preg_replace('/[^A-Z]/', '', $upper);
        $consonants = preg_replace('/[AEIOU]/', '', $clean);

        if (strlen($consonants) >= 3) {
            return substr($consonants, 0, 3);
        }

        // Kalau konsonan kurang dari 3, ambil 3 huruf pertama
        return strtoupper(substr($clean, 0, 3));
    }

    /**
     * Expose city map untuk JavaScript (live preview di form).
     */
    public static function getCityMapForJs(): array
    {
        return static::$cityMap;
    }

    /**
     * Generate billboard code.
     * Format: #003-SDA-I
     *   00  = billboard (01 = midiboard)
     *   3   = sequential number
     *   SDA = city abbreviation
     *   I   = side (roman numeral)
     */
    public static function generateCode(string $jenis, string $city, int $sisi): string
    {
        $typePrefix = $jenis === self::JENIS_MIDIBOARD ? '01' : '00';
        $cityAbbr   = static::getCityAbbreviation($city);
        $sideRoman  = $sisi == 2 ? 'II' : 'I';

        // Find the next sequence number for this type
        $maxSeq = 0;
        $existing = static::where('jenis', $jenis)->pluck('code');

        foreach ($existing as $code) {
            // Extract the number between type prefix and first dash
            // e.g., "#003-SDA-I" → after "#00" → "3-SDA-I" → number = 3
            if ($code && preg_match('/^#' . $typePrefix . '(\d+)-/', $code, $matches)) {
                $maxSeq = max($maxSeq, (int) $matches[1]);
            }
        }

        $seq = $maxSeq + 1;

        // Generate code and ensure uniqueness
        $code = "#{$typePrefix}{$seq}-{$cityAbbr}-{$sideRoman}";

        while (static::where('code', $code)->exists()) {
            $seq++;
            $code = "#{$typePrefix}{$seq}-{$cityAbbr}-{$sideRoman}";
        }

        return $code;
    }
}
?>
