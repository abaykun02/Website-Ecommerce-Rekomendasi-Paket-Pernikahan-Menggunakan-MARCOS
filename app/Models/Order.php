<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'customer_id',
        'customer_name',
        'customer_phone',
        'vendor_id',
        'bundling_id',
        'service_item_id',
        'event_date',
        'event_time',
        'event_location',
        'total_price',
        'payment_type',
        'dp_amount',
        'dp_percentage',
        'notes',
        'status',
        'snap_token',
        'midtrans_transaction_id',
        'midtrans_status',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'total_price' => 'decimal:2',
            'dp_amount' => 'decimal:2',
        ];
    }

    /**
     * Generate concise, clean order code: WED-ymd-001 (e.g. WED-261012-001)
     */
    public static function generateOrderCode(): string
    {
        $prefix = 'WED-' . date('ymd') . '-';
        $count = self::where('order_code', 'LIKE', $prefix . '%')->count() + 1;
        $code = $prefix . str_pad((string)$count, 3, '0', STR_PAD_LEFT);
        while (self::where('order_code', $code)->exists()) {
            $count++;
            $code = $prefix . str_pad((string)$count, 3, '0', STR_PAD_LEFT);
        }
        return $code;
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function bundling(): BelongsTo
    {
        return $this->belongsTo(Bundling::class);
    }

    public function serviceItem(): BelongsTo
    {
        return $this->belongsTo(ServiceItem::class, 'service_item_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function getPayableAmountAttribute(): float
    {
        if ($this->payment_type === 'dp' && $this->dp_amount > 0) {
            return (float) $this->dp_amount;
        }
        return (float) $this->total_price;
    }

    public function getItemNameAttribute(): string
    {
        if ($this->relationLoaded('items') && $this->items->isNotEmpty()) {
            $count = $this->items->count();
            if ($count === 1) {
                return $this->items->first()->item_name;
            }
            return $this->items->first()->item_name . ' (+' . ($count - 1) . ' item lainnya)';
        }

        // fallback if items not eager loaded but exists
        if ($this->items()->exists()) {
            $firstItem = $this->items()->first();
            $count = $this->items()->count();
            if ($count === 1) {
                return $firstItem->item_name;
            }
            return $firstItem->item_name . ' (+' . ($count - 1) . ' item lainnya)';
        }

        if ($this->bundling) {
            return $this->bundling->package_name;
        }
        if ($this->serviceItem) {
            return $this->serviceItem->name;
        }
        return 'Pesanan Paket & Layanan';
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Konfirmasi',
            'approved' => 'Diterima Vendor (Menunggu Pembayaran)',
            'paid' => 'Sudah Dibayar (Menunggu Verifikasi)',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }
}
