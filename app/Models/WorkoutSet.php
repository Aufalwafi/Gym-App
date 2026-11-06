<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkoutSet extends Model
{
    protected $fillable = [
        'workout_session_id',
        'exercise_id',
        'set_number',
        'weight_kg',
        'reps',
        'rpe',
        'is_warmup',
        'is_completed',
        'estimated_1rm',
        'notes',
    ];

    protected $casts = [
        'set_number' => 'integer',
        'weight_kg' => 'decimal:2',
        'reps' => 'integer',
        'rpe' => 'integer',
        'is_warmup' => 'boolean',
        'is_completed' => 'boolean',
        'estimated_1rm' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::saving(function ($set) {
            // Epley Formula: 1RM = weight * (1 + reps / 30)
            if ($set->reps > 0 && $set->weight_kg > 0) {
                if ($set->reps === 1) {
                    $set->estimated_1rm = $set->weight_kg;
                } else {
                    $set->estimated_1rm = round($set->weight_kg * (1 + ($set->reps / 30)), 2);
                }
            } else {
                $set->estimated_1rm = 0;
            }
        });
    }

    public function workoutSession(): BelongsTo
    {
        return $this->belongsTo(WorkoutSession::class);
    }

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }
}
