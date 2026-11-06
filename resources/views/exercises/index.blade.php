@extends('layouts.app')

@section('title', 'Katalog Latihan Gym')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Katalog Latihan Gym</h1>
            <p class="text-zinc-400 text-xs sm:text-sm">Ensiklopedia gerakan gym berdasarkan kelompok otot, alat, dan instruksi bentuk gerakan.</p>
        </div>

        <a href="{{ route('exercises.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-emerald-400 font-bold text-xs uppercase tracking-wider border border-zinc-700 transition-all">
            <i data-lucide="plus-circle" class="w-4 h-4"></i> Tambah Gerakan Kustom
        </a>
    </div>

    <!-- Filters & Search Bar -->
    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-4 sm:p-5 space-y-4 shadow-md">
        <!-- Muscle Category Pills -->
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('exercises.index', array_merge(request()->except('category', 'page'))) }}"
               class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-colors {{ !request('category') ? 'bg-emerald-500 text-zinc-950 font-bold' : 'bg-zinc-800 text-zinc-400 hover:text-white hover:bg-zinc-700' }}">
                Semua Otot
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('exercises.index', array_merge(request()->except('page'), ['category' => $cat->slug])) }}"
                   class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-colors flex items-center gap-1.5 {{ request('category') === $cat->slug ? 'bg-emerald-500 text-zinc-950 font-bold' : 'bg-zinc-800 text-zinc-300 hover:text-white hover:bg-zinc-700' }}">
                    <span>{{ $cat->name }}</span>
                    <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ request('category') === $cat->slug ? 'bg-zinc-950/20 text-zinc-950' : 'bg-zinc-900 text-zinc-500' }}">{{ $cat->exercises_count }}</span>
                </a>
            @endforeach
        </div>

        <!-- Search & Equipment Filter -->
        <form action="{{ route('exercises.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2 border-t border-zinc-800/80">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif

            <div class="sm:col-span-2 relative">
                <i data-lucide="search" class="w-4 h-4 absolute left-3.5 top-3 text-zinc-500"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama gerakan atau otot sasaran..."
                       class="w-full bg-zinc-950 border border-zinc-800 rounded-xl pl-10 pr-4 py-2 text-xs text-white placeholder-zinc-500 focus:outline-none focus:border-emerald-500">
            </div>

            <div class="flex gap-2">
                <select name="equipment" class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-2 text-xs text-zinc-200 focus:outline-none focus:border-emerald-500">
                    <option value="">Semua Alat</option>
                    <option value="barbell" {{ request('equipment') === 'barbell' ? 'selected' : '' }}>Barbell</option>
                    <option value="dumbbell" {{ request('equipment') === 'dumbbell' ? 'selected' : '' }}>Dumbbell</option>
                    <option value="cable" {{ request('equipment') === 'cable' ? 'selected' : '' }}>Cable</option>
                    <option value="machine" {{ request('equipment') === 'machine' ? 'selected' : '' }}>Machine</option>
                    <option value="bodyweight" {{ request('equipment') === 'bodyweight' ? 'selected' : '' }}>Bodyweight</option>
                </select>

                <button type="submit" class="px-4 py-2 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-zinc-200 text-xs font-semibold shrink-0">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Exercises Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($exercises as $exercise)
            <div class="bg-zinc-900 border border-zinc-800 hover:border-zinc-700 rounded-2xl p-5 flex flex-col justify-between transition-all group shadow-md">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-zinc-800 text-emerald-400 border border-zinc-700">
                            {{ $exercise->category->name ?? 'Gym' }}
                        </span>
                        <span class="text-[10px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded {{ $exercise->difficulty === 'advanced' ? 'bg-rose-500/10 text-rose-400' : ($exercise->difficulty === 'intermediate' ? 'bg-amber-500/10 text-amber-400' : 'bg-emerald-500/10 text-emerald-400') }}">
                            {{ $exercise->difficulty }}
                        </span>
                    </div>

                    <h3 class="text-base font-bold text-white group-hover:text-emerald-400 transition-colors">
                        <a href="{{ route('exercises.show', $exercise->id) }}">{{ $exercise->name }}</a>
                    </h3>

                    <p class="text-xs text-zinc-400 mt-1 flex items-center gap-1.5">
                        <span class="capitalize font-semibold text-zinc-300">{{ $exercise->equipment }}</span>
                        @if($exercise->secondary_muscles)
                            <span>&bull;</span>
                            <span class="truncate">Sekunder: {{ $exercise->secondary_muscles }}</span>
                        @endif
                    </p>

                    @if($exercise->instructions)
                        <p class="text-xs text-zinc-400 mt-2 line-clamp-2 leading-relaxed">
                            {{ $exercise->instructions }}
                        </p>
                    @endif
                </div>

                <div class="mt-4 pt-3 border-t border-zinc-800 flex items-center justify-between text-xs">
                    <span class="text-zinc-500 font-medium">Bentuk Gerakan</span>
                    <a href="{{ route('exercises.show', $exercise->id) }}" class="text-emerald-400 hover:text-emerald-300 font-semibold flex items-center gap-1">
                        Lihat Panduan & Rekor <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 bg-zinc-900/40 border border-dashed border-zinc-800 rounded-2xl p-12 text-center space-y-2">
                <i data-lucide="search-x" class="w-8 h-8 text-zinc-600 mx-auto"></i>
                <p class="text-zinc-400 font-semibold text-sm">Tidak ditemukan gerakan dengan filter tersebut</p>
                <a href="{{ route('exercises.index') }}" class="text-emerald-400 text-xs underline">Reset filter pencarian</a>
            </div>
        @endforelse
    </div>

    <div>
        {{ $exercises->links() }}
    </div>

</div>
@endsection
