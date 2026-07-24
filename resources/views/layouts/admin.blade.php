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

<body class="bg-gray-50 font-sans text-gray-900 min-h-screen" x-data="{ sidebarOpen: false }" :class="{ 'overflow-hidden lg:overflow-auto': sidebarOpen }">

    <div class="flex min-h-screen">

        @include('partials.adminSidebar')

        <main class="flex-1 flex flex-col min-h-screen overflow-y-auto lg:ml-0">

            <header
                class="bg-white border-b border-gray-200 px-4 sm:px-6 lg:px-8 flex items-center justify-between sticky top-0 z-20"
                style="height: 100px; box-sizing: border-box; overflow: visible;">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 class="text-xl font-bold text-gray-900">@yield('title', 'Admin Panel')</h1>
                </div>
                <div class="flex items-center gap-4">
                    @include('partials.adminProfileDropdown')
                </div>
            </header>

            <div class="p-4 sm:p-6 lg:p-8 flex flex-col gap-8">
                @yield('content')
            </div>
        </main>
    </div>

    @yield('scripts')
    @stack('scripts')

</body>

</html>
