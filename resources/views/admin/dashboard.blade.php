@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
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
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
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
@endpush
