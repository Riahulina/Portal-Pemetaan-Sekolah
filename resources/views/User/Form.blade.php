@extends('layouts.app') {{-- Menggunakan layout utama --}}

@section('title', isset($sekolah) ? 'Edit Pendaftaran - SatuPeta' : 'Form Pendaftaran - SatuPeta')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        /* ==========================================
               1. BASE & GRID LAYOUT
               ========================================== */
        .dashboard-layout {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            background-color: #f9fafb;
            width: 100%;
            box-sizing: border-box;
        }

        .form-container {
            max-width: 900px;
            margin: 0 auto;
            background: #ffffff;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .form-section {
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid #e5e7eb;
        }

        .section-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 16px;
        }

        /* Default Grid 2 Kolom untuk Desktop */
        .form-grid {
            display: grid;
            gap: 16px;
            margin-bottom: 16px;
        }

        .form-grid.col-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            width: 100%;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
        }

        .required {
            color: #ef4444;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="url"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.875rem;
            box-sizing: border-box;
            outline: none;
            transition: border-color 0.2s;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #008080;
        }

        /* ==========================================
               2. MEDIA QUERIES (RESPONSIF)
               ========================================== */

        /* Tablet (<= 768px) */
        @media (max-width: 768px) {
            .main-content {
                padding: 16px;
            }

            .form-container {
                padding: 16px;
            }

            /* Mengubah grid 2 kolom menjadi 1 kolom */
            .form-grid.col-2 {
                grid-template-columns: 1fr;
            }

            #map {
                height: 280px !important;
            }
        }

        /* Mobile (<= 480px) */
        @media (max-width: 480px) {
            .form-header h2 {
                font-size: 1.25rem;
            }

            .form-actions {
                flex-direction: column-reverse;
                gap: 10px;
            }

            .form-actions button {
                width: 100%;
                justify-content: center;
            }

            #map {
                height: 220px !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="dashboard-layout">

        <!-- 1. PANGGIL SIDEBAR KIRI -->
        @include('partials.sidebar')

        <!-- 2. KONTEN SEBELAH KANAN -->
        <main class="main-content">

            <div class="form-container">
                <!-- Header Form -->
                <div class="form-header" style="margin-bottom: 20px;">
                    <h2 style="margin: 0 0 8px 0; color: #1f2937;">
                        {{ isset($sekolah) ? 'Edit Data Sekolah' : 'Daftar Sekolah' }}</h2>
                    <p style="margin: 0; color: #6b7280; font-size: 14px;">
                        {{ isset($sekolah) ? 'Perbarui informasi sekolah Anda dengan benar. Data hasil edit akan ditinjau kembali oleh admin.' : 'Lengkapi informasi sekolah anda dengan benar. Data yang diisi akan ditinjau oleh admin sebelum ditampilkan.' }}
                    </p>
                </div>

                {{-- Alert jika ada validasi dari backend yang gagal --}}
                @if ($errors->any())
                    <div
                        style="background-color: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
                        <strong style="font-weight: 600;">Gagal Menyimpan Data!</strong>
                        <ul style="margin-top: 6px; margin-bottom: 0; padding-left: 20px; font-size: 14px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- FORM ACTION DINAMIS -->
                <form action="{{ isset($sekolah) ? route('sekolah.update', $sekolah->id) : route('sekolah.store') }}"
                    method="POST" class="main-form" id="schoolForm">
                    @csrf

                    @if (isset($sekolah))
                        @method('PUT')
                    @endif

                    <!-- SECTION 1: Informasi Utama -->
                    <div class="form-section">
                        <h3 class="section-title">Informasi Utama</h3>
                        <div class="form-grid col-2">
                            <div class="form-group">
                                <label for="npsn">NPSN <span class="required">*</span></label>
                                <input type="text" id="npsn" name="npsn"
                                    value="{{ old('npsn', $sekolah->npsn ?? '') }}" placeholder="Masukkan NPSN" required>
                                @error('npsn')
                                    <span class="input-helper text-danger"
                                        style="color: #ef4444; font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span>
                                @else
                                    <span class="input-helper text-danger"
                                        style="font-size: 12px; display: block; margin-top: 4px; color: #6b7280;">NPSN Harus
                                        unik dan tidak boleh sama</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="nama_sekolah">Nama Sekolah <span class="required">*</span></label>
                                <input type="text" id="nama_sekolah" name="nama_sekolah"
                                    value="{{ old('nama_sekolah', $sekolah->nama_sekolah ?? '') }}"
                                    placeholder="Masukkan Nama Sekolah" required>
                                @error('nama_sekolah')
                                    <span class="input-helper text-danger"
                                        style="color: #ef4444; font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2: Detail Sekolah -->
                    <div class="form-section">
                        <h3 class="section-title">Detail Sekolah</h3>

                        <div class="form-grid col-2">
                            <div class="form-group">
                                <label for="jenjang">Jenjang <span class="required">*</span></label>
                                <div class="select-wrapper">
                                    <select id="jenjang" name="jenjang" required>
                                        <option value="" disabled
                                            {{ !isset($sekolah) && !old('jenjang') ? 'selected' : '' }}>Pilih Jenjang
                                        </option>
                                        @foreach (['KB', 'TK', 'SD', 'SMP', 'SMA', 'SMK'] as $item)
                                            <option value="{{ $item }}"
                                                {{ old('jenjang', $sekolah->jenjang ?? '') == $item ? 'selected' : '' }}>
                                                {{ $item }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('jenjang')
                                    <span class="text-danger"
                                        style="color: #ef4444; font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="status">Status <span class="required">*</span></label>
                                <div class="select-wrapper">
                                    <select id="status" name="status" required>
                                        <option value="" disabled
                                            {{ !isset($sekolah) && !old('status') ? 'selected' : '' }}>Pilih Status
                                        </option>
                                        <option value="NEGERI"
                                            {{ strtoupper(old('status', $sekolah->status ?? '')) === 'NEGERI' ? 'selected' : '' }}>
                                            Negeri</option>
                                        <option value="SWASTA"
                                            {{ strtoupper(old('status', $sekolah->status ?? '')) === 'SWASTA' ? 'selected' : '' }}>
                                            Swasta</option>
                                    </select>
                                </div>
                                @error('status')
                                    <span class="text-danger"
                                        style="color: #ef4444; font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-grid col-2">
                            <div class="form-group">
                                <label for="akreditasi">Akreditasi <span class="required">*</span></label>
                                <div class="select-wrapper">
                                    <select id="akreditasi" name="akreditasi" required>
                                        <option value="" disabled
                                            {{ !isset($sekolah) && !old('akreditasi') ? 'selected' : '' }}>Pilih Akreditasi
                                        </option>
                                        @foreach (['A', 'B', 'C', 'Tidak Terakreditasi'] as $akred)
                                            <option value="{{ $akred }}"
                                                {{ old('akreditasi', $sekolah->akreditasi ?? '') == $akred ? 'selected' : '' }}>
                                                {{ $akred }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('akreditasi')
                                    <span class="text-danger"
                                        style="color: #ef4444; font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group" x-data="{ phone: '{{ old('no_telepon', $sekolah->no_telepon ?? '') }}' }">
                                <label for="no_telepon">No. Telepon <span class="required">*</span></label>
                                <div style="display: flex; align-items: center; gap: 0; width: 100%;">
                                    <span
                                        style="padding: 10px 12px; background: #f3f4f6; border: 1px solid #d1d5db; border-right: none; border-radius: 6px 0 0 6px; font-size: 14px; color: #374151; white-space: nowrap;">+62</span>
                                    <input type="tel" id="no_telepon" name="no_telepon" x-model="phone"
                                        @input="phone = phone.replace(/^0+/, '')"
                                        style="border-radius: 0 6px 6px 0; flex: 1;" placeholder="81369904725" required>
                                </div>
                                @error('no_telepon')
                                    <span class="text-danger"
                                        style="color: #ef4444; font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-grid col-2">
                            <div class="form-group">
                                <label for="email">Email <span class="required">*</span></label>
                                <input type="email" id="email" name="email"
                                    value="{{ old('email', $sekolah->email ?? '') }}" placeholder="Masukkan Email"
                                    required>
                                @error('email')
                                    <span class="text-danger"
                                        style="color: #ef4444; font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="social_media">Website / Sosmed Sekolah <span class="required">*</span></label>
                                <input type="url" id="social_media" name="social_media"
                                    value="{{ old('social_media', $sekolah->social_media ?? '') }}"
                                    placeholder="https://contoh.com" required>
                                @error('social_media')
                                    <span class="text-danger"
                                        style="color: #ef4444; font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-grid col-2">
                            <div class="form-group">
                                <label for="siswa_laki">Siswa <span class="required">*</span></label>
                                <input type="number" id="siswa_laki" name="siswa_laki" min="0"
                                    value="{{ old('siswa_laki', $sekolah->siswa_laki ?? 0) }}"
                                    placeholder="Jumlah siswa laki-laki" required>
                                @error('siswa_laki')
                                    <span class="text-danger"
                                        style="color: #ef4444; font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="siswa_perempuan">Siswi <span class="required">*</span></label>
                                <input type="number" id="siswa_perempuan" name="siswa_perempuan" min="0"
                                    value="{{ old('siswa_perempuan', $sekolah->siswa_perempuan ?? 0) }}"
                                    placeholder="Jumlah siswa perempuan" required>
                                @error('siswa_perempuan')
                                    <span class="text-danger"
                                        style="color: #ef4444; font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-grid col-2">
                            <div class="form-group">
                                <label for="total_siswa">Total Peserta Didik</label>
                                <input type="number" id="total_siswa" name="total_siswa"
                                    value="{{ old('total_siswa', $sekolah->total_siswa ?? 0) }}"
                                    style="background-color: #f3f4f6; cursor: not-allowed;" readonly>
                                <span class="input-helper text-muted"
                                    style="font-size: 12px; display: block; margin-top: 4px; color: #6b7280;">Terhitung
                                    otomatis dari jumlah siswa laki-laki + perempuan</span>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 3: Detail Lokasi -->
                    <div class="form-section" x-data="locationDropdowns()" x-init="init()">
                        <h3 class="section-title">Detail Lokasi</h3>

                        <div class="form-grid col-2">
                            <div class="form-group">
                                <label for="provinsi">Provinsi <span class="required">*</span></label>
                                <div class="select-wrapper">
                                    <select id="provinsi" name="provinsi" required x-model="selectedProvinsi"
                                        @change="onProvinsiChange()">
                                        <option value="" disabled>Pilih Provinsi</option>
                                        <template x-for="prov in provinces" :key="prov">
                                            <option :value="prov" x-text="prov"
                                                :selected="prov === '{{ old('provinsi', $sekolah->provinsi ?? '') }}'">
                                            </option>
                                        </template>
                                    </select>
                                </div>
                                @error('provinsi')
                                    <span class="text-danger"
                                        style="color: #ef4444; font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="kabupaten_kota">Kabupaten / Kota <span class="required">*</span></label>
                                <div class="select-wrapper">
                                    <select id="kabupaten_kota" name="kabupaten_kota" required
                                        x-model="selectedKabupaten" @change="onKabupatenChange()"
                                        :disabled="!selectedProvinsi">
                                        <option value="" disabled>Pilih Kabupaten / Kota</option>
                                        <template x-for="kab in kabupatens" :key="kab">
                                            <option :value="kab" x-text="kab"
                                                :selected="kab === '{{ old('kabupaten_kota', $sekolah->kabupaten_kota ?? '') }}'">
                                            </option>
                                        </template>
                                    </select>
                                </div>
                                @error('kabupaten_kota')
                                    <span class="text-danger"
                                        style="color: #ef4444; font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-grid col-2">
                            <div class="form-group">
                                <label for="kecamatan">Kecamatan <span class="required">*</span></label>
                                <div class="select-wrapper">
                                    <select id="kecamatan" name="kecamatan" required x-model="selectedKecamatan"
                                        :disabled="!selectedKabupaten">
                                        <option value="" disabled>Pilih Kecamatan</option>
                                        <template x-for="kec in kecamatans" :key="kec">
                                            <option :value="kec" x-text="kec"
                                                :selected="kec === '{{ old('kecamatan', $sekolah->kecamatan ?? '') }}'">
                                            </option>
                                        </template>
                                    </select>
                                </div>
                                @error('kecamatan')
                                    <span class="text-danger"
                                        style="color: #ef4444; font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group full-width" style="margin-bottom: 16px;">
                            <label for="alamat">Alamat Sekolah <span class="required">*</span></label>
                            <textarea id="alamat" name="alamat" rows="3" placeholder="Masukkan Alamat Lengkap Sekolah" required>{{ old('alamat', $sekolah->alamat ?? '') }}</textarea>
                            <span class="input-helper"
                                style="font-size: 12px; display: block; margin-top: 4px; color: #d97706;">Tulis alamat
                                lengkap termasuk nama jalan, RT/RW, Kode Pos, dll.</span>
                            @error('alamat')
                                <span class="text-danger" style="color: #ef4444; font-size: 12px;">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Wadah Peta Interaktif Leaflet -->
                        <div class="form-group full-width" style="margin-bottom: 20px;">
                            <label style="font-weight: 600;">Titik Koordinat Peta <span class="required">*</span></label>
                            <span class="input-helper"
                                style="font-size: 12px; display: block; margin-bottom: 8px; color: #6b7280;">Silakan cari
                                lokasi sekolah Anda, perbesar (zoom in), lalu klik pada titik lokasi untuk mengisi koordinat
                                otomatis.</span>
                            <div id="map"
                                style="height: 350px; border-radius: 8px; border: 1px solid #d1d5db; z-index: 1;"></div>
                        </div>

                        <!-- Input Koordinat Terisi Otomatis (Readonly) -->
                        <div class="form-grid col-2">
                            <div class="form-group">
                                <label for="longitude">Longitude <span class="required">*</span></label>
                                <input type="text" id="longitude" name="longitude"
                                    value="{{ old('longitude', $sekolah->longitude ?? '') }}"
                                    placeholder="Klik pada peta" style="background-color: #f3f4f6; cursor: not-allowed;"
                                    readonly required>
                                @error('longitude')
                                    <span class="text-danger"
                                        style="color: #ef4444; font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="latitude">Latitude <span class="required">*</span></label>
                                <input type="text" id="latitude" name="latitude"
                                    value="{{ old('latitude', $sekolah->latitude ?? '') }}" placeholder="Klik pada peta"
                                    style="background-color: #f3f4f6; cursor: not-allowed;" readonly required>
                                @error('latitude')
                                    <span class="text-danger"
                                        style="color: #ef4444; font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions"
                        style="margin-top: 30px; display: flex; gap: 15px; justify-content: flex-end;">
                        <button type="button" class="btn btn-cancel" onclick="window.history.back()"
                            style="padding: 10px 20px; border-radius: 6px; border: 1px solid #d1d5db; background: #fff; color: #374151; cursor: pointer;">Batal</button>

                        <button type="submit" class="btn btn-submit"
                            style="padding: 10px 20px; background: #008080; color: #fff; border: none; border-radius: 6px; display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            {{ isset($sekolah) ? 'Update Data' : 'Submit' }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

        </main>
    </div>
@endsection

@section('scripts')
    {{-- Memanggil JS Leaflet --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

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
                        this.onProvinsiChange(true);
                    }
                },

                onProvinsiChange(isInitial = false) {
                    if (!isInitial) {
                        this.selectedKabupaten = '';
                        this.selectedKecamatan = '';
                    }
                    const kabSet = [...new Set(
                        this.allRows
                        .filter(r => r.provinsi === this.selectedProvinsi && r.kabupaten_kota)
                        .map(r => r.kabupaten_kota)
                    )];
                    this.kabupatens = kabSet.sort((a, b) => a.localeCompare(b, 'id'));
                    this.kecamatans = [];

                    if (this.selectedKabupaten) {
                        this.onKabupatenChange(isInitial);
                    }
                },

                onKabupatenChange(isInitial = false) {
                    if (!isInitial) {
                        this.selectedKecamatan = '';
                    }
                    const kecSet = [...new Set(
                        this.allRows
                        .filter(r => r.provinsi === this.selectedProvinsi && r.kabupaten_kota === this
                            .selectedKabupaten && r.kecamatan)
                        .map(r => r.kecamatan)
                    )];
                    this.kecamatans = kecSet.sort((a, b) => a.localeCompare(b, 'id'));
                }
            };
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // LOGIKA 1: PENJUMLAHAN OTOMATIS SISWA
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

            // LOGIKA 2: PETA INTERAKTIF (LEAFLET)
            const defaultLat = parseFloat(document.getElementById('latitude').value) || -0.789275;
            const defaultLng = parseFloat(document.getElementById('longitude').value) || 113.921327;
            const defaultZoom = document.getElementById('latitude').value ? 16 : 5;

            const map = L.map('map').setView([defaultLat, defaultLng], defaultZoom);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // Inisialisasi ulang render map agar ukuran terhitung pas jika container berubah
            setTimeout(() => {
                map.invalidateSize();
            }, 300);

            let marker;

            if (document.getElementById('latitude').value && document.getElementById('longitude').value) {
                marker = L.marker([defaultLat, defaultLng]).addTo(map);
            }

            map.on('click', function(e) {
                const lat = e.latlng.lat.toFixed(7);
                const lng = e.latlng.lng.toFixed(7);

                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;

                if (marker) {
                    marker.setLatLng(e.latlng);
                } else {
                    marker = L.marker(e.latlng).addTo(map);
                }
            });

            // Peringatan jika belum menentukan titik peta
            document.getElementById('schoolForm').addEventListener('submit', function(e) {
                const lat = document.getElementById('latitude').value;
                const lng = document.getElementById('longitude').value;

                if (!lat || !lng) {
                    e.preventDefault();
                    alert('Harap tentukan titik lokasi sekolah pada Peta terlebih dahulu!');
                    document.getElementById('map').scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
@endsection
