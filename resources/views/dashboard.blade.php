@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Top Hero Banner / Greeting -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-zinc-900 via-zinc-900/90 to-zinc-800/80 border border-zinc-800 p-6 sm:p-8 shadow-xl">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-semibold mb-3">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Target: {{ ucfirst($user->fitness_goal ?? 'Maintenance') }}
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                    Semangat Latihan, <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-200">{{ $user->name }}</span>! 💪
                </h1>
                <p class="text-zinc-400 text-sm mt-1 max-w-xl">
                    Minggu ini Anda telah menyelesaikan <span class="font-bold text-emerald-400">{{ $thisWeekCount }} sesi latihan</span>. Konsistensi adalah kunci transformasi fisik!
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('workouts.create') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-zinc-950 font-bold text-sm shadow-xl shadow-emerald-500/30 transition-all hover:scale-105 active:scale-95">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i> Mulai Latihan Baru
                </a>
            </div>
        </div>

        <!-- Decorative background glow -->
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    <!-- 4 Stats Cards Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Sesi Selesai -->
        <div class="bg-zinc-900/80 border border-zinc-800 rounded-2xl p-4 sm:p-5 flex flex-col justify-between">
            <div class="flex items-center justify-between text-zinc-400 mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Sesi Latihan</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
                    <i data-lucide="flame" class="w-4 h-4"></i>
                </div>
            </div>
            <div>
                <p class="text-2xl sm:text-3xl font-extrabold text-white">{{ $totalWorkouts }}</p>
                <p class="text-xs text-zinc-400 mt-1"><span class="text-emerald-400 font-semibold">+{{ $thisMonthWorkouts }}</span> bulan ini</p>
            </div>
        </div>

        <!-- 2. Total Volume -->
        <div class="bg-zinc-900/80 border border-zinc-800 rounded-2xl p-4 sm:p-5 flex flex-col justify-between">
            <div class="flex items-center justify-between text-zinc-400 mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Total Beban</span>
                <div class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-400 flex items-center justify-center">
                    <i data-lucide="dumbbell" class="w-4 h-4"></i>
                </div>
            </div>
            <div>
                <p class="text-2xl sm:text-3xl font-extrabold text-white">{{ number_format($totalVolume, 0, ',', '.') }} <span class="text-sm font-semibold text-zinc-500">kg</span></p>
                <p class="text-xs text-zinc-400 mt-1">Volume angkatan kumulatif</p>
            </div>
        </div>

        <!-- 3. Berat Badan & BMI -->
        <div class="bg-zinc-900/80 border border-zinc-800 rounded-2xl p-4 sm:p-5 flex flex-col justify-between">
            <div class="flex items-center justify-between text-zinc-400 mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Berat Badan</span>
                <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center">
                    <i data-lucide="scale" class="w-4 h-4"></i>
                </div>
            </div>
            <div>
                <div class="flex items-baseline gap-2">
                    <p class="text-2xl sm:text-3xl font-extrabold text-white">{{ $bmiData['weight'] ?? ($user->target_weight_kg ?? '-') }} <span class="text-sm font-semibold text-zinc-500">kg</span></p>
                    @if($bmiData)
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                            BMI {{ $bmiData['score'] }} ({{ $bmiData['category'] }})
                        </span>
                    @endif
                </div>
                <p class="text-xs text-zinc-400 mt-1">Tinggi: {{ $user->height_cm ?? '-' }} cm</p>
            </div>
        </div>

        <!-- 4. Target Kalori & Protein -->
        <div class="bg-zinc-900/80 border border-zinc-800 rounded-2xl p-4 sm:p-5 flex flex-col justify-between">
            <div class="flex items-center justify-between text-zinc-400 mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Target Nutrisi</span>
                <div class="w-8 h-8 rounded-lg bg-purple-500/10 text-purple-400 flex items-center justify-center">
                    <i data-lucide="utensils" class="w-4 h-4"></i>
                </div>
            </div>
            <div>
                <p class="text-2xl sm:text-3xl font-extrabold text-white">{{ $user->daily_calorie_target ?? ($tdeeData['target_calories'] ?? 2400) }} <span class="text-sm font-semibold text-zinc-500">kcal</span></p>
                <p class="text-xs text-zinc-400 mt-1">Protein: <span class="text-purple-400 font-semibold">{{ $user->daily_protein_target ?? ($tdeeData['protein_grams'] ?? 140) }}g</span> / hari</p>
            </div>
        </div>
    </div>

    <!-- Quick Start Workout Template Cards -->
    <div class="bg-zinc-900/60 border border-zinc-800 rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-bold text-white flex items-center gap-2">
                    <i data-lucide="zap" class="w-5 h-5 text-amber-400"></i> Mulai Cepat dari Program
                </h2>
                <p class="text-xs text-zinc-400">Pilih template latihan untuk otomatis mengisi daftar gerakan</p>
            </div>
            <a href="{{ route('routines.index') }}" class="text-xs font-semibold text-emerald-400 hover:text-emerald-300 flex items-center gap-1">
                Lihat Semua <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @forelse($quickRoutines as $routine)
                <div class="bg-zinc-900 border border-zinc-800 hover:border-zinc-700 rounded-xl p-4 transition-all group flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-zinc-800 text-zinc-300 border border-zinc-700">
                                {{ $routine->target_split ?? 'Program' }}
                            </span>
                            <span class="text-xs text-zinc-500 flex items-center gap-1">
                                <i data-lucide="list" class="w-3.5 h-3.5"></i> {{ $routine->routine_exercises_count }} Gerakan
                            </span>
                        </div>
                        <h3 class="font-bold text-white text-base group-hover:text-emerald-400 transition-colors">
                            {{ $routine->name }}
                        </h3>
                        <p class="text-xs text-zinc-400 mt-1 line-clamp-2">
                            {{ $routine->description ?? 'Jadwal latihan terstruktur.' }}
                        </p>
                    </div>

                    <a href="{{ route('workouts.create', ['routine_id' => $routine->id]) }}" class="mt-4 w-full py-2 px-3 rounded-lg bg-zinc-800 hover:bg-emerald-500 hover:text-zinc-950 text-emerald-400 font-semibold text-xs flex items-center justify-center gap-1.5 transition-colors">
                        <i data-lucide="play" class="w-3.5 h-3.5 fill-current"></i> Mulai Sesi Ini
                    </a>
                </div>
            @empty
                <div class="col-span-3 text-center py-6 text-zinc-500 text-sm">
                    Belum ada program latihan. <a href="{{ route('routines.create') }}" class="text-emerald-400 underline">Buat program pertama Anda</a>.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Two Columns: Weight Trend Chart & Recent Workouts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Weight Trend Chart (1 Col) -->
        <div class="bg-zinc-900/60 border border-zinc-800 rounded-2xl p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <i data-lucide="trending-up" class="w-5 h-5 text-emerald-400"></i> Tren Berat Badan
                    </h2>
                    <a href="{{ route('progress.index') }}" class="text-xs font-semibold text-emerald-400 hover:text-emerald-300">
                        Detail
                    </a>
                </div>

                @if(count($chartWeights) > 0)
                    <div class="h-48 w-full">
                        <canvas id="weightMiniChart"></canvas>
                    </div>
                @else
                    <div class="h-48 flex items-center justify-center text-center text-zinc-500 text-xs">
                        Belum ada rekaman berat badan.<br>
                        <a href="{{ route('progress.index') }}" class="text-emerald-400 underline mt-1 block">Catat berat badan sekarang</a>
                    </div>
                @endif
            </div>

            <div class="mt-4 pt-4 border-t border-zinc-800 flex items-center justify-between text-xs">
                <span class="text-zinc-400">Target Berat:</span>
                <span class="font-bold text-white">{{ $user->target_weight_kg ?? '-' }} kg</span>
            </div>
        </div>

        <!-- Recent Workout Logs (2 Cols) -->
        <div class="lg:col-span-2 bg-zinc-900/60 border border-zinc-800 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-white flex items-center gap-2">
                    <i data-lucide="history" class="w-5 h-5 text-blue-400"></i> Riwayat Latihan Terakhir
                </h2>
                <a href="{{ route('workouts.index') }}" class="text-xs font-semibold text-emerald-400 hover:text-emerald-300">
                    Lihat Riwayat Lengkap
                </a>
            </div>

            <div class="space-y-3">
                @forelse($recentWorkouts as $session)
                    <div class="bg-zinc-900 border border-zinc-800 hover:border-zinc-700 rounded-xl p-4 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-semibold text-emerald-400">
                                    {{ \Carbon\Carbon::parse($session->started_at)->translatedFormat('l, d M Y') }}
                                </span>
                                <span class="text-xs text-zinc-500">&bull;</span>
                                <span class="text-xs text-zinc-400 flex items-center gap-1">
                                    <i data-lucide="clock" class="w-3 h-3"></i> {{ $session->duration_minutes }} menit
                                </span>
                            </div>
                            <h3 class="font-bold text-white text-base">
                                <a href="{{ route('workouts.show', $session->id) }}" class="hover:text-emerald-400 transition-colors">
                                    {{ $session->title }}
                                </a>
                            </h3>
                            <div class="flex flex-wrap gap-1.5 pt-1">
                                @foreach($session->sets->groupBy('exercise_id')->take(3) as $exId => $sets)
                                    <span class="text-[11px] px-2 py-0.5 rounded bg-zinc-800 text-zinc-300 border border-zinc-700/60">
                                        {{ $sets->first()->exercise->name ?? 'Latihan' }} ({{ $sets->count() }} set)
                                    </span>
                                @endforeach
                                @if($session->sets->groupBy('exercise_id')->count() > 3)
                                    <span class="text-[11px] px-2 py-0.5 rounded bg-zinc-800/60 text-zinc-400">
                                        +{{ $session->sets->groupBy('exercise_id')->count() - 3 }} lainnya
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center justify-between sm:flex-col sm:items-end gap-1 shrink-0 pt-2 sm:pt-0 border-t sm:border-0 border-zinc-800">
                            <span class="text-xs text-zinc-500">Volume</span>
                            <span class="text-base font-extrabold text-white">{{ number_format($session->total_volume_kg, 0, ',', '.') }} kg</span>
                            <a href="{{ route('workouts.show', $session->id) }}" class="text-xs font-semibold text-emerald-400 hover:text-emerald-300 inline-flex items-center gap-0.5 mt-1">
                                Detail <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-zinc-500 text-sm">
                        Belum ada riwayat sesi latihan.<br>
                        <a href="{{ route('workouts.create') }}" class="text-emerald-400 underline font-semibold mt-2 inline-block">Mulai catat sesi latihan pertama Anda</a>!
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('weightMiniChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Berat (kg)',
                        data: {!! json_encode($chartWeights) !!},
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 4,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#09090b',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            grid: { color: 'rgba(255, 255, 255, 0.05)' },
                            ticks: { color: '#71717a', font: { size: 10 } }
                        },
                        y: {
                            grid: { color: 'rgba(255, 255, 255, 0.05)' },
                            ticks: { color: '#71717a', font: { size: 10 } }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
