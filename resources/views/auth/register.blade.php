@extends('layouts.app') {{-- Ganti ke path master template-mu yang benar --}}

@section('title', 'Daftar Akun - SatuPeta')

@section('content')
    <div class="auth-container">

        <div class="auth-bg-decorator1"></div>
        <div class="auth-bg-decorator2"></div>
        <div class="auth-bg-decorator3"></div>

        <!-- Wrapper Tengah -->
        <div class="auth-form-wrapper">
            <div class="auth-card">

                <!-- Logo SatuPeta -->
                <div class="auth-card-header">
                    <a href="/" class="navbar-brand">
                        <img src="{{ asset('assets/logo.png') }}" class="brand-logo-img">

                        <div class="brand-text">
                            <div class="brand-text--top">
                                <span class="brand-kids">Satu</span>
                                <span class="brand-nesia">Peta</span>
                            </div>

                            <div class="brand-text--bottom">
                                <span class="brand-edu">Peta Pendidikan Indonesia</span>
                            </div>
                        </div>
                    </a>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Nama Lengkap -->
                    <div class="auth-form-group">
                        <label for="name" class="auth-label">Nama Lengkap</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                            class="auth-input" placeholder="Nama Lengkap">
                        <x-input-error :messages="$errors->get('name')" style="color: #ef4444; font-size: 0.75rem; margin-top: 0.25rem;" />
                    </div>

                    <!-- Email -->
                    <div class="auth-form-group">
                        <label for="email" class="auth-label">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            class="auth-input" placeholder="Email">
                        <x-input-error :messages="$errors->get('email')" style="color: #ef4444; font-size: 0.75rem; margin-top: 0.25rem;" />
                    </div>

                    <!-- Nomor Telepon -->
                    <div class="auth-form-group">
                        <label for="phone_number" class="auth-label">Nomor Telepon</label>
                        <input id="phone_number" type="tel" name="phone_number" value="{{ old('phone_number') }}"
                            required class="auth-input" placeholder="Nomor Telepon">
                        <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="auth-form-group">
                        <label for="password" class="auth-label">Password</label>
                        <div class="auth-input-wrapper">
                            <input id="password" type="password" name="password" required class="auth-input"
                                placeholder="Password">
                        </div>
                        <x-input-error :messages="$errors->get('password')" style="color: #ef4444; font-size: 0.75rem; margin-top: 0.25rem;" />
                    </div>


                    <!-- Konfirmasi Password -->
                    <div class="auth-form-group">
                        <label for="password_confirmation" class="auth-label">Konfirmasi Password</label>
                        <div class="auth-input-wrapper">
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                class="auth-input" placeholder="Ulangi Password">

                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" style="color: #ef4444; font-size: 0.75rem; margin-top: 0.25rem;" />
                    </div>

                    <!-- Tombol Register (Pendek & Di Tengah) -->
                    <div class="auth-btn-container">
                        <button type="submit" class="auth-btn-primary">
                            Register
                        </button>
                    </div>

                    <!-- Pembatas atau -->
                    <div class="auth-divider">
                        <span>atau</span>
                    </div>

                    <!-- Tombol Google Lonjong -->
                    <div class="auth-btn-google-container">
                        <a href="{{ route('google.redirect') }}" class="auth-btn-google">
                            <!-- Icon Google Putih -->
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12.24 10.285V14.4h6.887c-.275 1.565-1.88 4.604-6.887 4.604-4.33 0-7.866-3.577-7.866-8s3.536-8 7.866-8c2.46 0 4.105 1.025 5.047 1.926l3.227-3.227C18.26 1.76 15.44 1 12.24 1 6.18 1 1.25 5.93 1.25 12s4.93 11 10.99 11c6.32 0 10.53-4.43 10.53-10.72 0-.72-.08-1.27-.17-1.71h-10.36z"
                                    fill="#ffffff" />
                            </svg>
                            <span>Login menggunakan Google</span>
                        </a>
                    </div>

                    <p class="auth-footer-text">
                        Sudah punya akun? <a href="{{ route('login') }}" class="auth-link">Login disini.</a>
                    </p>
                </form>

            </div>
        </div>
    </div>
@endsection
