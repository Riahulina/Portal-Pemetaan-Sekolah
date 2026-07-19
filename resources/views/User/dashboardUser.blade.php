@extends('layouts.app') {{-- Menggunakan layout utama yang sudah terpasang Navbar Utama --}}

@section('title', 'Dashboard - SatuPeta')

@section('content')
    <div class="dashboard-layout">

        <!-- 1. PANGGIL SIDEBAR KIRI (FILE TERPISAH) -->
        @include('partials.sidebar')

        <!-- 2. KONTEN SEBELAH KANAN -->
        <main class="main-content">

            <!-- ISI DATA DASHBOARD -->
            <div class="content-body">

                <!-- Ucapan Selamat Datang Dinamis -->
                <div class="welcome-section">
                    <h2 class="welcome-title">Halo {{ Auth::user()->name }} 👋</h2>
                    <p class="welcome-subtitle">Kelola pendaftaran sekolah Anda dengan mudah dan pantau status verifikasinya.
                    </p>
                </div>

                <!-- Tiga Grid Card Statistik Dinamis -->
                <div class="stats-grid">
                    <!-- Total Pendaftaran -->
                    <div class="stat-card">
                        <img src="{{ asset('assets/TotalPendaftar.png') }}" class="brand-logo-imguser1">
                        <div class="stat-info">
                            <span class="stat-title">Total Pendaftaran</span>
                            <h3 class="stat-value">{{ $totalPendaftaran }}</h3>
                            <span class="stat-unit">Sekolah</span>
                        </div>
                    </div>

                    <!-- Menunggu Verifikasi -->
                    <div class="stat-card">
                        <img src="{{ asset('assets/Menunggu.png') }}" class="brand-logo-imguser1">
                        <div class="stat-info">
                            <span class="stat-title">Menunggu Verifikasi</span>
                            <h3 class="stat-value">{{ $menungguVerifikasi }}</h3>
                            <span class="stat-unit">Sekolah</span>
                        </div>
                    </div>

                    <!-- Disetujui -->
                    <div class="stat-card">
                        <img src="{{ asset('assets/Disetujui.png') }}" class="brand-logo-imguser1">
                        <div class="stat-info">
                            <span class="stat-title">Disetujui</span>
                            <h3 class="stat-value">{{ $disetujui }}</h3>
                            <span class="stat-unit">Sekolah</span>
                        </div>
                    </div>
                </div>

                <!-- Row Dua Kolom (Dinamis: Tampilkan Empty State ATAU Tabel Data) -->
                <div class="dashboard-row">

                    <!-- Box Kiri Dinamis -->
                    @if ($sekolahDaftar->isEmpty())
                        <!-- TAMPILKAN INI JIKA BELUM ADA DATA -->
                        <div class="empty-state-card">
                            <img src="{{ asset('assets/MendaftarSekolah.png') }}" class="brand-logo-imguser2">
                            <h4 class="empty-state-title">Anda Belum Mendaftarkan Sekolah</h4>
                            <p class="empty-state-desc">Daftarkan Sekolah Anda Sekarang Untuk Ditampilkan di SatuPeta
                                Pendidikan Indonesia.</p>
                            <a href="{{ route('Form.user') }}" class="btn-teal">+ Mulai Peta Data</a>
                        </div>
                    @else
                        <!-- TAMPILKAN TABEL DAFTAR SEKOLAH JIKA SUDAH ADA DATA -->
                        <div class="empty-state-card" style="text-align: left; display: block;">
                            <h4 class="empty-state-title" style="margin-bottom: 15px;">Daftar Sekolah Anda</h4>
                            <div style="overflow-x: auto;">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <thead>
                                        <tr style="border-bottom: 2px solid #e2e8f0; text-align: left;">
                                            <th style="padding: 10px;">Nama Sekolah</th>
                                            <th style="padding: 10px;">NPSN</th>
                                            <th style="padding: 10px;">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sekolahDaftar as $sekolah)
                                            <tr style="border-bottom: 1px solid #edf2f7;">
                                                <td style="padding: 10px;">{{ $sekolah->nama_sekolah }}</td>
                                                <td style="padding: 10px;">{{ $sekolah->npsn }}</td>
                                                <td style="padding: 10px;">
                                                    {{-- Menggunakan status_verifikasi dari DB --}}
                                                    @if (strtolower($sekolah->status_verifikasi) == 'approved' || strtolower($sekolah->status_verifikasi) == 'disetujui')
                                                        <span style="color: #48bb78; font-weight: bold;">Disetujui</span>
                                                    @elseif(strtolower($sekolah->status_verifikasi) == 'rejected' || strtolower($sekolah->status_verifikasi) == 'ditolak')
                                                        <span style="color: #f56565; font-weight: bold;">Ditolak</span>
                                                    @else
                                                        {{-- Jika statusnya 'pending', maka tampilkan Menunggu --}}
                                                        <span style="color: #ecc94b; font-weight: bold;">Menunggu</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <a href="{{ route('Form.user') }}" class="btn-teal"
                                style="margin-top: 15px; display: inline-block;">+ Tambah Sekolah Lagi</a>
                        </div>
                    @endif

                    <!-- Box Kanan: Butuh Bantuan -->
                    <div class="support-card">
                        <h4 class="support-title">Butuh Bantuan?</h4>
                        <p class="support-desc">Kami siap membantu jika Anda mengalami kesulitan saat pendaftaran.</p>

                        <div class="support-item">
                            <div class="support-item-icon">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div class="support-item-text">
                                <strong>0821-3878-8678</strong><br>
                                <span style="color: #64748b;">Senin - Jumat, 08:30-17:00 WIB<br>Sabtu, 08:30 - 14:00
                                    WIB</span>
                            </div>
                        </div>

                        <div class="support-item">
                            <div class="support-item-icon">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="support-item-text">
                                <strong>hello@satupeta.com</strong><br>
                                <span style="color: #64748b;">Respons dalam 1 x 24 jam</span>
                            </div>
                        </div>

                        <div class="support-item" style="margin-bottom: 0;">
                            <div class="support-item-icon">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <div class="support-item-text">
                                <strong><a href="#" class="support-item-link">Bantuan Online</a></strong><br>
                                <span style="color: #64748b;">Chat dengan tim kami</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>

    </div>
@endsection
