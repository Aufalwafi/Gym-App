<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\ExerciseCategory;
use App\Models\WorkoutSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ExerciseController extends Controller
{
    public function index(Request $request)
    {
        $categories = ExerciseCategory::withCount('exercises')->get();

        $query = Exercise::with('category');

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('equipment')) {
            $query->where('equipment', $request->equipment);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('secondary_muscles', 'like', "%{$search}%");
            });
        }

        $exercises = $query->orderBy('name')->paginate(12)->withQueryString();

        return view('exercises.index', compact('exercises', 'categories'));
    }

    public function show(int $id)
    {
        $exercise = Exercise::with('category')->findOrFail($id);

        // Fetch user's best set (PR) and set history for this exercise
        $userSets = WorkoutSet::where('exercise_id', $exercise->id)
            ->whereHas('workoutSession', fn($q) => $q->where('user_id', Auth::id()))
            ->where('is_completed', true)
            ->with('workoutSession')
            ->orderByDesc('estimated_1rm')
            ->get();

        $personalRecord = $userSets->first();

        // Recent sets for history
        $recentSets = WorkoutSet::where('exercise_id', $exercise->id)
            ->whereHas('workoutSession', fn($q) => $q->where('user_id', Auth::id()))
            ->with('workoutSession')
            ->latest()
            ->take(8)
            ->get();

        return view('exercises.show', compact('exercise', 'personalRecord', 'recentSets'));
    }

    public function create()
    {
        $categories = ExerciseCategory::all();
        return view('exercises.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'exercise_category_id' => 'required|exists:exercise_categories,id',
            'name' => 'required|string|max:255',
            'equipment' => 'required|string|in:barbell,dumbbell,cable,machine,bodyweight,other',
            'difficulty' => 'required|string|in:beginner,intermediate,advanced',
            'secondary_muscles' => 'nullable|string|max:255',
            'instructions' => 'nullable|string',
            'tips' => 'nullable|string',
        ]);

        $slug = Str::slug($request->name) . '-' . uniqid();

        $exercise = Exercise::create([
            'exercise_category_id' => $request->exercise_category_id,
            'name' => $request->name,
            'slug' => $slug,
            'equipment' => $request->equipment,
            'difficulty' => $request->difficulty,
            'secondary_muscles' => $request->secondary_muscles,
            'instructions' => $request->instructions,
            'tips' => $request->tips,
            'is_custom' => true,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('exercises.show', $exercise->id)
            ->with('success', 'Gerakan baru "' . $exercise->name . '" berhasil ditambahkan ke katalog!');
    }
}
