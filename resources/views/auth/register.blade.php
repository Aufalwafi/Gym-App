<!DOCTYPE html>
<html lang="id" class="dark h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru - FitTrack</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    @vite(['resources/js/app.js'])
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-zinc-950 text-zinc-100 min-h-screen flex items-center justify-center p-4 py-12 selection:bg-emerald-500 selection:text-white">

    <div class="w-full max-w-lg">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-400 text-white shadow-xl shadow-emerald-500/25 mb-3">
                <i data-lucide="dumbbell" class="w-7 h-7"></i>
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight text-white">Mulai di Fit<span class="text-emerald-400">Track</span></h1>
            <p class="text-zinc-400 text-sm mt-1">Daftarkan akun dan mulai catat latihan gym & target fisik Anda</p>
        </div>

        @if($errors->any())
            <div class="mb-4 p-4 rounded-xl bg-rose-950/70 border border-rose-500/40 text-rose-300 text-sm">
                <div class="flex items-center gap-2 font-semibold mb-1">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-rose-400"></i> Periksa kembali isian Anda:
                </div>
                <ul class="list-disc list-inside text-xs space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 sm:p-8 shadow-2xl backdrop-blur-sm">
            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Basic Account Info -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso"
                               class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com"
                               class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Password</label>
                        <input type="password" name="password" required placeholder="Minimal 8 karakter"
                               class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" required placeholder="Ulangi password"
                               class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                    </div>
                </div>

                <!-- Fitness Profile (Optional / Initial) -->
                <div class="pt-4 border-t border-zinc-800">
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-400 mb-3 flex items-center gap-1.5">
                        <i data-lucide="activity" class="w-4 h-4"></i> Data Fisik Awal (Opsional)
                    </p>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-[11px] font-medium text-zinc-400 mb-1">Jenis Kelamin</label>
                            <select name="gender" class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-2 text-xs text-zinc-200 focus:outline-none focus:border-emerald-500">
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Pria</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Wanita</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-medium text-zinc-400 mb-1">Usia (Tahun)</label>
                            <input type="number" name="age" value="{{ old('age', 21) }}" min="12" max="100"
                                   class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-2 text-xs text-zinc-200 focus:outline-none focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-medium text-zinc-400 mb-1">Tinggi (cm)</label>
                            <input type="number" step="0.5" name="height_cm" value="{{ old('height_cm', 170) }}" min="100" max="250"
                                   class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-2 text-xs text-zinc-200 focus:outline-none focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-medium text-zinc-400 mb-1">Berat Saat Ini (kg)</label>
                            <input type="number" step="0.1" name="initial_weight_kg" value="{{ old('initial_weight_kg', 65) }}" min="30" max="300"
                                   class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-2 text-xs text-zinc-200 focus:outline-none focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-medium text-zinc-400 mb-1">Target Berat (kg)</label>
                            <input type="number" step="0.1" name="target_weight_kg" value="{{ old('target_weight_kg', 70) }}" min="30" max="300"
                                   class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-2 text-xs text-zinc-200 focus:outline-none focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-medium text-zinc-400 mb-1">Tujuan Fitness</label>
                            <select name="fitness_goal" class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-2 text-xs text-zinc-200 focus:outline-none focus:border-emerald-500">
                                <option value="bulking" {{ old('fitness_goal') == 'bulking' ? 'selected' : '' }}>Bulking (Massa Otot)</option>
                                <option value="cutting" {{ old('fitness_goal') == 'cutting' ? 'selected' : '' }}>Cutting (Bakar Lemak)</option>
                                <option value="maintenance" {{ old('fitness_goal') == 'maintenance' ? 'selected' : '' }}>Maintenance (Pertahankan)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-zinc-950 font-bold text-sm shadow-lg shadow-emerald-500/25 transition-all hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center gap-2 mt-4">
                    <i data-lucide="user-plus" class="w-4 h-4"></i> Buat Akun & Mulai
                </button>
            </form>
        </div>

        <p class="text-center text-sm text-zinc-400 mt-6">
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="font-semibold text-emerald-400 hover:text-emerald-300 underline underline-offset-4">Masuk di sini</a>
        </p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                window.lucide.createIcons();
            }
        });
    </script>
</body>
</html>
