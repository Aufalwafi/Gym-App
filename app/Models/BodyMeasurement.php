<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BodyMeasurement extends Model
{
    protected $fillable = [
        'user_id',
        'recorded_date',
        'weight_kg',
        'body_fat_percentage',
        'waist_cm',
        'chest_cm',
        'arms_cm',
        'notes',
    ];

    protected $casts = [
        'recorded_date' => 'date',
        'weight_kg' => 'decimal:2',
        'body_fat_percentage' => 'decimal:1',
        'waist_cm' => 'decimal:1',
        'chest_cm' => 'decimal:1',
        'arms_cm' => 'decimal:1',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
