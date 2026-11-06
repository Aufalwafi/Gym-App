<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\ExerciseCategory;
use App\Models\Routine;
use App\Models\RoutineExercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoutineController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $myRoutines = Routine::where('user_id', $userId)
            ->with(['routineExercises.exercise.category'])
            ->withCount('routineExercises')
            ->orderBy('name')
            ->get();

        $systemTemplates = Routine::where('is_system_template', true)
            ->with(['routineExercises.exercise.category'])
            ->withCount('routineExercises')
            ->orderBy('name')
            ->get();

        return view('routines.index', compact('myRoutines', 'systemTemplates'));
    }

    public function create()
    {
        $allExercises = Exercise::with('category')->orderBy('name')->get();
        $categories = ExerciseCategory::all();

        return view('routines.create', compact('allExercises', 'categories'));
    }

    public function store(Request $request)
    {
        $userId = Auth::id();

        $request->validate([
            'name' => 'required|string|max:255',
            'target_split' => 'nullable|string|max:100',
            'day_schedule' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'exercises' => 'required|array|min:1',
            'exercises.*.id' => 'required|exists:exercises,id',
            'exercises.*.target_sets' => 'required|integer|min:1|max:20',
            'exercises.*.target_reps' => 'required|string|max:50',
            'exercises.*.rest_seconds' => 'required|integer|min:10|max:600',
            'exercises.*.notes' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $routine = Routine::create([
                'user_id' => $userId,
                'name' => $request->name,
                'target_split' => $request->target_split,
                'day_schedule' => $request->day_schedule,
                'description' => $request->description,
                'is_system_template' => false,
            ]);

            $order = 1;
            foreach ($request->exercises as $exItem) {
                RoutineExercise::create([
                    'routine_id' => $routine->id,
                    'exercise_id' => $exItem['id'],
                    'order_index' => $order++,
                    'target_sets' => (int) $exItem['target_sets'],
                    'target_reps' => $exItem['target_reps'],
                    'rest_seconds' => (int) $exItem['rest_seconds'],
                    'notes' => $exItem['notes'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('routines.show', $routine->id)
                ->with('success', 'Program latihan "' . $routine->name . '" berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan program latihan: ' . $e->getMessage());
        }
    }

    public function show(int $id)
    {
        $userId = Auth::id();
        $routine = Routine::where(function ($q) use ($userId) {
            $q->where('user_id', $userId)->orWhere('is_system_template', true);
        })->with(['routineExercises.exercise.category'])->findOrFail($id);

        return view('routines.show', compact('routine'));
    }

    public function edit(int $id)
    {
        $routine = Routine::where('user_id', Auth::id())->with('routineExercises.exercise')->findOrFail($id);
        $allExercises = Exercise::with('category')->orderBy('name')->get();
        $categories = ExerciseCategory::all();

        return view('routines.edit', compact('routine', 'allExercises', 'categories'));
    }

    public function update(Request $request, int $id)
    {
        $routine = Routine::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'target_split' => 'nullable|string|max:100',
            'day_schedule' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'exercises' => 'required|array|min:1',
            'exercises.*.id' => 'required|exists:exercises,id',
            'exercises.*.target_sets' => 'required|integer|min:1|max:20',
            'exercises.*.target_reps' => 'required|string|max:50',
            'exercises.*.rest_seconds' => 'required|integer|min:10|max:600',
            'exercises.*.notes' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $routine->update([
                'name' => $request->name,
                'target_split' => $request->target_split,
                'day_schedule' => $request->day_schedule,
                'description' => $request->description,
            ]);

            // Replace routine exercises
            $routine->routineExercises()->delete();

            $order = 1;
            foreach ($request->exercises as $exItem) {
                RoutineExercise::create([
                    'routine_id' => $routine->id,
                    'exercise_id' => $exItem['id'],
                    'order_index' => $order++,
                    'target_sets' => (int) $exItem['target_sets'],
                    'target_reps' => $exItem['target_reps'],
                    'rest_seconds' => (int) $exItem['rest_seconds'],
                    'notes' => $exItem['notes'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('routines.show', $routine->id)
                ->with('success', 'Program latihan berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui program: ' . $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        $routine = Routine::where('user_id', Auth::id())->findOrFail($id);
        $routine->delete();

        return redirect()->route('routines.index')->with('success', 'Program latihan berhasil dihapus.');
    }
}
