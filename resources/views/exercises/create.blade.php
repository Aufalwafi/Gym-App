@extends('layouts.app')

@section('title', 'Tambah Gerakan Kustom')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div>
        <a href="{{ route('exercises.index') }}" class="inline-flex items-center gap-1.5 text-xs text-zinc-400 hover:text-white transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Katalog
        </a>
        <h1 class="text-2xl font-extrabold text-white tracking-tight mt-1">Tambah Gerakan Latihan Kustom</h1>
        <p class="text-zinc-400 text-xs">Tambahkan variasi gerakan gym baru yang belum ada di katalog.</p>
    </div>

    <form action="{{ route('exercises.store') }}" method="POST" class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 space-y-4 shadow-xl">
        @csrf

        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Nama Gerakan</label>
            <input type="text" name="name" required placeholder="Contoh: Smith Machine Incline Press" value="{{ old('name') }}"
                   class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-emerald-500">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Kelompok Otot Utama</label>
                <select name="exercise_category_id" required class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-zinc-200 focus:outline-none focus:border-emerald-500">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Peralatan / Alat</label>
                <select name="equipment" required class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-zinc-200 focus:outline-none focus:border-emerald-500">
                    <option value="barbell">Barbell</option>
                    <option value="dumbbell">Dumbbell</option>
                    <option value="cable">Cable / Katrol</option>
                    <option value="machine">Mesin Gym</option>
                    <option value="bodyweight">Bodyweight / Kalistenik</option>
                    <option value="other">Lainnya</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Tingkat Kesulitan</label>
                <select name="difficulty" required class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-zinc-200 focus:outline-none focus:border-emerald-500">
                    <option value="beginner">Beginner (Pemula)</option>
                    <option value="intermediate" selected>Intermediate (Menengah)</option>
                    <option value="advanced">Advanced (Lanjutan)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Otot Sekunder (Opsional)</label>
                <input type="text" name="secondary_muscles" placeholder="Contoh: Triceps, Bahu Depan" value="{{ old('secondary_muscles') }}"
                       class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-emerald-500">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Instruksi Eksekusi</label>
            <textarea name="instructions" rows="3" placeholder="Jelaskan langkah-langkah melakukan gerakan..."
                      class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-emerald-500">{{ old('instructions') }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Tips Form & Keamanan</label>
            <textarea name="tips" rows="2" placeholder="Hal penting yang perlu diperhatikan agar tidak cedera..."
                      class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-emerald-500">{{ old('tips') }}</textarea>
        </div>

        <div class="pt-2 flex items-center justify-end gap-3">
            <a href="{{ route('exercises.index') }}" class="px-5 py-2.5 rounded-xl bg-zinc-800 text-zinc-300 font-semibold text-xs">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-zinc-950 font-bold text-xs uppercase tracking-wider transition-all">
                Simpan Gerakan
            </button>
        </div>
    </form>

</div>
@endsection
