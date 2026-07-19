<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pendaftaran Sekolah - Admin SatuPeta</title>
    @vite(['resources/css/app.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            overflow: auto !important;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans text-gray-900 min-h-screen">

    <div class="flex min-h-screen">

        <!-- SIDEBAR -->
        @include('partials.adminSidebar')

        <!-- MAIN CONTENT -->
        <main class="flex-1 flex flex-col min-h-screen overflow-y-auto">

            <!-- HEADER -->
            <header
                class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between sticky top-0 z-30">
                <div class="relative w-full max-w-md">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" placeholder="Cari Sekolah, NPSN, atau Wilayah..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#0d9296]/30 focus:border-[#0d9296] transition-all">
                </div>

                <div class="flex items-center gap-4">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <div
                                class="w-8 h-8 rounded-full bg-[#0d9296] text-white flex items-center justify-center text-sm font-bold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <span class="text-sm font-bold text-gray-700">Admin</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition x-cloak
                            class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-xl shadow-lg py-1 z-50">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- CONTENT -->
            <div class="p-8 flex-1" x-data="{ rejectModal: false }">

                <!-- TOP ACTION BAR -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('admin.pendaftaran.index') }}"
                            class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-medium text-gray-600 hover:bg-gray-50 transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali
                        </a>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">Detail Pendaftaran Sekolah</h1>
                            <p class="text-sm text-gray-500 mt-0.5">Informasi lengkap sekolah yang mendaftar</p>
                        </div>
                    </div>
                    <div>
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-orange-50 border border-orange-200 text-orange-700 text-xs font-bold shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                            Menunggu Verifikasi
                        </span>
                    </div>
                </div>

                <!-- MAIN WORKSPACE SIDE BY SIDE -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

                    <!-- LEFT COLUMN: SCHOOL INFORMATION CARD -->
                    <div class="lg:col-span-2 bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">

                        <!-- 1. INFORMASI UTAMA -->
                        <h2 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">Informasi Utama</h2>
                        <div class="grid grid-cols-2 gap-y-4 gap-x-6 border-b border-gray-100 pb-5 mb-5">
                            <div>
                                <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">NPSN</label>
                                <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $sekolah->npsn }}</p>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Nama
                                    Sekolah</label>
                                <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $sekolah->nama_sekolah }}</p>
                            </div>
                        </div>

                        <!-- 2. DETAIL SEKOLAH -->
                        <h2 class="text-sm font-bold text-gray-900 mb-4">Detail Sekolah</h2>
                        <div class="grid grid-cols-2 gap-y-4 gap-x-6 border-b border-gray-100 pb-5 mb-5 text-sm">
                            <div>
                                <label
                                    class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Jenjang</label>
                                <span
                                    class="inline-block px-2.5 py-0.5 text-xs font-bold rounded bg-teal-50 text-teal-600 border border-teal-200 uppercase">
                                    {{ $sekolah->jenjang }}
                                </span>
                            </div>
                            <div>
                                <label
                                    class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Status</label>
                                <span
                                    class="inline-block px-2.5 py-0.5 text-xs font-bold rounded bg-emerald-50 text-emerald-600 border border-emerald-200">
                                    {{ $sekolah->status ?? 'Swasta' }}
                                </span>
                            </div>
                            <div>
                                <label
                                    class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Akreditasi</label>
                                <span
                                    class="inline-block px-2.5 py-0.5 text-xs font-bold rounded bg-amber-50 text-amber-600 border border-amber-200">
                                    {{ $sekolah->akreditasi ?? 'B' }}
                                </span>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">No.
                                    Telepon</label>
                                <p class="text-gray-800 font-medium mt-0.5">{{ $sekolah->no_telepon ?? '-' }}</p>
                            </div>
                            <div>
                                <label
                                    class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Email</label>
                                <p class="text-gray-800 font-medium mt-0.5">{{ $sekolah->email ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Sosmed /
                                    Web</label>
                                <p class="text-gray-800 font-medium mt-0.5 truncate">
                                    {{ $sekolah->social_media ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Jumlah Siswa
                                    Laki-Laki</label>
                                <p class="text-gray-800 font-medium mt-0.5">{{ $sekolah->siswa_laki ?? 0 }}</p>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Jumlah Siswi
                                    Perempuan</label>
                                <p class="text-gray-800 font-medium mt-0.5">{{ $sekolah->siswa_perempuan ?? 0 }}</p>
                            </div>
                        </div>

                        <!-- 3. DETAIL LOKASI / WILAYAH -->
                        <h2 class="text-sm font-bold text-gray-900 mb-4">Detail Wilayah</h2>
                        <div class="grid grid-cols-2 gap-y-4 gap-x-6 text-sm">
                            <div>
                                <label
                                    class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Provinsi</label>
                                <p class="text-gray-800 font-medium mt-0.5">{{ $sekolah->provinsi }}</p>
                            </div>
                            <div>
                                <label
                                    class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Kabupaten/Kota</label>
                                <p class="text-gray-800 font-medium mt-0.5">{{ $sekolah->kabupaten_kota }}</p>
                            </div>
                            <div>
                                <label
                                    class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Kecamatan</label>
                                <p class="text-gray-800 font-medium mt-0.5">{{ $sekolah->kecamatan }}</p>
                            </div>
                            <div>
                                <label
                                    class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Longitude</label>
                                <p class="text-gray-800 font-medium mt-0.5">{{ $sekolah->longitude }}</p>
                                <span class="text-[9px] text-orange-500 font-medium block">Longitude Harus Format
                                    Standar Google Maps</span>
                            </div>
                            <div>
                                <label
                                    class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Latitude</label>
                                <p class="text-gray-800 font-medium mt-0.5">{{ $sekolah->latitude }}</p>
                                <span class="text-[9px] text-orange-500 font-medium block">Latitude Harus Format Standar
                                    Google Maps</span>
                            </div>
                        </div>

                        <!-- KOTAK ALAMAT LENGKAP -->
                        <div class="mt-5 p-4 bg-gray-50 border border-gray-200 rounded-xl">
                            <label
                                class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Alamat
                                Lengkap</label>
                            <p class="text-xs text-gray-700 leading-relaxed">{{ $sekolah->alamat }}, Kec.
                                {{ $sekolah->kecamatan }}, Kab/Kota. {{ $sekolah->kabupaten_kota }}, Prov.
                                {{ $sekolah->provinsi }}</p>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN: ACTION PANEL CARD -->
                    <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm sticky top-24">
                        <h2 class="text-sm font-bold text-gray-900 mb-3">Tindakan Verifikasi</h2>

                        <div
                            class="p-3 bg-sky-50 border border-sky-100 rounded-xl text-sky-800 text-xs leading-relaxed mb-6">
                            Periksa semua informasi data sekolah dengan teliti sebelum melakukan verifikasi
                        </div>

                        <div class="flex flex-col gap-3">
                            <!-- FORM APPROVE -->
                            <form method="POST" action="{{ route('admin.pendaftaran.verifikasi', $sekolah->id) }}">
                                @csrf
                                <input type="hidden" name="status" value="approved">
                                <button type="submit"
                                    class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold flex items-center justify-center gap-1.5 transition-colors shadow-sm shadow-emerald-600/10">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                        viewBox="0 0 24 24">
                                        <path d="M5 13l4 4L19 7" />
                                    </svg>
                                    Setujui Pendaftaran
                                </button>
                            </form>

                            <!-- TOMBOL TRIGGER MODAL REJECT -->
                            <button @click="rejectModal = true"
                                class="w-full py-2.5 px-4 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-bold flex items-center justify-center gap-1.5 transition-colors shadow-sm shadow-red-600/10">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <path d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Tolak Pendaftaran
                            </button>

                            <!-- BUTTON MAPS MOCKUP -->
                            <a href="https://www.google.com/maps/search/?api=1&query={{ $sekolah->latitude }},{{ $sekolah->longitude }}"
                                target="_blank"
                                class="w-full py-2 px-4 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 rounded-lg text-xs font-medium flex items-center justify-center gap-1.5 transition-colors shadow-sm mt-3">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Lihat di Maps
                            </a>
                        </div>
                    </div>

                </div>

                <!-- MODAL ALASAN PENOLAKAN -->
                <div x-show="rejectModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="rejectModal = false"></div>
                    <div x-show="rejectModal" x-transition
                        class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6 overflow-hidden">
                        <h3 class="text-base font-bold text-gray-900 mb-2">Tolak Pendaftaran Sekolah</h3>
                        <p class="text-xs text-gray-500 mb-4">Berikan alasan penolakan agar pendaftar dapat memperbaiki
                            berkas atau data sekolah mereka.</p>

                        <form method="POST" action="{{ route('admin.pendaftaran.verifikasi', $sekolah->id) }}">
                            @csrf
                            <input type="hidden" name="status" value="rejected">

                            <textarea name="catatan_admin" required rows="4"
                                placeholder="Contoh: Koordinat latitude dan longitude tidak akurat atau alamat tidak sesuai..."
                                class="w-full border border-gray-200 rounded-xl p-3 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 mb-4 resize-none"></textarea>

                            <div class="flex items-center justify-end gap-2">
                                <button type="button" @click="rejectModal = false"
                                    class="px-4 py-2 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Batal</button>
                                <button type="submit"
                                    class="px-4 py-2 text-xs font-bold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors shadow-sm">Kirim
                                    & Tolak</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </main>
    </div>

</body>

</html>
