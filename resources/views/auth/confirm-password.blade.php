@extends('layouts.app')

@section('title', 'Konfirmasi Password - SatuPeta')

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

                <div class="mb-4 text-sm text-gray-600 text-center">
                    {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
                </div>

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <!-- Password -->
                    <div class="auth-form-group">
                        <label for="password" class="auth-label">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            class="auth-input" placeholder="Masukkan Password">
                        <x-input-error :messages="$errors->get('password')" style="color: #ef4444; font-size: 0.75rem; margin-top: 0.25rem;" />
                    </div>

                    <!-- Tombol Submit -->
                    <div class="auth-btn-container">
                        <button type="submit" class="auth-btn-primary">
                            {{ __('Confirm') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
