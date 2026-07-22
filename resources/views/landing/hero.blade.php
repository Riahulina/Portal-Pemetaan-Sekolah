<section class="hero">
    <div class="container hero-grid">
        <div class="hero-text-col">
            <span class="eyebrow">Peta Pendidikan Indonesia</span>
            <h1>Memetakan Pendidikan,<br>Membangun <span class="accent">Masa Depan Indonesia</span></h1>
            <p class="lead">SatuPeta adalah platform inovatif untuk visualisasi data dan pemetaan sekolah
                secara terpadu di seluruh Indonesia. Transparansi data adalah kunci untuk pendidikan yang lebih
                merata dan inklusif.</p>

            <a href="{{ url('/dashboard') }}">
                <button class="btn-primary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.2">
                        <path d="M9 20l-5.5-2V4L9 6m0 14l6-2m-6 2V6m6 12l5.5 2V6L15 4m0 14V4m0 0L9 6" />
                    </svg>
                    Mulai Peta Data
                </button>
            </a>

            <div class="hero-stats-row">
                <div class="hero-stat">
                    <span class="dot">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.2">
                            <path d="M9 12l2 2 4-4" />
                            <circle cx="12" cy="12" r="9" />
                        </svg>
                    </span>
                    Data Akurat & Terpercaya
                </div>
                <div class="hero-stat">
                    <span class="dot">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.2">
                            <path d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                    </span>
                    Mudah Diakses & Interaktif
                </div>
                <div class="hero-stat">
                    <span class="dot">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.2">
                            <path d="M12 3l7 4v5c0 5-3 8-7 9-4-1-7-4-7-9V7l7-4Z" />
                        </svg>
                    </span>
                    Untuk Semua Jenjang
                </div>
            </div>
        </div>

        <!-- MOCKUP LAPTOP WRAPPER -->
        <div class="laptop-mockup-wrapper">
            <div class="laptop-frame">
                <!-- Top Screen Header Bar -->
                <div class="browser-bar">
                    <div class="browser-dots">
                        <span class="dot red"></span>
                        <span class="dot yellow"></span>
                        <span class="dot green"></span>
                    </div>
                    <div class="browser-address-bar">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        <span>satupeta/Dashboard</span>
                    </div>
                </div>

                <!-- Screen Viewport -->
                <div class="screen-viewport">
                    <div class="mockup-overlay">

                    </div>

                    <!-- Scaled Dashboard Container -->
                    <div class="scaled-content">
                        <div class="dash-card">
                            <div class="dash-top">
                                <div class="dash-top-left">
                                    <div class="brand-badge">
                                        <img src="{{ asset('assets/logo.png') }}" class="brand-logo-img">
                                    </div>
                                    <div>
                                        <div class="dash-title">Data Sekolah Tahun 2025/2026</div>
                                        <div class="dash-sub">Peta Persebaran Sekolah &amp; Potensi Peserta Didik</div>
                                    </div>
                                </div>
                            </div>

                            <div class="dash-body">
                                <div class="filter-panel">
                                    <h4>Filter Pencarian</h4>
                                    <div class="field"><label>Pilih Jenjang</label>
                                        <div class="select"><span>Semua</span><svg viewBox="0 0 24 24"
                                                fill="currentColor">
                                                <path d="M7 10l5 5 5-5z" />
                                            </svg></div>
                                    </div>
                                    <div class="field"><label>Pilih Status</label>
                                        <div class="select"><span>Semua</span><svg viewBox="0 0 24 24"
                                                fill="currentColor">
                                                <path d="M7 10l5 5 5-5z" />
                                            </svg></div>
                                    </div>
                                    <div class="field"><label>Pilih Provinsi</label>
                                        <div class="select"><span>Semua</span><svg viewBox="0 0 24 24"
                                                fill="currentColor">
                                                <path d="M7 10l5 5 5-5z" />
                                            </svg></div>
                                    </div>
                                    <div class="field"><label>Pilih Kab/Kota</label>
                                        <div class="select"><span>Semua</span><svg viewBox="0 0 24 24"
                                                fill="currentColor">
                                                <path d="M7 10l5 5 5-5z" />
                                            </svg></div>
                                    </div>
                                    <button class="btn-apply">Terapkan Filter</button>
                                    <button class="btn-reset">Mulai Ulang</button>
                                    <div class="filter-hint">Gunakan filter untuk menampilkan data sekolah sesuai
                                        kategori yang diinginkan.</div>
                                </div>

                                <div class="map-panel">
                                    <div class="map-panel-head">
                                        <h4>Sebaran Sekolah</h4>
                                        <span class="period">Data Per Juli 2026</span>
                                    </div>

                                    <!-- KPI -->
                                    <div class="kpi-row">
                                        <div class="kpi teal">
                                            <img src="{{ asset('assets/iconsekolah.png') }}" alt=""
                                                class="stat-card__icon">
                                            <div>
                                                <div class="kpi-num">56.666</div>
                                                <div class="kpi-label">Sekolah Terdaftar</div>
                                            </div>
                                        </div>

                                        <div class="kpi orange">
                                            <img src="{{ asset('assets/iconsiswa.png') }}" alt=""
                                                class="stat-card__icon">
                                            <div>
                                                <div class="kpi-num">8.326.278</div>
                                                <div class="kpi-label">Peserta Didik</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- MAP -->
                                    <div id="map"></div>

                                    <!-- Legend -->
                                    <div class="map-legend">
                                        <span>
                                            <div class="legend-pin" style="background:#ef4444"></div> KB
                                        </span>
                                        <span>
                                            <div class="legend-pin" style="background:#3b82f6"></div> TK
                                        </span>
                                        <span>
                                            <div class="legend-pin" style="background:#22c55e"></div> SD
                                        </span>
                                        <span>
                                            <div class="legend-pin" style="background:#a855f7"></div> SMP
                                        </span>
                                        <span>
                                            <div class="legend-pin" style="background:#f97316"></div> SMA/SMK
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Laptop Base Bottom -->
            <div class="laptop-base">
                <div class="laptop-notch"></div>
            </div>
        </div>
    </div>
</section>

<!-- STYLES -->
<style>
    /* Styling Frame Laptop Presisi & Responsive Scaling */
    .laptop-mockup-wrapper {
        width: 100%;
        max-width: 580px;
        margin: 0 auto;
        position: relative;
    }

    .laptop-frame {
        background: #0f172a;
        border-radius: 12px 12px 0 0;
        padding: 6px 6px 0 6px;
        border: 2px solid #334155;
        border-bottom: none;
        box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.25);
    }

    .browser-bar {
        display: flex;
        align-items: center;
        background: #1e293b;
        padding: 5px 10px;
        border-radius: 8px 8px 0 0;
        gap: 10px;
    }

    .browser-dots {
        display: flex;
        gap: 4px;
    }

    .browser-dots .dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
    }

    .dot.red {
        background-color: #ef4444;
    }

    .dot.yellow {
        background-color: #f59e0b;
    }

    .dot.green {
        background-color: #10b981;
    }

    .browser-address-bar {
        background: #334155;
        color: #94a3b8;
        font-size: 10px;
        padding: 2px 10px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 5px;
        width: 100%;
        max-width: 180px;
    }

    .screen-viewport {
        position: relative;
        width: 100%;
        height: 350px;
        overflow: hidden;
        background: #f8fafc;
        border-radius: 0 0 6px 6px;
    }

    .mockup-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 50;
        cursor: default;
        pointer-events: all;
    }

    .preview-badge {
        position: absolute;
        top: 8px;
        right: 8px;
        background: rgba(15, 23, 42, 0.8);
        color: #ffffff;
        font-size: 10px;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 12px;
        backdrop-filter: blur(4px);
    }

    .scaled-content {
        width: 188%;
        transform: scale(0.53);
        transform-origin: top left;
        pointer-events: none;
    }

    .laptop-base {
        height: 10px;
        background: linear-gradient(to bottom, #cbd5e1, #64748b);
        border-radius: 0 0 10px 10px;
        position: relative;
        margin: 0 -12px;
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
    }

    .laptop-notch {
        width: 50px;
        height: 3px;
        background: #475569;
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        border-radius: 0 0 4px 4px;
    }

    /* Pin Marker CSS */
    .custom-pin {
        width: 22px;
        height: 22px;
        border-radius: 50% 50% 50% 0;
        position: absolute;
        transform: rotate(-45deg);
        left: 50%;
        top: 50%;
        margin-left: -11px;
        margin-top: -22px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: -1px 2px 4px rgba(0, 0, 0, 0.25);
    }

    .custom-pin::after {
        content: '';
        width: 7px;
        height: 7px;
        background: #ffffff;
        border-radius: 50%;
        transform: rotate(45deg);
    }
</style>

<!-- SCRIPTS & LEAFLET MAP -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    // 1. Inisialisasi peta berfokus di Indonesia Bagian Barat/Tengah
    const map = L.map('map').setView([-2.0, 108.0], 5);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    // 2. DATA DUMMY LENGKAP (Sumatra & Jakarta)
    const dataSekolah = [
        // --- JAKARTA ---
        {
            nama: "KB Pelita Hati Jakarta",
            jenjang: "KB",
            lat: -6.2088,
            lng: 106.8456,
            warna: "#ef4444"
        },
        {
            nama: "TK Pembina Jakarta",
            jenjang: "TK",
            lat: -6.2200,
            lng: 106.8200,
            warna: "#3b82f6"
        },
        {
            nama: "SDN 01 Menteng Jakarta",
            jenjang: "SD",
            lat: -6.1950,
            lng: 106.8320,
            warna: "#22c55e"
        },
        {
            nama: "SMPN 1 Jakarta",
            jenjang: "SMP",
            lat: -6.1751,
            lng: 106.8272,
            warna: "#a855f7"
        },
        {
            nama: "SMAN 68 Jakarta",
            jenjang: "SMA/SMK",
            lat: -6.1914,
            lng: 106.8480,
            warna: "#f97316"
        },

        // --- SUMATRA UTARA (MEDAN) ---
        {
            nama: "KB Ceria Medan",
            jenjang: "KB",
            lat: 3.5952,
            lng: 98.6722,
            warna: "#ef4444"
        },
        {
            nama: "TK Sutomo Medan",
            jenjang: "TK",
            lat: 3.5890,
            lng: 98.6870,
            warna: "#3b82f6"
        },
        {
            nama: "SDN 060843 Medan",
            jenjang: "SD",
            lat: 3.5700,
            lng: 98.6500,
            warna: "#22c55e"
        },
        {
            nama: "SMPN 1 Medan",
            jenjang: "SMP",
            lat: 3.5833,
            lng: 98.6750,
            warna: "#a855f7"
        },
        {
            nama: "SMAN 1 Medan",
            jenjang: "SMA/SMK",
            lat: 3.5780,
            lng: 98.6670,
            warna: "#f97316"
        },

        // --- SUMATRA BARAT (PADANG) ---
        {
            nama: "TK Adabiah Padang",
            jenjang: "TK",
            lat: -0.9471,
            lng: 100.3543,
            warna: "#3b82f6"
        },
        {
            nama: "SDN 01 Sawahan Padang",
            jenjang: "SD",
            lat: -0.9410,
            lng: 100.3680,
            warna: "#22c55e"
        },
        {
            nama: "SMPN 8 Padang",
            jenjang: "SMP",
            lat: -0.9250,
            lng: 100.3620,
            warna: "#a855f7"
        },
        {
            nama: "SMAN 1 Padang",
            jenjang: "SMA/SMK",
            lat: -0.9120,
            lng: 100.3520,
            warna: "#f97316"
        },

        // --- SUMATRA SELATAN (PALEMBANG) ---
        {
            nama: "KB Islam Al-Azhar Palembang",
            jenjang: "KB",
            lat: -2.9761,
            lng: 104.7621,
            warna: "#ef4444"
        },
        {
            nama: "SDN 1 Palembang",
            jenjang: "SD",
            lat: -2.9850,
            lng: 104.7500,
            warna: "#22c55e"
        },
        {
            nama: "SMPN 1 Palembang",
            jenjang: "SMP",
            lat: -2.9680,
            lng: 104.7420,
            warna: "#a855f7"
        },
        {
            nama: "SMAN 3 Palembang",
            jenjang: "SMA/SMK",
            lat: -2.9550,
            lng: 104.7580,
            warna: "#f97316"
        },

        // --- RIAU (PEKANBARU) ---
        {
            nama: "SDN 01 Pekanbaru",
            jenjang: "SD",
            lat: 0.5333,
            lng: 101.4500,
            warna: "#22c55e"
        },
        {
            nama: "SMAN 1 Pekanbaru",
            jenjang: "SMA/SMK",
            lat: 0.5167,
            lng: 101.4433,
            warna: "#f97316"
        }
    ];

    // 3. GENERATE PIN MAPS
    dataSekolah.forEach(sekolah => {
        const pinIcon = L.divIcon({
            className: 'clear-backend-icon',
            html: `<div class="custom-pin" style="background: ${sekolah.warna}"></div>`,
            iconSize: [22, 32],
            iconAnchor: [11, 32],
            popupAnchor: [0, -28]
        });

        L.marker([sekolah.lat, sekolah.lng], {
                icon: pinIcon
            })
            .addTo(map)
            .bindPopup(`
                <div style="font-family: sans-serif; padding: 2px;">
                    <strong style="font-size: 13px; color: #111827;">${sekolah.nama}</strong><br>
                    <span style="display:inline-block; margin-top:4px; padding: 2px 6px; font-size: 10px; font-weight: bold; border-radius: 4px; background: ${sekolah.warna}; color: white;">
                        Jenjang: ${sekolah.jenjang}
                    </span>
                </div>
            `);
    });

    // 4. Force map render agar pas dalam frame viewport laptop
    window.dispatchEvent(new Event('resize'));
    setTimeout(() => {
        map.invalidateSize();
    }, 400);
</script>
