<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'age',
        'height_cm',
        'target_weight_kg',
        'fitness_goal',
        'activity_level',
        'daily_calorie_target',
        'daily_protein_target',
        'theme_preference',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'age' => 'integer',
            'height_cm' => 'decimal:2',
            'target_weight_kg' => 'decimal:2',
            'daily_calorie_target' => 'integer',
            'daily_protein_target' => 'integer',
        ];
    }

    public function workoutSessions(): HasMany
    {
        return $this->hasMany(WorkoutSession::class)->orderByDesc('started_at');
    }

    public function routines(): HasMany
    {
        return $this->hasMany(Routine::class);
    }

    public function bodyMeasurements(): HasMany
    {
        return $this->hasMany(BodyMeasurement::class)->orderByDesc('recorded_date');
    }

    public function customExercises(): HasMany
    {
        return $this->hasMany(Exercise::class);
    }

    public function latestWeight(): ?float
    {
        $latest = $this->bodyMeasurements()->first();
        return $latest ? (float) $latest->weight_kg : null;
    }

    /**
     * Calculate BMI based on height and latest recorded weight
     */
    public function calculateBmi(): ?array
    {
        $weight = $this->latestWeight();
        if (!$weight || !$this->height_cm || $this->height_cm <= 0) {
            return null;
        }

        $heightInMeters = $this->height_cm / 100;
        $bmi = round($weight / ($heightInMeters * $heightInMeters), 1);

        $category = 'Normal';
        $color = 'emerald';
        if ($bmi < 18.5) {
            $category = 'Underweight';
            $color = 'amber';
        } elseif ($bmi >= 25.0 && $bmi < 29.9) {
            $category = 'Overweight';
            $color = 'amber';
        } elseif ($bmi >= 30.0) {
            $category = 'Obese';
            $color = 'rose';
        }

        return [
            'score' => $bmi,
            'category' => $category,
            'color' => $color,
            'weight' => $weight,
            'height' => (float) $this->height_cm,
        ];
    }

    /**
     * Calculate BMR & TDEE using Mifflin-St Jeor formula
     */
    public function calculateTdee(): ?array
    {
        $weight = $this->latestWeight();
        if (!$weight || !$this->height_cm || !$this->age || !$this->gender) {
            return null;
        }

        // Mifflin-St Jeor
        // Male: 10 * weight(kg) + 6.25 * height(cm) - 5 * age + 5
        // Female: 10 * weight(kg) + 6.25 * height(cm) - 5 * age - 161
        if ($this->gender === 'male') {
            $bmr = (10 * $weight) + (6.25 * (float)$this->height_cm) - (5 * $this->age) + 5;
        } else {
            $bmr = (10 * $weight) + (6.25 * (float)$this->height_cm) - (5 * $this->age) - 161;
        }

        $multipliers = [
            'sedentary' => 1.2,
            'light' => 1.375,
            'moderate' => 1.55,
            'active' => 1.725,
            'very_active' => 1.9,
        ];

        $multiplier = $multipliers[$this->activity_level] ?? 1.55;
        $tdee = round($bmr * $multiplier);

        // Adjust based on goal
        $adjustedCalories = match ($this->fitness_goal) {
            'cutting' => round($tdee - 450), // 450 kcal deficit
            'bulking' => round($tdee + 350), // 350 kcal surplus
            default => $tdee,
        };

        // Macronutrient suggestion (approx 2g protein per kg)
        $proteinGrams = round($weight * 2.0);
        $fatCalories = $adjustedCalories * 0.25;
        $fatGrams = round($fatCalories / 9);
        $carbsCalories = $adjustedCalories - ($proteinGrams * 4) - $fatCalories;
        $carbsGrams = round(max(0, $carbsCalories / 4));

        return [
            'bmr' => round($bmr),
            'tdee' => $tdee,
            'target_calories' => $adjustedCalories,
            'protein_grams' => $proteinGrams,
            'carbs_grams' => $carbsGrams,
            'fat_grams' => $fatGrams,
        ];
    }
}
