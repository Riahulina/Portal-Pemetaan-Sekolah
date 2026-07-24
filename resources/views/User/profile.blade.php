@extends('layouts.app')

@section('title', 'Profile Akun - SatuPeta')

@section('styles')
    <style>
        /* Wrapper Utama & Grid Layout */
        .dashboard-layout {
            display: flex;
            min-height: calc(100vh - 80px);
            background-color: #f8fafc;
        }

        .profile-main-content {
            flex: 1;
            padding: 2rem;
            max-width: 1200px;
        }

        .profile-header {
            margin-bottom: 2rem;
        }

        .profile-header h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 0.25rem 0;
        }

        .profile-header p {
            color: #64748b;
            margin: 0;
        }

        /* Grid 2 Kolom untuk Info & Password */
        .profile-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        /* Card Box */
        .profile-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .profile-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
            margin-top: 0;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #f1f5f9;
        }

        /* Form Elements */
        .form-group-item,
        .form-group-item-lg {
            margin-bottom: 1.25rem;
        }

        .label-muted {
            display: block;
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 0.25rem;
        }

        .label-dark {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .val-bold {
            font-size: 1rem;
            font-weight: 600;
            color: #0f172a;
        }

        .input-custom {
            width: 100%;
            padding: 0.625rem 0.875rem;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .input-custom:focus {
            border-color: #0d9488;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15);
        }

        .help-text {
            font-size: 0.775rem;
            color: #64748b;
            margin-top: 0.35rem;
            margin-bottom: 0;
        }

        .error-text {
            font-size: 0.8rem;
            color: #ef4444;
            margin-top: 0.25rem;
            display: block;
        }

        /* Tombol Teal */
        .btn-teal {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            background-color: #0d9488;
            color: #ffffff;
            font-weight: 600;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-teal:hover {
            background-color: #0f766e;
        }

        /* Warning Card / Logout Box */
        .warning-card {
            background: #fff5f5;
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 1.25rem;
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }

        .warning-body h4 {
            margin: 0 0 0.25rem 0;
            color: #991b1b;
            font-size: 1.05rem;
        }

        .warning-body p {
            margin: 0 0 1rem 0;
            color: #7f1d1d;
            font-size: 0.9rem;
        }

        .btn-logout-outline {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: transparent;
            color: #dc2626;
            border: 1px solid #dc2626;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-logout-outline:hover {
            background: #dc2626;
            color: #ffffff;
        }

        /* Alerts */
        .alert-success {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 0.875rem 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        /* Responsive untuk Tablet / Mobile */
        @media (max-width: 768px) {
            .profile-main-content {
                padding: 1rem;
            }

            .warning-card {
                flex-direction: column;
            }
        }
    </style>
@endsection

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
                            <div style="display: flex; gap: 0.5rem; align-items: center;">
                                <span
                                    style="padding: 0.625rem 0.75rem; background: #e2e8f0; border-radius: 8px; font-weight: 600; color: #475569;">+62</span>
                                <input type="text" name="phone_number" placeholder="81234567890"
                                    value="{{ $user->phone_number }}" class="input-custom">
                            </div>
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
        </main>
    </div>
@endsection
