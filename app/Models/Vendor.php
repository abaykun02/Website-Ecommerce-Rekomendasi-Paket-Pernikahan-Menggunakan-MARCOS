<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vendor_name',
        'owner_name',
        'location_address',
        'google_maps_url',
        'phone',
        'description',
        'profile_image',
        'cover_image',
        'status',
        'verified_at',
        'rejection_reason',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'bank_notes',
    ];

    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
        ];
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bundlings(): HasMany
    {
        return $this->hasMany(Bundling::class);
    }

    public function serviceItems(): HasMany
    {
        return $this->hasMany(ServiceItem::class);
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function chatConversations(): HasMany
    {
        return $this->hasMany(ChatConversation::class);
    }

    public function averageRating(): float
    {
        return round((float) $this->reviews()->avg('rating'), 1) ?: 5.0;
    }

    public function hasBankAccount(): bool
    {
        return !empty(trim((string) $this->bank_account_number)) && !empty(trim((string) $this->bank_account_name));
    }

    public function hasAvatar(): bool
    {
        return !empty($this->profile_image);
    }

    public function hasCover(): bool
    {
        return !empty($this->cover_image);
    }

    public function getCoverUrlAttribute(): string
    {
        if (!empty($this->cover_image)) {
            if (str_starts_with($this->cover_image, 'http://') || str_starts_with($this->cover_image, 'https://')) {
                return $this->cover_image;
            }
            return asset('storage/' . $this->cover_image);
        }

        // Fallback banner cover estetis tema pernikahan & dekorasi
        return 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1200&q=80';
    }

    public function getAvatarUrlAttribute(): string
    {
        if (!empty($this->profile_image)) {
            if (str_starts_with($this->profile_image, 'http://') || str_starts_with($this->profile_image, 'https://') || str_starts_with($this->profile_image, 'data:image/')) {
                return $this->profile_image;
            }
            return asset('storage/' . $this->profile_image);
        }

        // SVG fallback avatar dengan inisial nama vendor dan warna aksen brand
        $initials = strtoupper(substr(trim($this->vendor_name ?: 'V'), 0, 2));
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">'
            . '<rect width="100" height="100" rx="50" fill="#8E3C00"/>'
            . '<text x="50%" y="54%" text-anchor="middle" dominant-baseline="middle" fill="#FFFFFF" font-family="Arial, sans-serif" font-weight="bold" font-size="38">' . htmlspecialchars($initials, ENT_QUOTES, 'UTF-8') . '</text>'
            . '</svg>';

        return 'data:image/svg+xml;utf8,' . rawurlencode($svg);
    }

    public function getStoreNameAttribute(): string
    {
        return $this->vendor_name ?? '';
    }

    public function getWhatsappNumberAttribute(): string
    {
        $phone = preg_replace('/[^0-9]/', '', $this->phone ?? '');
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (str_starts_with($phone, '8')) {
            $phone = '62' . $phone;
        }
        return $phone;
    }

    public function getWhatsappUrlAttribute(): string
    {
        return 'https://wa.me/' . $this->whatsapp_number;
    }

    public function getMapsUrlAttribute(): string
    {
        if (!empty($this->google_maps_url)) {
            return $this->google_maps_url;
        }
        return 'https://www.google.com/maps/search/?api=1&query=' . urlencode($this->location_address);
    }

    public const REGIONS = [
        'Tanjung Morawa' => 'Kec. Tanjung Morawa (Kab. Deli Serdang)',
        'Batang Kuis'    => 'Kec. Batang Kuis (Kab. Deli Serdang)',
        'Lubuk Pakam'    => 'Kec. Lubuk Pakam (Kab. Deli Serdang)',
        'Deli Serdang'   => 'Kabupaten Deli Serdang (Umum)',
        'Medan'          => 'Kota Medan',
        'Kisaran'        => 'Kota Kisaran (Kab. Asahan)',
        'Binjai'         => 'Kota Binjai',
        'Tebing Tinggi'  => 'Kota Tebing Tinggi',
        'Lainnya'        => 'Wilayah Lainnya / Luar Kota',
    ];

    /**
     * Resolve vendor's primary operational district / city
     */
    public function getRegionAttribute(): string
    {
        $addr = strtolower($this->location_address ?? '');
        if (str_contains($addr, 'tj. morawa') || str_contains($addr, 'tanjung morawa')) {
            return 'Tanjung Morawa';
        }
        if (str_contains($addr, 'batang kuis')) {
            return 'Batang Kuis';
        }
        if (str_contains($addr, 'kisaran') || str_contains($addr, 'asahan')) {
            return 'Kisaran';
        }
        if (str_contains($addr, 'medan')) {
            return 'Medan';
        }
        if (str_contains($addr, 'lubuk pakam')) {
            return 'Lubuk Pakam';
        }
        if (str_contains($addr, 'deli serdang')) {
            return 'Deli Serdang';
        }
        return 'Lainnya';
    }

    /**
     * Calculate proximity metrics between this vendor and a customer's event location
     * Returns score (1.0 to 5.0 for MARCOS Cost criterion C4 - Jarak) and descriptive metadata
     */
    public function getProximityTo(?string $customerLocation): array
    {
        $vendorRegion = $this->region;
        $customerRegion = self::normalizeRegionName($customerLocation);

        if (empty($customerRegion)) {
            return [
                'score' => 3.0,
                'level' => 'standar',
                'label' => 'Jarak Standar',
                'distance_km' => 'Standar',
                'badge_class' => 'badge-secondary',
                'badge_color' => '#64748B',
                'badge_bg' => '#F1F5F9',
                'badge_border' => '#CBD5E1',
                'description' => 'Lokasi acara belum ditentukan secara spesifik.',
                'vendor_region' => $vendorRegion,
                'customer_region' => null,
                'is_same_region' => false,
            ];
        }

        // Distance & Proximity Matrix (in North Sumatra / Deli Serdang area)
        // Score: 1 = Minim (Sangat Dekat, Cost Terendah), 5 = Sangat Jauh / Luar Kota (Cost Tertinggi)
        $isSame = ($vendorRegion === $customerRegion);

        if ($isSame) {
            return [
                'score' => 1.0,
                'level' => 'sangat_dekat',
                'label' => 'Satu Wilayah / Sangat Dekat',
                'distance_km' => '< 5 km',
                'badge_class' => 'badge-success',
                'badge_color' => '#166534',
                'badge_bg' => '#DCFCE7',
                'badge_border' => '#86EFAC',
                'description' => 'Vendor berada di satu wilayah acara, efisiensi maksimal & bebas biaya transportasi tambahan.',
                'vendor_region' => $vendorRegion,
                'customer_region' => $customerRegion,
                'is_same_region' => true,
            ];
        }

        // Specific inter-district adjacency rules
        $pair = [$vendorRegion, $customerRegion];
        sort($pair);
        $key = implode('__', $pair);

        $rules = [
            'Batang Kuis__Tanjung Morawa' => [
                'score' => 2.0,
                'level' => 'dekat',
                'label' => 'Wilayah Bersebelahan / Dekat',
                'distance_km' => '~10 - 15 km',
                'badge_color' => '#0369A1',
                'badge_bg' => '#E0F2FE',
                'badge_border' => '#7DD3FC',
                'description' => 'Kecamatan tetangga di Deli Serdang, jangkauan cepat dan ongkir transportasi sangat minim.',
            ],
            'Batang Kuis__Deli Serdang' => [
                'score' => 1.5,
                'level' => 'sangat_dekat',
                'label' => 'Satu Kabupaten (Deli Serdang)',
                'distance_km' => '~8 - 12 km',
                'badge_color' => '#166534',
                'badge_bg' => '#DCFCE7',
                'badge_border' => '#86EFAC',
                'description' => 'Masih dalam lingkup Deli Serdang, akses jalan lancar tanpa kendala.',
            ],
            'Deli Serdang__Tanjung Morawa' => [
                'score' => 1.5,
                'level' => 'sangat_dekat',
                'label' => 'Satu Kabupaten (Deli Serdang)',
                'distance_km' => '~8 - 12 km',
                'badge_color' => '#166534',
                'badge_bg' => '#DCFCE7',
                'badge_border' => '#86EFAC',
                'description' => 'Masih dalam lingkup Deli Serdang, respon cepat & efisien.',
            ],
            'Deli Serdang__Medan' => [
                'score' => 2.0,
                'level' => 'dekat',
                'label' => 'Perbatasan Dekat (Medan - Deli Serdang)',
                'distance_km' => '~15 km',
                'badge_color' => '#0369A1',
                'badge_bg' => '#E0F2FE',
                'badge_border' => '#7DD3FC',
                'description' => 'Wilayah penyangga kota, akses tol / jalan arteri sangat memadai.',
            ],
            'Batang Kuis__Medan' => [
                'score' => 2.5,
                'level' => 'sedang',
                'label' => 'Jarak Sedang (Medan - Batang Kuis)',
                'distance_km' => '~18 - 22 km',
                'badge_color' => '#854D0E',
                'badge_bg' => '#FEF9C3',
                'badge_border' => '#FDE047',
                'description' => 'Jarak menengah, waktu tempuh sekitar 30-45 menit.',
            ],
            'Medan__Tanjung Morawa' => [
                'score' => 2.5,
                'level' => 'sedang',
                'label' => 'Jarak Sedang (Medan - Tj. Morawa)',
                'distance_km' => '~20 - 25 km',
                'badge_color' => '#854D0E',
                'badge_bg' => '#FEF9C3',
                'badge_border' => '#FDE047',
                'description' => 'Jarak menengah via Jalan Lintas / Tol Belmera, vendor dapat tiba tepat waktu.',
            ],
            'Binjai__Medan' => [
                'score' => 2.5,
                'level' => 'sedang',
                'label' => 'Jarak Sedang (Medan - Binjai)',
                'distance_km' => '~22 km',
                'badge_color' => '#854D0E',
                'badge_bg' => '#FEF9C3',
                'badge_border' => '#FDE047',
                'description' => 'Akses tol Medan - Binjai cepat dan lancar.',
            ],
            'Kisaran__Tebing Tinggi' => [
                'score' => 3.5,
                'level' => 'sedang',
                'label' => 'Jarak Menengah Antar-Kota',
                'distance_km' => '~65 km',
                'badge_color' => '#9A3412',
                'badge_bg' => '#FFEDD5',
                'badge_border' => '#FDBA74',
                'description' => 'Jalur Lintas Timur, estimasi waktu tempuh sekitar 1 - 1.5 jam.',
            ],
            'Kisaran__Medan' => [
                'score' => 5.0,
                'level' => 'luar_kota',
                'label' => 'Luar Kota / Jarak Jauh',
                'distance_km' => '~155 km',
                'badge_color' => '#991B1B',
                'badge_bg' => '#FEE2E2',
                'badge_border' => '#FCA5A5',
                'description' => 'Luar kota lintas kabupaten, membutuhkan estimasi biaya akomodasi & transportasi tambahan.',
            ],
            'Deli Serdang__Kisaran' => [
                'score' => 5.0,
                'level' => 'luar_kota',
                'label' => 'Luar Kota / Jarak Jauh',
                'distance_km' => '~135 km',
                'badge_color' => '#991B1B',
                'badge_bg' => '#FEE2E2',
                'badge_border' => '#FCA5A5',
                'description' => 'Luar kota, disarankan koordinasi awal mengenai jadwal dan biaya transport vendor.',
            ],
            'Kisaran__Tanjung Morawa' => [
                'score' => 5.0,
                'level' => 'luar_kota',
                'label' => 'Luar Kota / Jarak Jauh',
                'distance_km' => '~135 km',
                'badge_color' => '#991B1B',
                'badge_bg' => '#FEE2E2',
                'badge_border' => '#FCA5A5',
                'description' => 'Luar kota lintas kabupaten (~135 km), memerlukan konfirmasi biaya perjalanan tim vendor.',
            ],
            'Batang Kuis__Kisaran' => [
                'score' => 5.0,
                'level' => 'luar_kota',
                'label' => 'Luar Kota / Jarak Jauh',
                'distance_km' => '~140 km',
                'badge_color' => '#991B1B',
                'badge_bg' => '#FEE2E2',
                'badge_border' => '#FCA5A5',
                'description' => 'Luar kota lintas kabupaten, memerlukan konfirmasi akomodasi & transportasi.',
            ],
        ];

        if (isset($rules[$key])) {
            $match = $rules[$key];
            return array_merge($match, [
                'vendor_region' => $vendorRegion,
                'customer_region' => $customerRegion,
                'is_same_region' => false,
            ]);
        }

        // Default fallback for other combinations
        $isLuarKota = ($vendorRegion === 'Kisaran' || $customerRegion === 'Kisaran' || $customerRegion === 'Lainnya');
        return [
            'score' => $isLuarKota ? 5.0 : 3.5,
            'level' => $isLuarKota ? 'luar_kota' : 'sedang',
            'label' => $isLuarKota ? 'Luar Kota / Jarak Jauh' : 'Jarak Menengah',
            'distance_km' => $isLuarKota ? '> 100 km' : '~30 - 60 km',
            'badge_color' => $isLuarKota ? '#991B1B' : '#854D0E',
            'badge_bg' => $isLuarKota ? '#FEE2E2' : '#FEF9C3',
            'badge_border' => $isLuarKota ? '#FCA5A5' : '#FDE047',
            'description' => $isLuarKota 
                ? 'Jarak cukup jauh dari lokasi vendor, harap diskusikan akomodasi dan biaya transport tambahan.' 
                : 'Jarak menengah, disarankan konfirmasi jadwal keberangkatan lebih awal.',
            'vendor_region' => $vendorRegion,
            'customer_region' => $customerRegion,
            'is_same_region' => false,
        ];
    }

    /**
     * Standardize any input string into one of the known region keys
     */
    public static function normalizeRegionName(?string $raw): ?string
    {
        if (empty($raw)) {
            return null;
        }

        $str = strtolower(trim($raw));
        if (str_contains($str, 'tanjung morawa') || str_contains($str, 'tj. morawa') || str_contains($str, 'tj morawa')) {
            return 'Tanjung Morawa';
        }
        if (str_contains($str, 'batang kuis')) {
            return 'Batang Kuis';
        }
        if (str_contains($str, 'kisaran') || str_contains($str, 'asahan')) {
            return 'Kisaran';
        }
        if (str_contains($str, 'medan')) {
            return 'Medan';
        }
        if (str_contains($str, 'lubuk pakam') || str_contains($str, 'pakam')) {
            return 'Lubuk Pakam';
        }
        if (str_contains($str, 'deli serdang')) {
            return 'Deli Serdang';
        }
        if (str_contains($str, 'binjai')) {
            return 'Binjai';
        }
        if (str_contains($str, 'tebing tinggi') || str_contains($str, 'tebing')) {
            return 'Tebing Tinggi';
        }
        if (str_contains($str, 'lainnya') || str_contains($str, 'luar')) {
            return 'Lainnya';
        }

        // If exact key in REGIONS
        foreach (array_keys(self::REGIONS) as $k) {
            if (strcasecmp($k, $raw) === 0) {
                return $k;
            }
        }

        return $raw;
    }
}
