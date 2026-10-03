<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bundling extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'package_name',
        'description',
        'base_price',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function getPriceAttribute(): float
    {
        return (float) ($this->base_price ?? 0);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(BundlingEvaluation::class);
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
