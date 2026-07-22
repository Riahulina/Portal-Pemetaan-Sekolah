@extends('layouts.app')

@section('title', 'Data Sekolah Saya - SatuPeta')

@section('styles')
    <style>
        /* CSS Responsif Khusus Halaman Ini */
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            padding: 30px;
            background-color: #f8fafc;
            box-sizing: border-box;
        }

        /* Tampilan Tabel (Default Desktop) */
        .desktop-table-container {
            display: block;
            background: #ffffff;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            margin-bottom: 30px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        /* Tampilan Kartu Mobile (Sembunyi di Desktop) */
        .mobile-cards-container {
            display: none;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 25px;
        }

        .mobile-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 16px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
        }

        .mobile-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
            gap: 10px;
        }

        .mobile-card-body {
            font-size: 13px;
            color: #475569;
            margin-bottom: 15px;
        }

        .mobile-card-body p {
            margin: 4px 0;
        }

        .mobile-card-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
            align-items: center;
            border-top: 1px solid #f1f5f9;
            padding-top: 12px;
        }

        /* MEDIA QUERY: Tampilan Layar HP (Maksimal 768px) */
        @media (max-width: 768px) {
            .main-content {
                padding: 15px !important;
            }

            .header-section {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 15px;
            }

            .btn-add-school {
                width: 100%;
                justify-content: center;
                box-sizing: border-box;
            }

            .desktop-table-container {
                display: none !important;
                /* Sembunyikan tabel di HP */
            }

            .mobile-cards-container {
                display: flex !important;
                /* Tampilkan kartu di HP */
            }

            .info-box {
                flex-direction: column !important;
                text-align: center;
                padding: 15px !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="dashboard-layout dashboard-container">
        {{-- 1. PANGGIL SIDEBAR KIRI --}}
        @include('partials.sidebar')

        {{-- 2. KONTEN SEBELAH KANAN --}}
        <main class="main-content">

            {{-- Header Halaman & Tombol Tambah --}}
            <div class="header-section"
                style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px;">
                <div>
                    <h2 style="font-size: 24px; font-weight: 700; color: #1e293b; margin: 0 0 5px 0;">Data Sekolah Saya</h2>
                    <p style="color: #64748b; font-size: 14px; margin: 0;">Berikut adalah daftar sekolah yang anda daftarkan.
                    </p>
                </div>
                <a href="{{ route('Form.user') }}" class="btn-submit btn-add-school"
                    style="padding: 10px 20px; background: #008080; color: #fff; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <span>+</span> Daftarkan sekolah baru
                </a>
            </div>

            {{-- ========================================== --}}
            {{-- MODUL 1: TAMPILAN KARTU (KHUSUS LAYAR HP)  --}}
            {{-- ========================================== --}}
            <div class="mobile-cards-container">
                @forelse($sekolahList as $item)
                    <div class="mobile-card">
                        <div class="mobile-card-header">
                            <div>
                                <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin: 0 0 4px 0;">
                                    {{ $item->nama_sekolah }}
                                </h3>
                                <span style="font-size: 12px; color: #64748b;">NPSN: {{ $item->npsn }}</span>
                            </div>

                            {{-- Badge Status --}}
                            <div>
                                @if ($item->status_verifikasi == 'pending')
                                    <span
                                        style="background: #fef3c7; color: #d97706; padding: 4px 10px; border-radius: 50px; font-size: 11px; font-weight: 600;">
                                        Menunggu
                                    </span>
                                @elseif($item->status_verifikasi == 'approved')
                                    <span
                                        style="background: #d1fae5; color: #065f46; padding: 4px 10px; border-radius: 50px; font-size: 11px; font-weight: 600;">
                                        Disetujui
                                    </span>
                                @else
                                    <span
                                        style="background: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 50px; font-size: 11px; font-weight: 600;">
                                        Ditolak
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="mobile-card-body">
                            <p><strong>Tgl Daftar:</strong>
                                {{ $item->created_at ? $item->created_at->translatedFormat('d M Y, H:i') : '-' }} WIB</p>
                        </div>

                        <div class="mobile-card-actions">
                            <a href="{{ route('status.user', $item->id) }}"
                                style="border: 1px solid #cbd5e1; background: #fff; padding: 6px 12px; border-radius: 6px; font-size: 12px; color: #334155; text-decoration: none;">
                                👁️ Lihat
                            </a>

                            @if ($item->status_verifikasi == 'pending' || $item->status_verifikasi == 'rejected')
                                <a href="{{ route('sekolah.edit', $item->id) }}"
                                    style="border: 1px solid #cbd5e1; background: #fff; padding: 6px 12px; border-radius: 6px; font-size: 12px; color: #334155; text-decoration: none;">
                                    ✏️ Edit
                                </a>

                                <form action="{{ route('sekolah.destroy', $item->id) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengajuan ini?')"
                                    style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        style="border: 1px solid #fca5a5; background: #fee2e2; padding: 6px 12px; border-radius: 6px; font-size: 12px; color: #991b1b; cursor: pointer;">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div
                        style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 25px; text-align: center; color: #94a3b8; font-size: 14px;">
                        Belum ada data sekolah yang didaftarkan.
                    </div>
                @endforelse
            </div>

            {{-- ========================================== --}}
            {{-- MODUL 2: TAMPILAN TABEL (DESKTOP & TABLET) --}}
            {{-- ========================================== --}}
            <div class="desktop-table-container">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                    <thead>
                        <tr
                            style="background: #f1f5f9; border-bottom: 1px solid #e2e8f0; color: #334155; font-weight: 700; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">
                            <th style="padding: 15px 20px;">Nama Sekolah</th>
                            <th style="padding: 15px 20px;">NPSN</th>
                            <th style="padding: 15px 20px;">Tanggal Daftar</th>
                            <th style="padding: 15px 20px; text-align: center;">Status</th>
                            <th style="padding: 15px 20px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sekolahList as $item)
                            <tr style="border-bottom: 1px solid #f1f5f9; color: #1e293b;">
                                <td style="padding: 15px 20px; font-weight: 600;">{{ $item->nama_sekolah }}</td>
                                <td style="padding: 15px 20px; color: #475569;">{{ $item->npsn }}</td>
                                <td style="padding: 15px 20px; color: #64748b;">
                                    {{ $item->created_at ? $item->created_at->translatedFormat('d F Y, H:i') : '-' }} WIB
                                </td>
                                <td style="padding: 15px 20px; text-align: center;">
                                    @if ($item->status_verifikasi == 'pending')
                                        <span
                                            style="background: #fef3c7; color: #d97706; padding: 4px 12px; border-radius: 50px; font-size: 12px; font-weight: 600; display: inline-block;">
                                            Menunggu Verifikasi
                                        </span>
                                    @elseif($item->status_verifikasi == 'approved')
                                        <span
                                            style="background: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 50px; font-size: 12px; font-weight: 600; display: inline-block;">
                                            Disetujui
                                        </span>
                                    @else
                                        <span
                                            style="background: #fee2e2; color: #991b1b; padding: 4px 12px; border-radius: 50px; font-size: 12px; font-weight: 600; display: inline-block;">
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td style="padding: 15px 20px; text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                                        <a href="{{ route('status.user', $item->id) }}"
                                            style="border: 1px solid #cbd5e1; background: #fff; padding: 4px 10px; border-radius: 4px; font-size: 12px; color: #334155; text-decoration: none; display: flex; align-items: center; gap: 4px;">
                                            👁️ Lihat
                                        </a>

                                        @if ($item->status_verifikasi == 'pending' || $item->status_verifikasi == 'rejected')
                                            <a href="{{ route('sekolah.edit', $item->id) }}"
                                                style="border: 1px solid #cbd5e1; background: #fff; padding: 4px 10px; border-radius: 4px; font-size: 12px; color: #334155; text-decoration: none; display: flex; align-items: center; gap: 4px;">
                                                ✏️ Edit
                                            </a>
                                        @endif

                                        <div class="action-dropdown" style="position: relative; display: inline-block;">
                                            <button onclick="toggleDropdown(event, 'dropdown-{{ $item->id }}')"
                                                style="background: none; border: none; color: #94a3b8; cursor: pointer; font-size: 18px; padding: 4px 8px; font-weight: bold;">
                                                ⋮
                                            </button>

                                            <div id="dropdown-{{ $item->id }}" class="dropdown-content"
                                                style="display: none; position: absolute; right: 0; top: 100%; background-color: #ffffff; min-width: 140px; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; border-radius: 6px; z-index: 50;">
                                                @if ($item->status_verifikasi !== 'approved')
                                                    <form action="{{ route('sekolah.destroy', $item->id) }}" method="POST"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengajuan sekolah ini? Data tidak dapat dikembalikan.')"
                                                        style="margin: 0;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            style="color: #ef4444; padding: 10px 16px; text-decoration: none; display: block; width: 100%; text-align: left; background: none; border: none; font-size: 13px; cursor: pointer; font-weight: 500; border-radius: 6px;">
                                                            🗑️ Hapus Data
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding: 30px; text-align: center; color: #94a3b8;">
                                    Belum ada data sekolah yang didaftarkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Footer Tabel --}}
                <div
                    style="background: #ffffff; padding: 15px 20px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #64748b;">
                    <div>
                        Menampilkan {{ $sekolahList->count() }} dari {{ $sekolahList->count() }} data
                    </div>
                    <div style="display: flex; gap: 5px;">
                        <button disabled
                            style="background: #fff; border: 1px solid #e2e8f0; border-radius: 4px; padding: 3px 8px; color: #cbd5e1; cursor: not-allowed;">
                            << /button>
                                <button
                                    style="background: #008080; border: 1px solid #008080; border-radius: 4px; padding: 3px 8px; color: #fff; font-weight: bold;">1</button>
                                <button disabled
                                    style="background: #fff; border: 1px solid #e2e8f0; border-radius: 4px; padding: 3px 8px; color: #cbd5e1; cursor: not-allowed;">></button>
                    </div>
                </div>
            </div>

            {{-- Box Informasi / Tips --}}
            <div class="info-box"
                style="background: #f0fdfa; border: 1px solid #ccfbf1; padding: 20px 25px; border-radius: 12px; display: flex; gap: 20px; align-items: center;">
                <div
                    style="width: 48px; height: 48px; background: #ccfbf1; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin: 0 auto;">
                    <svg width="24" height="24" fill="none" stroke="#0d9488" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 18a3.75 3.75 0 0 0 .495-7.467 5.99 5.99 0 0 0-1.925 0A3.75 3.75 0 0 0 12 18Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v1.5m0 1.5h.008m-3.008-3h6M9 15h6" />
                    </svg>
                </div>
                <div>
                    <p style="font-size: 14px; color: #115e59; font-weight: 500; margin: 0; line-height: 1.5;">
                        Status sekolah akan berubah setelah proses verifikasi oleh admin selesai. <br>
                        Pastikan data yang anda isi sudah benar dan sesuai.
                    </p>
                </div>
            </div>

        </main>
    </div>

    <script>
        function toggleDropdown(event, dropdownId) {
            event.stopPropagation();
            document.querySelectorAll('.dropdown-content').forEach(function(el) {
                if (el.id !== dropdownId) {
                    el.style.display = 'none';
                }
            });

            var dropdown = document.getElementById(dropdownId);
            dropdown.style.display = (dropdown.style.display === "block") ? "none" : "block";
        }

        window.onclick = function(event) {
            document.querySelectorAll('.dropdown-content').forEach(function(el) {
                el.style.display = 'none';
            });
        }
    </script>
@endsection
