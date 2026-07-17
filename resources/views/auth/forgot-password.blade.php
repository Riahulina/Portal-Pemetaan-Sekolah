@extends('layouts.app')

@section('title', 'Lupa Password - SatuPeta')

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


                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="auth-form-group">
                        <label for="email" class="auth-label">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="auth-input" placeholder="Masukkan Email">
                        <x-input-error :messages="$errors->get('email')" style="color: #ef4444; font-size: 0.75rem; margin-top: 0.25rem;" />
                    </div>

                    <!-- Tombol Submit -->
                    <div class="auth-btn-container">
                        <button type="submit" class="auth-btn-primary">
                            {{ __('Email Password Reset Link') }}
                        </button>
                    </div>

                    <p class="auth-footer-text">
                        <a href="{{ route('login') }}" class="auth-link">Kembali ke Login</a>
                    </p>
                </form>

            </div>
        </div>
    </div>
@endsection
