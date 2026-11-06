<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkoutSession extends Model
{
    protected $fillable = [
        'user_id',
        'routine_id',
        'title',
        'started_at',
        'completed_at',
        'duration_minutes',
        'total_volume_kg',
        'total_sets',
        'total_reps',
        'status',
        'notes',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'duration_minutes' => 'integer',
        'total_volume_kg' => 'decimal:2',
        'total_sets' => 'integer',
        'total_reps' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function routine(): BelongsTo
    {
        return $this->belongsTo(Routine::class);
    }

    public function sets(): HasMany
    {
        return $this->hasMany(WorkoutSet::class)->orderBy('exercise_id')->orderBy('set_number');
    }

    /**
     * Recalculate totals (volume, sets, reps) from sets
     */
    public function recalculateTotals(): void
    {
        $completedSets = $this->sets()->where('is_completed', true)->get();
        $this->total_sets = $completedSets->count();
        $this->total_reps = $completedSets->sum('reps');
        $this->total_volume_kg = $completedSets->sum(fn($s) => (float)$s->weight_kg * (int)$s->reps);
        $this->save();
    }
}
