@extends('layouts.app')

@section('title', 'Program & Jadwal Latihan')

@section('content')
<div class="space-y-8">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Program & Template Latihan</h1>
            <p class="text-zinc-400 text-xs sm:text-sm">Rancang jadwal mingguan Anda (Push/Pull/Legs, Upper/Lower, dsb) atau gunakan template siap pakai.</p>
        </div>

        <a href="{{ route('routines.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium text-xs transition-colors">
            <i data-lucide="plus" class="w-4 h-4"></i> Buat Program Baru
        </a>
    </div>

    <!-- Section 1: User's Custom Routines -->
    <div class="space-y-4">
        <h2 class="text-lg font-bold text-white flex items-center gap-2">
            <i data-lucide="user" class="w-5 h-5 text-green-500"></i> Program Saya
        </h2>

        @if($myRoutines->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($myRoutines as $routine)
                    <div class="bg-zinc-900 border border-zinc-800 hover:border-zinc-700 rounded-xl p-5 flex flex-col justify-between transition-colors group">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-zinc-800 text-zinc-300 border border-zinc-700">
                                    {{ $routine->target_split ?? 'Custom Split' }}
                                </span>
                                @if($routine->day_schedule)
                                    <span class="text-xs text-zinc-400 font-medium">{{ $routine->day_schedule }}</span>
                                @endif
                            </div>

                            <h3 class="text-lg font-bold text-white group-hover:text-green-400 transition-colors">
                                <a href="{{ route('routines.show', $routine->id) }}">{{ $routine->name }}</a>
                            </h3>

                            <p class="text-xs text-zinc-400 mt-1 line-clamp-2">
                                {{ $routine->description ?? 'Program latihan kustom.' }}
                            </p>

                            <div class="mt-4 pt-3 border-t border-zinc-800 space-y-1.5">
                                <p class="text-xs text-zinc-500 font-medium">Daftar Gerakan ({{ $routine->routine_exercises_count }}):</p>
                                <ul class="text-xs text-zinc-300 space-y-1">
                                    @foreach($routine->routineExercises->take(4) as $re)
                                        <li class="flex items-center justify-between">
                                            <span class="truncate">&bull; {{ $re->exercise->name ?? 'Latihan' }}</span>
                                            <span class="text-zinc-500 text-[11px] shrink-0">{{ $re->target_sets }} set &bull; {{ $re->target_reps }}</span>
                                        </li>
                                    @endforeach
                                    @if($routine->routineExercises->count() > 4)
                                        <li class="text-[11px] text-zinc-500 italic">+{{ $routine->routineExercises->count() - 4 }} gerakan lainnya</li>
                                    @endif
                                </ul>
                            </div>
                        </div>

                        <div class="mt-5 pt-3 border-t border-zinc-800 flex items-center gap-2">
                            <a href="{{ route('workouts.create', ['routine_id' => $routine->id]) }}" class="flex-1 py-2 px-3 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium text-xs flex items-center justify-center gap-1.5 transition-colors">
                                <i data-lucide="play" class="w-3.5 h-3.5 fill-current"></i> Mulai Latihan
                            </a>
                            <a href="{{ route('routines.edit', $routine->id) }}" class="p-2 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-300 transition-colors" title="Edit">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-zinc-900 border border-dashed border-zinc-800 rounded-xl p-8 text-center space-y-3">
                <i data-lucide="dumbbell" class="w-8 h-8 text-zinc-600 mx-auto"></i>
                <p class="text-zinc-400 font-semibold text-sm">Anda belum membuat program latihan sendiri</p>
                <p class="text-zinc-500 text-xs">Buat jadwal latihan yang disesuaikan dengan hari dan target Anda.</p>
                <a href="{{ route('routines.create') }}" class="inline-block px-4 py-2 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-green-400 font-medium text-xs mt-1 transition-colors">
                    + Buat Program Sekarang
                </a>
            </div>
        @endif
    </div>

    <!-- Section 2: System Template Routines (PPL, etc.) -->
    <div class="space-y-4">
        <h2 class="text-lg font-bold text-white flex items-center gap-2">
            <i data-lucide="layout-grid" class="w-5 h-5 text-amber-400"></i> Template Standar Gym (Siap Pakai)
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($systemTemplates as $routine)
                <div class="bg-zinc-900 border border-zinc-800 hover:border-zinc-700 rounded-xl p-5 flex flex-col justify-between transition-colors group">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-zinc-800 text-amber-400 border border-zinc-700">
                                {{ $routine->target_split ?? 'PPL Split' }}
                            </span>
                            @if($routine->day_schedule)
                                <span class="text-xs text-zinc-400 font-medium">{{ $routine->day_schedule }}</span>
                            @endif
                        </div>

                        <h3 class="text-lg font-bold text-white group-hover:text-green-400 transition-colors">
                            <a href="{{ route('routines.show', $routine->id) }}">{{ $routine->name }}</a>
                        </h3>

                        <p class="text-xs text-zinc-400 mt-1 line-clamp-2">
                            {{ $routine->description }}
                        </p>

                        <div class="mt-4 pt-3 border-t border-zinc-800 space-y-1.5">
                            <p class="text-xs text-zinc-500 font-medium">Susunan Gerakan ({{ $routine->routine_exercises_count }}):</p>
                            <ul class="text-xs text-zinc-300 space-y-1">
                                @foreach($routine->routineExercises->take(4) as $re)
                                    <li class="flex items-center justify-between">
                                        <span class="truncate">&bull; {{ $re->exercise->name ?? 'Latihan' }}</span>
                                        <span class="text-zinc-500 text-[11px] shrink-0">{{ $re->target_sets }} set &bull; {{ $re->target_reps }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="mt-5 pt-3 border-t border-zinc-800 flex items-center gap-2">
                        <a href="{{ route('workouts.create', ['routine_id' => $routine->id]) }}" class="w-full py-2 px-3 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium text-xs flex items-center justify-center gap-1.5 transition-colors">
                            <i data-lucide="play" class="w-3.5 h-3.5 fill-current"></i> Mulai Latihan Dari Template Ini
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
