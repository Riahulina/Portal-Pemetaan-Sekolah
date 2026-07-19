<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menunggu Verifikasi - Admin SatuPeta</title>
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
                <!-- Search Global atas sesuai mockup -->
                <div class="relative w-full max-w-md">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" placeholder="Cari Sekolah, NPSN, atau Wilayah..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#0d9296]/30 focus:border-[#0d9296] transition-all">
                </div>

                <!-- Admin Profile Dropdown -->
                <div class="flex items-center gap-4">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <div
                                class="w-8 h-8 rounded-full bg-[#0d9296] text-white flex items-center justify-center text-sm font-bold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <span class="text-sm font-bold text-gray-900">Admin</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition x-cloak
                            class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-xl shadow-lg py-1 z-50">
                            <a href="{{ route('admin.dashboard') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- CONTENT CONTROLLER -->
            <div class="p-8 flex-1" x-data="{ filterOpen: true }">

                <!-- Title & Filter Button Group -->
                <div class="flex items-start justify-between mb-2">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Menunggu Verifikasi</h1>
                        <p class="text-sm text-gray-500 mt-0.5">Daftar sekolah yang menunggu verifikasi</p>
                    </div>
                    <button @click="filterOpen = !filterOpen"
                        class="flex items-center gap-2 px-4 py-2 bg-[#0d9296] text-white text-sm font-medium rounded-lg hover:bg-[#0b7e82] transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>
                </div>

                <!-- FILTER CARD ROW (Sesuai Mockup) -->
                <div x-show="filterOpen" x-transition class="bg-white border border-gray-200 rounded-xl p-5 mb-6">
                    <div class="grid grid-columns-1 md:grid-cols-5 gap-4 items-end">
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 block mb-1.5 uppercase">Provinsi</label>
                            <select
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs text-gray-500 bg-white focus:outline-none focus:border-[#0d9296]">
                                <option>Semua Provinsi</option>
                            </select>
                        </div>
                        <div>
                            <label
                                class="text-[11px] font-bold text-gray-500 block mb-1.5 uppercase">Kabupaten/Kota</label>
                            <select
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs text-gray-500 bg-white focus:outline-none focus:border-[#0d9296]">
                                <option>Semua Kabupaten/Kota</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 block mb-1.5 uppercase">Jenjang</label>
                            <select
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs text-gray-500 bg-white focus:outline-none focus:border-[#0d9296]">
                                <option>Semua Jenjang</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 block mb-1.5 uppercase">Status</label>
                            <select
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs text-gray-500 bg-white focus:outline-none focus:border-[#0d9296]">
                                <option>Semua Status</option>
                            </select>
                        </div>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400"
                                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" placeholder="Cari Sekolah"
                                class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-lg text-xs text-gray-600 focus:outline-none focus:border-[#0d9296]">
                        </div>
                    </div>
                </div>

                <!-- DATA TABLE -->
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm table-fixed min-w-[900px]">
                            <thead>
                                <tr class="border-b border-gray-200 bg-gray-50 text-gray-700">
                                    <th class="text-left px-6 py-4 font-bold text-xs uppercase w-[25%]">Nama Sekolah
                                    </th>
                                    <th class="text-left px-6 py-4 font-bold text-xs uppercase w-[12%]">NPSN</th>
                                    <th class="text-center px-6 py-4 font-bold text-xs uppercase w-[10%]">Jenjang</th>
                                    <th class="text-left px-6 py-4 font-bold text-xs uppercase w-[18%]">Tanggal daftar
                                    </th>
                                    <th class="text-left px-6 py-4 font-bold text-xs uppercase w-[15%]">Kontak</th>
                                    <th class="text-left px-6 py-4 font-bold text-xs uppercase w-[20%]">User</th>
                                    <th class="text-center px-6 py-4 font-bold text-xs uppercase w-[10%]">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($pendaftaran as $row)
                                    <tr class="hover:bg-gray-50/70 transition-colors">
                                        <td class="px-6 py-4 font-medium text-gray-900 truncate">
                                            {{ $row->nama_sekolah }}</td>
                                        <td class="px-6 py-4 text-gray-600 font-mono text-xs">{{ $row->npsn }}</td>
                                        <td class="px-6 py-4 text-center">
                                            @php
                                                $jenjang = strtoupper($row->jenjang);
                                                $badgeClass = match ($jenjang) {
                                                    'SD' => 'bg-red-50 text-red-600 border border-red-200',
                                                    'SMP' => 'bg-amber-50 text-amber-600 border border-amber-200',
                                                    'SMA' => 'bg-pink-50 text-pink-600 border border-pink-200',
                                                    'SMK' => 'bg-teal-50 text-teal-600 border border-teal-200',
                                                    default => 'bg-gray-50 text-gray-600 border border-gray-200',
                                                };
                                            @endphp
                                            <span
                                                class="inline-block px-3 py-0.5 text-xs font-bold rounded {{ $badgeClass }}">
                                                {{ $jenjang }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-gray-500 text-xs">
                                            {{ \Carbon\Carbon::parse($row->created_at)->translatedFormat('d F Y, H:i') }}
                                        </td>
                                        <td class="px-6 py-4 text-gray-600 text-xs">{{ $row->no_telepon ?? '-' }}</td>
                                        <td class="px-6 py-4 text-gray-500 text-xs truncate">
                                            {{ $row->user->email ?? '-' }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <!-- Tombol verifikasi mengarah ke halaman detail / form persetujuan -->
                                            <a href="{{ route('admin.pendaftaran.show', $row->id) }}"
                                                class="inline-block px-3 py-1 text-xs font-medium text-[#0d9296] bg-teal-50 border border-teal-200 rounded hover:bg-[#0d9296] hover:text-white transition-all">
                                                Verifikasi
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-16 text-center">
                                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none"
                                                stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path
                                                    d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                            </svg>
                                            <p class="text-gray-500 text-sm">Tidak ada pendaftaran baru yang menunggu
                                                verifikasi.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINATION ROW (Sesuai Mockup) -->
                    @if ($pendaftaran->hasPages())
                        <div
                            class="px-6 py-4 border-t border-gray-100 flex items-center justify-between bg-white text-xs">
                            <p class="text-gray-400">
                                Menampilkan {{ $pendaftaran->firstItem() }}-{{ $pendaftaran->lastItem() }} dari
                                {{ $pendaftaran->total() }} data
                            </p>

                            <div class="flex items-center gap-4">
                                <!-- Pagination Numbering -->
                                <div class="flex items-center gap-1">
                                    @if ($pendaftaran->onFirstPage())
                                        <span
                                            class="px-2.5 py-1 text-gray-300 bg-gray-50 border border-gray-200 rounded cursor-not-allowed">&lt;</span>
                                    @else
                                        <a href="{{ $pendaftaran->previousPageUrl() }}"
                                            class="px-2.5 py-1 text-gray-600 bg-white border border-gray-200 rounded hover:bg-gray-50 transition-colors">&lt;</a>
                                    @endif

                                    @foreach ($pendaftaran->getUrlRange(1, $pendaftaran->lastPage()) as $page => $url)
                                        @if ($page == $pendaftaran->currentPage())
                                            <span
                                                class="px-2.5 py-1 text-white bg-[#0d9296] rounded font-bold">{{ $page }}</span>
                                        @else
                                            <a href="{{ $url }}"
                                                class="px-2.5 py-1 text-gray-600 bg-white border border-gray-200 rounded hover:bg-gray-50 transition-colors">{{ $page }}</a>
                                        @endif
                                    @endforeach

                                    @if ($pendaftaran->hasMorePages())
                                        <a href="{{ $pendaftaran->nextPageUrl() }}"
                                            class="px-2.5 py-1 text-gray-600 bg-white border border-gray-200 rounded hover:bg-gray-50 transition-colors">&gt;</a>
                                    @else
                                        <span
                                            class="px-2.5 py-1 text-gray-300 bg-gray-50 border border-gray-200 rounded cursor-not-allowed">&gt;</span>
                                    @endif
                                </div>

                                <!-- Rows Per Page Mockup -->
                                <div class="flex items-center gap-1">
                                    <select
                                        class="px-2 py-1 border border-gray-200 rounded text-gray-600 bg-white focus:outline-none">
                                        <option>5 / Halaman</option>
                                        <option>10 / Halaman</option>
                                        <option>25 / Halaman</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </main>
    </div>

</body>

</html>
