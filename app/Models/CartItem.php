<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vendor_id',
        'bundling_id',
        'service_item_id',
        'item_type',
        'item_name',
        'price',
        'quantity',
        'event_date',
        'event_location',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'quantity' => 'integer',
            'event_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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

    public function getResolvedNameAttribute(): string
    {
        if ($this->bundling) {
            return $this->bundling->package_name;
        }
        if ($this->serviceItem) {
            return $this->serviceItem->name;
        }
        return $this->item_name ?: 'Item Pesanan';
    }

    public function getResolvedPriceAttribute(): float
    {
        if ($this->price > 0) {
            return (float) $this->price;
        }
        if ($this->bundling) {
            return (float) $this->bundling->base_price;
        }
        if ($this->serviceItem) {
            return (float) $this->serviceItem->price;
        }
        return 0.0;
    }

    public function getSubtotalAttribute(): float
    {
        return $this->resolved_price * max(1, $this->quantity);
    }

    public function getThumbnailAttribute(): string
    {
        $defaultImg = 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80';

        if ($this->bundling) {
            $galleryUrl = $this->bundling->galleries?->first()?->url 
                ?: $this->bundling->vendor?->galleries?->first()?->url;
            return $galleryUrl ?: $defaultImg;
        }

        if ($this->serviceItem) {
            return $this->serviceItem->image_url 
                ?: ($this->vendor?->galleries?->first()?->url ?: $defaultImg);
        }

        return $defaultImg;
    }
}
