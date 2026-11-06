<?php

namespace App\Http\Controllers;

use App\Models\BodyMeasurement;
use App\Models\Routine;
use App\Models\WorkoutSession;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Core Metrics
        $totalWorkouts = WorkoutSession::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $thisMonthWorkouts = WorkoutSession::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereMonth('started_at', Carbon::now()->month)
            ->whereYear('started_at', Carbon::now()->year)
            ->count();

        $totalVolume = WorkoutSession::where('user_id', $user->id)
            ->where('status', 'completed')
            ->sum('total_volume_kg');

        // Workouts this week (Monday to Sunday)
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $thisWeekCount = WorkoutSession::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereBetween('started_at', [$startOfWeek, $endOfWeek])
            ->count();

        // 2. BMI and Nutrition Target
        $bmiData = $user->calculateBmi();
        $tdeeData = $user->calculateTdee();

        // 3. Recent Workouts
        $recentWorkouts = WorkoutSession::where('user_id', $user->id)
            ->with(['routine', 'sets.exercise'])
            ->orderByDesc('started_at')
            ->take(4)
            ->get();

        // 4. Quick-start Routines (User's custom + system templates)
        $quickRoutines = Routine::where('user_id', $user->id)
            ->orWhere('is_system_template', true)
            ->withCount('routineExercises')
            ->take(3)
            ->get();

        // 5. Weight Trend (last 6 recordings)
        $weightLogs = BodyMeasurement::where('user_id', $user->id)
            ->orderBy('recorded_date')
            ->take(10)
            ->get();

        $chartLabels = $weightLogs->pluck('recorded_date')->map(fn($d) => Carbon::parse($d)->format('d M'))->toArray();
        $chartWeights = $weightLogs->pluck('weight_kg')->map(fn($w) => (float)$w)->toArray();

        return view('dashboard', compact(
            'user',
            'totalWorkouts',
            'thisMonthWorkouts',
            'totalVolume',
            'thisWeekCount',
            'bmiData',
            'tdeeData',
            'recentWorkouts',
            'quickRoutines',
            'chartLabels',
            'chartWeights'
        ));
    }
}
