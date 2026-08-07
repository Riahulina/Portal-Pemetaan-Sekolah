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
