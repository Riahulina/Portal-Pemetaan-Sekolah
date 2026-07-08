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
</head>

<body>
    @include('partials.navbar')
    @yield('content')
    @include('partials.footer')
</body>

</html>
