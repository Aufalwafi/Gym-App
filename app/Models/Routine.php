<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Routine extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'target_split',
        'day_schedule',
        'description',
        'is_system_template',
    ];

    protected $casts = [
        'is_system_template' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function routineExercises(): HasMany
    {
        return $this->hasMany(RoutineExercise::class)->orderBy('order_index');
    }

    public function workoutSessions(): HasMany
    {
        return $this->hasMany(WorkoutSession::class);
    }
}
