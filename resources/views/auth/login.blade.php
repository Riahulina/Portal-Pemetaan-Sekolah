@extends('layouts.app') {{-- Ganti ke path master template-mu yang benar --}}

@section('title', 'Login - SatuPeta')

@section('content')
    <div class="auth-container">

        <div class="auth-bg-decorator1"></div>
        <div class="auth-bg-decorator2"></div>
        <div class="auth-bg-decorator3"></div>

        <div class="auth-form-wrapper">
            <div class="auth-card">

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

                <!-- Session Status Error -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="auth-form-group">
                        <label for="email" class="auth-label">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="auth-input" placeholder="Masukkan Email">
                        <x-input-error :messages="$errors->get('email')" style="color: #ef4444; font-size: 0.75rem; margin-top: 0.25rem;" />
                    </div>

                    <!-- Password -->
                    <div class="auth-form-group">
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem;">
                            <label for="password" class="auth-label" style="margin-bottom: 0;">Password</label>
                            @if (Route::has('password.request'))
                                <a class="auth-link" href="{{ route('password.request') }}" style="font-size: 0.75rem;">Lupa
                                    password?</a>
                            @endif
                        </div>
                        <input id="password" type="password" name="password" required class="auth-input"
                            placeholder="Masukkan Password">
                        <x-input-error :messages="$errors->get('password')" style="color: #ef4444; font-size: 0.75rem; margin-top: 0.25rem;" />
                    </div>

                    <!-- Tombol Login (Pendek & Di Tengah) -->
                    <div class="auth-btn-container">
                        <button type="submit" class="auth-btn-primary">
                            Login
                        </button>
                    </div>

                    <div class="auth-divider">
                        <span>atau</span>
                    </div>

                    <!-- Tombol Google Lonjong -->
                    <div class="auth-btn-google-container">
                        <a href="#" class="auth-btn-google">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12.24 10.285V14.4h6.887c-.275 1.565-1.88 4.604-6.887 4.604-4.33 0-7.866-3.577-7.866-8s3.536-8 7.866-8c2.46 0 4.105 1.025 5.047 1.926l3.227-3.227C18.26 1.76 15.44 1 12.24 1 6.18 1 1.25 5.93 1.25 12s4.93 11 10.99 11c6.32 0 10.53-4.43 10.53-10.72 0-.72-.08-1.27-.17-1.71h-10.36z"
                                    fill="#ffffff" />
                            </svg>
                            <span>Login menggunakan Google</span>
                        </a>
                    </div>

                    <p class="auth-footer-text">
                        Belum punya akun? <a href="{{ route('register') }}" class="auth-link">Daftar disini.</a>
                    </p>
                </form>

            </div>
        </div>
    </div>
@endsection
