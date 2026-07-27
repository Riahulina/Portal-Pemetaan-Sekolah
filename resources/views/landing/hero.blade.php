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

        <!-- HERO IMAGE (Ganti mockup laptop dengan gambar) -->
        <div class="hero-image-col">
            <img src="{{ asset('assets/ver 5.png') }}" alt="SatuPeta Responsive Showcase" class="hero-mockup-img">
        </div>
    </div>
</section>

<!-- STYLES -->
<style>
    .hero-image-col {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
    }

    .hero-mockup-img {
        width: 100%;
        max-width: 650px;
        /* Ukuran bisa disesuaikan */
        height: auto;
        object-fit: contain;
        filter: drop-shadow(0 15px 25px rgba(0, 0, 0, 0.1));
        border-radius: 20px;
    }

    /* --- BASE & CONTAINER HERO --- */
    .hero {
        width: 100%;
        padding: 2rem 1rem;
        overflow-x: hidden;
        /* Mencegah elemen berlebih melebarkan screen */
    }

    .hero-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        /* 2 Kolom di Desktop */
        gap: 2.5rem;
        align-items: center;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* --- KOLOM TEKS HERO --- */
    .hero-text-col {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .hero-text-col h1 {
        font-size: clamp(1.8rem, 4vw, 3rem);
        /* Ukuran font fleksibel */
        line-height: 1.25;
        margin: 0.75rem 0;
    }

    .hero-text-col .lead {
        font-size: clamp(0.9rem, 1.5vw, 1.1rem);
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    /* --- STATS ROW (3 Card Kecil) --- */
    .hero-stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.75rem;
        width: 100%;
        margin-top: 1.5rem;
    }

    .hero-stat {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        padding: 0.5rem 0.75rem;
        background: #f8fafc;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    /* --- KOLOM GAMBAR MOCKUP --- */
    .hero-image-col {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
    }

    .hero-mockup-img {
        width: 100%;
        max-width: 600px;
        height: auto;
        object-fit: contain;
        border-radius: 12px;
    }

    /* ==========================================
   RESPONSIVE (MOBILE & TABLET BREAKPOINTS)
   ========================================== */

    /* Tablet & HP (< 992px) */
    @media (max-width: 991px) {
        .hero-grid {
            grid-template-columns: 1fr;
            /* Ubah ke 1 Kolom ke bawah */
            gap: 2rem;
            text-align: center;
        }

        .hero-text-col {
            align-items: center;
            /* Teks jadi rata tengah di HP */
        }

        .hero-stats-row {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    /* HP Kecil (< 600px) */
    @media (max-width: 600px) {
        .hero {
            padding: 1.5rem 0.75rem;
        }

        .hero-stats-row {
            grid-template-columns: 1fr;
            /* Tumpuk badge statistik jadi 1 baris per item */
            gap: 0.5rem;
        }

        .hero-stat {
            justify-content: center;
            font-size: 0.85rem;
            padding: 0.6rem;
        }

        .btn-primary {
            width: 100%;
            /* Tombol memenuhi lebar layar di HP */
            justify-content: center;
        }
    }
</style>
