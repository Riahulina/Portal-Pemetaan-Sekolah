<section class="hero">
    <div class="container hero-grid">
        <div>
            <span class="eyebrow">Peta Pendidikan Indonesia</span>
            <h1>Memetakan Pendidikan,<br>Membangun <span class="accent">Masa Depan Indonesia</span></h1>
            <p class="lead">SatuPeta adalah platform inovatif untuk visualisasi data dan pemetaan sekolah
                secara terpadu di seluruh Indonesia. Transparansi data adalah kunci untuk pendidikan yang lebih
                merata dan inklusif.</p>
            <a href="/dashboard"><button class="btn-primary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.2">
                        <path d="M9 20l-5.5-2V4L9 6m0 14l6-2m-6 2V6m6 12l5.5 2V6L15 4m0 14V4m0 0L9 6" />
                    </svg>
                    Mulai Peta Data
                </button></a>

            <div class="hero-stats-row">
                <div class="hero-stat">
                    <span class="dot"><svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.2">
                            <path d="M9 12l2 2 4-4" />
                            <circle cx="12" cy="12" r="9" />
                        </svg></span>
                    Data Akurat & Terpercaya
                </div>
                <div class="hero-stat">
                    <span class="dot"><svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.2">
                            <path d="M5 12h14M13 6l6 6-6 6" />
                        </svg></span>
                    Mudah Diakses & Interaktif
                </div>
                <div class="hero-stat">
                    <span class="dot"><svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.2">
                            <path d="M12 3l7 4v5c0 5-3 8-7 9-4-1-7-4-7-9V7l7-4Z" />
                        </svg></span>
                    Untuk Semua Jenjang
                </div>
            </div>
        </div>

        <!-- Dashboard mock card -->
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
                        <div class="select"><span>Semua</span><svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M7 10l5 5 5-5z" />
                            </svg></div>
                    </div>
                    <div class="field"><label>Pilih Status</label>
                        <div class="select"><span>Semua</span><svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M7 10l5 5 5-5z" />
                            </svg></div>
                    </div>
                    <div class="field"><label>Pilih Provinsi</label>
                        <div class="select"><span>Semua</span><svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M7 10l5 5 5-5z" />
                            </svg></div>
                    </div>
                    <div class="field"><label>Pilih Kab/Kota</label>
                        <div class="select"><span>Semua</span><svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M7 10l5 5 5-5z" />
                            </svg></div>
                    </div>
                    <button class="btn-apply">Terapkan Filter</button>
                    <button class="btn-reset">Reset</button>
                    <div class="filter-hint">Gunakan filter untuk menampilkan data sekolah sesuai kategori yang
                        diinginkan.</div>
                </div>

                <div class="map-panel">
                    <div class="map-panel-head">
                        <h4>Sebaran Sekolah</h4>
                        <span class="period">Data Per Juli 2026</span>
                    </div>

                    <!-- KPI -->
                    <div class="kpi-row">
                        <div class="kpi teal">
                            <img src="{{ asset('assets/iconsekolah.png') }}" alt="" class="stat-card__icon">
                            <div>
                                <div class="kpi-num">553.449</div>
                                <div class="kpi-label">Sekolah Terdaftar</div>
                            </div>
                        </div>

                        <div class="kpi orange">
                            <img src="{{ asset('assets/iconsiswa.png') }}" alt="" class="stat-card__icon">
                            <div>
                                <div class="kpi-num">52.789.420</div>
                                <div class="kpi-label">Peserta Didik</div>
                            </div>
                        </div>
                    </div>

                    <!-- MAP -->
                    <div id="map"></div>

                    <!-- Legend -->
                    <div class="map-legend">
                        <span>
                            <div class="legend-pin" style="background:#ef4444"></div>
                            KB
                        </span>
                        <span>
                            <div class="legend-pin" style="background:#3b82f6"></div>
                            TK
                        </span>
                        <span>
                            <div class="legend-pin" style="background:#22c55e"></div>
                            SD
                        </span>
                        <span>
                            <div class="legend-pin" style="background:#a855f7"></div>
                            SMP
                        </span>
                        <span>
                            <div class="legend-pin" style="background:#f97316"></div>
                            SMA/SMK
                        </span>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tambahan Style agar Icon Pin berbentuk Drop Google Maps dengan Titik Putih di Tengah -->
<style>
    .custom-pin {
        width: 24px;
        height: 24px;
        border-radius: 50% 50% 50% 0;
        position: absolute;
        transform: rotate(-45deg);
        left: 50%;
        top: 50%;
        margin-left: -12px;
        margin-top: -24px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: -1px 2px 4px rgba(0, 0, 0, 0.25);
    }

    .custom-pin::after {
        content: '';
        width: 8px;
        height: 8px;
        background: #ffffff;
        border-radius: 50%;
        transform: rotate(45deg);
    }
</style>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    // 1. Inisialisasi peta dasar
    const map = L.map('map').setView([-2.5489, 118.0149], 5);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    // 2. DATA SEKOLAH (Menggunakan warna yang serasi dengan Legenda)
    const dataSekolah = [{
            nama: "KB Pelita Hati",
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
            nama: "SDN 01 Menteng",
            jenjang: "SD",
            lat: -6.1950,
            lng: 106.8320,
            warna: "#22c55e"
        },
        {
            nama: "SMPN 1 Surabaya",
            jenjang: "SMP",
            lat: -7.2575,
            lng: 112.7521,
            warna: "#a855f7"
        },
        {
            nama: "SMAN 3 Bandung",
            jenjang: "SMA/SMK",
            lat: -6.9175,
            lng: 107.6191,
            warna: "#f97316"
        }
    ];

    // 3. LOOPING UNTUK MEMBUAT PIN MAPS CUSTOM
    dataSekolah.forEach(sekolah => {
        const pinIcon = L.divIcon({
            className: 'clear-backend-icon',
            html: `<div class="custom-pin" style="background: ${sekolah.warna}"></div>`,
            iconSize: [24, 34],
            iconAnchor: [12, 34],
            popupAnchor: [0, -30]
        });

        L.marker([sekolah.lat, sekolah.lng], {
                icon: pinIcon
            })
            .addTo(map)
            .bindPopup(`
                <div style="font-family: sans-serif; padding: 2px;">
                    <strong style="font-size: 14px; color: #111827;">${sekolah.nama}</strong><br>
                    <span style="display:inline-block; margin-top:5px; padding: 2px 8px; font-size: 11px; font-weight: bold; border-radius: 4px; background: ${sekolah.warna}; color: white;">
                        Jenjang: ${sekolah.jenjang}
                    </span>
                </div>
            `);
    });

    // 4. Perbaikan layouting otomatis
    window.dispatchEvent(new Event('resize'));
    setTimeout(() => {
        map.invalidateSize();
    }, 300);
</script>
