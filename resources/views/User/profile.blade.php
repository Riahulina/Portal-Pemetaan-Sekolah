@extends('layouts.app')

@section('title', 'Profile Akun - SatuPeta')

@section('content')
    <div class="dashboard-layout">
        <!-- 1. PANGGIL SIDEBAR KIRI -->
        @include('partials.sidebar')

        <!-- 2. KONTEN UTAMA -->
        <main class="main-content profile-main-content">
            <div class="profile-header">
                <h2>Profile Akun</h2>
                <p>Kelola Informasi akun anda</p>
            </div>

            @if (session('status') === 'password-updated')
                <div class="alert-success">
                    ✓ Password Berhasil Diperbarui!
                </div>
            @endif

            @if (session('status') === 'info-updated')
                <div class="alert-success">
                    ✓ Informasi Akun Berhasil Diperbarui!
                </div>
            @endif

            <!-- Grid layout untuk Informasi Akun & Ubah Password -->
            <div class="profile-grid">

                <!-- Box Kiri: Informasi Akun -->
                <div class="profile-card">
                    <h3>Informasi Akun</h3>

                    <form action="{{ route('profile.info.update') }}" method="POST">
                        @csrf
                        @method('put')

                        <div class="form-group-item">
                            <label class="label-muted">Nama Lengkap</label>
                            <div class="val-bold">{{ $user->name }}</div>
                        </div>

                        <div class="form-group-item">
                            <label class="label-muted">Email</label>
                            <div class="val-bold">{{ $user->email }}</div>
                        </div>

                        <div class="form-group-item">
                            <label class="label-dark">No. Telepon</label>
                            <input type="text" name="phone_number" placeholder="81234567890"
                                value="{{ $user->phone_number }}" class="input-custom">
                            <p class="help-text">Harap awali nomor dengan angka 8, bukan 0 (Contoh: 8123...).</p>
                            @error('phone_number')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group-item-lg">
                            <label class="label-muted">Bergabung Sejak</label>
                            <div class="val-bold">{{ $user->created_at->format('d F Y') }}</div>
                        </div>

                        <button type="submit" class="btn-teal">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Informasi Akun
                        </button>
                    </form>
                </div>

                <!-- Box Kanan: Ubah Password -->
                <div class="profile-card">
                    <h3>Ubah Password</h3>

                    @if (auth()->user()->google_id === null)
                        <form action="{{ route('profile.password.update') }}" method="POST">
                            @csrf
                            @method('put')

                            <div class="form-group-item">
                                <label class="label-dark">Password Saat ini</label>
                                <input type="password" name="current_password" placeholder="Masukkan Password Saat Ini"
                                    class="input-custom">
                                @error('current_password')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group-item">
                                <label class="label-dark">Password Baru</label>
                                <input type="password" name="password" placeholder="Masukkan Password Baru"
                                    class="input-custom">
                                @error('password')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group-item-lg">
                                <label class="label-dark">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" placeholder="Konfirmasi Password Baru"
                                    class="input-custom">
                            </div>

                            <button type="submit" class="btn-teal">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Simpan Password Baru
                            </button>
                        </form>
                    @else
                        <div class="google-info-box">
                            <div class="google-alert">
                                <svg width="24" height="24" fill="none" stroke="#2563eb" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                </svg>
                                <p class="g-title">Akun Terhubung via Google</p>
                                <p class="g-desc">Akun Anda terdaftar menggunakan Google. Pengelolaan password dilakukan
                                    melalui akun Google Anda.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Warning Card / Box Keluar-Logout -->
            <div class="warning-card">
                <div class="warning-icon-wrapper">
                    <svg width="32" height="32" fill="none" stroke="#dc2626" stroke-width="2" viewBox="0 0 24 24">
                        <path
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="warning-body">
                    <h4>Warning</h4>
                    <p>Jika Anda Keluar, Anda Harus Login Kembali Untuk Mengakses Akun Anda</p>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-logout-outline">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Keluar/Logout
                        </button>
                    </form>
                </div>
            </div>
        </main>
    </div>
@endsection
