<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exercise_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. Dada (Chest), Punggung (Back), Kaki (Legs), etc.
            $table->string('slug')->unique();
            $table->string('icon')->default('dumbbell'); // icon name
            $table->string('color')->default('blue');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercise_categories');
    }
};
