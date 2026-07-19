<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SatuPeta')</title>

    <!-- 1. Taruh CSS Leaflet di paling atas (Default Global) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <!-- 2. File CSS bawaan aplikasi -->
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboardUser.css') }}">
    <link rel="stylesheet" href="{{ asset('css/StyleForm.css') }}">

    <!-- Tempat menampung CSS tambahan khusus dari halaman tertentu jika ada -->
    @yield('styles')
</head>

<body>
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

        // Menutup dropdown otomatis jika user mengklik di luar area profil
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

    <!-- PENTING: Tempat menampung JavaScript khusus dari halaman child (seperti logika Leaflet di form) -->
    @yield('scripts')
</body>

</html>
