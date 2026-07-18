<header class="nav">
    <div class="nav-inner">

        <!-- Brand -->
        <a href="{{ auth()->check() ? (auth()->user()->is_admin ? url('/admin/dashboard') : route('dashboard')) : url('/') }}" class="navbar-brand">
            <img src="{{ asset('assets/logo.png') }}" class="brand-logo-img">
            <div class="brand-text">
                <div class="brand-text--top">
                    <span class="brand-kids">Satu</span>
                    <span class="brand-nesia">Peta</span>
                </div>
                <div class="brand-text--bottom">
                    <span class="brand-edu">Peta Pendidikan Indonesia</span>
                </div>
            </div>
        </a>

        <!-- Menu -->
        <nav class="nav-links">
            <a href="/">Home</a>
            <a href="{{ request()->is('/') ? '#tentang' : url('/#tentang') }}">Tentang Kami</a>
            <a href="{{ request()->is('/') ? '#peta' : url('/#peta') }}">Peta Data</a>
            <a href="#kontak">Kontak</a>
        </nav>

        <!-- Bagian Tombol Kanan (Dinamis) -->
        <div class="nav-actions">
            @auth
                <div class="user-profile-dropdown-wrapper">

                    <button type="button" class="user-profile-nav trigger-dropdown" onclick="toggleNavbarDropdown()">

                        <div class="avatar-initial-nav">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>

                        <span class="username-nav">
                            {{ Auth::user()->name }}
                        </span>

                        <svg class="dropdown-chevron" width="12" height="12" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="3">
                            <path d="m6 9 6 6 6-6" />
                        </svg>

                    </button>

                    <div id="navProfileDropdown" class="nav-dropdown-menu">

                        <div class="dropdown-user-info">
                            <span class="dropdown-email">
                                {{ Auth::user()->email }}
                            </span>
                        </div>

                        <hr class="dropdown-divider">

                        <a href="{{ auth()->user()->is_admin ? url('/admin/dashboard') : url('/user/dashboard') }}" class="dropdown-link">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>

                            Profil & Dashboard
                        </a>

                        <hr class="dropdown-divider">

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button type="submit" class="dropdown-link dropdown-logout">
                                <svg width="16" height="16" fill="none" stroke="#ef4444" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>

                                Keluar / Logout
                            </button>
                        </form>

                    </div>

                </div>
            @else
                <a href="{{ url('/dashboard') }}" class="btn-nav">Mulai Pemetaan</a>
                <a href="/login" class="btn-nav2">Daftarkan Sekolah</a>
            @endauth
        </div>

    </div>
</header>
