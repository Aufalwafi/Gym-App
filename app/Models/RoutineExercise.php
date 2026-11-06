<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoutineExercise extends Model
{
    protected $fillable = [
        'routine_id',
        'exercise_id',
        'order_index',
        'target_sets',
        'target_reps',
        'rest_seconds',
        'notes',
    ];

    protected $casts = [
        'order_index' => 'integer',
        'target_sets' => 'integer',
        'rest_seconds' => 'integer',
    ];

    public function routine(): BelongsTo
    {
        return $this->belongsTo(Routine::class);
    }

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }
}
