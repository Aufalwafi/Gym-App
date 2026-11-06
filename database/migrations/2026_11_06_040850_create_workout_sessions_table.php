<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workout_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('routine_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title'); // e.g. "Sesi Push Day - Dada & Bahu"
            $table->dateTime('started_at');
            $table->dateTime('completed_at')->nullable();
            $table->integer('duration_minutes')->default(0);
            $table->decimal('total_volume_kg', 10, 2)->default(0);
            $table->integer('total_sets')->default(0);
            $table->integer('total_reps')->default(0);
            $table->string('status')->default('completed'); // in_progress, completed, cancelled
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workout_sessions');
    }
};
