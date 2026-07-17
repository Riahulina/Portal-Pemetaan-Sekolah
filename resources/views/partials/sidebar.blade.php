<!-- resources/views/partials/sidebar.blade.php -->
<aside class="sidebar">
    <div class="sidebar-menu">

        <!-- 1. Dashboard -->
        <a href="{{ route('dashboard.user') }}" class="sidebar-item {{ Request::is('user/dashboard') ? 'active' : '' }}">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path
                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
            Dashboard
        </a>

        <!-- 2. Daftarkan Sekolah (Formulir) -->
        <a href="{{ route('Form.user') }}" class="sidebar-item {{ Request::is('user/Form') ? 'active' : '' }}">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Daftarkan Sekolah
        </a>

        <!-- 3. Data Sekolah Saya (Tabel Pengajuan) -->
        <a href="{{ route('sekolah.index') }}" class="sidebar-item {{ Request::is('sekolah-saya') ? 'active' : '' }}">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            Data Sekolah Saya
        </a>

        <!-- 4. Status Verifikasi (Stepper) -->
        <a href="{{ route('status.user') }}"
            class="sidebar-item {{ Request::is('user/status-verifikasi') ? 'active' : '' }}">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path
                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            Status Verifikasi
        </a>

    </div>
</aside>
