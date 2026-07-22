<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SatuPeta')</title>

    <!-- 1. CSS Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <!-- 2. Google Fonts — Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- 3. Alpine.js (Di-load di head agar x-data langsung siap saat render) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- 4. CSS Bawaan Aplikasi -->
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboardUser.css') }}">
    <link rel="stylesheet" href="{{ asset('css/StyleForm.css') }}">

    <!-- 5. CSS Responsif Global & Fix Sidebar Overlay -->
    <!-- 5. CSS Responsif Global & Fix Sidebar Overlay -->
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html,
        body {
            max-width: 100%;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            padding-top: 50px !important;
        }

        /* ==========================================
           NAVBAR FIXED/STICKY SAAT DI-SCROLL
           ========================================== */
        header.nav,
        .nav {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            width: 100% !important;
            z-index: 9999 !important;
            background-color: #ffffff !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08) !important;
        }

        img,
        svg,
        video,
        iframe {
            max-width: 100%;
            height: auto;
        }

        .main-container,
        .container {
            width: 100%;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .mobile-hamburger-btn {
            display: none;
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 8px;
            color: #1e293b;
        }

        @media screen and (max-width: 768px) {
            .mobile-hamburger-btn {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            /* 1. LAYAR GELAP / OVERLAY */
            .sidebar-backdrop {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: rgba(0, 0, 0, 0.4);
                z-index: 9998 !important;
            }

            .sidebar-backdrop.show {
                display: block;
            }

            /* 2. SIDEBAR UTAMA */
            .sidebar {
                position: fixed !important;
                top: 0 !important;
                left: -100% !important;
                width: 260px !important;
                height: 100vh !important;
                background-color: #ffffff !important;
                z-index: 99999 !important;
                /* Diberi z-index paling tinggi agar berada di atas navbar */
                transition: left 0.3s ease-in-out !important;
                box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15) !important;
            }

            /* Saat sidebarOpen = true */
            .sidebar.open {
                left: 0 !important;
            }
        }
    </style>

    @yield('styles')
</head>

<body x-data="{ sidebarOpen: false }">

    <!-- Overlay Latar Gelap (Hanya di bawah sidebar saat terbuka) -->
    <div class="sidebar-backdrop" :class="{ 'show': sidebarOpen }" @click="sidebarOpen = false"></div>

    @include('partials.navbar')

    @yield('content')

    @include('partials.footer')

    <!-- Script Utama Bawaan Layout -->
    <script>
        function toggleNavbarDropdown() {
            const dropdown = document.getElementById('navProfileDropdown');
            if (dropdown) {
                dropdown.classList.toggle('show');
            }
        }

        window.onclick = function(event) {
            if (!event.target.closest('.user-profile-dropdown-wrapper')) {
                const dropdowns = document.getElementsByClassName("nav-dropdown-menu");
                for (let i = 0; i < dropdowns.length; i++) {
                    let openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>

    @yield('scripts')
</body>

</html>
