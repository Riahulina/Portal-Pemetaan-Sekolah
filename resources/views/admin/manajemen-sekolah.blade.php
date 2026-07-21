@extends('layouts.admin')

@section('title', 'Manajemen Sekolah')

@section('content')
    <div x-data="sekolahManager()" x-init="init()">

        <!-- FLASH MESSAGE -->
        @if (session('success'))
            <div class="mb-6 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- SEARCH BAR + FILTER -->
        <form method="GET" action="{{ route('admin.sekolah.index') }}" class="mb-6">
            <div class="flex items-center gap-3 flex-wrap">
                <div class="relative flex-1 min-w-[200px] max-w-md">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari berdasarkan nama atau NPSN..."
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-[#0d9296]/30 focus:border-[#0d9296] transition-all">
                </div>
                <select name="filter_kurang"
                    class="px-3 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-[#0d9296]/30 focus:border-[#0d9296] transition-all">
                    <option value="">Semua Data</option>
                    <option value="koordinat" {{ $filterKurang === 'koordinat' ? 'selected' : '' }}>Koordinat Belum Ada</option>
                    <option value="siswa" {{ $filterKurang === 'siswa' ? 'selected' : '' }}>Data Siswa Kosong</option>
                    <option value="sosmed" {{ $filterKurang === 'sosmed' ? 'selected' : '' }}>Web/Sosmed Kosong</option>
                    <option value="email" {{ $filterKurang === 'email' ? 'selected' : '' }}>Email Kosong</option>
                    <option value="telepon" {{ $filterKurang === 'telepon' ? 'selected' : '' }}>No Telepon Kosong</option>
                </select>
                <button type="submit" class="px-5 py-2.5 bg-[#0d9296] text-white text-sm font-medium rounded-lg hover:bg-[#0b7e82] transition-colors">
                    Cari
                </button>
                @if ($search || $filterKurang)
                    <a href="{{ route('admin.sekolah.index') }}" class="px-4 py-2.5 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <!-- DATA TABLE -->
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="text-center px-6 py-3.5 font-bold text-gray-500 uppercase tracking-wide text-xs w-16">Nomor</th>
                            <th class="text-left px-6 py-3.5 font-bold text-gray-500 uppercase tracking-wide text-xs">Nama Sekolah</th>
                            <th class="text-left px-6 py-3.5 font-bold text-gray-500 uppercase tracking-wide text-xs">NPSN</th>
                            <th class="text-left px-6 py-3.5 font-bold text-gray-500 uppercase tracking-wide text-xs">Jenjang</th>
                            <th class="text-left px-6 py-3.5 font-bold text-gray-500 uppercase tracking-wide text-xs">Status</th>
                            <th class="text-left px-6 py-3.5 font-bold text-gray-500 uppercase tracking-wide text-xs">Provinsi</th>
                            <th class="text-left px-6 py-3.5 font-bold text-gray-500 uppercase tracking-wide text-xs">Kabupaten/Kota</th>
                            <th class="text-center px-6 py-3.5 font-bold text-gray-500 uppercase tracking-wide text-xs">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sekolah as $row)
                            <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-center text-gray-500">
                                    {{ $sekolah->firstItem() + $loop->index }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-medium text-gray-900">{{ $row->nama_sekolah }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <code class="text-xs bg-gray-100 px-2 py-1 rounded font-mono text-gray-600">{{ $row->npsn }}</code>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $jenjangColors = [
                                            'SD' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'SMP' => 'bg-purple-50 text-purple-700 border-purple-200',
                                            'SMA' => 'bg-orange-50 text-orange-700 border-orange-200',
                                            'SMK' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'TK' => 'bg-pink-50 text-pink-700 border-pink-200',
                                        ];
                                        $jenjangClass = $jenjangColors[$row->jenjang] ?? 'bg-gray-50 text-gray-600 border-gray-200';
                                    @endphp
                                    <span class="inline-block px-2.5 py-0.5 text-xs font-medium rounded-full border {{ $jenjangClass }}">
                                        {{ $row->jenjang ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                @if (strtoupper($row->status) === 'NEGERI')
                                    <span class="inline-block px-2.5 py-0.5 text-xs font-medium rounded-full bg-teal-50 text-teal-700 border border-teal-200">Negeri</span>
                                @elseif (strtoupper($row->status) === 'SWASTA')
                                    <span class="inline-block px-2.5 py-0.5 text-xs font-medium rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200">Swasta</span>
                                @else
                                        <span class="inline-block px-2.5 py-0.5 text-xs font-medium rounded-full bg-gray-50 text-gray-500 border border-gray-200">{{ $row->status ?? '-' }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $row->provinsi ?? '-' }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $row->kabupaten_kota ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-1" x-data="{ menuOpen: false }">
                                        <button @click="menuOpen = !menuOpen" class="p-1.5 rounded-lg hover:bg-gray-100 transition-colors">
                                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                                <circle cx="12" cy="5" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="12" cy="19" r="2"/>
                                            </svg>
                                        </button>
                                        <div x-show="menuOpen" @click.away="menuOpen = false" x-transition
                                            class="absolute right-0 mt-1 w-40 bg-white border border-gray-200 rounded-xl shadow-lg py-1 z-40">
                                            <button @click="openViewModal({{ $row->toJson() }}); menuOpen = false"
                                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                                <svg class="w-4 h-4 text-[#0d9296]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                Lihat
                                            </button>
                                            <button @click="openEditModal({{ $row->toJson() }}); menuOpen = false"
                                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                Edit
                                            </button>
                                            <button @click="openDeleteModal({{ $row->toJson() }}); menuOpen = false"
                                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <p class="text-gray-500 text-sm">Tidak ada data sekolah ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            @if ($sekolah->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                    <p class="text-sm text-gray-500">
                        Menampilkan {{ $sekolah->firstItem() }}-{{ $sekolah->lastItem() }} dari {{ $sekolah->total() }} sekolah
                    </p>
                    <div class="flex items-center gap-1">
                        @if ($sekolah->onFirstPage())
                            <span class="px-3 py-1.5 text-sm text-gray-300 bg-gray-50 rounded-lg cursor-not-allowed">&laquo;</span>
                        @else
                            <a href="{{ $sekolah->previousPageUrl() }}&search={{ $search }}&filter_kurang={{ $filterKurang }}" class="px-3 py-1.5 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">&laquo;</a>
                        @endif

                        @foreach ($sekolah->getUrlRange(max(1, $sekolah->currentPage() - 2), min($sekolah->lastPage(), $sekolah->currentPage() + 2)) as $page => $url)
                            @if ($page == $sekolah->currentPage())
                                <span class="px-3 py-1.5 text-sm text-white bg-[#0d9296] rounded-lg font-medium">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}&search={{ $search }}&filter_kurang={{ $filterKurang }}" class="px-3 py-1.5 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if ($sekolah->hasMorePages())
                            <a href="{{ $sekolah->nextPageUrl() }}&search={{ $search }}&filter_kurang={{ $filterKurang }}" class="px-3 py-1.5 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">&raquo;</a>
                        @else
                            <span class="px-3 py-1.5 text-sm text-gray-300 bg-gray-50 rounded-lg cursor-not-allowed">&raquo;</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- ==================== MODALS ==================== -->

        <!-- MODAL: LIHAT DETAIL -->
        <div x-show="viewModal" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="viewModal = false">
            <div class="absolute inset-0 bg-black/40"></div>
            <div x-show="viewModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[85vh] overflow-y-auto">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Detail Sekolah</h3>
                    <button @click="viewModal = false" class="p-1.5 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="px-6 py-5 space-y-4" x-show="viewData">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Nama Sekolah</p>
                            <p class="text-sm font-semibold text-gray-900 mt-1" x-text="viewData?.nama_sekolah || '-'"></p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">NPSN</p>
                            <p class="text-sm font-mono text-gray-900 mt-1" x-text="viewData?.npsn || '-'"></p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Jenjang</p>
                            <p class="text-sm text-gray-900 mt-1" x-text="viewData?.jenjang || '-'"></p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Status</p>
                            <p class="text-sm text-gray-900 mt-1" x-text="viewData?.status || '-'"></p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Akreditasi</p>
                            <p class="text-sm text-gray-900 mt-1" x-text="viewData?.akreditasi || '-'"></p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Total Siswa</p>
                            <p class="text-sm font-semibold text-gray-900 mt-1" x-text="(viewData?.total_siswa || 0).toLocaleString('id-ID')"></p>
                        </div>
                    </div>
                    <div class="pt-3 border-t border-gray-100">
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">Lokasi</p>
                        <p class="text-sm text-gray-900" x-text="[viewData?.alamat, viewData?.kelurahan, viewData?.kecamatan, viewData?.kabupaten_kota, viewData?.provinsi].filter(Boolean).join(', ') || '-'"></p>
                    </div>
                    <div class="pt-3 border-t border-gray-100">
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">Kontak</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-400">Telepon</p>
                                <p class="text-sm text-gray-900 mt-1" x-text="viewData?.no_telepon || '-'"></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Email</p>
                                <p class="text-sm text-gray-900 mt-1" x-text="viewData?.email || '-'"></p>
                            </div>
                        </div>
                    </div>
                    <div class="pt-3 border-t border-gray-100">
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">Koordinat</p>
                        <p class="text-sm text-gray-900" x-text="viewData?.latitude && viewData?.longitude ? viewData.latitude + ', ' + viewData.longitude : 'Belum terdaftar'"></p>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 flex justify-end">
                    <button @click="viewModal = false" class="px-5 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Tutup</button>
                </div>
            </div>
        </div>

        <!-- MODAL: EDIT -->
        <div x-show="editModal" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="editModal = false">
            <div class="absolute inset-0 bg-black/40"></div>
            <div x-show="editModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[85vh] overflow-y-auto">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white z-10">
                    <h3 class="text-lg font-bold text-gray-900">Edit Sekolah</h3>
                    <button @click="editModal = false" class="p-1.5 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form :action="'{{ url('admin/sekolah') }}/' + (editData?.npsn || '')" method="POST" x-show="editData">
                    @csrf
                    @method('PUT')
                    <div class="px-6 py-5 space-y-4">
                        <!-- Informasi Utama -->
                        <div>
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-3">Informasi Utama</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">NPSN</label>
                                    <input type="text" name="npsn" :value="editData?.npsn" readonly
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-gray-50 text-gray-500 cursor-not-allowed">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Nama Sekolah <span class="text-red-500">*</span></label>
                                    <input type="text" name="nama_sekolah" :value="editData?.nama_sekolah" required
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0d9296]/30 focus:border-[#0d9296]">
                                </div>
                            </div>
                        </div>

                        <!-- Detail Sekolah -->
                        <div>
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-3">Detail Sekolah</h4>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Jenjang</label>
                                    <select name="jenjang"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0d9296]/30 focus:border-[#0d9296]">
                                        <option value="">Pilih</option>
                                        <option value="KB" x-bind:selected="editData?.jenjang === 'KB'">KB</option>
                                        <option value="TK" x-bind:selected="editData?.jenjang === 'TK'">TK</option>
                                        <option value="SD" x-bind:selected="editData?.jenjang === 'SD'">SD</option>
                                        <option value="SMP" x-bind:selected="editData?.jenjang === 'SMP'">SMP</option>
                                        <option value="SMA" x-bind:selected="editData?.jenjang === 'SMA'">SMA</option>
                                        <option value="SMK" x-bind:selected="editData?.jenjang === 'SMK'">SMK</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                                    <select name="status"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0d9296]/30 focus:border-[#0d9296]">
                                        <option value="">Pilih</option>
                                        <option value="NEGERI" x-bind:selected="editData?.status?.toUpperCase() === 'NEGERI'">Negeri</option>
                                        <option value="SWASTA" x-bind:selected="editData?.status?.toUpperCase() === 'SWASTA'">Swasta</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Akreditasi</label>
                                    <select name="akreditasi"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0d9296]/30 focus:border-[#0d9296]">
                                        <option value="">Pilih</option>
                                        <option value="A" x-bind:selected="editData?.akreditasi === 'A'">A</option>
                                        <option value="B" x-bind:selected="editData?.akreditasi === 'B'">B</option>
                                        <option value="C" x-bind:selected="editData?.akreditasi === 'C'">C</option>
                                        <option value="Tidak Terakreditasi" x-bind:selected="editData?.akreditasi === 'Tidak Terakreditasi'">Tidak Terakreditasi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-4 mt-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">No. Telepon</label>
                                    <input type="text" name="no_telepon" :value="editData?.no_telepon"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0d9296]/30 focus:border-[#0d9296]">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Email</label>
                                    <input type="email" name="email" :value="editData?.email"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0d9296]/30 focus:border-[#0d9296]">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Total Siswa</label>
                                    <input type="number" name="total_siswa" :value="editData?.total_siswa" min="0"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0d9296]/30 focus:border-[#0d9296]">
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Social Media</label>
                                <input type="text" name="social_media" :value="editData?.social_media"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0d9296]/30 focus:border-[#0d9296]">
                            </div>
                        </div>

                        <!-- Detail Lokasi -->
                        <div>
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-3">Detail Lokasi</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Provinsi</label>
                                    <input type="text" name="provinsi" :value="editData?.provinsi"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0d9296]/30 focus:border-[#0d9296]">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Kabupaten/Kota</label>
                                    <input type="text" name="kabupaten_kota" :value="editData?.kabupaten_kota"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0d9296]/30 focus:border-[#0d9296]">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Kecamatan</label>
                                    <input type="text" name="kecamatan" :value="editData?.kecamatan"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0d9296]/30 focus:border-[#0d9296]">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Kelurahan</label>
                                    <input type="text" name="kelurahan" :value="editData?.kelurahan"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0d9296]/30 focus:border-[#0d9296]">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Latitude</label>
                                    <input type="text" name="latitude" :value="editData?.latitude"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0d9296]/30 focus:border-[#0d9296]">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Longitude</label>
                                    <input type="text" name="longitude" :value="editData?.longitude"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0d9296]/30 focus:border-[#0d9296]">
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Alamat</label>
                                <textarea name="alamat" rows="2"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0d9296]/30 focus:border-[#0d9296]"
                                    x-text="editData?.alamat"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3 sticky bottom-0 bg-white">
                        <button type="button" @click="editModal = false" class="px-5 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Batal</button>
                        <button type="submit" class="px-5 py-2 text-sm font-medium text-white bg-[#0d9296] rounded-lg hover:bg-[#0b7e82] transition-colors">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: HAPUS -->
        <div x-show="deleteModal" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="deleteModal = false">
            <div class="absolute inset-0 bg-black/40"></div>
            <div x-show="deleteModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                class="relative bg-white rounded-2xl shadow-xl w-full max-w-md">
                <div class="px-6 py-5 text-center">
                    <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Hapus Sekolah?</h3>
                    <p class="text-sm text-gray-500 mb-1">Anda yakin ingin menghapus sekolah ini?</p>
                    <p class="text-sm font-semibold text-gray-900" x-text="deleteData?.nama_sekolah || ''"></p>
                    <p class="text-xs text-gray-400 mt-1">NPSN: <span x-text="deleteData?.npsn || ''" class="font-mono"></span></p>
                    <p class="text-xs text-red-500 mt-3">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-center gap-3">
                    <button @click="deleteModal = false" class="px-5 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Batal</button>
                    <form :action="'{{ url('admin/sekolah') }}/' + (deleteData?.npsn || '')" method="POST" x-show="deleteData">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-5 py-2 text-sm font-medium text-white bg-red-500 rounded-lg hover:bg-red-600 transition-colors">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        function sekolahManager() {
            return {
                viewModal: false,
                editModal: false,
                deleteModal: false,
                viewData: null,
                editData: null,
                deleteData: null,

                init() {},

                openViewModal(data) {
                    this.viewData = data;
                    this.viewModal = true;
                },

                openEditModal(data) {
                    this.editData = { ...data };
                    this.editModal = true;
                },

                openDeleteModal(data) {
                    this.deleteData = data;
                    this.deleteModal = true;
                },
            };
        }
    </script>
@endpush
