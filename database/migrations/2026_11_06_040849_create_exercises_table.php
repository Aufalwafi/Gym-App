<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercise_category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('equipment')->default('barbell'); // barbell, dumbbell, cable, machine, bodyweight, other
            $table->string('secondary_muscles')->nullable();
            $table->string('difficulty')->default('beginner'); // beginner, intermediate, advanced
            $table->text('instructions')->nullable();
            $table->text('tips')->nullable();
            $table->boolean('is_custom')->default(false);
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
