@extends('layouts.app')

@section('title', 'Catat Sesi Latihan')

@section('content')
<div x-data="workoutLogger()" class="max-w-4xl mx-auto space-y-6">

    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 text-xs text-emerald-400 font-semibold mb-1">
                <i data-lucide="dumbbell" class="w-4 h-4"></i>
                @if($routine)
                    Dari Program: {{ $routine->name }}
                @else
                    Sesi Latihan Mandiri
                @endif
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Catat Sesi Latihan</h1>
            <p class="text-zinc-400 text-xs sm:text-sm">Input beban, repetisi, dan manfaatkan rest timer di sela-sela set Anda.</p>
        </div>

        <!-- Floating / Sticky Rest Timer Widget -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-3 flex items-center gap-4">
            <div>
                <div class="text-[10px] font-bold uppercase tracking-wider text-zinc-400">Rest Timer</div>
                <div class="text-2xl font-mono font-bold" :class="timerRunning ? 'text-green-500 animate-pulse' : 'text-white'" x-text="formatTime(timerSeconds)">01:30</div>
            </div>
            <div class="flex items-center gap-1.5">
                <button type="button" @click="startTimer(60)" class="px-2 py-1 text-xs rounded bg-zinc-800 hover:bg-zinc-700 text-zinc-300 font-semibold">60s</button>
                <button type="button" @click="startTimer(90)" class="px-2 py-1 text-xs rounded bg-zinc-800 hover:bg-zinc-700 text-zinc-300 font-semibold">90s</button>
                <button type="button" @click="startTimer(120)" class="px-2 py-1 text-xs rounded bg-zinc-800 hover:bg-zinc-700 text-zinc-300 font-semibold">120s</button>
                <button type="button" @click="toggleTimer()" class="w-8 h-8 rounded-lg bg-green-600 hover:bg-green-700 text-white font-bold flex items-center justify-center">
                    <i :data-lucide="timerRunning ? 'pause' : 'play'" class="w-4 h-4 fill-current"></i>
                </button>
                <button type="button" @click="resetTimer()" class="w-8 h-8 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-400 hover:text-white flex items-center justify-center">
                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Main Workout Form -->
    <form action="{{ route('workouts.store') }}" method="POST" id="workoutForm" class="space-y-6">
        @csrf
        @if($routine)
            <input type="hidden" name="routine_id" value="{{ $routine->id }}">
        @endif

        <!-- Session Overview Card -->
        <div class="bg-zinc-900/80 border border-zinc-800 rounded-2xl p-5 sm:p-6 space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Judul Sesi Latihan</label>
                    <input type="text" name="title" required
                           value="{{ old('title', $routine ? 'Sesi ' . $routine->name : 'Latihan Gym - ' . now()->format('d M Y')) }}"
                           class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Waktu Latihan</label>
                    <input type="datetime-local" name="started_at" required
                           value="{{ old('started_at', now()->format('Y-m-d\TH:i')) }}"
                           class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-zinc-100 focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Durasi (Menit)</label>
                    <div class="relative">
                        <i data-lucide="clock" class="w-4 h-4 absolute left-3.5 top-3 text-zinc-500"></i>
                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', 60) }}" min="5" max="360"
                               class="w-full bg-zinc-950 border border-zinc-800 rounded-xl pl-10 pr-4 py-2.5 text-sm text-zinc-100 focus:outline-none focus:border-emerald-500">
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Catatan Sesi (Opsional)</label>
                    <input type="text" name="notes" placeholder="Contoh: Fokus progressive overload, tidur cukup, pump sangat baik"
                           value="{{ old('notes') }}"
                           class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-emerald-500">
                </div>
            </div>
        </div>

        <!-- Exercises List Section -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-white flex items-center gap-2">
                    <i data-lucide="list-checks" class="w-5 h-5 text-green-500"></i> Gerakan & Set Latihan
                </h2>
                <button type="button" @click="openExerciseModal = true" class="px-3.5 py-1.5 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-green-500 font-medium text-xs flex items-center gap-1.5 border border-zinc-700 transition-colors">
                    <i data-lucide="plus" class="w-4 h-4"></i> Tambah Gerakan
                </button>
            </div>

            <!-- Empty State -->
            <template x-if="exercises.length === 0">
                <div class="bg-zinc-900 border border-dashed border-zinc-800 rounded-xl p-10 text-center space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-zinc-800 text-zinc-400 flex items-center justify-center mx-auto">
                        <i data-lucide="dumbbell" class="w-6 h-6"></i>
                    </div>
                    <p class="text-zinc-300 font-semibold text-sm">Belum ada gerakan latihan yang ditambahkan</p>
                    <p class="text-zinc-500 text-xs">Klik tombol di bawah untuk memilih gerakan dari katalog gym.</p>
                    <button type="button" @click="openExerciseModal = true" class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium text-xs inline-flex items-center gap-2 transition-colors">
                        <i data-lucide="plus" class="w-4 h-4"></i> Pilih Gerakan Latihan
                    </button>
                </div>
            </template>

            <!-- Exercise Cards Loop -->
            <template x-for="(ex, exIndex) in exercises" :key="exIndex">
                <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-4 sm:p-5 space-y-3">
                    <!-- Exercise Header -->
                    <div class="flex items-center justify-between border-b border-zinc-800 pb-3">
                        <div class="flex items-center gap-3">
                            <span class="w-7 h-7 rounded-lg bg-zinc-800 text-zinc-300 text-xs font-black flex items-center justify-center" x-text="exIndex + 1"></span>
                            <div>
                                <h3 class="font-bold text-white text-base" x-text="ex.name"></h3>
                                <p class="text-[11px] text-zinc-400 flex items-center gap-2">
                                    <span class="text-emerald-400 font-medium" x-text="ex.category"></span>
                                    <span>&bull;</span>
                                    <span class="capitalize" x-text="ex.equipment"></span>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="hidden" :name="'exercises[' + exIndex + '][id]'" :value="ex.id">
                            <button type="button" @click="removeExercise(exIndex)" class="p-1.5 rounded-lg text-zinc-500 hover:text-rose-400 hover:bg-zinc-800 transition-colors" title="Hapus Gerakan">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Sets Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="text-zinc-400 border-b border-zinc-800/80">
                                    <th class="py-2 px-2 w-12 text-center font-semibold">Set</th>
                                    <th class="py-2 px-2 w-28 font-semibold">Beban (kg)</th>
                                    <th class="py-2 px-2 w-24 font-semibold">Reps</th>
                                    <th class="py-2 px-2 w-20 font-semibold">RPE</th>
                                    <th class="py-2 px-2 font-semibold">Est. 1RM</th>
                                    <th class="py-2 px-2 w-12 text-center"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-800/40">
                                <template x-for="(s, sIndex) in ex.sets" :key="sIndex">
                                    <tr class="hover:bg-zinc-800/30 transition-colors">
                                        <!-- Set Number -->
                                        <td class="py-2 px-2 text-center font-bold text-zinc-300" x-text="sIndex + 1"></td>
                                        
                                        <!-- Weight (kg) -->
                                        <td class="py-2 px-2">
                                            <input type="number" step="0.5" min="0" max="500" required
                                                   :name="'exercises[' + exIndex + '][sets][' + sIndex + '][weight_kg]'"
                                                   x-model.number="s.weight_kg"
                                                   @input="calculate1rm(s)"
                                                   placeholder="0"
                                                   class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-2.5 py-1.5 text-xs text-white text-center font-semibold focus:outline-none focus:border-emerald-500">
                                        </td>

                                        <!-- Reps -->
                                        <td class="py-2 px-2">
                                            <input type="number" min="0" max="200" required
                                                   :name="'exercises[' + exIndex + '][sets][' + sIndex + '][reps]'"
                                                   x-model.number="s.reps"
                                                   @input="calculate1rm(s)"
                                                   placeholder="0"
                                                   class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-2.5 py-1.5 text-xs text-white text-center font-semibold focus:outline-none focus:border-emerald-500">
                                        </td>

                                        <!-- RPE -->
                                        <td class="py-2 px-2">
                                            <select :name="'exercises[' + exIndex + '][sets][' + sIndex + '][rpe]'"
                                                    x-model.number="s.rpe"
                                                    class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-2 py-1.5 text-xs text-zinc-300 text-center focus:outline-none focus:border-emerald-500">
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10 (Max)</option>
                                            </select>
                                        </td>

                                        <!-- Live 1RM Estimate -->
                                        <td class="py-2 px-2">
                                            <span class="font-mono font-bold text-emerald-400" x-text="(s.est1rm > 0 ? s.est1rm + ' kg' : '-')"></span>
                                        </td>

                                        <!-- Delete Set -->
                                        <td class="py-2 px-2 text-center">
                                            <button type="button" @click="removeSet(exIndex, sIndex)" class="text-zinc-600 hover:text-rose-400 transition-colors" title="Hapus Set">
                                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- Bottom Exercise Actions -->
                    <div class="flex items-center justify-between pt-2">
                        <button type="button" @click="addSet(exIndex)" class="px-3 py-1.5 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-300 font-semibold text-xs flex items-center gap-1.5 transition-colors">
                            <i data-lucide="plus" class="w-3.5 h-3.5 text-green-500"></i> Tambah Set
                        </button>
                        <button type="button" @click="startTimer(ex.rest_seconds || 90)" class="text-xs text-zinc-400 hover:text-green-400 flex items-center gap-1">
                            <i data-lucide="timer" class="w-3.5 h-3.5"></i> Mulai Istirahat (<span x-text="ex.rest_seconds || 90"></span>s)
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Add Exercise & Finish Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-zinc-800">
            <button type="button" @click="openExerciseModal = true" class="w-full sm:w-auto px-5 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 hover:border-zinc-600 text-white font-medium text-sm flex items-center justify-center gap-2 transition-colors">
                <i data-lucide="plus" class="w-4 h-4 text-green-500"></i> Tambah Gerakan Lain
            </button>

            <button type="submit" :disabled="exercises.length === 0" class="w-full sm:w-auto px-6 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-semibold text-sm transition-colors flex items-center justify-center gap-2">
                <i data-lucide="check" class="w-4 h-4"></i> Simpan Sesi Latihan
            </button>
        </div>
    </form>

    <!-- Modal: Select Exercise from Catalog -->
    <div x-show="openExerciseModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        <div class="bg-zinc-900 border border-zinc-800 rounded-xl w-full max-w-xl max-h-[85vh] flex flex-col shadow-xl" @click.outside="openExerciseModal = false">
            <!-- Modal Header -->
            <div class="p-5 border-b border-zinc-800 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-white">Pilih Gerakan Latihan</h3>
                    <p class="text-xs text-zinc-400">Pilih dari katalog gerakan gym yang tersedia</p>
                </div>
                <button type="button" @click="openExerciseModal = false" class="text-zinc-500 hover:text-white p-1">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Search & Filter -->
            <div class="p-4 border-b border-zinc-800 bg-zinc-950">
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3.5 top-3 text-zinc-500"></i>
                    <input type="text" x-model="searchQuery" placeholder="Cari gerakan (contoh: Bench Press, Squat, Curl)..."
                           class="w-full bg-zinc-900 border border-zinc-800 rounded-lg pl-10 pr-4 py-2 text-xs text-white placeholder-zinc-500 focus:outline-none focus:border-green-500">
                </div>
            </div>

            <!-- Exercises List -->
            <div class="overflow-y-auto p-4 space-y-2 flex-1 divide-y divide-zinc-800">
                <template x-for="item in filteredCatalog" :key="item.id">
                    <div class="pt-2 pb-2 flex items-center justify-between hover:bg-zinc-800/50 p-2 rounded-lg transition-colors cursor-pointer" @click="addExerciseFromCatalog(item)">
                        <div>
                            <h4 class="font-bold text-white text-sm" x-text="item.name"></h4>
                            <p class="text-[11px] text-zinc-400">
                                <span class="text-green-500" x-text="item.category ? item.category.name : ''"></span> &bull; 
                                <span class="capitalize" x-text="item.equipment"></span>
                            </p>
                        </div>
                        <button type="button" class="px-3 py-1.5 rounded-lg bg-green-600 hover:bg-green-700 text-white text-xs font-medium transition-colors">
                            Pilih
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function workoutLogger() {
        return {
            openExerciseModal: false,
            searchQuery: '',
            timerSeconds: 90,
            timerRunning: false,
            timerInterval: null,
            wakeLock: null,
            catalog: {!! json_encode($allExercises) !!},
            exercises: {!! json_encode($preloadedExercises) !!},

            init() {
                // Initial 1RM calculations for preloaded sets
                this.exercises.forEach(ex => {
                    ex.sets.forEach(s => this.calculate1rm(s));
                });
                
                // Hardware Wake Lock: Jaga layar smartphone tetap menyala selama sesi latihan
                this.requestWakeLock();
                document.addEventListener('visibilitychange', () => {
                    if (document.visibilityState === 'visible') {
                        this.requestWakeLock();
                    }
                });

                this.$nextTick(() => {
                    if (window.lucide) window.lucide.createIcons();
                });
            },

            async requestWakeLock() {
                try {
                    if ('wakeLock' in navigator) {
                        this.wakeLock = await navigator.wakeLock.request('screen');
                    }
                } catch (err) {
                    console.log('Screen Wake Lock inactive:', err);
                }
            },

            get filteredCatalog() {
                if (!this.searchQuery) return this.catalog;
                const q = this.searchQuery.toLowerCase();
                return this.catalog.filter(item => 
                    item.name.toLowerCase().includes(q) ||
                    (item.category && item.category.name.toLowerCase().includes(q))
                );
            },

            addExerciseFromCatalog(item) {
                this.exercises.push({
                    id: item.id,
                    name: item.name,
                    category: item.category ? item.category.name : '',
                    equipment: item.equipment,
                    rest_seconds: 90,
                    sets: [
                        { weight_kg: 0, reps: 0, rpe: 8, is_warmup: false, est1rm: 0 },
                        { weight_kg: 0, reps: 0, rpe: 8, is_warmup: false, est1rm: 0 },
                        { weight_kg: 0, reps: 0, rpe: 8, is_warmup: false, est1rm: 0 },
                    ]
                });
                this.openExerciseModal = false;
                this.searchQuery = '';
                this.$nextTick(() => {
                    if (window.lucide) window.lucide.createIcons();
                });
            },

            removeExercise(index) {
                this.exercises.splice(index, 1);
            },

            addSet(exIndex) {
                const prevSet = this.exercises[exIndex].sets.slice(-1)[0];
                this.exercises[exIndex].sets.push({
                    weight_kg: prevSet ? prevSet.weight_kg : 0,
                    reps: prevSet ? prevSet.reps : 0,
                    rpe: 8,
                    is_warmup: false,
                    est1rm: prevSet ? prevSet.est1rm : 0
                });
                this.$nextTick(() => {
                    if (window.lucide) window.lucide.createIcons();
                });
            },

            removeSet(exIndex, sIndex) {
                if (this.exercises[exIndex].sets.length > 1) {
                    this.exercises[exIndex].sets.splice(sIndex, 1);
                }
            },

            calculate1rm(set) {
                const w = parseFloat(set.weight_kg) || 0;
                const r = parseInt(set.reps) || 0;
                if (w > 0 && r > 0) {
                    if (r === 1) {
                        set.est1rm = w;
                    } else {
                        // Epley formula: weight * (1 + reps / 30)
                        set.est1rm = Math.round(w * (1 + (r / 30)) * 10) / 10;
                    }
                } else {
                    set.est1rm = 0;
                }
            },

            // Rest Timer Methods
            startTimer(seconds) {
                this.timerSeconds = seconds;
                this.timerRunning = true;
                if (this.timerInterval) clearInterval(this.timerInterval);

                this.timerInterval = setInterval(() => {
                    if (this.timerSeconds > 0) {
                        this.timerSeconds--;
                    } else {
                        clearInterval(this.timerInterval);
                        this.timerRunning = false;
                        this.playTimerDing();
                    }
                }, 1000);
            },

            toggleTimer() {
                if (this.timerRunning) {
                    clearInterval(this.timerInterval);
                    this.timerRunning = false;
                } else {
                    this.startTimer(this.timerSeconds || 90);
                }
            },

            resetTimer() {
                clearInterval(this.timerInterval);
                this.timerRunning = false;
                this.timerSeconds = 90;
            },

            formatTime(seconds) {
                const m = Math.floor(seconds / 60);
                const s = seconds % 60;
                return `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
            },

            playTimerDing() {
                // 1. Interaksi Hardware Haptic (Vibration API Smartphone)
                if ('vibrate' in navigator) {
                    try {
                        // Pola getar smartphone saat istirahat selesai: getar 250ms, jeda 100ms, getar 250ms, jeda 100ms, getar 500ms
                        navigator.vibrate([250, 100, 250, 100, 500]);
                    } catch (e) {
                        console.log('Vibration not supported/permitted');
                    }
                }

                // 2. Interaksi Hardware Speaker (Web Audio API Synthesizer)
                try {
                    const ctx = new (window.AudioContext || window.webkitAudioContext)();
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.frequency.value = 880;
                    gain.gain.setValueAtTime(0.3, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.8);
                    osc.start();
                    osc.stop(ctx.currentTime + 0.8);
                } catch (e) {
                    console.log('Audio notification played');
                }
            }
        }
    }
</script>
@endpush
