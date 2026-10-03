<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BundlingEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'bundling_id',
        'criteria_id',
        'score',
    ];

    public function bundling(): BelongsTo
    {
        return $this->belongsTo(Bundling::class);
    }

    public function criteria(): BelongsTo
    {
        return $this->belongsTo(Criteria::class);
    }
}
