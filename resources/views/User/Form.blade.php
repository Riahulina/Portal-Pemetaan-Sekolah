@extends('layouts.app') {{-- Menggunakan layout utama yang sudah terpasang Navbar Utama --}}

@section('title', isset($sekolah) ? 'Edit Pendaftaran - SatuPeta' : 'Form Pendaftaran - SatuPeta')

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

                        <div class="form-grid col-2">
                            <div class="form-group">
                                <label for="total_siswa">Total Siswa <span class="required">*</span></label>
                                <input type="number" id="total_siswa" name="total_siswa"
                                    value="{{ old('total_siswa', $sekolah->total_siswa ?? '') }}"
                                    placeholder="Masukkan Total Siswa" required>
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
                            <div class="form-group">
                                <label for="longitude">Longitude <span class="required">*</span></label>
                                <input type="text" id="longitude" name="longitude"
                                    value="{{ old('longitude', $sekolah->longitude ?? '') }}"
                                    placeholder="Contoh: 106.8456" required>
                                <span class="input-helper text-warning"
                                    style="font-size: 12px; display: block; mt-1;">Longitude Harus Format Standar Google
                                    Maps</span>
                            </div>
                        </div>

                        <div class="form-grid col-2">
                            <div class="form-group">
                                <label for="latitude">Latitude <span class="required">*</span></label>
                                <input type="text" id="latitude" name="latitude"
                                    value="{{ old('latitude', $sekolah->latitude ?? '') }}" placeholder="Contoh: -6.2088"
                                    required>
                                <span class="input-helper text-warning"
                                    style="font-size: 12px; display: block; mt-1;">Latitude Harus Format Standar Google
                                    Maps</span>
                            </div>
                            <div></div>
                        </div>

                        <div class="form-group full-width">
                            <label for="alamat">Alamat Sekolah <span class="required">*</span></label>
                            <textarea id="alamat" name="alamat" rows="4" placeholder="Masukkan Alamat Lengkap Sekolah" required>{{ old('alamat', $sekolah->alamat ?? '') }}</textarea>
                            <span class="input-helper text-warning" style="font-size: 12px; display: block; mt-1;">Tulis
                                alamat lengkap termasuk nama jalan, RT/RW, Kode Pos, dll</span>
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
