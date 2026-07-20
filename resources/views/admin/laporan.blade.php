<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Ringkasan - Admin SatuPeta</title>
    @vite(['resources/css/app.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Tambahkan Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            overflow: auto !important;
        }

        [x-cloak] {
            display: none !important;
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
            <header
                class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between sticky top-0 z-30">
                <h1 class="text-xl font-bold text-gray-800">Laporan</h1>

                <div class="flex items-center gap-6">
                    <!-- Search Bar Mockup -->
                    <div class="relative w-64">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" placeholder="Cari Sekolah, NPSN..."
                            class="w-full pl-9 pr-4 py-1.5 border border-gray-200 rounded-lg text-xs bg-gray-50 focus:outline-none focus:ring-1 focus:ring-[#0d9296]">
                    </div>

                    <!-- Profile Dropdown -->
                    <div class="flex items-center gap-2">
                        <div
                            class="w-8 h-8 rounded-full bg-teal-700 text-white flex items-center justify-center font-bold text-sm">
                            A</div>
                        <span class="text-sm font-bold text-gray-700">Admin</span>
                    </div>
                </div>
            </header>

            <!-- WORKSPACE CONTAINER -->
            <div class="p-8 flex-1">

                <!-- TITLE RINGKASAN & EXPORT -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Laporan Ringkasan</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Ringkasan Statistik data sekolah</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Date Range Picker Mockup -->
                        <div
                            class="flex items-center gap-2 bg-white border border-gray-200 px-3 py-1.5 rounded-lg text-xs font-medium text-gray-600 shadow-sm">
                            <span>1 July 2026 - 30 July 2026</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 002-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>

                        <!-- Dropdown Pilihan Export -->
                        <div class="relative" x-data="{ openExport: false }">
                            <button @click="openExport = !openExport"
                                class="px-5 py-1.5 bg-[#0d9296] hover:bg-[#0b7c80] text-white text-xs font-bold rounded-lg shadow-sm transition-colors flex items-center gap-1">
                                Export
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <path d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Menu Pilihan -->
                            <div x-show="openExport" @click.away="openExport = false" x-transition x-cloak
                                class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-xl shadow-lg py-1 z-50 text-xs">
                                <a href="{{ route('admin.laporan.excel') }}"
                                    class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50 font-medium">
                                    Export ke Excel
                                </a>
                                <a href="{{ route('admin.laporan.pdf') }}"
                                    class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50 font-medium border-t border-gray-100">
                                    Export ke PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4 BOX METRICS (CARD ATAS) -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

                    <!-- Card 1: Total Sekolah -->
                    <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm flex items-center gap-4">
                        <div class="p-3 bg-teal-50 text-teal-600 rounded-xl border border-teal-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Total
                                Sekolah</span>
                            <span
                                class="text-xl font-black text-gray-800">{{ number_format($totalSekolah, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Card 2: Menunggu Verifikasi -->
                    <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm flex items-center gap-4">
                        <div class="p-3 bg-orange-50 text-orange-600 rounded-xl border border-orange-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Menunggu
                                Verifikasi</span>
                            <div class="flex items-baseline gap-2">
                                <span
                                    class="text-xl font-black text-gray-800">{{ number_format($menungguVerifikasi, 0, ',', '.') }}</span>
                                <span
                                    class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1 rounded">+76%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Disetujui -->
                    <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm flex items-center gap-4">
                        <div class="p-3 bg-sky-50 text-sky-600 rounded-xl border border-sky-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <span
                                class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Disetujui</span>
                            <div class="flex items-baseline gap-2">
                                <span
                                    class="text-xl font-black text-gray-800">{{ number_format($disetujui, 0, ',', '.') }}</span>
                                <span
                                    class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1 rounded">+678%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4: Ditolak -->
                    <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm flex items-center gap-4">
                        <div class="p-3 bg-red-50 text-red-600 rounded-xl border border-red-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <span
                                class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Ditolak</span>
                            <div class="flex items-baseline gap-2">
                                <span
                                    class="text-xl font-black text-gray-800">{{ number_format($ditolak, 0, ',', '.') }}</span>
                                <span class="text-[10px] font-bold text-red-600 bg-red-50 px-1 rounded">-13%</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- CHARTS ROW DISPLAY -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

                    <!-- LEFT SIDE: Pendaftaran Harian (Line/Area Chart) -->
                    <div class="lg:col-span-2 bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                        <h3 class="text-xs font-bold text-gray-800 mb-4">Pendaftaran Harian</h3>
                        <div class="w-full relative h-64">
                            <canvas id="lineChartHarian"></canvas>
                        </div>
                    </div>

                    <!-- RIGHT SIDE: Pendaftaran Berdasarkan Jenjang (Donut Chart) -->
                    <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                        <h3 class="text-xs font-bold text-gray-800 mb-4">Pendaftaran Berdasarkan Jenjang</h3>
                        <div class="w-full relative h-64 flex justify-center items-center">
                            <canvas id="donutChartJenjang"></canvas>
                        </div>
                    </div>

                </div>

            </div>
        </main>
    </div>

    <!-- INJECT LOGIK CHART SCRIPT -->
    <script>
        // 1. Setup Line/Area Chart Pendaftaran Harian
        const ctxLine = document.getElementById('lineChartHarian').getContext('2d');
        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartHarianLabels) !!},
                datasets: [{
                    label: 'Pendaftaran',
                    data: {!! json_encode($chartHarianValues) !!},
                    borderColor: '#0d9296',
                    backgroundColor: 'rgba(13, 146, 150, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#0d9296',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        min: 0,
                        max: 100,
                        ticks: {
                            stepSize: 20,
                            font: {
                                size: 10
                            }
                        },
                        grid: {
                            borderDash: [4, 4]
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 10
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // 2. Setup Donut Chart Jenjang Sekolah (Dengan Fallback Data Mockup Jika Kosong)
        let backendLabels = {!! json_encode($labelsJenjang) !!};
        let backendValues = {!! json_encode($valuesJenjang) !!};

        // Jika database sekolah utama kosong/belum dikelompokkan, pakai data mockup agar tidak kosong
        if (backendLabels.length === 0) {
            backendLabels = ['SD', 'SMP', 'SMA', 'SMK', 'TK'];
            backendValues = [40, 25, 15, 12, 8];
        }

        const ctxDonut = document.getElementById('donutChartJenjang').getContext('2d');
        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: backendLabels,
                datasets: [{
                    data: backendValues,
                    backgroundColor: ['#0d9296', '#818cf8', '#fb923c', '#4ade80', '#f87171'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 10,
                            font: {
                                size: 10
                            },
                            padding: 15
                        }
                    }
                },
                cutout: '65%'
            }
        });
    </script>
</body>

</html>
