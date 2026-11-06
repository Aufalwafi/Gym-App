@extends('layouts.app')

@section('title', 'Profil & Pengaturan Akun')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    <!-- Header -->
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Profil & Pengaturan Akun</h1>
        <p class="text-zinc-400 text-xs sm:text-sm">Kelola informasi pribadi, data antropometri fisik, dan target kebugaran Anda.</p>
    </div>

    <!-- Section 1: Personal & Fitness Information -->
    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 sm:p-8 space-y-6">
        <div class="flex items-center gap-3 border-b border-zinc-800 pb-4">
            <div class="w-10 h-10 rounded-lg bg-green-500/10 text-green-500 flex items-center justify-center font-bold">
                <i data-lucide="user-check" class="w-5 h-5"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-white">Data Profil & Sasaran Fitness</h2>
                <p class="text-xs text-zinc-400">Informasi ini digunakan dalam perhitungan kalkulator TDEE dan rekomendasi beban</p>
            </div>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-4 py-2.5 text-sm text-zinc-100 focus:outline-none focus:border-green-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-4 py-2.5 text-sm text-zinc-100 focus:outline-none focus:border-green-500">
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Jenis Kelamin</label>
                    <select name="gender" class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-2 text-xs text-zinc-200 focus:outline-none focus:border-emerald-500">
                        <option value="male" {{ $user->gender === 'male' ? 'selected' : '' }}>Pria</option>
                        <option value="female" {{ $user->gender === 'female' ? 'selected' : '' }}>Wanita</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Usia (Tahun)</label>
                    <input type="number" min="12" max="100" name="age" value="{{ old('age', $user->age) }}" required
                           class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Tinggi (cm)</label>
                    <input type="number" step="0.5" name="height_cm" value="{{ old('height_cm', $user->height_cm) }}" required
                           class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Target Berat (kg)</label>
                    <input type="number" step="0.5" name="target_weight_kg" value="{{ old('target_weight_kg', $user->target_weight_kg) }}"
                           class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Tujuan Kebugaran</label>
                    <select name="fitness_goal" class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-2 text-xs text-zinc-200 focus:outline-none focus:border-emerald-500">
                        <option value="bulking" {{ $user->fitness_goal === 'bulking' ? 'selected' : '' }}>Bulking (Massa Otot)</option>
                        <option value="cutting" {{ $user->fitness_goal === 'cutting' ? 'selected' : '' }}>Cutting (Bakar Lemak)</option>
                        <option value="maintenance" {{ $user->fitness_goal === 'maintenance' ? 'selected' : '' }}>Maintenance (Stabil)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Tingkat Aktivitas</label>
                    <select name="activity_level" class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-2 text-xs text-zinc-200 focus:outline-none focus:border-emerald-500">
                        <option value="sedentary" {{ $user->activity_level === 'sedentary' ? 'selected' : '' }}>Sedentary (Jarang Olahraga)</option>
                        <option value="light" {{ $user->activity_level === 'light' ? 'selected' : '' }}>Light (1-3 hari / minggu)</option>
                        <option value="moderate" {{ $user->activity_level === 'moderate' ? 'selected' : '' }}>Moderate (3-5 hari / minggu)</option>
                        <option value="active" {{ $user->activity_level === 'active' ? 'selected' : '' }}>Active (6-7 hari / minggu)</option>
                        <option value="very_active" {{ $user->activity_level === 'very_active' ? 'selected' : '' }}>Very Active (Atlet / Fisik)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Preferensi Tampilan</label>
                    <select name="theme_preference" class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-2 text-xs text-zinc-200 focus:outline-none focus:border-emerald-500">
                        <option value="dark" {{ $user->theme_preference === 'dark' ? 'selected' : '' }}>Dark Gym Aesthetic</option>
                        <option value="light" {{ $user->theme_preference === 'light' ? 'selected' : '' }}>Light Mode</option>
                    </select>
                </div>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium text-xs transition-colors">
                    Simpan Perubahan Profil
                </button>
            </div>
        </form>
    </div>

    <!-- Section 2: Change Password -->
    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 sm:p-8 space-y-6">
        <div class="flex items-center gap-3 border-b border-zinc-800 pb-4">
            <div class="w-10 h-10 rounded-xl bg-rose-500/10 text-rose-400 flex items-center justify-center font-bold">
                <i data-lucide="lock" class="w-5 h-5"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-white">Ubah Password Akun</h2>
                <p class="text-xs text-zinc-400">Pastikan menggunakan kombinasi kata sandi yang aman</p>
            </div>
        </div>

        <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Password Saat Ini</label>
                    <input type="password" name="current_password" required
                           class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-zinc-100 focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Password Baru</label>
                    <input type="password" name="password" required
                           class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-zinc-100 focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-zinc-100 focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-white font-bold text-xs uppercase tracking-wider transition-all">
                    Perbarui Password
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
