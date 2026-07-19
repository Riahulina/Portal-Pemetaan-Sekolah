@extends('layouts.app') {{-- Menggunakan layout utama yang sudah terpasang Navbar Utama --}}

@section('title', isset($sekolah) ? 'Edit Pendaftaran - SatuPeta' : 'Form Pendaftaran - SatuPeta')

{{-- Menyisipkan CSS Leaflet di bagian atas --}}
@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endsection

@section('content')
    <div class="dashboard-layout">

        <!-- 1. PANGGIL SIDEBAR KIRI (FILE TERPISAH) -->
        @include('partials.sidebar')

        <!-- 2. KONTEN SEBELAH KANAN -->
        <main class="main-content">

            <div class="form-container">
                <!-- Header Form -->
                <div class="form-header">
                    <h2>{{ isset($sekolah) ? 'Edit Data Sekolah' : 'Daftar Sekolah' }}</h2>
                    <p>{{ isset($sekolah) ? 'Perbarui informasi sekolah Anda dengan benar. Data hasil edit akan ditinjau kembali oleh admin.' : 'Lengkapi informasi sekolah anda dengan benar. Data yang diisi akan ditinjau oleh admin sebelum ditampilkan.' }}
                    </p>
                </div>

                <!-- FORM ACTION DINAMIS (Bisa POST untuk Store, atau PUT untuk Update) -->
                <form action="{{ isset($sekolah) ? route('sekolah.update', $sekolah->id) : route('sekolah.store') }}"
                    method="POST" class="main-form">
                    @csrf

                    {{-- Wajib ditambahkan jika dalam mode Edit/Update --}}
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
                                        style="color: #ef4444; font-size: 12px; display: block; mt-1;">{{ $message }}</span>
                                @else
                                    <span class="input-helper text-danger" style="font-size: 12px; display: block; mt-1;">NPSN
                                        Harus unik dan tidak boleh sama</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="nama_sekolah">Nama Sekolah <span class="required">*</span></label>
                                <input type="text" id="nama_sekolah" name="nama_sekolah"
                                    value="{{ old('nama_sekolah', $sekolah->nama_sekolah ?? '') }}"
                                    placeholder="Masukkan Nama Sekolah" required>
                                @error('nama_sekolah')
                                    <span class="input-helper text-danger"
                                        style="color: #ef4444; font-size: 12px;">{{ $message }}</span>
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
                                        <option value="" disabled {{ !isset($sekolah) ? 'selected' : '' }}>Pilih
                                            Jenjang</option>
                                        <option value="SD"
                                            {{ old('jenjang', $sekolah->jenjang ?? '') == 'SD' ? 'selected' : '' }}>SD
                                        </option>
                                        <option value="SMP"
                                            {{ old('jenjang', $sekolah->jenjang ?? '') == 'SMP' ? 'selected' : '' }}>SMP
                                        </option>
                                        <option value="SMA"
                                            {{ old('jenjang', $sekolah->jenjang ?? '') == 'SMA' ? 'selected' : '' }}>SMA
                                        </option>
                                        <option value="SMK"
                                            {{ old('jenjang', $sekolah->jenjang ?? '') == 'SMK' ? 'selected' : '' }}>SMK
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="status">Status <span class="required">*</span></label>
                                <div class="select-wrapper">
                                    <select id="status" name="status" required>
                                        <option value="" disabled {{ !isset($sekolah) ? 'selected' : '' }}>Pilih
                                            Status</option>
                                        <option value="Negeri"
                                            {{ old('status', $sekolah->status ?? '') == 'Negeri' ? 'selected' : '' }}>
                                            Negeri</option>
                                        <option value="Swasta"
                                            {{ old('status', $sekolah->status ?? '') == 'Swasta' ? 'selected' : '' }}>
                                            Swasta</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-grid col-2">
                            <div class="form-group">
                                <label for="akreditasi">Akreditasi <span class="required">*</span></label>
                                <div class="select-wrapper">
                                    <select id="akreditasi" name="akreditasi" required>
                                        <option value="" disabled {{ !isset($sekolah) ? 'selected' : '' }}>Pilih
                                            Akreditasi</option>
                                        <option value="A"
                                            {{ old('akreditasi', $sekolah->akreditasi ?? '') == 'A' ? 'selected' : '' }}>A
                                        </option>
                                        <option value="B"
                                            {{ old('akreditasi', $sekolah->akreditasi ?? '') == 'B' ? 'selected' : '' }}>B
                                        </option>
                                        <option value="C"
                                            {{ old('akreditasi', $sekolah->akreditasi ?? '') == 'C' ? 'selected' : '' }}>C
                                        </option>
                                        <option value="Tidak Terakreditasi"
                                            {{ old('akreditasi', $sekolah->akreditasi ?? '') == 'Tidak Terakreditasi' ? 'selected' : '' }}>
                                            Tidak Terakreditasi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="no_telepon">No. Telepon <span class="required">*</span></label>
                                <input type="tel" id="no_telepon" name="no_telepon"
                                    value="{{ old('no_telepon', $sekolah->no_telepon ?? '') }}"
                                    placeholder="Contoh : 81369904725" required>
                            </div>
                        </div>

                        <div class="form-grid col-2">
                            <div class="form-group">
                                <label for="email">Email <span class="required">*</span></label>
                                <input type="email" id="email" name="email"
                                    value="{{ old('email', $sekolah->email ?? '') }}" placeholder="Masukkan Email"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="social_media">Website / Sosmed Sekolah <span class="required">*</span></label>
                                <input type="text" id="social_media" name="social_media"
                                    value="{{ old('social_media', $sekolah->social_media ?? '') }}"
                                    placeholder="Masukkan URL Website atau Media Sosial" required>
                            </div>
                        </div>

                        <!-- Input Siswa Laki & Perempuan berdampingan -->
                        <div class="form-grid col-2">
                            <div class="form-group">
                                <label for="siswa_laki">Siswa Laki-Laki <span class="required">*</span></label>
                                <input type="number" id="siswa_laki" name="siswa_laki" min="0"
                                    value="{{ old('siswa_laki', $sekolah->siswa_laki ?? 0) }}"
                                    placeholder="Jumlah siswa laki-laki" required>
                                @error('siswa_laki')
                                    <span class="text-danger" style="font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="siswa_perempuan">Siswa Perempuan <span class="required">*</span></label>
                                <input type="number" id="siswa_perempuan" name="siswa_perempuan" min="0"
                                    value="{{ old('siswa_perempuan', $sekolah->siswa_perempuan ?? 0) }}"
                                    placeholder="Jumlah siswa perempuan" required>
                                @error('siswa_perempuan')
                                    <span class="text-danger" style="font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Menampilkan Total Siswa (Otomatis terjumlahkan & Readonly) -->
                        <div class="form-grid col-2">
                            <div class="form-group">
                                <label for="total_siswa">Total Peserta Didik</label>
                                <input type="number" id="total_siswa" name="total_siswa"
                                    value="{{ old('total_siswa', $sekolah->total_siswa ?? 0) }}"
                                    style="background-color: #f3f4f6; cursor: not-allowed;" readonly>
                                <span class="input-helper text-muted"
                                    style="font-size: 12px; display: block; margin-top: 4px;">Terhitung otomatis dari
                                    jumlah siswa laki-laki + perempuan</span>
                            </div>
                            <div></div>
                        </div>
                    </div>

                    <!-- SECTION 3: Detail Lokasi -->
                    <div class="form-section">
                        <h3 class="section-title">Detail Lokasi</h3>

                        <div class="form-grid col-2">
                            <div class="form-group">
                                <label for="provinsi">Provinsi <span class="required">*</span></label>
                                <input type="text" id="provinsi" name="provinsi"
                                    value="{{ old('provinsi', $sekolah->provinsi ?? '') }}"
                                    placeholder="Masukkan Provinsi" required>
                            </div>
                            <div class="form-group">
                                <label for="kabupaten_kota">Kabupaten / Kota <span class="required">*</span></label>
                                <input type="text" id="kabupaten_kota" name="kabupaten_kota"
                                    value="{{ old('kabupaten_kota', $sekolah->kabupaten_kota ?? '') }}"
                                    placeholder="Masukkan Kabupaten / Kota" required>
                            </div>
                        </div>

                        <div class="form-grid col-2">
                            <div class="form-group">
                                <label for="kecamatan">Kecamatan <span class="required">*</span></label>
                                <input type="text" id="kecamatan" name="kecamatan"
                                    value="{{ old('kecamatan', $sekolah->kecamatan ?? '') }}"
                                    placeholder="Masukkan Kecamatan" required>
                            </div>
                            <div></div>
                        </div>

                        <div class="form-group full-width">
                            <label for="alamat">Alamat Sekolah <span class="required">*</span></label>
                            <textarea id="alamat" name="alamat" rows="4" placeholder="Masukkan Alamat Lengkap Sekolah" required>{{ old('alamat', $sekolah->alamat ?? '') }}</textarea>
                            <span class="input-helper text-warning"
                                style="font-size: 12px; display: block; margin-top: 4px; margin-bottom: 12px;">Tulis alamat
                                lengkap termasuk nama jalan, RT/RW, Kode Pos, dll</span>
                        </div>

                        <!-- Wadah Peta Interaktif Leaflet -->
                        <div class="form-group full-width" style="margin-bottom: 20px;">
                            <label style="font-weight: 600; margin-bottom: 6px; display: block;">Titik Koordinat Peta <span
                                    class="required">*</span></label>
                            <span class="input-helper text-muted"
                                style="font-size: 12px; display: block; margin-bottom: 10px;">Silakan cari lokasi sekolah
                                Anda, perbesar (zoom in), lalu <strong>klik pada titik lokasi bangunan sekolah</strong>
                                untuk mengisi koordinat secara otomatis.</span>
                            <div id="map"
                                style="height: 350px; border-radius: 8px; border: 1px solid #d1d5db; z-index: 1;"></div>
                        </div>

                        <!-- Input Koordinat Terisi Otomatis (Readonly) -->
                        <div class="form-grid col-2">
                            <div class="form-group">
                                <label for="longitude">Longitude <span class="required">*</span></label>
                                <input type="text" id="longitude" name="longitude"
                                    value="{{ old('longitude', $sekolah->longitude ?? '') }}"
                                    placeholder="Klik pada peta untuk mengisi otomatis"
                                    style="background-color: #f3f4f6; cursor: not-allowed;" readonly required>
                                @error('longitude')
                                    <span class="text-danger" style="font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="latitude">Latitude <span class="required">*</span></label>
                                <input type="text" id="latitude" name="latitude"
                                    value="{{ old('latitude', $sekolah->latitude ?? '') }}"
                                    placeholder="Klik pada peta untuk mengisi otomatis"
                                    style="background-color: #f3f4f6; cursor: not-allowed;" readonly required>
                                @error('latitude')
                                    <span class="text-danger" style="font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions (Cancel & Submit Buttons) -->
                    <div class="form-actions"
                        style="margin-top: 30px; display: flex; gap: 15px; justify-content: flex-end;">
                        <button type="button" class="btn btn-cancel" onclick="window.history.back()"
                            style="padding: 10px 25px; border-radius: 6px; cursor: pointer;">Cancel</button>

                        <button type="submit" class="btn btn-submit"
                            style="padding: 10px 25px; background: #008080; color: #fff; border: none; border-radius: 6px; display: flex; align-items: center; gap: 8px; cursor: pointer;">
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
        document.addEventListener('DOMContentLoaded', function() {
            // ==========================================
            // LOGIKA 1: PENJUMLAHAN OTOMATIS SISWA
            // ==========================================
            const inputLaki = document.getElementById('siswa_laki');
            const inputPerempuan = document.getElementById('siswa_perempuan');
            const inputTotal = document.getElementById('total_siswa');

            function hitungTotal() {
                const laki = parseInt(inputLaki.value) || 0;
                const perempuan = parseInt(inputPerempuan.value) || 0;
                inputTotal.value = laki + perempuan;
            }

            inputLaki.addEventListener('input', hitungTotal);
            inputPerempuan.addEventListener('input', hitungTotal);


            // ==========================================
            // LOGIKA 2: PETA INTERAKTIF (LEAFLET)
            // ==========================================
            // Mengambil titik koordinat awal. Jika edit data gunakan data lama, jika baru arahkan ke koordinat default pusat Indonesia.
            const defaultLat = parseFloat(document.getElementById('latitude').value) || -0.789275;
            const defaultLng = parseFloat(document.getElementById('longitude').value) || 113.921327;
            const defaultZoom = document.getElementById('latitude').value ? 16 :
            5; // Zoom dekat jika sudah ada titik lokasi

            // Inisialisasi peta ke elemen #map
            const map = L.map('map').setView([defaultLat, defaultLng], defaultZoom);

            // Menambahkan layer peta OpenStreetMap gratisan
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            let marker;

            // Jika sedang dalam mode edit dan datanya sudah ada, pasang penanda pin di peta sejak awal
            if (document.getElementById('latitude').value && document.getElementById('longitude').value) {
                marker = L.marker([defaultLat, defaultLng]).addTo(map);
            }

            // Fungsi ketika peta diklik oleh user
            map.on('click', function(e) {
                const lat = e.latlng.lat.toFixed(
                7); // Dibatasi 7 angka di belakang koma demi presisi database
                const lng = e.latlng.lng.toFixed(7);

                // Sinkronisasi otomatis ke kolom input text form
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;

                // Memindahkan pin atau membuat pin baru jika belum ada
                if (marker) {
                    marker.setLatLng(e.latlng);
                } else {
                    marker = L.marker(e.latlng).addTo(map);
                }
            });
        });
    </script>
@endsection
