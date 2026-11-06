<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\ExerciseCategory;
use App\Models\Routine;
use App\Models\WorkoutSession;
use App\Models\WorkoutSet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkoutSessionController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $query = WorkoutSession::where('user_id', $userId)
            ->with(['routine', 'sets.exercise'])
            ->orderByDesc('started_at');

        if ($request->filled('month')) {
            $monthDate = Carbon::parse($request->month);
            $query->whereYear('started_at', $monthDate->year)
                  ->whereMonth('started_at', $monthDate->month);
        }

        $sessions = $query->paginate(10)->withQueryString();

        $totalCompleted = WorkoutSession::where('user_id', $userId)->where('status', 'completed')->count();
        $totalVolumeSum = WorkoutSession::where('user_id', $userId)->where('status', 'completed')->sum('total_volume_kg');

        return view('workouts.index', compact('sessions', 'totalCompleted', 'totalVolumeSum'));
    }

    public function create(Request $request)
    {
        $userId = Auth::id();

        // Check if starting from a routine template
        $routine = null;
        $preloadedExercises = [];

        if ($request->filled('routine_id')) {
            $routine = Routine::where(function ($q) use ($userId) {
                $q->where('user_id', $userId)->orWhere('is_system_template', true);
            })->with('routineExercises.exercise.category')->find($request->routine_id);

            if ($routine) {
                foreach ($routine->routineExercises as $re) {
                    $preloadedExercises[] = [
                        'id' => $re->exercise->id,
                        'name' => $re->exercise->name,
                        'category' => $re->exercise->category->name ?? '',
                        'equipment' => $re->exercise->equipment,
                        'target_sets' => $re->target_sets,
                        'target_reps' => $re->target_reps,
                        'rest_seconds' => $re->rest_seconds,
                        'default_sets' => array_map(function ($setNum) {
                            return [
                                'set_number' => $setNum,
                                'weight_kg' => 0,
                                'reps' => 0,
                                'rpe' => 8,
                                'is_warmup' => false,
                                'is_completed' => true,
                            ];
                        }, range(1, $re->target_sets)),
                    ];
                }
            }
        }

        // All exercises for quick search & add
        $allExercises = Exercise::with('category')->orderBy('name')->get();
        $categories = ExerciseCategory::with('exercises')->get();

        return view('workouts.create', compact('routine', 'preloadedExercises', 'allExercises', 'categories'));
    }

    public function store(Request $request)
    {
        $userId = Auth::id();

        $request->validate([
            'title' => 'required|string|max:255',
            'started_at' => 'required|date',
            'duration_minutes' => 'nullable|integer|min:0',
            'routine_id' => 'nullable|exists:routines,id',
            'notes' => 'nullable|string',
            'exercises' => 'required|array|min:1',
            'exercises.*.id' => 'required|exists:exercises,id',
            'exercises.*.sets' => 'required|array|min:1',
            'exercises.*.sets.*.weight_kg' => 'required|numeric|min:0',
            'exercises.*.sets.*.reps' => 'required|integer|min:0',
            'exercises.*.sets.*.rpe' => 'nullable|integer|min:1|max:10',
            'exercises.*.sets.*.is_warmup' => 'nullable|boolean',
        ]);

        $startedAt = Carbon::parse($request->started_at);
        $duration = (int) ($request->duration_minutes ?: 45);
        $completedAt = $startedAt->copy()->addMinutes($duration);

        DB::beginTransaction();
        try {
            $session = WorkoutSession::create([
                'user_id' => $userId,
                'routine_id' => $request->routine_id,
                'title' => $request->title,
                'started_at' => $startedAt,
                'completed_at' => $completedAt,
                'duration_minutes' => $duration,
                'status' => 'completed',
                'notes' => $request->notes,
            ]);

            $totalSets = 0;
            $totalReps = 0;
            $totalVolume = 0;

            foreach ($request->exercises as $exItem) {
                $exerciseId = $exItem['id'];
                $setNum = 1;

                foreach ($exItem['sets'] as $set) {
                    $weight = (float) ($set['weight_kg'] ?? 0);
                    $reps = (int) ($set['reps'] ?? 0);
                    $rpe = !empty($set['rpe']) ? (int) $set['rpe'] : null;
                    $isWarmup = !empty($set['is_warmup']);

                    WorkoutSet::create([
                        'workout_session_id' => $session->id,
                        'exercise_id' => $exerciseId,
                        'set_number' => $setNum++,
                        'weight_kg' => $weight,
                        'reps' => $reps,
                        'rpe' => $rpe,
                        'is_warmup' => $isWarmup,
                        'is_completed' => true,
                    ]);

                    if (!$isWarmup) {
                        $totalSets++;
                        $totalReps += $reps;
                        $totalVolume += ($weight * $reps);
                    }
                }
            }

            $session->update([
                'total_sets' => $totalSets,
                'total_reps' => $totalReps,
                'total_volume_kg' => $totalVolume,
            ]);

            DB::commit();

            return redirect()->route('workouts.show', $session->id)
                ->with('success', 'Sesi latihan berhasil disimpan! Total volume: ' . number_format($totalVolume, 0) . ' kg.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan sesi latihan: ' . $e->getMessage());
        }
    }

    public function show(int $id)
    {
        $userId = Auth::id();
        $session = WorkoutSession::where('user_id', $userId)
            ->with(['routine', 'sets.exercise.category'])
            ->findOrFail($id);

        // Group sets by exercise
        $groupedSets = $session->sets->groupBy('exercise_id');

        return view('workouts.show', compact('session', 'groupedSets'));
    }

    public function destroy(int $id)
    {
        $session = WorkoutSession::where('user_id', Auth::id())->findOrFail($id);
        $session->delete();

        return redirect()->route('workouts.index')->with('success', 'Catatan sesi latihan berhasil dihapus.');
    }
}
