@extends('layouts.app')

@section('title', $session->title)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Navigation & Action -->
    <div class="flex items-center justify-between no-print">
        <a href="{{ route('workouts.index') }}" class="inline-flex items-center gap-1.5 text-xs text-zinc-400 hover:text-white transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Riwayat
        </a>

        <div class="flex items-center gap-2">
            <button type="button" onclick="window.print()" class="px-3.5 py-1.5 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-zinc-200 text-xs font-semibold flex items-center gap-1.5 border border-zinc-700 transition-colors">
                <i data-lucide="printer" class="w-4 h-4"></i> Cetak / Ekspor PDF
            </button>
            <form action="{{ route('workouts.destroy', $session->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan latihan ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-rose-950/40 hover:bg-rose-900/60 border border-rose-800/60 text-rose-300 text-xs font-semibold flex items-center gap-1.5 transition-colors">
                    <i data-lucide="trash-2" class="w-4 h-4"></i> Hapus
                </button>
            </form>
        </div>
    </div>

    <!-- Session Header Card -->
    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 sm:p-8 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-green-500 mb-1">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                    <span>{{ \Carbon\Carbon::parse($session->started_at)->translatedFormat('l, d F Y &bull; H:i') }} WIB</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">{{ $session->title }}</h1>
                @if($session->routine)
                    <p class="text-xs text-zinc-400 mt-1">Dibuat dari template program: <span class="text-zinc-200 font-semibold">{{ $session->routine->name }}</span></p>
                @endif
            </div>

            <div class="flex items-center gap-4 bg-zinc-950 border border-zinc-800 rounded-xl p-4 shrink-0">
                <div class="text-center px-2">
                    <span class="text-[10px] uppercase font-bold text-zinc-500 block">Durasi</span>
                    <span class="text-lg font-extrabold text-white">{{ $session->duration_minutes }} <span class="text-xs font-normal text-zinc-400">m</span></span>
                </div>
                <div class="w-px h-8 bg-zinc-800"></div>
                <div class="text-center px-2">
                    <span class="text-[10px] uppercase font-bold text-zinc-500 block">Total Volume</span>
                    <span class="text-lg font-extrabold text-green-500">{{ number_format($session->total_volume_kg, 0, ',', '.') }} <span class="text-xs font-normal text-zinc-400">kg</span></span>
                </div>
                <div class="w-px h-8 bg-zinc-800"></div>
                <div class="text-center px-2">
                    <span class="text-[10px] uppercase font-bold text-zinc-500 block">Set / Reps</span>
                    <span class="text-lg font-extrabold text-white">{{ $session->total_sets }} / {{ $session->total_reps }}</span>
                </div>
            </div>
        </div>

        @if($session->notes)
            <div class="pt-3 border-t border-zinc-800 text-xs text-zinc-300 bg-zinc-950 p-3 rounded-lg">
                <span class="font-bold text-zinc-400">Catatan:</span> {{ $session->notes }}
            </div>
        @endif
    </div>

    <!-- Exercises & Sets Breakdown -->
    <div class="space-y-4">
        <h2 class="text-lg font-bold text-white flex items-center gap-2">
            <i data-lucide="dumbbell" class="w-5 h-5 text-green-500"></i> Rincian Gerakan & Angkatan
        </h2>

        @foreach($groupedSets as $exerciseId => $sets)
            @php $firstSet = $sets->first(); $exercise = $firstSet->exercise; @endphp
            <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-5 space-y-3">
                <div class="flex items-center justify-between border-b border-zinc-800 pb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-zinc-800 flex items-center justify-center text-green-500 font-bold text-xs">
                            <i data-lucide="activity" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-white text-base">
                                <a href="{{ route('exercises.show', $exercise->id) }}" class="hover:text-green-400 transition-colors">
                                    {{ $exercise->name }}
                                </a>
                            </h3>
                            <p class="text-[11px] text-zinc-400">
                                Otot: <span class="text-zinc-300">{{ $exercise->category->name ?? '-' }}</span> &bull;
                                Alat: <span class="capitalize text-zinc-300">{{ $exercise->equipment }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="text-right">
                        <span class="text-[11px] text-zinc-500 block">Subtotal Volume</span>
                        <span class="text-sm font-bold text-white">
                            {{ number_format($sets->sum(fn($s) => $s->weight_kg * $s->reps), 0, ',', '.') }} kg
                        </span>
                    </div>
                </div>

                <!-- Sets Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-zinc-500 border-b border-zinc-800/80">
                                <th class="py-2 px-3 w-16">Set</th>
                                <th class="py-2 px-3">Beban</th>
                                <th class="py-2 px-3">Repetisi</th>
                                <th class="py-2 px-3">RPE</th>
                                <th class="py-2 px-3">Estimated 1RM</th>
                                <th class="py-2 px-3 text-right">Volume Set</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800/40">
                            @foreach($sets as $set)
                                <tr class="hover:bg-zinc-800/30">
                                    <td class="py-2.5 px-3 font-bold text-zinc-300">Set {{ $set->set_number }}</td>
                                    <td class="py-2.5 px-3 font-semibold text-white">{{ $set->weight_kg }} kg</td>
                                    <td class="py-2.5 px-3 font-semibold text-white">{{ $set->reps }} reps</td>
                                    <td class="py-2.5 px-3 text-zinc-400">
                                        @if($set->rpe)
                                            <span class="px-2 py-0.5 rounded bg-zinc-800 text-zinc-300 border border-zinc-700 font-medium">RPE {{ $set->rpe }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="py-2.5 px-3 font-mono font-bold text-green-500">
                                        {{ $set->estimated_1rm ? $set->estimated_1rm . ' kg' : '-' }}
                                    </td>
                                    <td class="py-2.5 px-3 text-right font-medium text-zinc-300">
                                        {{ number_format($set->weight_kg * $set->reps, 0, ',', '.') }} kg
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
