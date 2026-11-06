<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\RoutineController;
use App\Http\Controllers\WorkoutSessionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Workouts (Live Logging & History)
    Route::resource('workouts', WorkoutSessionController::class)->except(['edit', 'update']);

    // Routines (Workout Templates)
    Route::resource('routines', RoutineController::class);

    // Exercises (Library / Catalog)
    Route::resource('exercises', ExerciseController::class)->only(['index', 'show', 'create', 'store']);

    // Fitness Calculators (BMI, BMR, TDEE, Macros)
    Route::get('/calculator', [CalculatorController::class, 'index'])->name('calculator.index');
    Route::post('/calculator/save-goal', [CalculatorController::class, 'saveGoal'])->name('calculator.save-goal');

    // Body Measurements & PR Progress
    Route::get('/progress', [ProgressController::class, 'index'])->name('progress.index');
    Route::post('/progress', [ProgressController::class, 'store'])->name('progress.store');
    Route::delete('/progress/{id}', [ProgressController::class, 'destroy'])->name('progress.destroy');

    // Profile & Settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});
