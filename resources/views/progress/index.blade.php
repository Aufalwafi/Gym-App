@extends('layouts.app')

@section('title', 'Progres Fisik & Personal Records')

@section('content')
<div class="space-y-8">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Progres Fisik & Rekor Angkatan (PR)</h1>
            <p class="text-zinc-400 text-xs sm:text-sm">Pantau perubahan berat badan, komposisi tubuh, serta catatan rekor angkatan terberat Anda.</p>
        </div>
    </div>

    <!-- Weight Trend Chart (Full width) -->
    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 sm:p-8 space-y-4 shadow-xl">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-white flex items-center gap-2">
                <i data-lucide="trending-up" class="w-5 h-5 text-emerald-400"></i> Grafik Perkembangan Berat Badan
            </h2>
            <span class="text-xs text-zinc-400">30 Catatan Terakhir</span>
        </div>

        @if(count($chartWeights) > 0)
            <div class="h-64 sm:h-80 w-full">
                <canvas id="mainProgressChart"></canvas>
            </div>
        @else
            <div class="h-48 flex items-center justify-center text-center text-zinc-500 text-xs">
                Belum ada rekaman berat badan. Silakan input data berat badan pertama Anda di bawah.
            </div>
        @endif
    </div>

    <!-- Personal Records (PR) Hall of Fame -->
    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 sm:p-8 space-y-4 shadow-xl">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-white flex items-center gap-2">
                    <i data-lucide="award" class="w-5 h-5 text-amber-400"></i> Personal Records (PR) Hall of Fame
                </h2>
                <p class="text-xs text-zinc-400">Rekor angkatan terberat dan estimasi 1-Rep Max tertinggi Anda</p>
            </div>
        </div>

        @if($personalRecords->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 pt-2">
                @foreach($personalRecords as $pr)
                    <div class="bg-zinc-950 border border-zinc-800 hover:border-zinc-700 rounded-2xl p-4 transition-all">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-zinc-900 text-emerald-400 border border-zinc-800">
                                {{ $pr->exercise->category->name ?? 'Latihan' }}
                            </span>
                            <span class="text-[10px] text-amber-400 font-bold flex items-center gap-1">
                                <i data-lucide="trophy" class="w-3 h-3"></i> PR
                            </span>
                        </div>

                        <h3 class="font-bold text-white text-base">
                            <a href="{{ route('exercises.show', $pr->exercise->id) }}" class="hover:text-emerald-400 transition-colors">
                                {{ $pr->exercise->name }}
                            </a>
                        </h3>

                        <div class="mt-3 pt-3 border-t border-zinc-800/80 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-zinc-500 block">Est. 1RM</span>
                                <span class="text-xl font-black text-emerald-400 font-mono">{{ $pr->max_1rm }} kg</span>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] uppercase font-bold text-zinc-500 block">Beban Terbaik</span>
                                <span class="text-sm font-extrabold text-white">
                                    {{ $pr->best_set->weight_kg ?? $pr->max_weight }} kg &times; {{ $pr->best_set->reps ?? 1 }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-zinc-500 text-xs italic py-4">Belum ada rekor angkatan yang tercatat dari sesi latihan.</p>
        @endif
    </div>

    <!-- Two Columns: Log Measurement Form & Measurement History -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Form: Log Measurement (1 Col) -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 space-y-4 shadow-xl h-fit">
            <h2 class="text-base font-bold text-white flex items-center gap-2">
                <i data-lucide="plus-circle" class="w-4 h-4 text-emerald-400"></i> Catat Ukuran Hari Ini
            </h2>

            <form action="{{ route('progress.store') }}" method="POST" class="space-y-3">
                @csrf

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1">Tanggal</label>
                    <input type="date" name="recorded_date" required value="{{ date('Y-m-d') }}"
                           class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1">Berat Badan (kg) *</label>
                    <input type="number" step="0.1" name="weight_kg" required placeholder="Contoh: 68.5"
                           class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-emerald-500 font-bold">
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[10px] uppercase text-zinc-400 mb-1">Body Fat (%)</label>
                        <input type="number" step="0.1" name="body_fat_percentage" placeholder="15.0"
                               class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase text-zinc-400 mb-1">Pinggang (cm)</label>
                        <input type="number" step="0.5" name="waist_cm" placeholder="78"
                               class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none focus:border-emerald-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[10px] uppercase text-zinc-400 mb-1">Lingkar Dada (cm)</label>
                        <input type="number" step="0.5" name="chest_cm" placeholder="96"
                               class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase text-zinc-400 mb-1">Lingkar Lengan (cm)</label>
                        <input type="number" step="0.5" name="arms_cm" placeholder="34"
                               class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none focus:border-emerald-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1">Catatan</label>
                    <input type="text" name="notes" placeholder="Catatan kondisi fisik / pola makan..."
                           class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-emerald-500">
                </div>

                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-zinc-950 font-bold text-xs uppercase tracking-wider transition-all mt-2">
                    Simpan Catatan
                </button>
            </form>
        </div>

        <!-- Measurement History Table (2 Cols) -->
        <div class="lg:col-span-2 bg-zinc-900 border border-zinc-800 rounded-3xl p-6 space-y-4 shadow-xl">
            <h2 class="text-base font-bold text-white flex items-center gap-2">
                <i data-lucide="history" class="w-4 h-4 text-blue-400"></i> Riwayat Pengukuran Fisik
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-zinc-500 border-b border-zinc-800">
                            <th class="py-2.5 px-3">Tanggal</th>
                            <th class="py-2.5 px-3">Berat</th>
                            <th class="py-2.5 px-3">Body Fat</th>
                            <th class="py-2.5 px-3">Lengan</th>
                            <th class="py-2.5 px-3">Catatan</th>
                            <th class="py-2.5 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800/40">
                        @forelse($measurements as $m)
                            <tr class="hover:bg-zinc-800/30">
                                <td class="py-2.5 px-3 font-semibold text-zinc-300">{{ \Carbon\Carbon::parse($m->recorded_date)->format('d M Y') }}</td>
                                <td class="py-2.5 px-3 font-bold text-white text-sm">{{ $m->weight_kg }} kg</td>
                                <td class="py-2.5 px-3 text-zinc-400">{{ $m->body_fat_percentage ? $m->body_fat_percentage . '%' : '-' }}</td>
                                <td class="py-2.5 px-3 text-zinc-400">{{ $m->arms_cm ? $m->arms_cm . ' cm' : '-' }}</td>
                                <td class="py-2.5 px-3 text-zinc-400 truncate max-w-[150px]">{{ $m->notes ?? '-' }}</td>
                                <td class="py-2.5 px-3 text-right">
                                    <form action="{{ route('progress.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus catatan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-zinc-600 hover:text-rose-400 transition-colors">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-zinc-500 italic">Belum ada rekaman fisik</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $measurements->links() }}
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('mainProgressChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [
                        {
                            label: 'Berat Badan (kg)',
                            data: {!! json_encode($chartWeights) !!},
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            fill: true,
                            tension: 0.35,
                            pointRadius: 5,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#09090b',
                            pointBorderWidth: 2,
                            yAxisID: 'y'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            grid: { color: 'rgba(255, 255, 255, 0.05)' },
                            ticks: { color: '#71717a' }
                        },
                        y: {
                            grid: { color: 'rgba(255, 255, 255, 0.05)' },
                            ticks: { color: '#71717a' }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
