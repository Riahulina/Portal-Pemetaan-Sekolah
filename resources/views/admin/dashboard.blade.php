<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SatuPeta</title>
    @vite(['resources/css/app.css'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            overflow: auto !important;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans text-gray-900 min-h-screen">

    <div class="flex min-h-screen">

        <!-- SIDEBAR -->
        @include('partials.adminSidebar')

        <!-- MAIN CONTENT -->
        <main class="flex-1 flex flex-col min-h-screen overflow-y-auto">

            <!-- HEADER -->
            <header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between sticky top-0 z-30" x-data="{ open: false }">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Dashboard</h1>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Search Bar -->
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" placeholder="Cari Sekolah..." class="w-64 pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm text-gray-700 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#0d9296]/30 focus:border-[#0d9296] transition-all">
                    </div>

                    <!-- Profile Dropdown (Alpine.js) -->
                    <div class="relative" @click.away="open = false">
                        <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-50 transition-all">
                            <div class="w-8 h-8 rounded-full bg-[#0d9296] text-white flex items-center justify-center text-sm font-bold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                            <span class="text-sm font-medium text-gray-700 hidden sm:block">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-xl shadow-lg py-2 origin-top-right" style="display: none;">
                            <!-- User Info -->
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ Auth::user()->email }}</p>
                            </div>

                            <!-- Nav Links -->
                            <div class="py-1">
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                    </svg>
                                    Peta Interaktif
                                </a>
                                <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    Manajemen Sekolah
                                </a>
                                <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                    Pendaftaran
                                </a>
                                <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    Manajemen Pengguna
                                </a>
                                <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Laporan
                                </a>
                            </div>

                            <!-- Logout -->
                            <div class="border-t border-gray-100 pt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Keluar / Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- CONTENT -->
            <div class="p-8 flex flex-col gap-8">

                <!-- FLASH MESSAGE -->
                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 text-sm font-medium px-5 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- 4 METRIC CARDS -->
                <div class="grid grid-cols-4 gap-5">

                    <!-- Total Sekolah -->
                    <div class="bg-white border border-gray-200 rounded-xl p-5">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-teal-50 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-[#0d9296]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Total Sekolah</p>
                                <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ number_format($totalSekolah) }}</p>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-200 text-xs text-gray-500 flex flex-col gap-1">
                            <div class="flex justify-between items-center">
                                <span>Berkoordinat:</span>
                                <span class="font-medium text-emerald-600">{{ number_format($totalSekolah - $sekolahTanpaKoordinat, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Tanpa Koordinat:</span>
                                <span class="font-medium text-amber-600">{{ number_format($sekolahTanpaKoordinat, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Menunggu Verifikasi -->
                    <div class="bg-white border border-gray-200 rounded-xl p-5 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-amber-50 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Menunggu Verifikasi</p>
                            <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ number_format($menungguVerifikasi) }}</p>
                        </div>
                    </div>

                    <!-- Disetujui -->
                    <div class="bg-white border border-gray-200 rounded-xl p-5 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Disetujui</p>
                            <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ number_format($disetujui) }}</p>
                        </div>
                    </div>

                    <!-- Ditolak -->
                    <div class="bg-white border border-gray-200 rounded-xl p-5 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Ditolak</p>
                            <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ number_format($ditolak) }}</p>
                        </div>
                    </div>

                </div>

                <!-- CHARTS ROW -->
                <div class="grid grid-cols-5 gap-5">

                    <!-- Line Chart (7 Hari) -->
                    <div class="col-span-3 bg-white border border-gray-200 rounded-xl p-6">
                        <h3 class="text-sm font-bold text-gray-900 mb-4">Pendaftaran 7 Hari Terakhir</h3>
                        <div class="relative" style="height: 280px;">
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>

                    <!-- Donut Chart (Distribusi Jenjang) -->
                    <div class="col-span-2 bg-white border border-gray-200 rounded-xl p-6">
                        <h3 class="text-sm font-bold text-gray-900 mb-4">Distribusi Jenjang</h3>
                        <div class="relative flex justify-center" style="height: 240px;">
                            <canvas id="donutChart"></canvas>
                        </div>
                        <div id="donutLegend" class="flex flex-wrap justify-center gap-x-5 gap-y-2 mt-4"></div>
                    </div>

                </div>

                <!-- RECENT ACTIVITY -->
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <h3 class="text-sm font-bold text-gray-900 mb-4">Aktivitas Terbaru</h3>

                    @forelse ($recentActivity as $activity)
                        <div class="flex items-center justify-between py-3.5">
                            <div class="flex items-center gap-3">
                                @if ($activity->action === 'mendaftar')
                                    <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                @elseif ($activity->action === 'disetujui')
                                    <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $activity->school_name }}</p>
                                    <p class="text-xs text-gray-400">
                                        @if ($activity->action === 'mendaftar')
                                            Mendaftarkan sekolah
                                        @elseif ($activity->action === 'disetujui')
                                            Pengajuan disetujui
                                        @else
                                            Pengajuan ditolak
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                @if ($activity->action === 'mendaftar')
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-600 uppercase">Baru</span>
                                @elseif ($activity->action === 'disetujui')
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-50 text-green-600 uppercase">Disetujui</span>
                                @else
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-600 uppercase">Ditolak</span>
                                @endif
                                <p class="text-[11px] text-gray-400 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 text-center py-8">Belum ada aktivitas tercatat.</p>
                    @endforelse
                </div>

            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // ===== LINE CHART =====
            const lineLabels = @json(collect($lineChartData)->pluck('date'));
            const lineValues = @json(collect($lineChartData)->pluck('count'));

            // Build 7-day labels even if some days have 0
            const allLabels = [];
            const allValues = [];
            for (let i = 6; i >= 0; i--) {
                const d = new Date();
                d.setDate(d.getDate() - i);
                const dateStr = d.toISOString().slice(0, 10);
                const dayName = d.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric', month: 'short' });
                allLabels.push(dayName);
                const idx = lineLabels.indexOf(dateStr);
                allValues.push(idx >= 0 ? lineValues[idx] : 0);
            }

            new Chart(document.getElementById('lineChart'), {
                type: 'line',
                data: {
                    labels: allLabels,
                    datasets: [{
                        label: 'Pendaftaran',
                        data: allValues,
                        borderColor: '#0d9296',
                        backgroundColor: 'rgba(13, 146, 150, 0.08)',
                        borderWidth: 2.5,
                        pointRadius: 4,
                        pointBackgroundColor: '#0d9296',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        tension: 0.35,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                font: { size: 11, family: 'Public Sans' },
                                color: '#9ca3af'
                            },
                            grid: { color: '#f3f4f6' }
                        },
                        x: {
                            ticks: {
                                font: { size: 11, family: 'Public Sans' },
                                color: '#9ca3af'
                            },
                            grid: { display: false }
                        }
                    }
                }
            });

            // ===== DONUT CHART =====
            const donutLabels = @json(collect($donutData)->pluck('jenjang'));
            const donutValues = @json(collect($donutData)->pluck('count'));
            const donutColors = ['#22C55E', '#3B82F6', '#A855F7', '#F97316', '#EF4444', '#6366F1', '#EC4899'];

            new Chart(document.getElementById('donutChart'), {
                type: 'doughnut',
                data: {
                    labels: donutLabels,
                    datasets: [{
                        data: donutValues,
                        backgroundColor: donutColors.slice(0, donutLabels.length),
                        borderWidth: 2,
                        borderColor: '#fff',
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

            // Custom legend
            const legendContainer = document.getElementById('donutLegend');
            donutLabels.forEach(function (label, i) {
                const total = donutValues.reduce(function (a, b) { return a + b; }, 0);
                const pct = total > 0 ? ((donutValues[i] / total) * 100).toFixed(1) : 0;
                legendContainer.innerHTML +=
                    '<div class="flex items-center gap-1.5">' +
                        '<span class="w-2.5 h-2.5 rounded-full shrink-0" style="background:' + donutColors[i] + '"></span>' +
                        '<span class="text-xs font-medium text-gray-600">' + label + '</span>' +
                        '<span class="text-xs text-gray-400">(' + pct + '%)</span>' +
                    '</div>';
            });

        });
    </script>

</body>

</html>
