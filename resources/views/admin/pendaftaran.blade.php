@extends('layouts.admin')

@section('title', 'Pendaftaran')

@section('content')
    {{-- Inisialisasi Alpine.js state khusus 10 Provinsi Sumatra --}}
    <div x-data="{
        // Daftar 10 Provinsi di Pulau Sumatra saja
        provincesSumatra: [
            { id: '11', name: 'ACEH' },
            { id: '12', name: 'SUMATERA UTARA' },
            { id: '13', name: 'SUMATERA BARAT' },
            { id: '14', name: 'RIAU' },
            { id: '15', name: 'JAMBI' },
            { id: '16', name: 'SUMATERA SELATAN' },
            { id: '17', name: 'BENGKULU' },
            { id: '18', name: 'LAMPUNG' },
            { id: '19', name: 'KEPULAUAN BANGKA BELITUNG' },
            { id: '21', name: 'KEPULAUAN RIAU' }
        ],
        regencies: [],
        selectedProv: '{{ request('provinsi') }}',
        selectedKab: '{{ request('kabupaten_kota') }}',
        isLoadingKab: false,
    
        async init() {
            // Jika sebelumnya sudah ada provinsi terpilih dari URL, panggil kab/kota-nya
            if (this.selectedProv) {
                this.fetchRegencies(this.selectedProv);
            }
        },
    
        async fetchRegencies(provId) {
            if (!provId) {
                this.regencies = [];
                this.selectedKab = '';
                return;
            }
            this.isLoadingKab = true;
            try {
                let res = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provId}.json`);
                this.regencies = await res.json();
            } catch (e) {
                console.error('Gagal mengambil data kabupaten/kota:', e);
            } finally {
                this.isLoadingKab = false;
            }
        }
    }">

        <!-- Title & Button Tambah Pendaftar -->
        <div class="flex items-start justify-between mb-4">
            <div>
                <p class="text-sm text-gray-500 mt-0.5">Daftar sekolah yang menunggu verifikasi</p>
            </div>

            {{-- Tombol Tambah Pendaftar --}}
            <a href="{{ route('admin.formpendaftaran') }}"
                class="flex items-center gap-2 px-4 py-2 bg-[#0d9296] text-white text-sm font-medium rounded-lg hover:bg-[#0b7e82] transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Pendaftaran
            </a>
        </div>

        <!-- FILTER CARD ROW FORM -->
        <form method="GET" action="{{ route('admin.pendaftaran.index') }}">
            <div class="bg-white border border-gray-200 rounded-xl p-5 mb-6 shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">

                    <!-- PROVINSI (KHUSUS SUMATRA) -->
                    <div>
                        <label class="text-[11px] font-bold text-gray-500 block mb-1.5 uppercase">Provinsi</label>
                        <select name="provinsi" x-model="selectedProv" @change="fetchRegencies(selectedProv)"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs text-gray-600 bg-white focus:outline-none focus:border-[#0d9296]">
                            <option value="">Semua Provinsi (Sumatra)</option>
                            <template x-for="item in provincesSumatra" :key="item.id">
                                <option :value="item.id" :selected="item.id == selectedProv" x-text="item.name">
                                </option>
                            </template>
                        </select>
                    </div>

                    <!-- KABUPATEN / KOTA -->
                    <div>
                        <label class="text-[11px] font-bold text-gray-500 block mb-1.5 uppercase">Kabupaten/Kota</label>
                        <select name="kabupaten_kota" x-model="selectedKab" :disabled="!selectedProv || isLoadingKab"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs text-gray-600 bg-white focus:outline-none focus:border-[#0d9296] disabled:bg-gray-100 disabled:cursor-not-allowed">
                            <option value="" x-text="isLoadingKab ? 'Memuat data...' : 'Semua Kabupaten/Kota'">
                            </option>
                            <template x-for="item in regencies" :key="item.id">
                                <option :value="item.name" :selected="item.name == selectedKab" x-text="item.name">
                                </option>
                            </template>
                        </select>
                    </div>

                    <!-- JENJANG -->
                    <div>
                        <label class="text-[11px] font-bold text-gray-500 block mb-1.5 uppercase">Jenjang</label>
                        <select name="jenjang"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs text-gray-600 bg-white focus:outline-none focus:border-[#0d9296]">
                            <option value="">Semua Jenjang</option>
                            <option value="SD" {{ request('jenjang') == 'SD' ? 'selected' : '' }}>SD</option>
                            <option value="SMP" {{ request('jenjang') == 'SMP' ? 'selected' : '' }}>SMP</option>
                            <option value="SMA" {{ request('jenjang') == 'SMA' ? 'selected' : '' }}>SMA</option>
                            <option value="SMK" {{ request('jenjang') == 'SMK' ? 'selected' : '' }}>SMK</option>
                        </select>
                    </div>

                    <!-- CARI (SEARCH) -->
                    <div>
                        <label class="text-[11px] font-bold text-gray-500 block mb-1.5 uppercase">Pencarian</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none"
                                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" name="q" value="{{ request('q') }}"
                                placeholder="Cari Sekolah / NPSN"
                                class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-lg text-xs text-gray-600 focus:outline-none focus:border-[#0d9296]">
                        </div>
                    </div>

                    <!-- TOMBOL TERAPKAN & RESET -->
                    <div class="flex items-center gap-2">
                        <button type="submit"
                            class="w-full py-2 bg-[#0d9296] text-white text-xs font-semibold rounded-lg hover:bg-[#0b7e82] transition-colors">
                            Terapkan
                        </button>
                        <a href="{{ route('admin.pendaftaran.index') }}"
                            class="px-3 py-2 bg-gray-100 text-gray-600 text-xs font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                            Reset
                        </a>
                    </div>

                </div>
            </div>
        </form>

        <!-- DATA TABLE -->
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm table-fixed min-w-[900px]">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50 text-gray-700">
                            <th class="text-left px-6 py-4 font-bold text-xs uppercase w-[25%]">Nama Sekolah</th>
                            <th class="text-left px-6 py-4 font-bold text-xs uppercase w-[12%]">NPSN</th>
                            <th class="text-center px-6 py-4 font-bold text-xs uppercase w-[10%]">Jenjang</th>
                            <th class="text-left px-6 py-4 font-bold text-xs uppercase w-[18%]">Tanggal daftar</th>
                            <th class="text-left px-6 py-4 font-bold text-xs uppercase w-[15%]">Kontak</th>
                            <th class="text-left px-6 py-4 font-bold text-xs uppercase w-[20%]">User</th>
                            <th class="text-center px-6 py-4 font-bold text-xs uppercase w-[10%]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($pendaftaran as $row)
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900 truncate">{{ $row->nama_sekolah }}</td>
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
                                    <span class="inline-block px-3 py-0.5 text-xs font-bold rounded {{ $badgeClass }}">
                                        {{ $jenjang }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-500 text-xs">
                                    {{ \Carbon\Carbon::parse($row->created_at)->translatedFormat('d F Y, H:i') }}
                                </td>
                                <td class="px-6 py-4 text-gray-600 text-xs">{{ $row->no_telepon ?? '-' }}</td>
                                <td class="px-6 py-4 text-gray-500 text-xs truncate">{{ $row->user->email ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.pendaftaran.show', $row->id) }}"
                                        class="inline-block px-3 py-1 text-xs font-medium text-[#0d9296] bg-teal-50 border border-teal-200 rounded hover:bg-[#0d9296] hover:text-white transition-all">
                                        Verifikasi
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                        stroke-width="1.5" viewBox="0 0 24 24">
                                        <path
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                    <p class="text-gray-500 text-sm">Tidak ada pendaftaran baru yang sesuai dengan filter.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION ROW -->
            @if ($pendaftaran->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between bg-white text-xs">
                    <p class="text-gray-400">
                        Menampilkan {{ $pendaftaran->firstItem() }}-{{ $pendaftaran->lastItem() }} dari
                        {{ $pendaftaran->total() }} data
                    </p>

                    <div class="flex items-center gap-4">
                        {{ $pendaftaran->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>

    </div>
@endsection
