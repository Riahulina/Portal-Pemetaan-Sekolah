<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SatuPeta — Dashboard</title>

    <title>Satu Peta — Peta Pendidikan Indonesia</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logo.png') }}?v=2">

    @fonts
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/dashboard.js'])
</head>

<body>
    <div id="app-layout">
        <aside id="left-sidebar">
            <div class="sidebar-brand">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo Kidsnesia" class="brand-logo-img">
                <div class="brand-text">
                    <div class="brand-text--top">

                        <span class="brand-kids">Satu</span><span class="brand-nesia">Peta</span>
                    </div>
                    <div class="brand-text--bottom">
                        <span class="brand-edu">Peta Pendidikan Indonesia</span>


                    </div>
                    <div class="brand-text--bottom">
                        <span class="brand-petapendik whitespace-nowrap text-xs"></span>

                    </div>
                </div>
            </div>

            <!-- State A: Filter Form -->
            <div id="sidebar-filters" class="sidebar-filters sidebar-state-a">
                <div class="filter-group">
                    <label for="filter-jenjang">Pilih Jenjang</label>
                    <select id="filter-jenjang"></select>
                </div>
                <div class="filter-group">
                    <label for="filter-status">Pilih Status</label>
                    <select id="filter-status"></select>
                </div>
                <div class="filter-group">
                    <label for="filter-pulau">Pilih Pulau</label>
                    <select id="filter-pulau">
                        <option value="">Pilih Pulau</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="filter-provinsi">Pilih Provinsi</label>
                    <select id="filter-provinsi" disabled></select>
                </div>
                <div class="filter-group">
                    <label for="filter-kabupaten">Pilih Kabupaten/Kota</label>
                    <select id="filter-kabupaten" disabled>
                        <option value="">Pilih Kabupaten/Kota</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="filter-kecamatan">Pilih Kecamatan</label>
                    <select id="filter-kecamatan" disabled>
                        <option value="">Pilih Kecamatan</option>
                    </select>
                </div>
                <button id="btn-terapkan" class="btn-apply">Terapkan Filter</button>
                <button id="btn-reset" class="btn-reset">Mulai Ulang</button>
                <a href="/"> <button class="btn-back">Kembali Kehalaman Utama</button></a>
            </div>

            <div id="sidebar-filters-info" class="filter-info sidebar-state-a">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#FF9F44" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 18h6" />
                    <path d="M10 22h4" />
                    <path
                        d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1 .23 2.23 1.5 3.5A4.61 4.61 0 0 1 8.91 14" />
                </svg>
                <span>Filter di atas untuk menampilkan data sekolah berdasarkan kategori yang diinginkan.</span>



            </div>

            <!-- State B: Table slot — menerima #table-component saat detail aktif -->
            <div id="sidebar-table-slot"></div>
        </aside>

        <div id="right-area">
            <header id="main-header">
                <div class="header-left">
                    <h1 class="header-title">DATA SEKOLAH TAHUN 2025/2026</h1>
                    <p class="header-subtitle">Peta Persebaran Sekolah dan Potensi Peserta Didik</p>
                </div>
                <div class="header-right">
                    <div class="header-dropdown">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        <span>Data Per Agustus 2026</span>

                    </div>
                </div>
            </header>

            <div id="main-content">
                <div id="map-section">
                    <div id="stat-cards">
                        <div class="stat-card">
                            <div class="stat-card__inner">
                                <img src="{{ asset('assets/iconsekolah.png') }}" alt="" class="stat-card__icon">
                                <div class="stat-card__value" id="total-sekolah">0</div>
                            </div>
                            <div class="stat-card__label">Total Sekolah Terdaftar</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-card__inner">
                                <img src="{{ asset('assets/iconsiswa.png') }}" alt=""
                                    class="stat-card__icon">
                                <div class="stat-card__value" id="total-murid">0</div>
                            </div>
                            <div class="stat-card__label">Total Peserta Didik Terdaftar</div>
                        </div>
                    </div>

                    <div id="map-wrapper">
                        <div id="map"></div>
                        <div id="map-info-badge" class="map-info-badge">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0D9296"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="16" x2="12" y2="12" />
                                <line x1="12" y1="8" x2="12.01" y2="8" />
                            </svg>
                            <span>Terapkan filter wilayah untuk melihat sebaran titik sekolah</span>
                        </div>
                    </div>

                    <div id="legend-bar">
                        <div class="legend-item" data-jenjang="KB">
                            <span class="legend-marker" style="background:#EF4444;"></span>
                            KB
                        </div>
                        <div class="legend-item" data-jenjang="TK">
                            <span class="legend-marker" style="background:#3B82F6;"></span>
                            TK
                        </div>
                        <div class="legend-item" data-jenjang="SD">
                            <span class="legend-marker" style="background:#22C55E;"></span>
                            SD
                        </div>
                        <div class="legend-item" data-jenjang="SMP">
                            <span class="legend-marker" style="background:#A855F7;"></span>
                            SMP
                        </div>
                        <div class="legend-item" data-jenjang="SMA/SMK">
                            <span class="legend-marker" style="background:#F97316;"></span>
                            SMA/SMK
                        </div>
                    </div>
                </div>

                <div id="right-sidebar">
                    <div id="table-component" class="w-full">
                        <div class="sidebar-table__header">
                            <h3 class="sidebar-table__title">Daftar Sekolah Ditemukan (<span
                                    id="result-count">0</span>)</h3>
                        </div>
                        <div class="sidebar-table__search-container">
                            <svg class="sidebar-table__search-icon" width="16" height="16"
                                viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8" />
                                <line x1="21" y1="21" x2="16.65" y2="16.65" />
                            </svg>
                            <input type="text" id="table-search" class="sidebar-table__search"
                                placeholder="Cari nama sekolah..." />
                        </div>
                        <div class="sidebar-table__scroll">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Nama Sekolah</th>
                                        <th>Status</th>
                                        <th style="text-align:right;">Murid Aktif</th>
                                    </tr>
                                </thead>
                                <tbody id="table-body"></tbody>
                            </table>
                        </div>
                        <div id="pagination" class="pagination flex-wrap justify-center"></div>
                    </div>
                </div>
            </div>

            <!-- Detail Panel -->
            <div id="school-detail-overlay" class="detail-overlay">
                <div class="detail-panel">
                    <div class="detail-panel__header">
                        <h2 id="panel-nama" class="detail-panel__name">Nama Sekolah</h2>
                        <button id="panel-close-btn" class="detail-panel__close">&times;</button>
                    </div>
                    <div class="detail-panel__body">
                        <div class="detail-panel__col detail-panel__col--left">
                            <div class="detail-panel__badges">
                                <span id="panel-status-badge" class="status-badge"></span>
                                <div class="detail-panel__students">
                                    <img src="{{ asset('assets/iconsiswa2.png') }}" alt=""
                                        class="detail-panel__student-icon">
                                    <span id="panel-murid">0</span>
                                </div>
                            </div>
                            <div class="detail-panel__address" id="panel-address">-</div>
                            <button id="btn-gmaps" class="btn-gmaps">
                                <img src="{{ asset('assets/icongooglemaps.png') }}" alt=""
                                    class="btn-gmaps__icon">
                                Buka di Google Maps
                            </button>
                            <div class="detail-panel__contact">
                                <div class="detail-contact__item">
                                    <span class="contact-icon contact-icon--phone"></span>
                                    <span id="panel-telepon">-</span>
                                </div>
                                <div class="detail-contact__item">
                                    <span class="contact-icon contact-icon--email"></span>
                                    <span id="panel-email">-</span>
                                </div>
                            </div>
                            <div id="social-media-section" class="detail-panel__social hidden">
                                <a href="#" id="btn-sosmed-ig" class="social-icon-link hidden" target="_blank"
                                    rel="noopener noreferrer">
                                    <img src="{{ asset('assets/iconig.png') }}" alt="Instagram">
                                </a>
                                <a href="#" id="btn-sosmed-fb" class="social-icon-link hidden" target="_blank"
                                    rel="noopener noreferrer">
                                    <img src="{{ asset('assets/iconfb.png') }}" alt="Facebook">
                                </a>
                                <a href="#" id="btn-sosmed-tiktok" class="social-icon-link hidden"
                                    target="_blank" rel="noopener noreferrer">
                                    <img src="{{ asset('assets/icontiktok.png') }}" alt="TikTok">
                                </a>
                                <a href="#" id="btn-sosmed-web" class="social-icon-link hidden"
                                    target="_blank" rel="noopener noreferrer" title="Kunjungi Website">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                        stroke="#6B7280" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10" />
                                        <line x1="2" y1="12" x2="22" y2="12" />
                                        <path
                                            d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                                    </svg>
                                </a>
                            </div>

                            <div id="data-warning-card" class="data-warning-card hidden">
                                <div class="data-warning-card__header">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="#EF4444" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                                        <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                                    </svg>
                                    <span class="data-warning-card__title">Pemberitahuan Data</span>
                                </div>
                                <div class="data-warning-card__body" id="data-warning-body"></div>
                                <div class="data-warning-card__footer">
                                    Silahkan hubungi admin atau pihak sekolah untuk melengkapi data yang kurang.
                                </div>
                            </div>

                            @auth
                                <button id="btn-koreksi"
                                    class="mt-4 w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded transition-colors">
                                    Usulkan Perbaikan Data
                                </button>
                            @else
                                <a href="{{ route('login') }}"
                                    class="mt-4 w-full block text-center bg-[#0D9296] hover:bg-[#0b7c80] text-white font-bold py-2 px-4 rounded transition-colors">Login
                                    untuk Usulkan Perbaikan</a>
                            @endauth
                        </div>
                        <div class="detail-panel__col detail-panel__col--right">
                            <div class="detail-chart-container">
                                <h4 class="detail-chart__title">Jumlah Murid Berdasarkan Jenis Kelamin</h4>

                                <div class="detail-chart__canvas-wrap">
                                    <canvas id="siswaChart"></canvas>
                                </div>

                                <div class="detail-chart__legend" id="chart-legend">
                                    <!-- Diisi otomatis oleh JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Koreksi Data Modal — positioned outside #app-layout to avoid overflow clipping -->
    <div id="koreksiModal" class="hidden fixed inset-0 z-[10000] bg-black/50 flex items-center justify-center p-4">
        <div class="absolute inset-0" onclick="document.getElementById('koreksiModal').classList.add('hidden')"></div>

        <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg max-h-[90vh] flex flex-col z-10">
            <div class="flex items-center justify-between px-6 py-4 border-b shrink-0">
                <h3 class="text-lg font-semibold text-gray-800">Koreksi Data Sekolah</h3>
                <button onclick="document.getElementById('koreksiModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
            </div>

            <div class="overflow-y-auto overscroll-contain px-6 py-4 flex-1">
                <form id="formKoreksi" action="{{ route('laporan.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="sekolah_npsn" id="modal_sekolah_npsn" value="">

                    <p class="text-sm text-gray-600 mb-3 bg-gray-100 p-2 rounded">Melaporkan sebagai:
                        <strong>{{ auth()->user()?->name ?? 'Tamu' }}</strong>
                    </p>

                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Detail Perbaikan</label>
                        <textarea name="pesan_koreksi" rows="4" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#0d9296] focus:ring-[#0d9296]"
                            placeholder="Jelaskan data apa yang salah dan apa yang seharusnya..."></textarea>
                    </div>
                </form>
            </div>

            <div class="flex justify-end gap-3 px-6 py-4 border-t shrink-0 bg-gray-50 rounded-b-xl">
                <button type="button" onclick="document.getElementById('koreksiModal').classList.add('hidden')"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Batal</button>
                <button type="submit" form="formKoreksi"
                    class="px-4 py-2 text-sm font-medium text-white bg-[#0d9296] border border-transparent rounded-md hover:bg-[#0b7c80]">Kirim
                    Usulan</button>
            </div>
        </div>
    </div>

    <!-- Success toast -->
    @if (session('success'))
        <div id="koreksiToast"
            class="fixed top-6 right-6 z-[10001] bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg font-medium text-sm flex items-center gap-2 transition-opacity duration-300">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 6L9 17l-5-5" />
            </svg>
            {{ session('success') }}
        </div>
        <script>
            setTimeout(() => {
                const t = document.getElementById('koreksiToast');
                if (t) {
                    t.style.opacity = '0';
                    setTimeout(() => t.remove(), 300);
                }
            }, 4000);
        </script>
    @endif
</body>

</html>
