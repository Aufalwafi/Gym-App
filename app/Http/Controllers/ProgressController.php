<?php

namespace App\Http\Controllers;

use App\Models\BodyMeasurement;
use App\Models\WorkoutSet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProgressController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // 1. Body Measurements History
        $measurements = BodyMeasurement::where('user_id', $userId)
            ->orderByDesc('recorded_date')
            ->paginate(15);

        // 2. Chart Data (Chronological order)
        $chartData = BodyMeasurement::where('user_id', $userId)
            ->orderBy('recorded_date')
            ->take(30)
            ->get();

        $chartLabels = $chartData->pluck('recorded_date')->map(fn($d) => Carbon::parse($d)->format('d M'))->toArray();
        $chartWeights = $chartData->pluck('weight_kg')->map(fn($w) => (float)$w)->toArray();
        $chartBodyFat = $chartData->pluck('body_fat_percentage')->map(fn($bf) => $bf ? (float)$bf : null)->toArray();

        // 3. Personal Records (PRs)
        // Group by exercise, get maximum estimated_1rm
        $personalRecords = WorkoutSet::whereHas('workoutSession', fn($q) => $q->where('user_id', $userId))
            ->where('is_completed', true)
            ->where('estimated_1rm', '>', 0)
            ->with(['exercise.category', 'workoutSession'])
            ->select('exercise_id', DB::raw('MAX(estimated_1rm) as max_1rm'), DB::raw('MAX(weight_kg) as max_weight'))
            ->groupBy('exercise_id')
            ->orderByDesc('max_1rm')
            ->take(10)
            ->get();

        // Enrich PR details
        foreach ($personalRecords as $pr) {
            $bestSet = WorkoutSet::whereHas('workoutSession', fn($q) => $q->where('user_id', $userId))
                ->where('exercise_id', $pr->exercise_id)
                ->where('is_completed', true)
                ->orderByDesc('estimated_1rm')
                ->with('workoutSession')
                ->first();

            $pr->best_set = $bestSet;
        }

        return view('progress.index', compact('measurements', 'chartLabels', 'chartWeights', 'chartBodyFat', 'personalRecords'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'recorded_date' => 'required|date',
            'weight_kg' => 'required|numeric|min:20|max:300',
            'body_fat_percentage' => 'nullable|numeric|min:3|max:60',
            'waist_cm' => 'nullable|numeric|min:30|max:200',
            'chest_cm' => 'nullable|numeric|min:30|max:200',
            'arms_cm' => 'nullable|numeric|min:15|max:100',
            'notes' => 'nullable|string',
        ]);

        BodyMeasurement::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'recorded_date' => $request->recorded_date,
            ],
            [
                'weight_kg' => $request->weight_kg,
                'body_fat_percentage' => $request->body_fat_percentage,
                'waist_cm' => $request->waist_cm,
                'chest_cm' => $request->chest_cm,
                'arms_cm' => $request->arms_cm,
                'notes' => $request->notes,
            ]
        );

        return redirect()->route('progress.index')
            ->with('success', 'Catatan berat badan & ukuran fisik tanggal ' . Carbon::parse($request->recorded_date)->format('d M Y') . ' berhasil disimpan!');
    }

    public function destroy(int $id)
    {
        $measurement = BodyMeasurement::where('user_id', Auth::id())->findOrFail($id);
        $measurement->delete();

        return redirect()->route('progress.index')->with('success', 'Catatan progres fisik berhasil dihapus.');
    }
}
