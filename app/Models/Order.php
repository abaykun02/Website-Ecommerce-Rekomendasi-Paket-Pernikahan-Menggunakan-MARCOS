<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        ];
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

    public function getPayableAmountAttribute(): float
    {
        if ($this->payment_type === 'dp' && $this->dp_amount > 0) {
            return (float) $this->dp_amount;
        }
        return (float) $this->total_price;
    }

    public function getItemNameAttribute(): string
    {
        if ($this->bundling) {
            return $this->bundling->package_name;
        }
        if ($this->serviceItem) {
            return $this->serviceItem->name;
        }
        return 'Pesanan Layanan';
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
