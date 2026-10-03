<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Criteria extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
        'weight',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'weight' => 'decimal:4',
        ];
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(BundlingEvaluation::class);
    }
}
