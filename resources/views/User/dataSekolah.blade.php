@extends('layouts.app')

@section('title', 'Data Sekolah Saya - SatuPeta')

@section('content')
    <div class="dashboard-layout">
        {{-- 1. PANGGIL SIDEBAR KIRI --}}
        @include('partials.sidebar')

        {{-- 2. KONTEN SEBELAH KANAN --}}
        <main class="main-content" style="flex: 1; padding: 30px; background-color: #f8fafc;">

            {{-- Header Halaman & Tombol Tambah --}}
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px;">
                <div>
                    <h2 style="font-size: 24px; font-weight: 700; color: #1e293b; margin: 0 0 5px 0;">Data Sekolah Saya</h2>
                    <p style="color: #64748b; font-size: 14px; margin: 0;">Berikut adalah daftar sekolah yang anda daftarkan.
                    </p>
                </div>
                <a href="{{ route('Form.user') }}" class="btn-submit"
                    style="padding: 10px 20px; background: #008080; color: #fff; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <span>+</span> Daftarkan sekolah baru
                </a>
            </div>

            {{-- Komponen Tabel Data --}}
            <div
                style="background: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
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
                                {{-- Nama Sekolah --}}
                                <td style="padding: 15px 20px; font-weight: 600;">{{ $item->nama_sekolah }}</td>

                                {{-- NPSN --}}
                                <td style="padding: 15px 20px; color: #475569;">{{ $item->npsn }}</td>

                                {{-- Tanggal Daftar --}}
                                <td style="padding: 15px 20px; color: #64748b;">
                                    {{ $item->created_at ? $item->created_at->translatedFormat('d F Y, H:i') : '-' }} WIB
                                </td>

                                {{-- Badge Status Adaptif --}}
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

                                {{-- Tombol Aksi --}}
                                <td style="padding: 15px 20px; text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                                        <a href="{{ route('status.user') }}"
                                            style="border: 1px solid #cbd5e1; background: #fff; padding: 4px 10px; border-radius: 4px; font-size: 12px; color: #334155; text-decoration: none; display: flex; align-items: center; gap: 4px;">
                                            👁️ Lihat
                                        </a>

                                        @if ($item->status_verifikasi == 'pending' || $item->status_verifikasi == 'rejected')
                                            <a href="{{ route('sekolah.edit', $item->id) }}"
                                                style="border: 1px solid #cbd5e1; background: #fff; padding: 4px 10px; border-radius: 4px; font-size: 12px; color: #334155; text-decoration: none; display: flex; align-items: center; gap: 4px;">
                                                ✏️ Edit
                                            </a>
                                        @endif

                                        {{-- Dropdown Aksi (Titik Tiga) --}}
                                        <div class="action-dropdown" style="position: relative; display: inline-block;">
                                            <button onclick="toggleDropdown(event, 'dropdown-{{ $item->id }}')"
                                                style="background: none; border: none; color: #94a3b8; cursor: pointer; font-size: 18px; padding: 4px 8px; font-weight: bold;">
                                                ⋮
                                            </button>

                                            <div id="dropdown-{{ $item->id }}" class="dropdown-content"
                                                style="display: none; position: absolute; right: 0; top: 100%; background-color: #ffffff; min-width: 140px; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; border-radius: 6px; z-index: 50;">

                                                <!-- Form Hapus Data (Method DELETE demi keamanan Laravel) -->
                                                @if($item->status_verifikasi !== 'approved')
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

                {{-- Footer Tabel / Informasi Baris --}}
                <div
                    style="background: #ffffff; padding: 15px 20px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #64748b;">
                    <div>
                        Menampilkan {{ $sekolahList->count() }} dari {{ $sekolahList->count() }} data
                    </div>
                    <div style="display: flex; gap: 5px;">
                        <button disabled
                            style="background: #fff; border: 1px solid #e2e8f0; border-radius: 4px; padding: 3px 8px; color: #cbd5e1; cursor: not-allowed;">
                            < </button>
                                <button
                                    style="background: #008080; border: 1px solid #008080; border-radius: 4px; padding: 3px 8px; color: #fff; font-weight: bold;">1</button>
                                <button disabled
                                    style="background: #fff; border: 1px solid #e2e8f0; border-radius: 4px; padding: 3px 8px; color: #cbd5e1; cursor: not-allowed;">></button>
                    </div>
                </div>
            </div>

            {{-- Box Tips/Informasi Bawah (Persis Desain Figma) --}}
            <div
                style="background: #f0fdfa; border: 1px solid #ccfbf1; padding: 25px; border-radius: 12px; display: flex; gap: 20px; align-items: center;">
                <div
                    style="width: 50px; height: 50px; background: #ccfbf1; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    {{-- Icon Bohlam/Lampu --}}
                    <svg width="26" height="26" fill="none" stroke="#0d9488" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 18a3.75 3.75 0 0 0 .495-7.467 5.99 5.99 0 0 0-1.925 0A3.75 3.75 0 0 0 12 18Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v1.5m0 1.5h.008m-3.008-3h6M9 15h6" />
                    </svg>
                </div>
                <div>
                    <p style="font-size: 15px; color: #115e59; font-weight: 500; margin: 0; line-height: 1.5;">
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

            // Tutup semua dropdown lain yang sedang terbuka
            document.querySelectorAll('.dropdown-content').forEach(function(el) {
                if (el.id !== dropdownId) {
                    el.style.display = 'none';
                }
            });

            // Buka atau tutup dropdown yang ditargetkan
            var dropdown = document.getElementById(dropdownId);
            if (dropdown.style.display === "block") {
                dropdown.style.display = "none";
            } else {
                dropdown.style.display = "block";
            }
        }

        // Klik di mana saja untuk menutup dropdown
        window.onclick = function(event) {
            document.querySelectorAll('.dropdown-content').forEach(function(el) {
                el.style.display = 'none';
            });
        }
    </script>
@endsection
