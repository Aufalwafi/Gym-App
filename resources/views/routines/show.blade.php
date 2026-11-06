@extends('layouts.app')

@section('title', $routine->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Back & Actions -->
    <div class="flex items-center justify-between">
        <a href="{{ route('routines.index') }}" class="inline-flex items-center gap-1.5 text-xs text-zinc-400 hover:text-white transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Daftar Program
        </a>

        <div class="flex items-center gap-2">
            @if(!$routine->is_system_template)
                <a href="{{ route('routines.edit', $routine->id) }}" class="px-3.5 py-1.5 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-zinc-200 text-xs font-semibold flex items-center gap-1.5 border border-zinc-700 transition-colors">
                    <i data-lucide="edit-3" class="w-4 h-4"></i> Edit
                </a>
                <form action="{{ route('routines.destroy', $routine->id) }}" method="POST" onsubmit="return confirm('Hapus program latihan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-rose-950/40 hover:bg-rose-900/60 border border-rose-800/60 text-rose-300 text-xs font-semibold flex items-center gap-1.5 transition-colors">
                        <i data-lucide="trash-2" class="w-4 h-4"></i> Hapus
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Header Card -->
    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 sm:p-8 space-y-4 shadow-xl">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                        {{ $routine->target_split ?? 'Program Latihan' }}
                    </span>
                    @if($routine->day_schedule)
                        <span class="text-xs text-zinc-400">&bull; {{ $routine->day_schedule }}</span>
                    @endif
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">{{ $routine->name }}</h1>
                <p class="text-zinc-400 text-sm mt-1 max-w-xl">{{ $routine->description ?? 'Program terstruktur untuk progres latihan konsisten.' }}</p>
            </div>

            <a href="{{ route('workouts.create', ['routine_id' => $routine->id]) }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-zinc-950 font-black text-sm shadow-xl shadow-emerald-500/25 transition-all hover:scale-105 shrink-0">
                <i data-lucide="play" class="w-4 h-4 fill-current"></i> Mulai Sesi Sekarang
            </a>
        </div>
    </div>

    <!-- Exercises List -->
    <div class="space-y-3">
        <h2 class="text-lg font-bold text-white flex items-center gap-2">
            <i data-lucide="list-checks" class="w-5 h-5 text-emerald-400"></i> Susunan Gerakan ({{ $routine->routineExercises->count() }} Latihan)
        </h2>

        @foreach($routine->routineExercises as $index => $re)
            <div class="bg-zinc-900/80 border border-zinc-800 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-md">
                <div class="flex items-center gap-4">
                    <span class="w-9 h-9 rounded-xl bg-zinc-800 text-emerald-400 font-black text-sm flex items-center justify-center shrink-0">
                        {{ $index + 1 }}
                    </span>
                    <div>
                        <h3 class="font-bold text-white text-base">
                            <a href="{{ route('exercises.show', $re->exercise->id) }}" class="hover:text-emerald-400 transition-colors">
                                {{ $re->exercise->name }}
                            </a>
                        </h3>
                        <p class="text-xs text-zinc-400">
                            Kelompok: <span class="text-zinc-300">{{ $re->exercise->category->name ?? '-' }}</span> &bull;
                            Alat: <span class="capitalize text-zinc-300">{{ $re->exercise->equipment }}</span>
                            @if($re->notes)
                                &bull; <span class="text-amber-400 italic">"{{ $re->notes }}"</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-6 bg-zinc-950 border border-zinc-800/80 rounded-xl px-4 py-2 shrink-0">
                    <div class="text-center">
                        <span class="text-[10px] uppercase font-bold text-zinc-500 block">Target Set</span>
                        <span class="text-sm font-extrabold text-white">{{ $re->target_sets }} Set</span>
                    </div>
                    <div class="w-px h-6 bg-zinc-800"></div>
                    <div class="text-center">
                        <span class="text-[10px] uppercase font-bold text-zinc-500 block">Target Reps</span>
                        <span class="text-sm font-extrabold text-emerald-400">{{ $re->target_reps }}</span>
                    </div>
                    <div class="w-px h-6 bg-zinc-800"></div>
                    <div class="text-center">
                        <span class="text-[10px] uppercase font-bold text-zinc-500 block">Rest</span>
                        <span class="text-sm font-extrabold text-white">{{ $re->rest_seconds }}s</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
