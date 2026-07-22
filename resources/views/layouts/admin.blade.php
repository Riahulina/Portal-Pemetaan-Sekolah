<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - SatuPeta</title>
    @vite(['resources/css/app.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Leaflet CSS & JS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        body {
            overflow: auto !important;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
    @yield('styles')
</head>

<body class="bg-gray-50 font-sans text-gray-900 min-h-screen">

    <div class="flex min-h-screen">

        @include('partials.adminSidebar')

        <main class="flex-1 flex flex-col min-h-screen overflow-y-auto">

            <header
                class="bg-white border-b border-gray-200 px-8 flex items-center justify-between sticky top-0 z-20"
                style="height: 100px; box-sizing: border-box; overflow: visible;">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">@yield('title', 'Admin Panel')</h1>
                </div>
                <div class="flex items-center gap-4">
                    @include('partials.adminProfileDropdown')
                </div>
            </header>

            <div class="p-8 flex flex-col gap-8">
                @yield('content')
            </div>
        </main>
    </div>

    @yield('scripts')
    @stack('scripts')

</body>

</html>
