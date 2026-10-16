<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    ];

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
}
