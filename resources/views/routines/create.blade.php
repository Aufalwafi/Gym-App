@extends('layouts.app')

@section('title', 'Buat Program Latihan Baru')

@section('content')
<div x-data="routineBuilder()" class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('routines.index') }}" class="inline-flex items-center gap-1 text-xs text-zinc-400 hover:text-white mb-1">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Kembali
            </a>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Buat Program Latihan</h1>
            <p class="text-zinc-400 text-xs sm:text-sm">Rancang daftar latihan, target set, repetisi, dan jeda istirahat ideal.</p>
        </div>
    </div>

    <form action="{{ route('routines.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Routine Metadata Card -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5 sm:p-6 space-y-4 shadow-xl">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Nama Program Latihan</label>
                    <input type="text" name="name" required placeholder="Contoh: Chest & Triceps Blast, Leg Day Heavy"
                           value="{{ old('name') }}"
                           class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Kategori / Split</label>
                    <select name="target_split" class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-zinc-200 focus:outline-none focus:border-emerald-500">
                        <option value="PPL">PPL (Push / Pull / Legs)</option>
                        <option value="Upper/Lower">Upper / Lower Body</option>
                        <option value="Bro Split">Bro Split (1 Otot / Hari)</option>
                        <option value="Full Body">Full Body</option>
                        <option value="Custom">Custom Program</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-1">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Rekomendasi Hari</label>
                    <input type="text" name="day_schedule" placeholder="Contoh: Senin & Kamis"
                           value="{{ old('day_schedule') }}"
                           class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-emerald-500">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Deskripsi / Sasaran Program</label>
                    <input type="text" name="description" placeholder="Contoh: Fokus pertumbuhan dada atas dan kekuatan bahu samping."
                           value="{{ old('description') }}"
                           class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-emerald-500">
                </div>
            </div>
        </div>

        <!-- Exercises Selection Section -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-white flex items-center gap-2">
                    <i data-lucide="list-ordered" class="w-5 h-5 text-emerald-400"></i> Urutan Gerakan Latihan
                </h2>
                <button type="button" @click="openModal = true" class="px-3.5 py-1.5 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-emerald-400 font-bold text-xs flex items-center gap-1.5 border border-zinc-700 transition-colors">
                    <i data-lucide="plus" class="w-4 h-4"></i> Tambah Gerakan
                </button>
            </div>

            <!-- Empty State -->
            <template x-if="routineExercises.length === 0">
                <div class="bg-zinc-900/40 border border-dashed border-zinc-800 rounded-2xl p-10 text-center space-y-3">
                    <i data-lucide="dumbbell" class="w-8 h-8 text-zinc-600 mx-auto"></i>
                    <p class="text-zinc-400 font-semibold text-sm">Belum ada gerakan yang ditambahkan ke program ini</p>
                    <button type="button" @click="openModal = true" class="px-4 py-2 rounded-xl bg-emerald-500 text-zinc-950 font-bold text-xs inline-flex items-center gap-1.5">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i> Pilih Gerakan dari Katalog
                    </button>
                </div>
            </template>

            <!-- Exercises Loop -->
            <div class="space-y-3">
                <template x-for="(item, index) in routineExercises" :key="index">
                    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-md">
                        <div class="flex items-center gap-3">
                            <span class="w-7 h-7 rounded-lg bg-zinc-800 text-emerald-400 font-extrabold text-xs flex items-center justify-center shrink-0" x-text="index + 1"></span>
                            <div>
                                <h4 class="font-bold text-white text-sm" x-text="item.name"></h4>
                                <p class="text-[11px] text-zinc-400">
                                    <span class="text-zinc-300" x-text="item.category"></span> &bull; 
                                    <span class="capitalize" x-text="item.equipment"></span>
                                </p>
                            </div>
                        </div>

                        <!-- Config target sets, reps, rest -->
                        <div class="flex flex-wrap items-center gap-3">
                            <input type="hidden" :name="'exercises[' + index + '][id]'" :value="item.id">

                            <div>
                                <label class="block text-[10px] uppercase font-bold text-zinc-500 mb-1">Target Set</label>
                                <input type="number" min="1" max="20" required
                                       :name="'exercises[' + index + '][target_sets]'"
                                       x-model.number="item.target_sets"
                                       class="w-20 bg-zinc-950 border border-zinc-800 rounded-lg px-2.5 py-1.5 text-xs text-white text-center font-bold focus:outline-none focus:border-emerald-500">
                            </div>

                            <div>
                                <label class="block text-[10px] uppercase font-bold text-zinc-500 mb-1">Target Reps</label>
                                <input type="text" required placeholder="8-12"
                                       :name="'exercises[' + index + '][target_reps]'"
                                       x-model="item.target_reps"
                                       class="w-24 bg-zinc-950 border border-zinc-800 rounded-lg px-2.5 py-1.5 text-xs text-white text-center font-bold focus:outline-none focus:border-emerald-500">
                            </div>

                            <div>
                                <label class="block text-[10px] uppercase font-bold text-zinc-500 mb-1">Rest (Detik)</label>
                                <input type="number" min="10" max="600" step="15" required
                                       :name="'exercises[' + index + '][rest_seconds]'"
                                       x-model.number="item.rest_seconds"
                                       class="w-20 bg-zinc-950 border border-zinc-800 rounded-lg px-2.5 py-1.5 text-xs text-white text-center font-bold focus:outline-none focus:border-emerald-500">
                            </div>

                            <button type="button" @click="removeExercise(index)" class="p-2 mt-4 text-zinc-600 hover:text-rose-400 transition-colors" title="Hapus">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-4 border-t border-zinc-800 flex items-center justify-end gap-3">
            <a href="{{ route('routines.index') }}" class="px-5 py-2.5 rounded-xl bg-zinc-800 text-zinc-300 font-semibold text-xs">
                Batal
            </a>
            <button type="submit" :disabled="routineExercises.length === 0" class="px-6 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 disabled:opacity-50 text-zinc-950 font-bold text-xs uppercase tracking-wider transition-all">
                Simpan Program Latihan
            </button>
        </div>
    </form>

    <!-- Modal Exercise Picker -->
    <div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl w-full max-w-lg max-h-[80vh] flex flex-col shadow-2xl" @click.outside="openModal = false">
            <div class="p-4 border-b border-zinc-800 flex items-center justify-between">
                <h3 class="font-bold text-white text-base">Pilih Gerakan</h3>
                <button type="button" @click="openModal = false" class="text-zinc-500 hover:text-white">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <div class="p-3 border-b border-zinc-800 bg-zinc-950/40">
                <input type="text" x-model="search" placeholder="Cari gerakan..."
                       class="w-full bg-zinc-900 border border-zinc-800 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none focus:border-emerald-500">
            </div>

            <div class="overflow-y-auto p-3 space-y-1.5 flex-1 divide-y divide-zinc-800/40">
                <template x-for="item in filteredCatalog" :key="item.id">
                    <div class="pt-2 pb-2 flex items-center justify-between hover:bg-zinc-800/50 p-2 rounded-xl cursor-pointer" @click="addExercise(item)">
                        <div>
                            <p class="font-bold text-white text-xs" x-text="item.name"></p>
                            <p class="text-[10px] text-zinc-400" x-text="(item.category ? item.category.name : '') + ' &bull; ' + item.equipment"></p>
                        </div>
                        <span class="text-emerald-400 text-xs font-bold">+ Tambah</span>
                    </div>
                </template>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function routineBuilder() {
        return {
            openModal: false,
            search: '',
            catalog: {!! json_encode($allExercises) !!},
            routineExercises: [],

            get filteredCatalog() {
                if (!this.search) return this.catalog;
                const q = this.search.toLowerCase();
                return this.catalog.filter(i => i.name.toLowerCase().includes(q));
            },

            addExercise(item) {
                this.routineExercises.push({
                    id: item.id,
                    name: item.name,
                    category: item.category ? item.category.name : '',
                    equipment: item.equipment,
                    target_sets: 3,
                    target_reps: '8-12',
                    rest_seconds: 90,
                });
                this.openModal = false;
                this.search = '';
                this.$nextTick(() => {
                    if (window.lucide) window.lucide.createIcons();
                });
            },

            removeExercise(index) {
                this.routineExercises.splice(index, 1);
            }
        }
    }
</script>
@endpush
