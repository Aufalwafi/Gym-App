<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalculatorController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $latestWeight = $user->latestWeight() ?? 70.0;
        $height = (float) ($user->height_cm ?? 170.0);
        $age = (int) ($user->age ?? 22);
        $gender = $user->gender ?? 'male';
        $activity = $user->activity_level ?? 'moderate';
        $goal = $user->fitness_goal ?? 'maintenance';

        return view('calculator.index', compact('user', 'latestWeight', 'height', 'age', 'gender', 'activity', 'goal'));
    }

    public function saveGoal(Request $request)
    {
        $request->validate([
            'daily_calorie_target' => 'required|integer|min:800|max:10000',
            'daily_protein_target' => 'required|integer|min:30|max:500',
            'fitness_goal' => 'required|in:bulking,cutting,maintenance',
            'activity_level' => 'required|in:sedentary,light,moderate,active,very_active',
        ]);

        $user = Auth::user();
        $user->update([
            'daily_calorie_target' => $request->daily_calorie_target,
            'daily_protein_target' => $request->daily_protein_target,
            'fitness_goal' => $request->fitness_goal,
            'activity_level' => $request->activity_level,
        ]);

        return redirect()->route('calculator.index')
            ->with('success', 'Target kalori (' . $request->daily_calorie_target . ' kcal) & protein (' . $request->daily_protein_target . ' g) berhasil disimpan ke profil Anda!');
    }
}
