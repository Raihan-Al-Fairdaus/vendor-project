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
        'simulation_path',
        'simulation_type',
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
     * URL untuk file simulasi (gambar/video)
     */
    public function getSimulationUrlAttribute(): ?string
    {
        if (!$this->simulation_path) {
            return null;
        }

        if (str_starts_with($this->simulation_path, 'http://') || str_starts_with($this->simulation_path, 'https://')) {
            return $this->simulation_path;
        }

        // Jika path disimpan di disk supabase / S3
        if (config('filesystems.disks.supabase.key')) {
            try {
                return \Illuminate\Support\Facades\Storage::disk('supabase')->url($this->simulation_path);
            } catch (\Exception $e) {
                // fallback
            }
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->url($this->simulation_path);
    }

    public function getHasSimulationAttribute(): bool
    {
        return !empty($this->simulation_path);
    }

    public function getIsVideoSimulationAttribute(): bool
    {
        if ($this->simulation_type === 'video') return true;
        if ($this->simulation_path) {
            $ext = strtolower(pathinfo($this->simulation_path, PATHINFO_EXTENSION));
            return in_array($ext, ['mp4', 'mov', 'webm', 'ogg']);
        }
        return false;
    }

    public function getIsPdfSimulationAttribute(): bool
    {
        if ($this->simulation_type === 'pdf') return true;
        if ($this->simulation_path) {
            $ext = strtolower(pathinfo($this->simulation_path, PATHINFO_EXTENSION));
            return $ext === 'pdf';
        }
        return false;
    }

    public function getSimulationKindAttribute(): string
    {
        if ($this->is_video_simulation) return 'video';
        if ($this->is_pdf_simulation) return 'pdf';
        return 'image';
    }

    public function getSimulationFileUrlAttribute(): string
    {
        return route('bilboard.simulation.file', $this->id);
    }

    /**
     * URL foto asli cover billboard (diekstrak dari simulasi) jika tersedia.
     */
    public function getCardPhotoUrlAttribute(): ?string
    {
        $codeClean = ltrim($this->code, '#');
        if (file_exists(public_path("images/billboards/{$codeClean}.jpg"))) {
            return asset("images/billboards/{$codeClean}.jpg");
        }
        if (file_exists(public_path("images/billboards/{$codeClean}.png"))) {
            return asset("images/billboards/{$codeClean}.png");
        }
        return null;
    }

    public function getHasCardPhotoAttribute(): bool
    {
        return !empty($this->card_photo_url);
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
     *   3   = urutan global (billboard ke berapa yang di-input)
     *   SDA = city abbreviation
     *   I   = side (roman numeral)
     */
    public static function generateCode(string $jenis, string $city, int $sisi): string
    {
        $typePrefix = $jenis === self::JENIS_MIDIBOARD ? '01' : '00';
        $cityAbbr   = static::getCityAbbreviation($city);
        $sideRoman  = $sisi == 2 ? 'II' : 'I';

        // Urutan global: hitung SEMUA billboard + midiboard yang pernah di-input
        $seq = static::count() + 1;

        // Generate code dan pastikan unik
        $code = "#{$typePrefix}{$seq}-{$cityAbbr}-{$sideRoman}";

        while (static::where('code', $code)->exists()) {
            $seq++;
            $code = "#{$typePrefix}{$seq}-{$cityAbbr}-{$sideRoman}";
        }

        return $code;
    }
}
?>
