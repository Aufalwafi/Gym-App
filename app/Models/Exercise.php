<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exercise extends Model
{
    protected $fillable = [
        'exercise_category_id',
        'name',
        'slug',
        'equipment',
        'secondary_muscles',
        'difficulty',
        'instructions',
        'tips',
        'is_custom',
        'user_id',
    ];

    protected $casts = [
        'is_custom' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ExerciseCategory::class, 'exercise_category_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function workoutSets(): HasMany
    {
        return $this->hasMany(WorkoutSet::class);
    }

    public function routineExercises(): HasMany
    {
        return $this->hasMany(RoutineExercise::class);
    }

    /**
     * Get user's personal record (PR) for this exercise (max 1RM or max weight)
     */
    public function getPersonalRecordForUser(int $userId): ?WorkoutSet
    {
        return $this->workoutSets()
            ->whereHas('workoutSession', fn($q) => $q->where('user_id', $userId))
            ->where('is_completed', true)
            ->orderByDesc('estimated_1rm')
            ->first();
    }
}
