@extends('layouts.app')

@section('title', 'Kalkulator Kebugaran (BMI, BMR & TDEE)')

@section('content')
<div x-data="fitnessCalculator()" class="max-w-4xl mx-auto space-y-8">

    <!-- Header -->
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Kalkulator Kebugaran & Nutrisi</h1>
        <p class="text-zinc-400 text-xs sm:text-sm">Hitung Indeks Massa Tubuh (BMI), Total Daily Energy Expenditure (TDEE), serta target makronutrisi harian Anda.</p>
    </div>

    <!-- Section 1: BMI Calculator -->
    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 sm:p-8 space-y-6">
        <div class="flex items-center gap-3 border-b border-zinc-800 pb-4">
            <div class="w-10 h-10 rounded-lg bg-green-500/10 text-green-500 flex items-center justify-center font-bold">
                <i data-lucide="scale" class="w-5 h-5"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-white">1. Kalkulator Indeks Massa Tubuh (BMI)</h2>
                <p class="text-xs text-zinc-400">Menilai proporsi berat badan terhadap tinggi badan Anda</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
            <!-- Inputs -->
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-xs font-semibold text-zinc-400 mb-1.5">
                        <span>Tinggi Badan (cm)</span>
                        <span class="text-white font-mono" x-text="height + ' cm'"></span>
                    </div>
                    <input type="range" min="120" max="220" step="0.5" x-model.number="height"
                           class="w-full accent-green-600 bg-zinc-950 rounded-lg cursor-pointer">
                    <input type="number" step="0.5" x-model.number="height"
                           class="w-full mt-1 bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-1.5 text-xs text-white">
                </div>

                <div>
                    <div class="flex justify-between text-xs font-semibold text-zinc-400 mb-1.5">
                        <span>Berat Badan (kg)</span>
                        <span class="text-white font-mono" x-text="weight + ' kg'"></span>
                    </div>
                    <input type="range" min="30" max="180" step="0.5" x-model.number="weight"
                           class="w-full accent-green-600 bg-zinc-950 rounded-lg cursor-pointer">
                    <input type="number" step="0.5" x-model.number="weight"
                           class="w-full mt-1 bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-1.5 text-xs text-white">
                </div>
            </div>

            <!-- BMI Result Card -->
            <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-6 text-center space-y-3">
                <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-500 block">Skor BMI Anda</span>
                <div class="text-4xl font-extrabold text-white font-mono" x-text="bmiScore">22.5</div>
                
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded text-xs font-medium"
                     :class="{
                         'bg-amber-500/10 text-amber-400 border border-amber-500/20': bmiCategory === 'Underweight',
                         'bg-green-500/10 text-green-400 border border-green-500/20': bmiCategory === 'Normal (Ideal)',
                         'bg-amber-500/10 text-amber-400 border border-amber-500/20': bmiCategory === 'Overweight',
                         'bg-rose-500/10 text-rose-400 border border-rose-500/20': bmiCategory === 'Obesitas'
                     }"
                     x-text="bmiCategory">
                </div>

                <p class="text-xs text-zinc-400 pt-2 border-t border-zinc-800/80">
                    Rentang berat badan ideal untuk tinggi <span x-text="height"></span> cm adalah:
                    <span class="font-bold text-white block mt-0.5" x-text="idealWeightRange"></span>
                </p>
            </div>
        </div>
    </div>

    <!-- Section 2: BMR & TDEE + Macro Nutrition Calculator -->
    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 sm:p-8 space-y-6">
        <div class="flex items-center gap-3 border-b border-zinc-800 pb-4">
            <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center font-bold">
                <i data-lucide="utensils" class="w-5 h-5"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-white">2. Kalkulator BMR, TDEE & Makronutrisi</h2>
                <p class="text-xs text-zinc-400">Formula Mifflin-St Jeor untuk menghitung kebutuhan kalori dan protein harian</p>
            </div>
        </div>

        <form action="{{ route('calculator.save-goal') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Inputs -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Jenis Kelamin</label>
                    <select name="gender" x-model="gender" class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-2 text-xs text-zinc-200 focus:outline-none focus:border-emerald-500">
                        <option value="male">Pria</option>
                        <option value="female">Wanita</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Usia (Tahun)</label>
                    <input type="number" min="12" max="100" x-model.number="age"
                           class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Tingkat Aktivitas Fisik</label>
                    <select name="activity_level" x-model="activity" class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-2 text-xs text-zinc-200 focus:outline-none focus:border-emerald-500">
                        <option value="sedentary">Jarang Olahraga (Sedentary)</option>
                        <option value="light">Ringan (1-3 kali gym / minggu)</option>
                        <option value="moderate">Sedang (3-5 kali gym / minggu)</option>
                        <option value="active">Tinggi (6-7 kali gym intensif)</option>
                        <option value="very_active">Sangat Tinggi (Atlet / Kerja Fisik)</option>
                    </select>
                </div>
            </div>

            <!-- Goal selector -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-2">Tujuan Kebugaran (Fitness Goal)</label>
                <div class="grid grid-cols-3 gap-3">
                    <button type="button" @click="goal = 'cutting'"
                            :class="goal === 'cutting' ? 'bg-emerald-500/10 border-emerald-500 text-emerald-400' : 'bg-zinc-950 border-zinc-800 text-zinc-400'"
                            class="p-3 rounded-2xl border text-center transition-all">
                        <span class="block font-bold text-xs">Cutting</span>
                        <span class="text-[10px] text-zinc-500">Defisit 450 kcal</span>
                    </button>

                    <button type="button" @click="goal = 'maintenance'"
                            :class="goal === 'maintenance' ? 'bg-emerald-500/10 border-emerald-500 text-emerald-400' : 'bg-zinc-950 border-zinc-800 text-zinc-400'"
                            class="p-3 rounded-2xl border text-center transition-all">
                        <span class="block font-bold text-xs">Maintenance</span>
                        <span class="text-[10px] text-zinc-500">Jaga Berat Badan</span>
                    </button>

                    <button type="button" @click="goal = 'bulking'"
                            :class="goal === 'bulking' ? 'bg-emerald-500/10 border-emerald-500 text-emerald-400' : 'bg-zinc-950 border-zinc-800 text-zinc-400'"
                            class="p-3 rounded-2xl border text-center transition-all">
                        <span class="block font-bold text-xs">Bulking</span>
                        <span class="text-[10px] text-zinc-500">Surplus 350 kcal</span>
                    </button>
                </div>
                <input type="hidden" name="fitness_goal" :value="goal">
            </div>

            <!-- TDEE & Target Results -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2">
                <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-3.5 text-center">
                    <span class="text-[10px] uppercase font-bold text-zinc-500 block">BMR (Metabolisme Dasar)</span>
                    <span class="text-xl font-extrabold text-white mt-0.5 block font-mono" x-text="bmr + ' kcal'"></span>
                </div>

                <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-3.5 text-center">
                    <span class="text-[10px] uppercase font-bold text-zinc-500 block">TDEE (Maintenance)</span>
                    <span class="text-xl font-extrabold text-zinc-300 mt-0.5 block font-mono" x-text="tdee + ' kcal'"></span>
                </div>

                <div class="bg-zinc-950 border border-emerald-500/40 rounded-xl p-3.5 text-center col-span-2 sm:col-span-2">
                    <span class="text-[10px] uppercase font-bold text-emerald-400 block">Target Kalori Harian Anda</span>
                    <span class="text-2xl font-black text-white mt-0.5 block font-mono" x-text="targetCalories + ' kcal'"></span>
                    <input type="hidden" name="daily_calorie_target" :value="targetCalories">
                </div>
            </div>

            <!-- Macros Breakdown -->
            <div class="bg-zinc-950/70 border border-zinc-800 rounded-2xl p-5 space-y-3">
                <span class="text-xs font-bold uppercase tracking-wider text-zinc-400 block">Rekomendasi Distribusi Makronutrisi</span>
                
                <div class="grid grid-cols-3 gap-3 text-center">
                    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-3">
                        <span class="text-[10px] uppercase font-bold text-purple-400 block">Protein (2g / kg)</span>
                        <span class="text-lg font-extrabold text-white mt-0.5 block" x-text="proteinGrams + ' g'"></span>
                        <span class="text-[10px] text-zinc-500" x-text="(proteinGrams * 4) + ' kcal'"></span>
                        <input type="hidden" name="daily_protein_target" :value="proteinGrams">
                    </div>

                    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-3">
                        <span class="text-[10px] uppercase font-bold text-amber-400 block">Karbohidrat</span>
                        <span class="text-lg font-extrabold text-white mt-0.5 block" x-text="carbsGrams + ' g'"></span>
                        <span class="text-[10px] text-zinc-500" x-text="(carbsGrams * 4) + ' kcal'"></span>
                    </div>

                    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-3">
                        <span class="text-[10px] uppercase font-bold text-cyan-400 block">Lemak Sehat</span>
                        <span class="text-lg font-extrabold text-white mt-0.5 block" x-text="fatGrams + ' g'"></span>
                        <span class="text-[10px] text-zinc-500" x-text="(fatGrams * 9) + ' kcal'"></span>
                    </div>
                </div>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium text-xs transition-colors">
                    Simpan Target Ini ke Profil Saya
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function fitnessCalculator() {
        return {
            height: {{ $height }},
            weight: {{ $latestWeight }},
            age: {{ $age }},
            gender: '{{ $gender }}',
            activity: '{{ $activity }}',
            goal: '{{ $goal }}',

            get bmiScore() {
                if (this.height <= 0 || this.weight <= 0) return 0;
                const hM = this.height / 100;
                return Math.round((this.weight / (hM * hM)) * 10) / 10;
            },

            get bmiCategory() {
                const s = this.bmiScore;
                if (s < 18.5) return 'Underweight';
                if (s < 25.0) return 'Normal (Ideal)';
                if (s < 30.0) return 'Overweight';
                return 'Obesitas';
            },

            get idealWeightRange() {
                if (this.height <= 0) return '-';
                const hM = this.height / 100;
                const min = Math.round(18.5 * hM * hM * 10) / 10;
                const max = Math.round(24.9 * hM * hM * 10) / 10;
                return `${min} kg - ${max} kg`;
            },

            get bmr() {
                if (this.height <= 0 || this.weight <= 0 || this.age <= 0) return 0;
                // Mifflin-St Jeor
                if (this.gender === 'male') {
                    return Math.round((10 * this.weight) + (6.25 * this.height) - (5 * this.age) + 5);
                } else {
                    return Math.round((10 * this.weight) + (6.25 * this.height) - (5 * this.age) - 161);
                }
            },

            get tdee() {
                const multipliers = {
                    'sedentary': 1.2,
                    'light': 1.375,
                    'moderate': 1.55,
                    'active': 1.725,
                    'very_active': 1.9,
                };
                const m = multipliers[this.activity] || 1.55;
                return Math.round(this.bmr * m);
            },

            get targetCalories() {
                if (this.goal === 'cutting') return Math.max(1200, this.tdee - 450);
                if (this.goal === 'bulking') return this.tdee + 350;
                return this.tdee;
            },

            get proteinGrams() {
                return Math.round(this.weight * 2.0);
            },

            get fatGrams() {
                const fatCals = this.targetCalories * 0.25;
                return Math.round(fatCals / 9);
            },

            get carbsGrams() {
                const proteinCals = this.proteinGrams * 4;
                const fatCals = this.fatGrams * 9;
                const remaining = Math.max(0, this.targetCalories - proteinCals - fatCals);
                return Math.round(remaining / 4);
            }
        }
    }
</script>
@endpush
