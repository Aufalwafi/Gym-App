@extends('layouts.app')

@section('title', $exercise->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Back Button -->
    <div>
        <a href="{{ route('exercises.index') }}" class="inline-flex items-center gap-1.5 text-xs text-zinc-400 hover:text-white transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Katalog
        </a>
    </div>

    <!-- Header Card -->
    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 sm:p-8 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
            <div>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <span class="text-xs font-medium uppercase tracking-wider px-2.5 py-0.5 rounded bg-zinc-800 text-zinc-300 border border-zinc-700">
                        {{ $exercise->category->name ?? 'Gym' }}
                    </span>
                    <span class="text-xs px-2.5 py-0.5 rounded bg-zinc-800 text-zinc-300 capitalize font-medium">
                        {{ $exercise->equipment }}
                    </span>
                    <span class="text-xs px-2.5 py-0.5 rounded bg-zinc-800 text-zinc-300 capitalize font-medium">
                        Level: {{ $exercise->difficulty }}
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">{{ $exercise->name }}</h1>
                @if($exercise->secondary_muscles)
                    <p class="text-xs text-zinc-400 mt-1">Otot Sekunder: <span class="text-zinc-200 font-semibold">{{ $exercise->secondary_muscles }}</span></p>
                @endif
            </div>

            <!-- Personal Record (PR) Badge -->
            <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-4 sm:min-w-[200px] text-right shrink-0">
                <span class="text-[10px] font-bold uppercase tracking-wider text-amber-400 flex items-center justify-end gap-1">
                    <i data-lucide="award" class="w-3.5 h-3.5"></i> Personal Record (PR)
                </span>
                @if($personalRecord)
                    <p class="text-2xl font-extrabold text-white mt-1">
                        {{ $personalRecord->weight_kg }} kg <span class="text-xs text-zinc-400">&times; {{ $personalRecord->reps }}</span>
                    </p>
                    <p class="text-[11px] text-green-500 font-semibold mt-0.5">
                        Est. 1RM: {{ $personalRecord->estimated_1rm }} kg
                    </p>
                    <p class="text-[10px] text-zinc-500 mt-0.5">
                        {{ \Carbon\Carbon::parse($personalRecord->workoutSession->started_at ?? now())->format('d M Y') }}
                    </p>
                @else
                    <p class="text-xs text-zinc-500 mt-1 italic">Belum ada rekor tercatat</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Instructions & Form Tips -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <!-- Cara Melakukan -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-5 space-y-3">
            <h2 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                <i data-lucide="check-circle-2" class="w-4 h-4 text-green-500"></i> Cara Melakukan Gerakan
            </h2>
            <p class="text-xs text-zinc-300 leading-relaxed">
                {{ $exercise->instructions ?? 'Lakukan pemanasan secukupnya, posisikan tubuh seimbang dan lakukan gerakan dengan rentang gerak penuh (full range of motion).' }}
            </p>
        </div>

        <!-- Tips Keamanan & Form -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-5 space-y-3">
            <h2 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4 text-amber-400"></i> Tips Form & Keamanan
            </h2>
            <p class="text-xs text-zinc-300 leading-relaxed">
                {{ $exercise->tips ?? 'Jangan mengayunkan beban secara berlebihan. Utamakan teknik gerakan yang benar daripada menambah beban terlalu dini.' }}
            </p>
        </div>
    </div>

    <!-- History of Sets for this Exercise -->
    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 space-y-4">
        <h2 class="text-base font-bold text-white flex items-center gap-2">
            <i data-lucide="history" class="w-5 h-5 text-blue-400"></i> Riwayat Angkatan Terakhir Anda
        </h2>

        @if($recentSets->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-zinc-500 border-b border-zinc-800">
                            <th class="py-2.5 px-3">Tanggal</th>
                            <th class="py-2.5 px-3">Sesi Latihan</th>
                            <th class="py-2.5 px-3">Beban</th>
                            <th class="py-2.5 px-3">Repetisi</th>
                            <th class="py-2.5 px-3">RPE</th>
                            <th class="py-2.5 px-3">Est. 1RM</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800/40">
                        @foreach($recentSets as $s)
                            <tr class="hover:bg-zinc-800/30">
                                <td class="py-2 px-3 text-zinc-400">{{ \Carbon\Carbon::parse($s->workoutSession->started_at ?? now())->format('d M Y') }}</td>
                                <td class="py-2 px-3 font-semibold text-white">{{ $s->workoutSession->title ?? '-' }}</td>
                                <td class="py-2 px-3 font-bold text-zinc-100">{{ $s->weight_kg }} kg</td>
                                <td class="py-2 px-3 text-zinc-200">{{ $s->reps }} reps</td>
                                <td class="py-2 px-3 text-zinc-400">{{ $s->rpe ? 'RPE ' . $s->rpe : '-' }}</td>
                                <td class="py-2 px-3 font-mono font-bold text-emerald-400">{{ $s->estimated_1rm }} kg</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-zinc-500 text-xs italic">Anda belum mencatat set untuk gerakan ini dalam sesi latihan.</p>
        @endif
    </div>

</div>
@endsection
