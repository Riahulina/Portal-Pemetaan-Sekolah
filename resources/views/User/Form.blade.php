@extends('layouts.app') {{-- Menggunakan layout utama yang sudah terpasang Navbar Utama --}}

@section('title', 'Form Pendaftaran - SatuPeta')

@section('content')
    <div class="dashboard-layout">

        <!-- 1. PANGGIL SIDEBAR KIRI (FILE TERPISAH) -->
        @include('partials.sidebar')

        <!-- 2. KONTEN SEBELAH KANAN -->
        <main class="main-content">


            <div class="form-container">
                <!-- Header Form -->
                <div class="form-header">
                    <h2>Daftar Sekolah</h2>
                    <p>Lengkapi informasi sekolah anda dengan benar. Data yang diisi akan ditinjau oleh admin sebelum
                        ditampilkan.
                    </p>
                </div>

                <form action="#" method="POST" class="main-form">
                    @csrf

                    <!-- SECTION 1: Informasi Utama -->
                    <div class="form-section">
                        <h3 class="section-title">Informasi Utama</h3>
                        <div class="form-grid col-2">
                            <div class="form-group">
                                <label for="npsn">NPSN <span class="required">*</span></label>
                                <input type="text" id="npsn" name="npsn" placeholder="Masukkan NPSN" required>
                                <span class="input-helper text-danger">NPSN Harus unik dan tidak boleh sama</span>
                            </div>
                            <div class="form-group">
                                <label for="nama_sekolah">Nama Sekolah <span class="required">*</span></label>
                                <input type="text" id="nama_sekolah" name="nama_sekolah"
                                    placeholder="Masukkan Nama Sekolah" required>
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
                                        <option value="" disabled selected>Pilih Jenjang</option>
                                        <option value="SD">SD</option>
                                        <option value="SMP">SMP</option>
                                        <option value="SMA">SMA</option>
                                        <option value="SMK">SMK</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="status">Status <span class="required">*</span></label>
                                <div class="select-wrapper">
                                    <select id="status" name="status" required>
                                        <option value="" disabled selected>Pilih Status</option>
                                        <option value="Negeri">Negeri</option>
                                        <option value="Swasta">Swasta</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-grid col-2">
                            <div class="form-group">
                                <label for="akreditasi">Akreditasi <span class="required">*</span></label>
                                <div class="select-wrapper">
                                    <select id="akreditasi" name="akreditasi" required>
                                        <option value="" disabled selected>Pilih Akreditasi</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                        <option value="Tidak Terakreditasi">Tidak Terakreditasi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="no_telepon">No. Telepon <span class="required">*</span></label>
                                <input type="tel" id="no_telepon" name="no_telepon" placeholder="Contoh : 81369904725"
                                    required>
                            </div>
                        </div>

                        <div class="form-grid col-2">
                            <div class="form-group">
                                <label for="email">Email <span class="required">*</span></label>
                                <input type="email" id="email" name="email" placeholder="Masukkan Email" required>
                            </div>
                            <div class="form-group">
                                <label for="website">Website Sekolah <span class="required">*</span></label>
                                <input type="url" id="website" name="website" placeholder="Masukkan Website Sekolah"
                                    required>
                            </div>
                        </div>

                        <div class="form-grid col-2">
                            <div class="form-group">
                                <label for="jumlah_siswa">Jumlah Siswa <span class="required">*</span></label>
                                <input type="number" id="jumlah_siswa" name="jumlah_siswa"
                                    placeholder="Masukkan Jumlah Siswa" required>
                            </div>
                            <div class="form-group">
                                <label for="jumlah_siswi">Jumlah Siswi <span class="required">*</span></label>
                                <input type="number" id="jumlah_siswi" name="jumlah_siswi"
                                    placeholder="Masukkan Jumlah Siswi" required>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 3: Detail Lokasi -->
                    <div class="form-section">
                        <h3 class="section-title">Detail Lokasi</h3>

                        <div class="form-grid col-2">
                            <div class="form-group">
                                <label for="provinsi">Provinsi <span class="required">*</span></label>
                                <input type="text" id="provinsi" name="provinsi" placeholder="Masukkan Provinsi"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="kabupaten">Kabupaten / Kota <span class="required">*</span></label>
                                <input type="text" id="kabupaten" name="kabupaten"
                                    placeholder="Masukkan Kabupaten / Kota" required>
                            </div>
                        </div>

                        <div class="form-grid col-2">
                            <div class="form-group">
                                <label for="kecamatan">Kecamatan <span class="required">*</span></label>
                                <input type="text" id="kecamatan" name="kecamatan" placeholder="Masukkan Kecamatan"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="longitude">Longitude <span class="required">*</span></label>
                                <input type="text" id="longitude" name="longitude"
                                    placeholder="Longitude: Masukkan longitude (Contoh: 106.8456)" required>
                                <span class="input-helper text-warning">Longitude Harus Format Standar Google Maps</span>
                            </div>
                        </div>

                        <div class="form-grid col-2">
                            <div class="form-group">
                                <label for="latitude">Latitude <span class="required">*</span></label>
                                <input type="text" id="latitude" name="latitude"
                                    placeholder="Latitude: Masukkan latitude (Contoh: -6.2088)" required>
                                <span class="input-helper text-warning">Latitude Harus Format Standar Google Maps</span>
                            </div>
                            <!-- Div kosong untuk menjaga grid alignment agar latitude tetap di sebelah kiri jika tidak ada pasangan kolomnya -->
                            <div></div>
                        </div>

                        <div class="form-group full-width">
                            <label for="alamat">Alamat Sekolah <span class="required">*</span></label>
                            <textarea id="alamat" name="alamat" rows="4" placeholder="Masukkan Alamat Lengkap Sekolah" required></textarea>
                            <span class="input-helper text-warning">Tulis alamat lengkap termasuk nama jalan, RT/RW, Kode
                                Pos,
                                dll</span>
                        </div>
                    </div>

                    <!-- Form Actions (Cancel & Submit Buttons) -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-cancel">Cancel</button>
                        <button type="submit" class="btn btn-submit">
                            Submit
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
