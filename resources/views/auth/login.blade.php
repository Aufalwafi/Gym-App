<!DOCTYPE html>
<html lang="id" class="dark h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FitTrack Gym Tracker</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    @vite(['resources/js/app.js'])
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-zinc-950 text-zinc-100 min-h-screen flex items-center justify-center p-4 selection:bg-green-600 selection:text-white">

    <div class="w-full max-w-md">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-green-600 text-white mb-4">
                <i data-lucide="dumbbell" class="w-7 h-7"></i>
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight text-white">Fit<span class="text-green-500">Track</span></h1>
            <p class="text-zinc-400 text-sm mt-1">Gym Workout, Routine & Fitness Tracker</p>
        </div>

        <!-- Alert Error -->
        @if($errors->any())
            <div class="mb-4 p-4 rounded-xl bg-rose-950 border border-rose-800 text-rose-300 text-sm">
                <div class="flex items-center gap-2 font-semibold mb-1">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-rose-400"></i> Terjadi kesalahan:
                </div>
                <ul class="list-disc list-inside text-xs space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="mb-4 p-4 rounded-xl bg-green-950 border border-green-800 text-green-300 text-sm flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4 text-green-400"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Login Card -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 sm:p-8">
            <h2 class="text-xl font-bold text-white mb-6">Masuk ke Akun Anda</h2>

            <form action="{{ route('login') }}" method="POST" class="space-y-4" id="loginForm">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Email</label>
                    <div class="relative">
                        <i data-lucide="mail" class="w-4 h-4 absolute left-3.5 top-3.5 text-zinc-500"></i>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               placeholder="nama@email.com"
                               class="w-full bg-zinc-950 border border-zinc-800 rounded-lg pl-10 pr-4 py-2.5 text-sm text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-colors">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">Password</label>
                    <div class="relative">
                        <i data-lucide="lock" class="w-4 h-4 absolute left-3.5 top-3.5 text-zinc-500"></i>
                        <input type="password" name="password" id="password" required
                               placeholder="••••••••"
                               class="w-full bg-zinc-950 border border-zinc-800 rounded-lg pl-10 pr-4 py-2.5 text-sm text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-colors">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 text-zinc-400 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded bg-zinc-950 border-zinc-700 text-green-600 focus:ring-0">
                        <span>Ingat saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium text-sm transition-colors flex items-center justify-center gap-2 mt-2">
                    <i data-lucide="log-in" class="w-4 h-4"></i> Masuk Sekarang
                </button>
            </form>

            <!-- Quick Demo Button -->
            <div class="mt-6 pt-5 border-t border-zinc-800">
                <button type="button" onclick="fillDemo()" class="w-full py-2 px-3 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-200 text-xs font-medium flex items-center justify-center gap-2 border border-zinc-700 transition-colors">
                    <i data-lucide="user-check" class="w-4 h-4 text-green-500"></i> Gunakan Akun Demo (1-Klik)
                </button>
                <p class="text-[11px] text-zinc-500 text-center mt-2">admin@fittrack.local / password123</p>
            </div>
        </div>

        <!-- Register Link -->
        <p class="text-center text-sm text-zinc-400 mt-6">
            Belum memiliki akun?
            <a href="{{ route('register') }}" class="font-semibold text-green-500 hover:text-green-400 underline underline-offset-4">Daftar sekarang</a>
        </p>
    </div>

    <script>
        function fillDemo() {
            document.getElementById('email').value = 'admin@fittrack.local';
            document.getElementById('password').value = 'password123';
        }
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                window.lucide.createIcons();
            }
        });
    </script>
</body>
</html>
