@extends('layouts.app')

@section('title', 'Status Verifikasi - SatuPeta')

@section('content')
    <div class="dashboard-layout">
        {{-- Sidebar Kiri --}}
        @include('partials.sidebar')

        {{-- Konten Utama Kanan --}}
        <main class="main-content">
            <h2 class="page-title" style="font-size: 24px; font-weight: 700; color: #1e293b; margin-bottom: 30px;">Status
                Verifikasi</h2>

            {{-- Jika User Belum Mengisi Form Sama Sekali --}}
            @if (!$sekolah)
                <div
                    style="text-align: center; padding: 50px 20px; background: #fff; border-radius: 12px; border: 1px solid #e2e8f0;">
                    <p style="color: #64748b; margin-bottom: 15px;">Anda belum melakukan pendaftaran data sekolah.</p>
                    <a href="{{ route('Form.user') }}" class="btn-nav"
                        style="padding: 10px 20px; background: #008080; color:#fff; text-decoration:none; border-radius:6px; display: inline-block;">
                        Daftarkan Sekolah Sekarang
                    </a>
                </div>
            @else
                {{-- Box Alert Atas Dinamis Mengikuti Status --}}
                <div style="text-align: center; margin-bottom: 40px;">
                    @if ($sekolah->status_verifikasi == 'pending')
                        <div
                            style="width: 100px; height: 100px; background: #fef3c7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                            <svg width="45" height="45" fill="none" stroke="#d97706" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                        <h3 style="font-size: 20px; font-weight: 700; color: #000; margin-bottom: 5px;">Terimakasih Telah
                            Melakukan Pendaftaran</h3>
                        <p style="color: #d97706; font-weight: 600;">Data Sedang Ditinjau Admin</p>
                    @elseif($sekolah->status_verifikasi == 'approved')
                        <div
                            style="width: 100px; height: 100px; background: #ccfbf1; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                            <svg width="45" height="45" fill="none" stroke="#0d9488" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 style="font-size: 20px; font-weight: 700; color: #0d9488; margin-bottom: 5px;">Selamat!
                            Pendaftaran Disetujui</h3>
                        <p style="color: #475569; font-weight: 600;">Data sekolah Anda telah berhasil diintegrasikan ke peta
                            utama.</p>
                    @elseif($sekolah->status_verifikasi == 'rejected')
                        <div
                            style="width: 100px; height: 100px; background: #fee2e2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                            <svg width="45" height="45" fill="none" stroke="#ef4444" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 style="font-size: 20px; font-weight: 700; color: #ef4444; margin-bottom: 5px;">Pendaftaran
                            Ditolak</h3>
                        <p
                            style="color: #b91c1c; font-weight: 600; background: #fef2f2; display: inline-block; padding: 6px 14px; border-radius: 6px; border: 1px solid #fca5a5;">
                            Catatan Admin: {{ $sekolah->catatan_admin ?? 'Data tidak sesuai kriteria verifikasi.' }}
                        </p>
                    @endif
                </div>

                {{-- Komponen Stepper Progress Line --}}
                <div
                    style="background: #ffffff; padding: 50px 40px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 30px;">
                    <div
                        style="display: flex; justify-content: space-between; position: relative; align-items: flex-start;">

                        {{-- Garis Background Penghubung Stepper --}}
                        <div
                            style="position: absolute; top: 20px; left: 12.5%; right: 12.5%; height: 4px; background: #cbd5e1; z-index: 1;">
                        </div>

                        {{-- Garis Aktif Spasial Dinamis --}}
                        <div
                            style="position: absolute; top: 20px; left: 12.5%; 
                                    width: {{ $sekolah->status_verifikasi == 'approved' ? '75%' : ($sekolah->status_verifikasi == 'pending' ? '37.5%' : '37.5%') }}; 
                                    height: 4px; 
                                    background: {{ $sekolah->status_verifikasi == 'pending' ? '#f59e0b' : ($sekolah->status_verifikasi == 'approved' ? '#0d9488' : '#ef4444') }}; 
                                    transition: width 0.5s ease; z-index: 1;">
                        </div>

                        {{-- Step 1: Dikirim --}}
                        <div style="flex: 1; text-align: center; z-index: 2;">
                            <div
                                style="width: 40px; height: 40px; background: #0d9488; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-weight: bold; border: 4px solid #fff; box-shadow: 0 0 0 1px #0d9488;">
                                ✓
                            </div>
                            <h4 style="font-size: 14px; font-weight: 700; margin-bottom: 5px; color: #1e293b;">Data Sekolah
                                Dikirim</h4>
                            <p style="font-size: 12px; color: #64748b;">
                                {{ $sekolah->created_at ? $sekolah->created_at->translatedFormat('d F Y, H:i') : now()->translatedFormat('d F Y, H:i') }}
                                WIB
                            </p>
                        </div>

                        {{-- Step 2: Verifikasi --}}
                        <div style="flex: 1; text-align: center; z-index: 2;">
                            <div
                                style="width: 40px; height: 40px; 
                                        background: {{ $sekolah->status_verifikasi == 'pending' ? '#f59e0b' : ($sekolah->status_verifikasi == 'approved' ? '#0d9488' : '#ef4444') }}; 
                                        color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-weight: bold; border: 4px solid #fff; 
                                        box-shadow: 0 0 0 1px {{ $sekolah->status_verifikasi == 'pending' ? '#f59e0b' : ($sekolah->status_verifikasi == 'approved' ? '#0d9488' : '#ef4444') }};">
                                {{ $sekolah->status_verifikasi == 'rejected' ? '✗' : '2' }}
                            </div>
                            <h4 style="font-size: 14px; font-weight: 700; margin-bottom: 5px; color: #1e293b;">
                                {{ $sekolah->status_verifikasi == 'rejected' ? 'Verifikasi Ditolak' : 'Dalam Verifikasi Admin' }}
                            </h4>
                            <p style="font-size: 12px; color: #64748b;">
                                {{ $sekolah->status_verifikasi == 'pending' ? 'Sedang Ditinjau Admin' : 'Selesai Diperiksa' }}
                            </p>
                        </div>

                        {{-- Step 3: Disetujui --}}
                        <div style="flex: 1; text-align: center; z-index: 2;">
                            <div
                                style="width: 40px; height: 40px; 
                                        background: {{ $sekolah->status_verifikasi == 'approved' ? '#0d9488' : '#cbd5e1' }}; 
                                        color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-weight: bold; border: 4px solid #fff; box-shadow: 0 0 0 1px {{ $sekolah->status_verifikasi == 'approved' ? '#0d9488' : '#cbd5e1' }};">
                                3
                            </div>
                            <h4
                                style="font-size: 14px; font-weight: 700; margin-bottom: 5px; color: #cbd5e1; {{ $sekolah->status_verifikasi == 'approved' ? 'color: #1e293b;' : '' }}">
                                Disetujui</h4>
                            <p style="font-size: 12px; color: #64748b;">Akan Tampil di Peta</p>
                        </div>

                        {{-- Step 4: Tersedia di Peta --}}
                        <div style="flex: 1; text-align: center; z-index: 2;">
                            <div
                                style="width: 40px; height: 40px; 
                                        background: {{ $sekolah->status_verifikasi == 'approved' ? '#0d9488' : '#cbd5e1' }}; 
                                        color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-weight: bold; border: 4px solid #fff; box-shadow: 0 0 0 1px {{ $sekolah->status_verifikasi == 'approved' ? '#0d9488' : '#cbd5e1' }};">
                                4
                            </div>
                            <h4
                                style="font-size: 14px; font-weight: 700; margin-bottom: 5px; color: #cbd5e1; {{ $sekolah->status_verifikasi == 'approved' ? 'color: #1e293b;' : '' }}">
                                Tersedia Dipeta</h4>
                            <p style="font-size: 12px; color: #64748b;">Sekolah muncul di peta</p>
                        </div>

                    </div>
                </div>

                {{-- Box Informasi Bawah --}}
                <div
                    style="background: {{ $sekolah->status_verifikasi == 'rejected' ? '#fef2f2' : '#f0fdfa' }}; border: 1px solid {{ $sekolah->status_verifikasi == 'rejected' ? '#fee2e2' : '#ccfbf1' }}; padding: 20px; border-radius: 12px; display: flex; gap: 15px; align-items: center;">
                    <div
                        style="color: {{ $sekolah->status_verifikasi == 'rejected' ? '#ef4444' : '#0d9488' }}; flex-shrink: 0;">
                        <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 18v-5.25m0 -3a.75.75 0 1 1 0 -1.5a.75.75 0 0 1 0 1.5m6.75 2.25a6.75 6.75 0 1 1 -13.5 0a6.75 6.75 0 0 1 13.5 0Z" />
                        </svg>
                    </div>
                    <div>
                        <h4
                            style="font-size: 16px; font-weight: 700; color: {{ $sekolah->status_verifikasi == 'rejected' ? '#991b1b' : '#0f766e' }}; margin-bottom: 3px;">
                            Informasi Pengajuan</h4>
                        <p
                            style="font-size: 14px; color: {{ $sekolah->status_verifikasi == 'rejected' ? '#7f1d1d' : '#115e59' }}; margin: 0;">
                            @if ($sekolah->status_verifikasi == 'pending')
                                Kami sedang memverifikasi data berkas sekolah yang Anda kirimkan. Cek status pengajuan
                                secara berkala.
                            @elseif($sekolah->status_verifikasi == 'approved')
                                Proses verifikasi selesai. Sekolah Anda kini dapat dicari oleh publik melalui halaman Peta
                                Data utama.
                            @else
                                Pengajuan Anda ditolak oleh admin. Silakan klik menu <b>"Data Sekolah Saya"</b> lalu pilih
                                <b>"Edit"</b> untuk memperbaiki berkas data sekolah Anda berdasarkan catatan di atas.
                            @endif
                        </p>
                    </div>
                </div>
            @endif
        </main>
    </div>
@endsection
