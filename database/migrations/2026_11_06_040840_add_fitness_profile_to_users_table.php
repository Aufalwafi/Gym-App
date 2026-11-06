<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('gender')->nullable()->after('password'); // male, female
            $table->integer('age')->nullable()->after('gender');
            $table->decimal('height_cm', 5, 2)->nullable()->after('age');
            $table->decimal('target_weight_kg', 5, 2)->nullable()->after('height_cm');
            $table->string('fitness_goal')->default('maintenance')->after('target_weight_kg'); // bulking, cutting, maintenance
            $table->string('activity_level')->default('moderate')->after('fitness_goal'); // sedentary, light, moderate, active, very_active
            $table->integer('daily_calorie_target')->nullable()->after('activity_level');
            $table->integer('daily_protein_target')->nullable()->after('daily_calorie_target');
            $table->string('theme_preference')->default('dark')->after('daily_protein_target'); // dark, light
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'gender',
                'age',
                'height_cm',
                'target_weight_kg',
                'fitness_goal',
                'activity_level',
                'daily_calorie_target',
                'daily_protein_target',
                'theme_preference',
            ]);
        });
    }
};
