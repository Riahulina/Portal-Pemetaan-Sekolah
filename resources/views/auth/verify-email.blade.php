@extends('layouts.app')

@section('title', 'Verifikasi Email - SatuPeta')

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
                    {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-4 font-medium text-sm text-green-600 text-center">
                        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                    </div>
                @endif

                <!-- Resend Verification -->
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf

                    <div class="auth-btn-container">
                        <button type="submit" class="auth-btn-primary">
                            {{ __('Resend Verification Email') }}
                        </button>
                    </div>
                </form>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <div class="auth-btn-container" style="margin-top: 0.75rem;">
                        <button type="submit" class="auth-link" style="font-size: 0.8rem; cursor: pointer; background: none; border: none; padding: 0;">
                            {{ __('Log Out') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
