<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workout_sets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workout_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exercise_id')->constrained()->cascadeOnDelete();
            $table->integer('set_number');
            $table->decimal('weight_kg', 6, 2)->default(0);
            $table->integer('reps')->default(0);
            $table->unsignedTinyInteger('rpe')->nullable(); // Rate of Perceived Exertion (1 - 10)
            $table->boolean('is_warmup')->default(false);
            $table->boolean('is_completed')->default(true);
            $table->decimal('estimated_1rm', 6, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workout_sets');
    }
};
