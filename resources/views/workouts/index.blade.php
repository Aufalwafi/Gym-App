@extends('layouts.app')

@section('title', 'Riwayat Sesi Latihan')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Riwayat Sesi Latihan</h1>
            <p class="text-zinc-400 text-xs sm:text-sm">Seluruh catatan log latihan gym dan volume beban yang telah Anda selesaikan.</p>
        </div>

        <a href="{{ route('workouts.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium text-xs transition-colors">
            <i data-lucide="plus" class="w-4 h-4"></i> Catat Sesi Baru
        </a>
    </div>

    <!-- Summary Stats Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
        <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-4">
            <span class="text-xs text-zinc-400 font-medium">Total Sesi Tercatat</span>
            <p class="text-2xl font-extrabold text-white mt-1">{{ $totalCompleted }}</p>
        </div>
        <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-4">
            <span class="text-xs text-zinc-400 font-medium">Total Volume Terangkat</span>
            <p class="text-2xl font-extrabold text-white mt-1">{{ number_format($totalVolumeSum, 0, ',', '.') }} <span class="text-xs font-semibold text-zinc-500">kg</span></p>
        </div>
        <div class="col-span-2 sm:col-span-1 bg-zinc-900 border border-zinc-800 rounded-xl p-4 flex items-center justify-between">
            <form action="{{ route('workouts.index') }}" method="GET" class="w-full flex items-center gap-2">
                <input type="month" name="month" value="{{ request('month') }}" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-1.5 text-xs text-zinc-200 focus:outline-none focus:border-green-500">
                <button type="submit" class="px-3 py-1.5 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-xs font-medium text-zinc-200">
                    Filter
                </button>
            </form>
        </div>
    </div>

    <!-- Workout History List -->
    <div class="space-y-4">
        @forelse($sessions as $session)
            <div class="bg-zinc-900 border border-zinc-800 hover:border-zinc-700 rounded-xl p-5 transition-colors">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                    <div class="space-y-2 flex-1">
                        <div class="flex flex-wrap items-center gap-2 text-xs">
                            <span class="px-2.5 py-0.5 rounded bg-zinc-800 text-zinc-300 border border-zinc-700 font-medium">
                                {{ \Carbon\Carbon::parse($session->started_at)->translatedFormat('l, d F Y') }}
                            </span>
                            <span class="text-zinc-500">&bull;</span>
                            <span class="text-zinc-400 flex items-center gap-1">
                                <i data-lucide="clock" class="w-3.5 h-3.5 text-zinc-500"></i> {{ $session->duration_minutes }} menit
                            </span>
                            @if($session->routine)
                                <span class="text-zinc-500">&bull;</span>
                                <span class="text-zinc-400">Program: {{ $session->routine->name }}</span>
                            @endif
                        </div>

                        <h2 class="text-lg font-bold text-white">
                            <a href="{{ route('workouts.show', $session->id) }}" class="hover:text-green-400 transition-colors">
                                {{ $session->title }}
                            </a>
                        </h2>

                        @if($session->notes)
                            <p class="text-xs text-zinc-400 italic">"{{ $session->notes }}"</p>
                        @endif

                        <!-- Exercises Badge List -->
                        <div class="flex flex-wrap gap-1.5 pt-1">
                            @foreach($session->sets->groupBy('exercise_id') as $exId => $sets)
                                <span class="text-xs px-2.5 py-1 rounded-lg bg-zinc-800 text-zinc-200 border border-zinc-700/50">
                                    <span class="font-semibold">{{ $sets->first()->exercise->name ?? 'Latihan' }}</span>
                                    <span class="text-zinc-400 text-[11px]">({{ $sets->count() }} set &bull; max {{ $sets->max('weight_kg') }}kg)</span>
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Volume, Sets & Action Buttons -->
                    <div class="flex sm:flex-col items-center sm:items-end justify-between sm:justify-start gap-3 shrink-0 pt-3 sm:pt-0 border-t sm:border-0 border-zinc-800">
                        <div class="text-left sm:text-right">
                            <span class="text-[11px] text-zinc-500 uppercase tracking-wider block">Total Volume</span>
                            <span class="text-xl font-extrabold text-white">{{ number_format($session->total_volume_kg, 0, ',', '.') }} kg</span>
                            <span class="text-xs text-zinc-400 block">{{ $session->total_sets }} set &bull; {{ $session->total_reps }} reps</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('workouts.show', $session->id) }}" class="px-3 py-1.5 rounded-lg bg-zinc-800 hover:bg-green-600 hover:text-white text-zinc-300 text-xs font-medium transition-colors">
                                Detail
                            </a>
                            <form action="{{ route('workouts.destroy', $session->id) }}" method="POST" onsubmit="return confirm('Hapus catatan sesi latihan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg text-zinc-500 hover:text-rose-400 hover:bg-zinc-800 transition-colors" title="Hapus">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-zinc-900 border border-dashed border-zinc-800 rounded-xl p-10 text-center space-y-3">
                <i data-lucide="calendar" class="w-8 h-8 text-zinc-600 mx-auto"></i>
                <p class="text-zinc-400 font-semibold text-sm">Belum ada catatan latihan pada filter ini</p>
                <a href="{{ route('workouts.create') }}" class="inline-block px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium text-xs mt-2 transition-colors">
                    Mulai Catat Sesi Latihan
                </a>
            </div>
        @endforelse

        <div class="mt-4">
            {{ $sessions->links() }}
        </div>
    </div>

</div>
@endsection
