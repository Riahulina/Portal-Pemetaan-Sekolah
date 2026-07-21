@extends('layouts.admin')

@section('title', 'Profile Akun')

@section('content')
    <div class="profile-header" style="margin-bottom: 25px;">
        <h2 style="font-size: 24px; font-weight: 700; color: #0f172a; margin: 0;">Profile Akun</h2>
        <p style="font-size: 14px; color: #64748b; margin: 5px 0 0 0;">Kelola Informasi akun anda</p>
    </div>

    @if (session('status') === 'password-updated')
        <div
            style="background-color: #d1fae5; color: #065f46; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; font-weight: 600;">
            ✓ Password Berhasil Diperbarui!
        </div>
    @endif

    @if (session('status') === 'info-updated')
        <div
            style="background-color: #d1fae5; color: #065f46; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; font-weight: 600;">
            ✓ Informasi Akun Berhasil Diperbarui!
        </div>
    @endif

    <!-- Grid layout untuk Informasi Akun & Ubah Password -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">

        <!-- Box Kiri: Informasi Akun -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px;">
            <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 20px;">
                Informasi Akun</h3>

            <form action="{{ route('admin.profile.info.update') }}" method="POST">
                @csrf
                @method('put')

                <div style="margin-bottom: 16px;">
                    <label style="font-size: 13px; color: #64748b; display: block; margin-bottom: 4px;">Nama
                        Lengkap</label>
                    <div style="font-size: 15px; font-weight: 600; color: #0f172a;">{{ $user->name }}</div>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="font-size: 13px; color: #64748b; display: block; margin-bottom: 4px;">Email</label>
                    <div style="font-size: 15px; font-weight: 600; color: #0f172a;">{{ $user->email }}</div>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="font-size: 14px; font-weight: 600; color: #0f172a; display: block; margin-bottom: 6px;">No.
                        Telepon</label>
                    <input type="text" name="phone_number" placeholder="81234567890"
                        value="{{ $user->phone_number }}"
                        style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
                    <p style="font-size: 12px; color: #6b7280; margin: 4px 0 0 0;">Harap awali nomor dengan angka 8, bukan 0 (Contoh: 8123...).</p>
                    @error('phone_number')
                        <span
                            style="color: #ef4444; font-size: 12px; margin-top: 4px; display:block;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="font-size: 13px; color: #64748b; display: block; margin-bottom: 4px;">Bergabung
                        Sejak</label>
                    <div style="font-size: 15px; font-weight: 600; color: #0f172a;">
                        {{ $user->created_at->format('d F Y') }}</div>
                </div>

                <button type="submit"
                    style="background-color: #007979; color: white; border: none; padding: 12px 20px; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path
                            d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Informasi Akun
                </button>
            </form>
        </div>

        <!-- Box Kanan: Ubah Password -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px;">
            <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 20px;">Ubah
                Password</h3>

            <form action="{{ route('admin.profile.password.update') }}" method="POST">
                @csrf
                @method('put')

                <!-- Password Saat Ini -->
                <div style="margin-bottom: 16px; position: relative;">
                    <label
                        style="font-size: 14px; font-weight: 600; color: #0f172a; display: block; margin-bottom: 6px;">Password
                        Saat ini</label>
                    <input type="password" name="current_password" placeholder="Masukkan Password Saat Ini"
                        style="width: 100%; padding: 10px 40px 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
                    @error('current_password')
                        <span
                            style="color: #ef4444; font-size: 12px; margin-top: 4px; display:block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Baru -->
                <div style="margin-bottom: 16px; position: relative;">
                    <label
                        style="font-size: 14px; font-weight: 600; color: #0f172a; display: block; margin-bottom: 6px;">Password
                        Baru</label>
                    <input type="password" name="password" placeholder="Masukkan Password Baru"
                        style="width: 100%; padding: 10px 40px 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
                    @error('password')
                        <span
                            style="color: #ef4444; font-size: 12px; margin-top: 4px; display:block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Konfirmasi Password Baru -->
                <div style="margin-bottom: 20px; position: relative;">
                    <label
                        style="font-size: 14px; font-weight: 600; color: #0f172a; display: block; margin-bottom: 6px;">Konfirmasi
                        Password Baru</label>
                    <input type="password" name="password_confirmation" placeholder="Konfirmasi Password Baru"
                        style="width: 100%; padding: 10px 40px 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
                </div>

                <button type="submit"
                    style="background-color: #007979; color: white; border: none; padding: 12px 20px; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Simpan Password Baru
                </button>
            </form>
        </div>
    </div>

    <!-- Warning Card / Box Keluar-Logout -->
    <div
        style="background-color: #fef2f2; border: 1px solid #fee2e2; border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 20px;">
        <div
            style="background: #fee2e2; padding: 12px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
            <svg width="32" height="32" fill="none" stroke="#dc2626" stroke-width="2" viewBox="0 0 24 24">
                <path
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <div style="flex: 1;">
            <h4 style="color: #991b1b; font-size: 18px; font-weight: 700; margin: 0 0 4px 0;">Warning</h4>
            <p style="color: #57534e; font-size: 14px; margin: 0 0 10px 0; font-weight: 500;">Jika Anda Keluar, Anda
                Harus Login Kembali Untuk Mengakses Akun Anda</p>

            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit"
                    style="background: white; border: 1px solid #dc2626; color: #dc2626; padding: 8px 16px; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
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
@endsection
