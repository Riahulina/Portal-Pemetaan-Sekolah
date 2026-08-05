<section class="why" id="tentang">
    <div class="container">
        <h2>Mengapa <span class="accent">SatuPeta</span>?</h2>
        <p>Kami menyediakan solusi pemetaan pendidikan yang komprehensif untuk mendukung pengambilan keputusan
            berbasis data.</p>

        <div class="why-cards">
            <div class="why-card">
                <div class="why-card-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="9" />
                        <circle cx="12" cy="12" r="4" />
                        <path d="M12 3v2M12 19v2M3 12h2M19 12h2" />
                    </svg></div>
                <h3>Data Terintegrasi</h3>
                <p>Menggabungkan data sekolah dari berbagai sumber dalam satu platform terpusat.</p>
            </div>
            <div class="why-card">
                <div class="why-card-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path d="M12 22s7-7.58 7-13a7 7 0 1 0-14 0c0 5.42 7 13 7 13Z" />
                        <circle cx="12" cy="9" r="2.4" />
                    </svg></div>
                <h3>Peta Interaktif</h3>
                <p>Visualisasi persebaran sekolah yang mudah dipahami dan dieksplorasi.</p>
            </div>
            <div class="why-card">
                <div class="why-card-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path d="M4 20V10M12 20V4M20 20v-7" />
                    </svg></div>
                <h3>Analisis Mendalam</h3>
                <p>Dapatkan insight berharga untuk perencanaan dan pengambilan keputusan strategis.</p>
            </div>
        </div>
    </div>

    <!-- SECTION TIM PENGEMBANG -->
    <section id="tentang" class="dev-section">
        <div class="dev-container">

            <!-- Header Section -->
            <div class="dev-header">
                <span class="dev-badge">Tim Pengembang</span>
                <h2 class="dev-title">Sosok Di Balik <span>SatuPeta</span></h2>
                <p class="dev-subtitle">
                    Tim pengembang di balik visualisasi dan pemetaan data pendidikan Indonesia.
                </p>
            </div>

            <!-- Grid 3 Developer -->
            <div class="dev-grid">

                <!-- Developer 1 -->
                <div class="dev-card">
                    <div class="dev-avatar-wrapper">
                        <img src="https://ui-avatars.com/api/?name=Riah+Ulina+Hutasoit&background=0d7a75&color=fff&size=200"
                            alt="Riah Ulina Hutasoit" class="dev-avatar">
                    </div>
                    <h3 class="dev-name">Riah Ulina Hutasoit</h3>
                    <p class="dev-instansi">Politeknik Negeri Medan</p>
                </div>

                <!-- Developer 2 -->
                <div class="dev-card">
                    <div class="dev-avatar-wrapper">
                        <img src="https://ui-avatars.com/api/?name=Nama+Developer+2&background=0d7a75&color=fff&size=200"
                            alt="Nama Developer 2" class="dev-avatar">
                    </div>
                    <h3 class="dev-name">Nama Developer 2</h3>
                    <p class="dev-instansi">Politeknik Negeri Medan</p>
                </div>

                <!-- Developer 3 -->
                <div class="dev-card">
                    <div class="dev-avatar-wrapper">
                        <img src="https://ui-avatars.com/api/?name=Nama+Developer+3&background=0d7a75&color=fff&size=200"
                            alt="Nama Developer 3" class="dev-avatar">
                    </div>
                    <h3 class="dev-name">Nama Developer 3</h3>
                    <p class="dev-instansi">Politeknik Negeri Medan</p>
                </div>

            </div>
        </div>
    </section>

    <!-- CSS PENGEMBANG (FOTO KOTAK & TIDAK MEPET) -->
    <style>
        .dev-section {
            padding: 70px 20px 90px 20px !important;
            /* Spasi atas & bawah agar lega dari banner bawah */
            background-color: #ffffff;
            font-family: 'Inter', sans-serif;
        }

        .dev-container {
            max-width: 1040px;
            margin: 0 auto;
        }

        /* Header Section */
        .dev-header {
            text-align: center;
            max-width: 600px;
            margin: 0 auto 40px auto;
        }

        .dev-badge {
            background-color: rgba(13, 122, 117, 0.1);
            color: #0d7a75;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
            margin-bottom: 10px;
        }

        .dev-title {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .dev-title span {
            color: #0d7a75;
        }

        .dev-subtitle {
            font-size: 14px;
            color: #64748b;
            line-height: 1.5;
        }

        /* Grid Layout 3 Kolom */
        .dev-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        /* Card Styling */
        .dev-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .dev-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(13, 122, 117, 0.12);
            border-color: #0d7a75;
        }

        /* Foto Kotak Rounded */
        .dev-avatar-wrapper {
            width: 100%;
            aspect-ratio: 1 / 1;
            /* Membuat bingkai kotak sempurna */
            max-width: 200px;
            margin: 0 auto 16px auto;
            border-radius: 12px;
            /* Melengkung halus di sudut kotak */
            overflow: hidden;
            border: 2px solid #e2e8f0;
        }

        .dev-avatar {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }

        /* Informasi Nama & Instansi */
        .dev-name {
            font-size: 17px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 6px 0;
        }

        .dev-instansi {
            font-size: 13px;
            font-weight: 500;
            color: #0d7a75;
            margin: 0;
        }

        /* Responsive HP */
        @media (max-width: 768px) {
            .dev-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
    </style>

    <!-- STATS BAND -->
    <div class="stats-band">
        <div class="container">
            <h3>SatuPeta dalam Angka</h3>
            <div class="stats-row">
                <div class="stat-block">
                    <div class="stat-icon"><svg width="30" height="30" viewBox="0 0 24 24" fill="none"
                            stroke="#fff" stroke-width="1.8">
                            <path d="M4 21V9l8-6 8 6v12" />
                            <path d="M9 21v-6h6v6" />
                        </svg></div>
                    <div>
                        <div class="stat-num">56.666+</div>
                        <div class="stat-label">Sekolah Terdaftar</div>
                    </div>
                </div>
                <div class="stat-block">
                    <div class="stat-icon"><svg width="30" height="30" viewBox="0 0 24 24" fill="none"
                            stroke="#fff" stroke-width="1.8">
                            <circle cx="9" cy="8" r="3.2" />
                            <circle cx="16.5" cy="9.5" r="2.5" />
                            <path d="M3 20c0-3 3-5 6-5s6 2 6 5" />
                            <path d="M14 15.2c2.4.3 4.5 2 4.5 4.8" />
                        </svg></div>
                    <div>
                        <div class="stat-num">8.326.278+</div>
                        <div class="stat-label">Peserta Didik</div>
                    </div>
                </div>
                <div class="stat-block">
                    <div class="stat-icon"><svg width="30" height="30" viewBox="0 0 24 24" fill="none"
                            stroke="#fff" stroke-width="1.8">
                            <path d="M12 22s7-7.58 7-13a7 7 0 1 0-14 0c0 5.42 7 13 7 13Z" />
                            <circle cx="12" cy="9" r="2.4" />
                        </svg></div>
                    <div>
                        <div class="stat-num">38</div>
                        <div class="stat-label">Provinsi Terpetakan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
