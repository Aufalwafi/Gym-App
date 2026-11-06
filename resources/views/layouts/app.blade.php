<!DOCTYPE html>
<html lang="id" class="dark h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#09090b">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FitTrack') - Gym Workout & Fitness Tracker</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons CDN fallback & Chart.js -->
    <script src="https://unpkg.com/lucide@latest"></script>

    @vite(['resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        [x-cloak] { display: none !important; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; color: black !important; }
        }
    </style>
</head>
<body class="bg-zinc-950 text-zinc-100 min-h-screen flex flex-col antialiased selection:bg-green-600 selection:text-white pb-20 md:pb-0" x-data="globalApp()">

    <!-- Top Navigation Bar (Desktop & Mobile Header) -->
    <header class="sticky top-0 z-40 bg-zinc-900 border-b border-zinc-800 no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand / Logo -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
                        <div class="w-9 h-9 rounded-lg bg-green-600 flex items-center justify-center text-white">
                            <i data-lucide="dumbbell" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <span class="font-bold text-xl tracking-tight text-white">Fit<span class="text-green-500">Track</span></span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-1">
                    <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-zinc-800 text-green-500' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }}">
                        <i data-lucide="layout-dashboard" class="w-4 h-4 inline-block mr-1.5 -mt-0.5"></i> Dashboard
                    </a>
                    <a href="{{ route('workouts.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('workouts.*') ? 'bg-zinc-800 text-green-500' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }}">
                        <i data-lucide="flame" class="w-4 h-4 inline-block mr-1.5 -mt-0.5"></i> Latihan
                    </a>
                    <a href="{{ route('routines.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('routines.*') ? 'bg-zinc-800 text-green-500' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }}">
                        <i data-lucide="calendar-range" class="w-4 h-4 inline-block mr-1.5 -mt-0.5"></i> Program
                    </a>
                    <a href="{{ route('exercises.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('exercises.*') ? 'bg-zinc-800 text-green-500' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }}">
                        <i data-lucide="book-open" class="w-4 h-4 inline-block mr-1.5 -mt-0.5"></i> Katalog
                    </a>
                    <a href="{{ route('calculator.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('calculator.*') ? 'bg-zinc-800 text-green-500' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }}">
                        <i data-lucide="calculator" class="w-4 h-4 inline-block mr-1.5 -mt-0.5"></i> Kalkulator
                    </a>
                    <a href="{{ route('progress.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('progress.*') ? 'bg-zinc-800 text-green-500' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }}">
                        <i data-lucide="trending-up" class="w-4 h-4 inline-block mr-1.5 -mt-0.5"></i> Progres
                    </a>
                </nav>

                <!-- Action CTA & User Dropdown -->
                <div class="flex items-center gap-3">
                    <!-- Quick Start Workout Button -->
                    <a href="{{ route('workouts.create') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium text-xs transition-colors">
                        <i data-lucide="plus" class="w-4 h-4"></i> Catat Sesi
                    </a>

                    <!-- User menu -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-zinc-800 text-zinc-300 hover:text-white transition-colors focus:outline-none">
                            <div class="w-8 h-8 rounded-lg bg-zinc-800 border border-zinc-700 text-zinc-300 font-bold flex items-center justify-center text-xs">
                                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                            </div>
                            <span class="hidden md:inline-block text-sm font-medium">{{ Auth::user()->name ?? 'Akun' }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-zinc-500"></i>
                        </button>

                        <div x-show="open" @click.outside="open = false" x-cloak
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             class="absolute right-0 mt-2 w-48 rounded-xl bg-zinc-900 border border-zinc-800 shadow-xl py-1 z-50">
                            <div class="px-4 py-2 border-b border-zinc-800 text-xs text-zinc-400">
                                <p class="font-semibold text-zinc-200">{{ Auth::user()->name ?? '' }}</p>
                                <p class="truncate">{{ Auth::user()->email ?? '' }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-zinc-300 hover:bg-zinc-800 hover:text-white">
                                <i data-lucide="user" class="w-4 h-4 text-zinc-400"></i> Profil Saya
                            </a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-rose-400 hover:bg-zinc-800 hover:text-rose-300 text-left">
                                    <i data-lucide="log-out" class="w-4 h-4"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Global Alerts (Success / Error) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full no-print">
        @if(session('success'))
            <div class="flex items-center gap-3 p-4 rounded-xl bg-green-950 border border-green-800 text-green-200 shadow-md text-sm" role="alert">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-400 flex-shrink-0"></i>
                <div class="flex-1 font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="flex items-center gap-3 p-4 rounded-xl bg-rose-950 border border-rose-800 text-rose-200 shadow-md text-sm" role="alert">
                <i data-lucide="alert-circle" class="w-5 h-5 text-rose-400 flex-shrink-0"></i>
                <div class="flex-1 font-medium">{{ session('error') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-xl bg-rose-950/60 border border-rose-500/40 text-rose-200 shadow-lg text-sm" role="alert">
                <div class="flex items-center gap-2 font-semibold mb-1 text-rose-400">
                    <i data-lucide="alert-triangle" class="w-4 h-4"></i> Terdapat kesalahan pengisian:
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-300">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    <!-- Mobile Bottom Navigation (App-like Gym UI for Phone) -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-zinc-900 border-t border-zinc-800 px-2 py-1.5 flex items-center justify-around no-print">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center py-1 px-3 text-xs font-medium {{ request()->routeIs('dashboard') ? 'text-green-500 font-bold' : 'text-zinc-400' }}">
            <i data-lucide="layout-dashboard" class="w-5 h-5 mb-0.5"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('workouts.index') }}" class="flex flex-col items-center py-1 px-3 text-xs font-medium {{ request()->routeIs('workouts.index') ? 'text-green-500 font-bold' : 'text-zinc-400' }}">
            <i data-lucide="flame" class="w-5 h-5 mb-0.5"></i>
            <span>Riwayat</span>
        </a>
        
        <!-- Big Plus Button in Center -->
        <a href="{{ route('workouts.create') }}" class="flex flex-col items-center -mt-5">
            <div class="w-12 h-12 rounded-full bg-green-600 hover:bg-green-700 text-white font-black flex items-center justify-center border-4 border-zinc-950">
                <i data-lucide="plus" class="w-6 h-6 stroke-[3]"></i>
            </div>
            <span class="text-[10px] text-green-500 font-medium mt-0.5">Latihan</span>
        </a>

        <a href="{{ route('routines.index') }}" class="flex flex-col items-center py-1 px-3 text-xs font-medium {{ request()->routeIs('routines.*') ? 'text-green-500 font-bold' : 'text-zinc-400' }}">
            <i data-lucide="calendar-range" class="w-5 h-5 mb-0.5"></i>
            <span>Program</span>
        </a>
        <a href="{{ route('progress.index') }}" class="flex flex-col items-center py-1 px-3 text-xs font-medium {{ request()->routeIs('progress.*') ? 'text-green-500 font-bold' : 'text-zinc-400' }}">
            <i data-lucide="trending-up" class="w-5 h-5 mb-0.5"></i>
            <span>Progres</span>
        </a>
    </nav>

    <!-- Footer for Academic Submission Reference -->
    <footer class="mt-auto border-t border-zinc-900 bg-zinc-950/80 py-6 text-center text-xs text-zinc-600 no-print">
        <p class="font-medium text-zinc-500">FitTrack &copy; {{ date('Y') }} &bull; Project Gym & Fitness Tracker Semester 7</p>
        <p class="mt-1">Dibuat menggunakan PHP Laravel 11, Tailwind CSS, Alpine.js, Chart.js & MySQL</p>
    </footer>

    <script>
        function globalApp() {
            return {
                init() {
                    if (window.lucide) {
                        window.lucide.createIcons();
                    }
                }
            }
        }
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                window.lucide.createIcons();
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
