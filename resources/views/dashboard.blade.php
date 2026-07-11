<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SatuPeta — Dashboard</title>

    <title>Satu Peta — Peta Pendidikan Indonesia</title>

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
                    <label for="filter-provinsi">Pilih Provinsi</label>
                    <select id="filter-provinsi"></select>
                </div>
                <div class="filter-group">
                    <label for="filter-kabupaten">Pilih Kabupaten/Kota</label>
                    <select id="filter-kabupaten">
                        <option value="">Pilih Kabupaten/Kota</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="filter-kecamatan">Pilih Kecamatan</label>
                    <select id="filter-kecamatan">
                        <option value="">Pilih Kecamatan</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="filter-kelurahan">Pilih Kelurahan</label>
                    <select id="filter-kelurahan">
                        <option value="">Pilih Kelurahan</option>
                    </select>
                </div>
                <button id="btn-terapkan" class="btn-apply">Terapkan Filter</button>
                <button id="btn-reset" class="btn-reset">Reset</button>
                <a href="/"> <button class="btn-back">Back Home</button></a>
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
                        <span>Data Per July 2026</span>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9" />
                        </svg>
                    </div>
                </div>
            </header>

            <div id="main-content">
                <div id="map-section">
                    <div id="stat-cards">
                        <div class="stat-card">
                            <div class="stat-card__inner">
                                <img src="{{ asset('assets/iconsekolah.png') }}" alt=""
                                    class="stat-card__icon">
                                <div class="stat-card__value" id="total-sekolah">0</div>
                            </div>
                            <div class="stat-card__label">Total Sekolah Terdaftar Dapodik</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-card__inner">
                                <img src="{{ asset('assets/iconsiswa.png') }}" alt=""
                                    class="stat-card__icon">
                                <div class="stat-card__value" id="total-murid">0</div>
                            </div>
                            <div class="stat-card__label">Total Peserta Didik Terdaftar Dapodik</div>
                        </div>
                    </div>

                    <div id="map-wrapper">
                        <div id="map"></div>
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
                            <div class="detail-panel__social">
                                <a href="#" id="panel-ig" class="social-icon-link" target="_blank"
                                    rel="noopener">
                                    <img src="{{ asset('assets/iconig.png') }}" alt="Instagram">
                                </a>
                                <a href="#" id="panel-fb" class="social-icon-link" target="_blank"
                                    rel="noopener">
                                    <img src="{{ asset('assets/iconfb.png') }}" alt="Facebook">
                                </a>
                                <a href="#" id="panel-tiktok" class="social-icon-link" target="_blank"
                                    rel="noopener">
                                    <img src="{{ asset('assets/icontiktok.png') }}" alt="TikTok">
                                </a>
                            </div>
                        </div>
                        <div class="detail-panel__col detail-panel__col--right">
                            <div class="detail-chart-container">
                                <h4 class="detail-chart__title">Jumlah Siswa per Jenjang</h4>
                                <div class="detail-chart__canvas-wrap">
                                    <canvas id="siswaChart"></canvas>
                                </div>
                                <div class="detail-chart__legend" id="chart-legend">
                                    <div class="legend-row"><span class="legend-dot"
                                            style="background:#22C55E;"></span> Kelas 7 SMP - <strong>350</strong>
                                    </div>
                                    <div class="legend-row"><span class="legend-dot"
                                            style="background:#F97316;"></span> Kelas 8 SMP - <strong>350</strong>
                                    </div>
                                    <div class="legend-row"><span class="legend-dot"
                                            style="background:#10a5b0;"></span> Kelas 9 SMP - <strong>350</strong>
                                    </div>
                                </div>
                                <div class="detail-chart__form">
                                    <div class="form-group">
                                        <label for="panel-status-hubungan">Status Hubungan</label>
                                        <select id="panel-status-hubungan">
                                            <option value="">Pilih Status</option>
                                            <option value="Belum Dihubungi">Belum Dihubungi</option>
                                            <option value="Sudah Dihubungi">Sudah Dihubungi</option>
                                            <option value="Dalam Proses">Dalam Proses</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="panel-catatan">Catatan</label>
                                        <textarea id="panel-catatan" placeholder="Tulis catatan tentang sekolah ini..."></textarea>
                                    </div>
                                    <button class="btn-save-catatan">Simpan Catatan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
