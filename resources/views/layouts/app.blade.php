<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SatuPeta')</title>

    <!-- 1. Taruh CSS Leaflet di paling atas -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <!-- 2. Baru kemudian file CSS kamu sendiri di bawahnya -->
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboardUser.css') }}">
    <link rel="stylesheet" href="{{ asset('css/StyleForm.css') }}">
</head>

<body>
    @include('partials.navbar')
    @yield('content')
    @include('partials.footer')
</body>

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

</html>
