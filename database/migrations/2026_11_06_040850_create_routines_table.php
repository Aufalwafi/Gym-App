<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('routines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete(); // nullable for system default templates
            $table->string('name'); // e.g. Push Day (Chest, Shoulders, Triceps)
            $table->string('target_split')->nullable(); // PPL, Upper/Lower, Bro Split, Full Body
            $table->string('day_schedule')->nullable(); // Senin, Selasa, etc.
            $table->text('description')->nullable();
            $table->boolean('is_system_template')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('routines');
    }
};
