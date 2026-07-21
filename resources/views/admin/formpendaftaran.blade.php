@extends('layouts.admin')

@section('title', isset($sekolah) ? 'Edit Pendaftaran' : 'Form Pendaftaran')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/StyleForm.css') }}">
    <style>
        .form-container {
            max-width: 100%;
            margin: 0;
        }
        #map,
        #map .leaflet-container {
            position: relative !important;
            overflow: hidden;
        }
    </style>
@endsection

@section('content')
    <div class="form-container">
        <div class="form-header">
            <h2>{{ isset($sekolah) ? 'Edit Data Sekolah' : 'Daftar Sekolah' }}</h2>
            <p>{{ isset($sekolah) ? 'Perbarui informasi sekolah Anda dengan benar. Data hasil edit akan ditinjau kembali oleh admin.' : 'Lengkapi informasi sekolah anda dengan benar. Data yang diisi akan ditinjau oleh admin sebelum ditampilkan.' }}</p>
        </div>

        <form action="{{ route('admin.formpendaftaran.store') }}" method="POST" class="main-form">
            @csrf

            <!-- SECTION 1: Informasi Utama -->
            <div class="form-section">
                <h3 class="section-title">Informasi Utama</h3>
                <div class="form-grid col-2">
                    <div class="form-group">
                        <label for="npsn">NPSN <span class="required">*</span></label>
                        <input type="text" id="npsn" name="npsn"
                            value="{{ old('npsn', $sekolah->npsn ?? '') }}" placeholder="Masukkan NPSN" required>
                        @error('npsn')
                            <span class="input-helper text-danger" style="font-size: 12px;">{{ $message }}</span>
                        @else
                            <span class="input-helper text-danger" style="font-size: 12px;">NPSN Harus unik dan tidak boleh sama</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="nama_sekolah">Nama Sekolah <span class="required">*</span></label>
                        <input type="text" id="nama_sekolah" name="nama_sekolah"
                            value="{{ old('nama_sekolah', $sekolah->nama_sekolah ?? '') }}"
                            placeholder="Masukkan Nama Sekolah" required>
                        @error('nama_sekolah')
                            <span class="input-helper text-danger" style="font-size: 12px;">{{ $message }}</span>
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
                                <option value="" disabled {{ !isset($sekolah) ? 'selected' : '' }}>Pilih Jenjang</option>
                                <option value="KB" {{ old('jenjang', $sekolah->jenjang ?? '') == 'KB' ? 'selected' : '' }}>KB</option>
                                <option value="TK" {{ old('jenjang', $sekolah->jenjang ?? '') == 'TK' ? 'selected' : '' }}>TK</option>
                                <option value="SD" {{ old('jenjang', $sekolah->jenjang ?? '') == 'SD' ? 'selected' : '' }}>SD</option>
                                <option value="SMP" {{ old('jenjang', $sekolah->jenjang ?? '') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                <option value="SMA" {{ old('jenjang', $sekolah->jenjang ?? '') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                <option value="SMK" {{ old('jenjang', $sekolah->jenjang ?? '') == 'SMK' ? 'selected' : '' }}>SMK</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status">Status <span class="required">*</span></label>
                        <div class="select-wrapper">
                            <select id="status" name="status" required>
                                <option value="" disabled {{ !isset($sekolah) ? 'selected' : '' }}>Pilih Status</option>
                                <option value="Negeri" {{ old('status', $sekolah->status ?? '') == 'Negeri' ? 'selected' : '' }}>Negeri</option>
                                <option value="Swasta" {{ old('status', $sekolah->status ?? '') == 'Swasta' ? 'selected' : '' }}>Swasta</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-grid col-2">
                    <div class="form-group">
                        <label for="akreditasi">Akreditasi <span class="required">*</span></label>
                        <div class="select-wrapper">
                            <select id="akreditasi" name="akreditasi" required>
                                <option value="" disabled {{ !isset($sekolah) ? 'selected' : '' }}>Pilih Akreditasi</option>
                                <option value="A" {{ old('akreditasi', $sekolah->akreditasi ?? '') == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ old('akreditasi', $sekolah->akreditasi ?? '') == 'B' ? 'selected' : '' }}>B</option>
                                <option value="C" {{ old('akreditasi', $sekolah->akreditasi ?? '') == 'C' ? 'selected' : '' }}>C</option>
                                <option value="Tidak Terakreditasi" {{ old('akreditasi', $sekolah->akreditasi ?? '') == 'Tidak Terakreditasi' ? 'selected' : '' }}>Tidak Terakreditasi</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" x-data="{ phone: '{{ old('no_telepon', $sekolah->no_telepon ?? '') }}' }">
                        <label for="no_telepon">No. Telepon <span class="required">*</span></label>
                        <div style="display: flex; align-items: center; gap: 0;">
                            <span style="padding: 8px 12px; background: #f3f4f6; border: 1px solid #d1d5db; border-right: none; border-radius: 6px 0 0 6px; font-size: 14px; color: #374151; white-space: nowrap;">+62</span>
                            <input type="tel" id="no_telepon" name="no_telepon" x-model="phone"
                                @input="phone = phone.replace(/^0+/, '')"
                                style="border-radius: 0 6px 6px 0; flex: 1;"
                                placeholder="81369904725" required>
                        </div>
                    </div>
                </div>

                <div class="form-grid col-2">
                    <div class="form-group">
                        <label for="email">Email <span class="required">*</span></label>
                        <input type="email" id="email" name="email"
                            value="{{ old('email', $sekolah->email ?? '') }}" placeholder="Masukkan Email" required>
                    </div>
                    <div class="form-group">
                        <label for="social_media">Website / Sosmed Sekolah <span class="required">*</span></label>
                        <input type="url" id="social_media" name="social_media"
                            value="{{ old('social_media', $sekolah->social_media ?? '') }}"
                            placeholder="https://contoh.com atau https://instagram.com/username" required>
                    </div>
                </div>

                <div class="form-grid col-2">
                    <div class="form-group">
                        <label for="siswa_laki">Siswa Laki-Laki <span class="required">*</span></label>
                        <input type="number" id="siswa_laki" name="siswa_laki" min="0"
                            value="{{ old('siswa_laki', $sekolah->siswa_laki ?? 0) }}"
                            placeholder="Jumlah siswa laki-laki" required>
                        @error('siswa_laki')
                            <span class="input-helper text-danger" style="font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="siswa_perempuan">Siswa Perempuan <span class="required">*</span></label>
                        <input type="number" id="siswa_perempuan" name="siswa_perempuan" min="0"
                            value="{{ old('siswa_perempuan', $sekolah->siswa_perempuan ?? 0) }}"
                            placeholder="Jumlah siswa perempuan" required>
                        @error('siswa_perempuan')
                            <span class="input-helper text-danger" style="font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-grid col-2">
                    <div class="form-group">
                        <label for="total_siswa">Total Peserta Didik</label>
                        <input type="number" id="total_siswa" name="total_siswa"
                            value="{{ old('total_siswa', $sekolah->total_siswa ?? 0) }}"
                            style="background-color: #f3f4f6; cursor: not-allowed;" readonly>
                        <span class="input-helper text-muted" style="font-size: 12px; display: block; margin-top: 4px;">Terhitung otomatis dari jumlah siswa laki-laki + perempuan</span>
                    </div>
                    <div></div>
                </div>
            </div>

            <!-- SECTION 3: Detail Lokasi -->
            <div class="form-section" x-data="locationDropdowns()" x-init="init()">
                <h3 class="section-title">Detail Lokasi</h3>

                <div class="form-grid col-2">
                    <div class="form-group">
                        <label for="provinsi">Provinsi <span class="required">*</span></label>
                        <div class="select-wrapper">
                            <select id="provinsi" name="provinsi" required
                                x-model="selectedProvinsi"
                                @change="onProvinsiChange()">
                                <option value="" disabled>Pilih Provinsi</option>
                                <template x-for="prov in provinces" :key="prov">
                                    <option :value="prov" x-text="prov"
                                        :selected="prov === '{{ old('provinsi', $sekolah->provinsi ?? '') }}'"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="kabupaten_kota">Kabupaten / Kota <span class="required">*</span></label>
                        <div class="select-wrapper">
                            <select id="kabupaten_kota" name="kabupaten_kota" required
                                x-model="selectedKabupaten"
                                @change="onKabupatenChange()"
                                :disabled="!selectedProvinsi">
                                <option value="" disabled>Pilih Kabupaten / Kota</option>
                                <template x-for="kab in kabupatens" :key="kab">
                                    <option :value="kab" x-text="kab"
                                        :selected="kab === '{{ old('kabupaten_kota', $sekolah->kabupaten_kota ?? '') }}'"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-grid col-2">
                    <div class="form-group">
                        <label for="kecamatan">Kecamatan <span class="required">*</span></label>
                        <div class="select-wrapper">
                            <select id="kecamatan" name="kecamatan" required
                                x-model="selectedKecamatan"
                                :disabled="!selectedKabupaten">
                                <option value="" disabled>Pilih Kecamatan</option>
                                <template x-for="kec in kecamatans" :key="kec">
                                    <option :value="kec" x-text="kec"
                                        :selected="kec === '{{ old('kecamatan', $sekolah->kecamatan ?? '') }}'"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                    <div></div>
                </div>

                <div class="form-group full-width">
                    <label for="alamat">Alamat Sekolah <span class="required">*</span></label>
                    <textarea id="alamat" name="alamat" rows="4" placeholder="Masukkan Alamat Lengkap Sekolah" required>{{ old('alamat', $sekolah->alamat ?? '') }}</textarea>
                    <span class="input-helper text-warning" style="font-size: 12px; display: block; margin-top: 4px; margin-bottom: 12px;">Tulis alamat lengkap termasuk nama jalan, RT/RW, Kode Pos, dll</span>
                </div>

                <!-- Peta Interaktif Leaflet -->
                <div class="form-group full-width" style="margin-bottom: 20px;">
                    <label style="font-weight: 600; margin-bottom: 6px; display: block;">Titik Koordinat Peta <span class="required">*</span></label>
                    <span class="input-helper text-muted" style="font-size: 12px; display: block; margin-bottom: 10px;">Silakan cari lokasi sekolah Anda, perbesar (zoom in), lalu <strong>klik pada titik lokasi bangunan sekolah</strong> untuk mengisi koordinat secara otomatis.</span>
                    <div id="map" style="height: 350px; border-radius: 8px; border: 1px solid #d1d5db; z-index: 1;"></div>
                </div>

                <!-- Input Koordinat Terisi Otomatis -->
                <div class="form-grid col-2">
                    <div class="form-group">
                        <label for="longitude">Longitude <span class="required">*</span></label>
                        <input type="text" id="longitude" name="longitude"
                            value="{{ old('longitude', $sekolah->longitude ?? '') }}"
                            placeholder="Klik pada peta untuk mengisi otomatis"
                            style="background-color: #f3f4f6; cursor: not-allowed;" readonly required>
                        @error('longitude')
                            <span class="input-helper text-danger" style="font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="latitude">Latitude <span class="required">*</span></label>
                        <input type="text" id="latitude" name="latitude"
                            value="{{ old('latitude', $sekolah->latitude ?? '') }}"
                            placeholder="Klik pada peta untuk mengisi otomatis"
                            style="background-color: #f3f4f6; cursor: not-allowed;" readonly required>
                        @error('latitude')
                            <span class="input-helper text-danger" style="font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="button" class="btn btn-cancel" onclick="window.history.back()">Cancel</button>
                <button type="submit" class="btn btn-submit">
                    {{ isset($sekolah) ? 'Update Data' : 'Submit' }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z" />
                    </svg>
                </button>
            </div>
        </form>
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
                    this.selectedKabupaten = '';
                    this.selectedKecamatan = '';
                    const kabSet = [...new Set(
                        this.allRows
                            .filter(r => r.provinsi === this.selectedProvinsi && r.kabupaten_kota)
                            .map(r => r.kabupaten_kota)
                    )];
                    this.kabupatens = kabSet.sort((a, b) => a.localeCompare(b, 'id'));
                    this.kecamatans = [];
                },

                onKabupatenChange() {
                    this.selectedKecamatan = '';
                    const kecSet = [...new Set(
                        this.allRows
                            .filter(r => r.provinsi === this.selectedProvinsi && r.kabupaten_kota === this.selectedKabupaten && r.kecamatan)
                            .map(r => r.kecamatan)
                    )];
                    this.kecamatans = kecSet.sort((a, b) => a.localeCompare(b, 'id'));
                }
            };
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-calculate total siswa
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

            // Leaflet map
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

            setTimeout(function() {
                map.invalidateSize();
            }, 400);
        });
    </script>
@endsection
