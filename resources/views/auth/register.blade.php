<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - SatuPeta</title>
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex flex-col" style="overflow-y: auto !important;">

    {{-- ============================================================ --}}
    {{-- HEADER                                                       --}}
    {{-- ============================================================ --}}
    <header class="nav shrink-0">
        <div class="nav-inner">

            <!-- Brand -->
            <a href="/" class="navbar-brand">
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
                <a href="#tentang">Tentang Kami</a>
                <a href="#peta">Peta Data</a>
                <a href="#kontak">Kontak</a>
            </nav>

            <!-- Button -->
            <a href="/dashboard" class="btn-nav">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M12 22s7-7.58 7-13a7 7 0 1 0-14 0c0 5.42 7 13 7 13Z" />
                    <circle cx="12" cy="9" r="2.4" />
                </svg>

                Mulai Pemetaan
            </a>

        </div>
    </header>

    {{-- ============================================================ --}}
    {{-- MAIN CONTENT — GRADIENT BACKGROUND + FORM CARD               --}}
    {{-- ============================================================ --}}
    <main class="flex-grow w-full py-16 px-4 flex flex-col items-center justify-center bg-gradient-to-b from-[#45AAAE] to-white">

        {{-- ── Form Card ── --}}
        <div class="w-full max-w-lg mx-auto bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.08)] p-10 sm:p-12 z-10">

            {{-- Logo --}}
            <div class="flex flex-col items-center mb-10">
                <img src="{{ asset('assets/logo.png') }}" alt="SatuPeta" class="h-14 mb-2">
                <span class="text-2xl font-bold text-[#45AAAE]">SatuPeta</span>
                <span class="text-sm text-gray-600 font-medium">Peta Pendidikan Indonesia</span>
            </div>

            <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-7">
                @csrf

                {{-- Name --}}
                <div>
                    <label for="name" class="block mb-2 text-sm font-semibold text-gray-700">Nama Lengkap</label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="Masukkan nama lengkap"
                        class="w-full px-4 py-3.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#45AAAE]/50 focus:border-[#45AAAE] focus:bg-white transition-all duration-200">
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block mb-2 text-sm font-semibold text-gray-700">Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="username"
                        placeholder="nama@email.com"
                        class="w-full px-4 py-3.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#45AAAE]/50 focus:border-[#45AAAE] focus:bg-white transition-all duration-200">
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block mb-2 text-sm font-semibold text-gray-700">Password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="Masukkan password"
                        class="w-full px-4 py-3.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#45AAAE]/50 focus:border-[#45AAAE] focus:bg-white transition-all duration-200">
                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block mb-2 text-sm font-semibold text-gray-700">Konfirmasi Password</label>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Ulangi password"
                        class="w-full px-4 py-3.5 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#45AAAE]/50 focus:border-[#45AAAE] focus:bg-white transition-all duration-200">
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full py-3.5 mt-2 bg-[#45AAAE] hover:bg-[#388b8e] text-white font-bold rounded-xl shadow-lg shadow-[#45AAAE]/30 transition-all duration-200 active:scale-[0.98]">
                    Register
                </button>
            </form>

            {{-- Divider --}}
            <div class="flex items-center gap-3 my-6">
                <hr class="flex-1 border-gray-200">
                <span class="text-xs text-gray-400">atau</span>
                <hr class="flex-1 border-gray-200">
            </div>

            {{-- Google Button --}}
            <a href="/auth/google"
               class="w-full py-3.5 bg-white border-2 border-gray-200 hover:border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold rounded-xl flex items-center justify-center gap-3 transition-all duration-200 active:scale-[0.98]">
                <svg class="h-5 w-5" viewBox="0 0 24 24">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Login menggunakan Google
            </a>

            {{-- Bottom text --}}
            <p class="text-center text-sm text-gray-500 mt-6">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="underline font-bold text-[#45AAAE] hover:text-[#3a9599] transition">Login disini.</a>
            </p>
        </div>
    </main>

    {{-- ============================================================ --}}
    {{-- FOOTER                                                       --}}
    {{-- ============================================================ --}}
    <footer id="kontak" class="shrink-0">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="/" class="navbar-brand">
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
                    <p>Platform pemetaan pendidikan terdepan di Indonesia untuk data yang akurat, transparan, dan mudah
                        diakses.</p>
                    <div class="socials">
                        <a href="#"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M13 22v-9h3l1-4h-4V7c0-1.2.3-2 2-2h2V1.2C16.6 1 15.4 1 14 1c-3 0-5 1.8-5 5v3H6v4h3v9h4Z" />
                            </svg></a>
                        <a href="#"><svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="5" />
                                <circle cx="12" cy="12" r="4" />
                                <circle cx="17.5" cy="6.5" r="1" />
                            </svg></a>
                        <a href="#"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M10 8l6 4-6 4V8Z" />
                                <rect x="2" y="4" width="20" height="16" rx="4" fill="none"
                                    stroke="currentColor" stroke-width="2" />
                            </svg></a>
                    </div>
                </div>

                <div>
                    <h4>Navigasi</h4>
                    <ul>
                        <li><a href="#tentang">Tentang Kami</a></li>
                        <li><a href="#peta">Peta Data</a></li>
                        <li><a href="#kontak">Kontak</a></li>
                    </ul>
                </div>

                <div>
                    <h4>Kontak</h4>
                    <ul class="footer-contact">
                        <li><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M4 4h16v16H4z" opacity="0" />
                                <path d="M3 6l9 6 9-6" />
                                <rect x="3" y="5" width="18" height="14" rx="2" />
                            </svg>hello@SatuPeta.com</li>
                        <li><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path
                                    d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.3 1.8.6 2.7a2 2 0 0 1-.5 2.1L8 9.7a16 16 0 0 0 6 6l1.2-1.2a2 2 0 0 1 2.1-.5c.9.3 1.8.5 2.7.6a2 2 0 0 1 1.7 2Z" />
                            </svg>0821-3878-8678</li>
                        <li><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M12 22s7-7.58 7-13a7 7 0 1 0-14 0c0 5.42 7 13 7 13Z" />
                                <circle cx="12" cy="9" r="2.4" />
                            </svg>Jl. Bunga Rinte, Selayang, Kec. Medan Tuntungan, Kota Medan, Sumatera Utara 20136</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">© 2026 SatuPeta. All rights reserved.</div>
        </div>
    </footer>

</body>
</html>
