@extends('layouts.admin')

@section('title', isset($sekolah) ? 'Edit Pendaftaran' : 'Form Pendaftaran')

@section('content')
    <div class="max-w-6xl mx-auto w-full">

        <!-- Card Container Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">

            <!-- Header Form -->
            <div class="border-b border-gray-100 pb-5 mb-6">
                <h2 class="text-2xl font-bold text-gray-800">
                    {{ isset($sekolah) ? 'Edit Data Sekolah' : 'Daftar Sekolah' }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    {{ isset($sekolah) ? 'Perbarui informasi sekolah Anda dengan benar. Data hasil edit akan ditinjau kembali oleh admin.' : 'Lengkapi informasi sekolah anda dengan benar. Data yang diisi akan ditinjau oleh admin sebelum ditampilkan.' }}
                </p>
            </div>

            <form action="{{ isset($sekolah) ? route('sekolah.update', $sekolah->id) : route('sekolah.store') }}"
                method="POST" class="space-y-8">
                @csrf
                @if (isset($sekolah))
                    @method('PUT')
                @endif

                <!-- SECTION 1: Informasi Utama -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-700 border-b border-gray-100 pb-2">Informasi Utama</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="npsn" class="block text-sm font-medium text-gray-700 mb-1">NPSN <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="npsn" name="npsn"
                                value="{{ old('npsn', $sekolah->npsn ?? '') }}" placeholder="Masukkan NPSN"
                                class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                                required>
                            @error('npsn')
                                <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                            @else
                                <span class="text-xs text-amber-600 mt-1 block">NPSN Harus unik dan tidak boleh sama</span>
                            @enderror
                        </div>

                        <div>
                            <label for="nama_sekolah" class="block text-sm font-medium text-gray-700 mb-1">Nama Sekolah
                                <span class="text-red-500">*</span></label>
                            <input type="text" id="nama_sekolah" name="nama_sekolah"
                                value="{{ old('nama_sekolah', $sekolah->nama_sekolah ?? '') }}"
                                placeholder="Masukkan Nama Sekolah"
                                class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                                required>
                            @error('nama_sekolah')
                                <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: Detail Sekolah -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-700 border-b border-gray-100 pb-2">Detail Sekolah</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="jenjang" class="block text-sm font-medium text-gray-700 mb-1">Jenjang <span
                                    class="text-red-500">*</span></label>
                            <select id="jenjang" name="jenjang"
                                class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                                required>
                                <option value="" disabled {{ !isset($sekolah) ? 'selected' : '' }}>Pilih Jenjang
                                </option>
                                <option value="KB"
                                    {{ old('jenjang', $sekolah->jenjang ?? '') == 'KB' ? 'selected' : '' }}>KB</option>
                                <option value="TK"
                                    {{ old('jenjang', $sekolah->jenjang ?? '') == 'TK' ? 'selected' : '' }}>TK</option>
                                <option value="SD"
                                    {{ old('jenjang', $sekolah->jenjang ?? '') == 'SD' ? 'selected' : '' }}>SD</option>
                                <option value="SMP"
                                    {{ old('jenjang', $sekolah->jenjang ?? '') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                <option value="SMA"
                                    {{ old('jenjang', $sekolah->jenjang ?? '') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                <option value="SMK"
                                    {{ old('jenjang', $sekolah->jenjang ?? '') == 'SMK' ? 'selected' : '' }}>SMK</option>
                            </select>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span
                                    class="text-red-500">*</span></label>
                            <select id="status" name="status"
                                class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                                required>
                                <option value="" disabled {{ !isset($sekolah) ? 'selected' : '' }}>Pilih Status
                                </option>
                                <option value="Negeri"
                                    {{ old('status', $sekolah->status ?? '') == 'Negeri' ? 'selected' : '' }}>Negeri
                                </option>
                                <option value="Swasta"
                                    {{ old('status', $sekolah->status ?? '') == 'Swasta' ? 'selected' : '' }}>Swasta
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="akreditasi" class="block text-sm font-medium text-gray-700 mb-1">Akreditasi <span
                                    class="text-red-500">*</span></label>
                            <select id="akreditasi" name="akreditasi"
                                class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                                required>
                                <option value="" disabled {{ !isset($sekolah) ? 'selected' : '' }}>Pilih Akreditasi
                                </option>
                                <option value="A"
                                    {{ old('akreditasi', $sekolah->akreditasi ?? '') == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B"
                                    {{ old('akreditasi', $sekolah->akreditasi ?? '') == 'B' ? 'selected' : '' }}>B</option>
                                <option value="C"
                                    {{ old('akreditasi', $sekolah->akreditasi ?? '') == 'C' ? 'selected' : '' }}>C</option>
                                <option value="Tidak Terakreditasi"
                                    {{ old('akreditasi', $sekolah->akreditasi ?? '') == 'Tidak Terakreditasi' ? 'selected' : '' }}>
                                    Tidak Terakreditasi</option>
                            </select>
                        </div>

                        <div x-data="{ phone: '{{ old('no_telepon', $sekolah->no_telepon ?? '') }}' }">
                            <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-1">No. Telepon <span
                                    class="text-red-500">*</span></label>
                            <div class="flex rounded-lg shadow-sm">
                                <span
                                    class="inline-flex items-center px-3.5 bg-gray-100 border border-r-0 border-gray-300 text-gray-600 text-sm rounded-l-lg">+62</span>
                                <input type="tel" id="no_telepon" name="no_telepon" x-model="phone"
                                    @input="phone = phone.replace(/^0+/, '')" placeholder="81369904725"
                                    class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-r-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                                    required>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span
                                    class="text-red-500">*</span></label>
                            <input type="email" id="email" name="email"
                                value="{{ old('email', $sekolah->email ?? '') }}" placeholder="Masukkan Email"
                                class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                                required>
                        </div>

                        <div>
                            <label for="social_media" class="block text-sm font-medium text-gray-700 mb-1">Website / Sosmed
                                Sekolah <span class="text-red-500">*</span></label>
                            <input type="url" id="social_media" name="social_media"
                                value="{{ old('social_media', $sekolah->social_media ?? '') }}"
                                placeholder="https://contoh.com"
                                class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                                required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="siswa_laki" class="block text-sm font-medium text-gray-700 mb-1">Siswa Laki-Laki
                                <span class="text-red-500">*</span></label>
                            <input type="number" id="siswa_laki" name="siswa_laki" min="0"
                                value="{{ old('siswa_laki', $sekolah->siswa_laki ?? 0) }}"
                                class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                                required>
                        </div>

                        <div>
                            <label for="siswa_perempuan" class="block text-sm font-medium text-gray-700 mb-1">Siswa
                                Perempuan <span class="text-red-500">*</span></label>
                            <input type="number" id="siswa_perempuan" name="siswa_perempuan" min="0"
                                value="{{ old('siswa_perempuan', $sekolah->siswa_perempuan ?? 0) }}"
                                class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                                required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="total_siswa" class="block text-sm font-medium text-gray-700 mb-1">Total Peserta
                                Didik</label>
                            <input type="number" id="total_siswa" name="total_siswa"
                                value="{{ old('total_siswa', $sekolah->total_siswa ?? 0) }}"
                                class="w-full px-3.5 py-2.5 bg-gray-100 border border-gray-300 rounded-lg text-sm text-gray-500 cursor-not-allowed"
                                readonly>
                            <span class="text-xs text-gray-400 mt-1 block">Terhitung otomatis dari jumlah siswa laki-laki +
                                perempuan</span>
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: Detail Lokasi -->
                <div class="space-y-4" x-data="locationDropdowns()" x-init="init()">
                    <h3 class="text-lg font-semibold text-gray-700 border-b border-gray-100 pb-2">Detail Lokasi</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="provinsi" class="block text-sm font-medium text-gray-700 mb-1">Provinsi <span
                                    class="text-red-500">*</span></label>
                            <select id="provinsi" name="provinsi" x-model="selectedProvinsi"
                                @change="onProvinsiChange()"
                                class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                                required>
                                <option value="" disabled>Pilih Provinsi</option>
                                <template x-for="prov in provinces" :key="prov">
                                    <option :value="prov" x-text="prov"
                                        :selected="prov === '{{ old('provinsi', $sekolah->provinsi ?? '') }}'"></option>
                                </template>
                            </select>
                        </div>

                        <div>
                            <label for="kabupaten_kota" class="block text-sm font-medium text-gray-700 mb-1">Kabupaten /
                                Kota <span class="text-red-500">*</span></label>
                            <select id="kabupaten_kota" name="kabupaten_kota" x-model="selectedKabupaten"
                                @change="onKabupatenChange()" :disabled="!selectedProvinsi"
                                class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition disabled:bg-gray-100"
                                required>
                                <option value="" disabled>Pilih Kabupaten / Kota</option>
                                <template x-for="kab in kabupatens" :key="kab">
                                    <option :value="kab" x-text="kab"
                                        :selected="kab === '{{ old('kabupaten_kota', $sekolah->kabupaten_kota ?? '') }}'">
                                    </option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="kecamatan" class="block text-sm font-medium text-gray-700 mb-1">Kecamatan <span
                                    class="text-red-500">*</span></label>
                            <select id="kecamatan" name="kecamatan" x-model="selectedKecamatan"
                                :disabled="!selectedKabupaten"
                                class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition disabled:bg-gray-100"
                                required>
                                <option value="" disabled>Pilih Kecamatan</option>
                                <template x-for="kec in kecamatans" :key="kec">
                                    <option :value="kec" x-text="kec"
                                        :selected="kec === '{{ old('kecamatan', $sekolah->kecamatan ?? '') }}'"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat Sekolah <span
                                class="text-red-500">*</span></label>
                        <textarea id="alamat" name="alamat" rows="3" placeholder="Masukkan Alamat Lengkap Sekolah"
                            class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                            required>{{ old('alamat', $sekolah->alamat ?? '') }}</textarea>
                        <span class="text-xs text-amber-600 mt-1 block">Tulis alamat lengkap termasuk nama jalan, RT/RW,
                            Kode Pos, dll</span>
                    </div>

                    <!-- Peta Interaktif Leaflet -->
                    <div class="pt-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Titik Koordinat Peta <span
                                class="text-red-500">*</span></label>
                        <p class="text-xs text-gray-500 mb-3">Silakan cari lokasi sekolah Anda, perbesar (zoom in), lalu
                            <strong class="text-gray-700">klik pada titik lokasi bangunan sekolah</strong> untuk mengisi
                            koordinat secara otomatis.
                        </p>

                        {{-- Container Peta dengan Inline Style Tinggi Pasti --}}
                        <div id="map"
                            style="width: 100%; height: 380px; border-radius: 0.5rem; border: 1px solid #d1d5db; position: relative; z-index: 10;">
                        </div>
                    </div>

                    <!-- Input Koordinat Terisi Otomatis -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                        <div>
                            <label for="longitude" class="block text-sm font-medium text-gray-700 mb-1">Longitude <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="longitude" name="longitude"
                                value="{{ old('longitude', $sekolah->longitude ?? '') }}"
                                placeholder="Klik pada peta untuk mengisi otomatis"
                                class="w-full px-3.5 py-2.5 bg-gray-100 border border-gray-300 rounded-lg text-sm text-gray-500 cursor-not-allowed"
                                readonly required>
                        </div>
                        <div>
                            <label for="latitude" class="block text-sm font-medium text-gray-700 mb-1">Latitude <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="latitude" name="latitude"
                                value="{{ old('latitude', $sekolah->latitude ?? '') }}"
                                placeholder="Klik pada peta untuk mengisi otomatis"
                                class="w-full px-3.5 py-2.5 bg-gray-100 border border-gray-300 rounded-lg text-sm text-gray-500 cursor-not-allowed"
                                readonly required>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                    <button type="button" onclick="window.history.back()"
                        class="px-5 py-2.5 rounded-lg border border-gray-300 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-lg bg-teal-700 hover:bg-teal-800 text-white text-sm font-medium flex items-center gap-2 shadow-sm transition">
                        <span>{{ isset($sekolah) ? 'Update Data' : 'Submit' }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function locationDropdowns() {
            return {
                allRows: [],
                provinces: [],
                kabupatens: [],
                kecamatans: [],
                selectedProvinsi: '{{ old('provinsi', $sekolah->provinsi ?? '') }}',
                selectedKabupaten: '{{ old('kabupaten_kota', $sekolah->kabupaten_kota ?? '') }}',
                selectedKecamatan: '{{ old('kecamatan', $sekolah->kecamatan ?? '') }}',

                async init() {
                    try {
                        const res = await fetch('/api/wilayah');
                        this.allRows = await res.json();
                    } catch (e) {
                        this.allRows = [];
                    }

                    const uniqueProvinces = [...new Set(this.allRows.map(r => r.provinsi).filter(Boolean))];
                    this.provinces = uniqueProvinces.sort((a, b) => a.localeCompare(b, 'id'));

                    if (this.selectedProvinsi) {
                        this.onProvinsiChange();
                    }
                },

                onProvinsiChange() {
                    const kabSet = [...new Set(
                        this.allRows
                        .filter(r => r.provinsi === this.selectedProvinsi && r.kabupaten_kota)
                        .map(r => r.kabupaten_kota)
                    )];
                    this.kabupatens = kabSet.sort((a, b) => a.localeCompare(b, 'id'));

                    if (!this.kabupatens.includes(this.selectedKabupaten)) {
                        this.selectedKabupaten = '';
                        this.selectedKecamatan = '';
                        this.kecamatans = [];
                    } else {
                        this.onKabupatenChange();
                    }
                },

                onKabupatenChange() {
                    const kecSet = [...new Set(
                        this.allRows
                        .filter(r => r.provinsi === this.selectedProvinsi && r.kabupaten_kota === this
                            .selectedKabupaten && r.kecamatan)
                        .map(r => r.kecamatan)
                    )];
                    this.kecamatans = kecSet.sort((a, b) => a.localeCompare(b, 'id'));

                    if (!this.kecamatans.includes(this.selectedKecamatan)) {
                        this.selectedKecamatan = '';
                    }
                }
            };
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Total Siswa Calculator
            const inputLaki = document.getElementById('siswa_laki');
            const inputPerempuan = document.getElementById('siswa_perempuan');
            const inputTotal = document.getElementById('total_siswa');

            function hitungTotal() {
                const laki = parseInt(inputLaki.value) || 0;
                const perempuan = parseInt(inputPerempuan.value) || 0;
                inputTotal.value = laki + perempuan;
            }

            if (inputLaki && inputPerempuan) {
                inputLaki.addEventListener('input', hitungTotal);
                inputPerempuan.addEventListener('input', hitungTotal);
            }

            // Init Peta Leaflet
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');

            const defaultLat = parseFloat(latInput.value) || -0.789275;
            const defaultLng = parseFloat(lngInput.value) || 113.921327;
            const defaultZoom = latInput.value ? 16 : 5;

            const map = L.map('map').setView([defaultLat, defaultLng], defaultZoom);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            let marker;

            if (latInput.value && lngInput.value) {
                marker = L.marker([defaultLat, defaultLng]).addTo(map);
            }

            map.on('click', function(e) {
                const lat = e.latlng.lat.toFixed(7);
                const lng = e.latlng.lng.toFixed(7);

                latInput.value = lat;
                lngInput.value = lng;

                if (marker) {
                    marker.setLatLng(e.latlng);
                } else {
                    marker = L.marker(e.latlng).addTo(map);
                }
            });

            // Trigger resize supaya peta tidak blank
            setTimeout(function() {
                map.invalidateSize();
            }, 400);
        });
    </script>
@endsection
