@extends('layouts.app')

@section('title', __('general.login') . ' | ' . \App\Models\Setting::get('site_name', 'Garikothay'))

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Oswald:wght@500;600;700&display=swap" rel="stylesheet">
<style>
    .gk-login {
        min-height: calc(100vh - 180px);
        background: #f8fafc;
        color: #111827;
        font-family: 'Inter', system-ui, sans-serif;
    }

    .gk-login h1,
    .gk-login h2 {
        font-family: 'Oswald', 'Inter', sans-serif;
        letter-spacing: 0;
    }

    .gk-login-container {
        width: 100%;
        max-width: 1504px;
        margin: 0 auto;
        padding-right: 1rem;
        padding-left: 1rem;
    }

    .gk-login-breadcrumb {
        border-bottom: 1px solid #e5e7eb;
        background: #ffffff;
    }

    .gk-login-breadcrumb-inner {
        display: flex;
        min-height: 42px;
        align-items: center;
        gap: 0.45rem;
        color: #6b7280;
        font-size: 0.78rem;
    }

    .gk-login-breadcrumb a {
        color: #6b7280;
        text-decoration: none;
    }

    .gk-login-breadcrumb a:hover {
        color: #e11d48;
    }

    .gk-login-wrap {
        display: grid;
        max-width: 920px;
        margin: 0 auto;
        padding-top: 3rem;
        padding-bottom: 3rem;
    }

    @media (min-width: 768px) {
        .gk-login-wrap {
            grid-template-columns: minmax(260px, 0.85fr) minmax(380px, 1.15fr);
        }
    }

    .gk-login-aside {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        border-radius: 8px 8px 0 0;
        background: #111827;
        color: #ffffff;
        padding: 2rem;
    }

    @media (min-width: 768px) {
        .gk-login-aside {
            border-radius: 8px 0 0 8px;
        }
    }

    .gk-login-brand {
        display: inline-flex;
        align-items: center;
        gap: 0.7rem;
        color: #ffffff;
        text-decoration: none;
    }

    .gk-login-brand-mark {
        display: grid;
        width: 2.7rem;
        height: 2.7rem;
        place-items: center;
        border-radius: 6px;
        background: #e11d48;
        font-family: 'Oswald', sans-serif;
        font-size: 1.3rem;
        font-weight: 900;
    }

    .gk-login-brand-name {
        font-family: 'Oswald', sans-serif;
        font-size: 1.2rem;
        font-weight: 900;
        text-transform: uppercase;
    }

    .gk-login-aside h2 {
        margin-top: 2.5rem;
        font-size: 1.8rem;
        font-weight: 900;
        line-height: 1.1;
        text-transform: uppercase;
    }

    .gk-login-aside p {
        margin-top: 0.7rem;
        color: #9ca3af;
        font-size: 0.85rem;
        line-height: 1.65;
    }

    .gk-login-benefits {
        display: grid;
        gap: 0.6rem;
        margin-top: 2rem;
        color: #d1d5db;
        font-size: 0.78rem;
    }

    .gk-login-benefits span::before {
        content: '✓';
        margin-right: 0.5rem;
        color: #fb7185;
        font-weight: 900;
    }

    .gk-login-card {
        border: 1px solid #e5e7eb;
        border-top: 0;
        border-radius: 0 0 8px 8px;
        background: #ffffff;
        padding: 1.5rem;
    }

    @media (min-width: 640px) {
        .gk-login-card {
            padding: 2rem;
        }
    }

    @media (min-width: 768px) {
        .gk-login-card {
            border-top: 1px solid #e5e7eb;
            border-left: 0;
            border-radius: 0 8px 8px 0;
        }
    }

    .gk-login-kicker {
        color: #e11d48;
        font-size: 0.7rem;
        font-weight: 900;
        text-transform: uppercase;
    }

    .gk-login-title {
        margin-top: 0.25rem;
        font-size: 2rem;
        font-weight: 900;
        line-height: 1;
        text-transform: uppercase;
    }

    .gk-login-copy {
        margin-top: 0.45rem;
        color: #6b7280;
        font-size: 0.82rem;
    }

    .gk-login-alert {
        margin-top: 1.2rem;
        border: 1px solid #86efac;
        border-radius: 6px;
        background: #f0fdf4;
        color: #166534;
        padding: 0.75rem 0.9rem;
        font-size: 0.78rem;
    }

    .gk-login-form {
        display: grid;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .gk-login-label-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 0.4rem;
    }

    .gk-login-label {
        display: block;
        color: #374151;
        font-size: 0.78rem;
        font-weight: 800;
    }

    .gk-login-link {
        color: #e11d48;
        font-size: 0.75rem;
        font-weight: 700;
        text-decoration: none;
    }

    .gk-login-link:hover {
        color: #be123c;
        text-decoration: underline;
    }

    .gk-login-input {
        width: 100%;
        min-height: 44px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        background: #ffffff;
        color: #111827;
        padding: 0 0.85rem;
        font-size: 0.85rem;
        outline: none;
    }

    .gk-login-input:focus {
        border-color: #e11d48;
        box-shadow: 0 0 0 3px rgba(225, 29, 72, 0.1);
    }

    .gk-login-input.is-invalid {
        border-color: #f43f5e;
    }

    .gk-login-error {
        margin-top: 0.3rem;
        color: #e11d48;
        font-size: 0.7rem;
    }

    .gk-login-remember {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #6b7280;
        font-size: 0.78rem;
    }

    .gk-login-remember input {
        width: 1rem;
        height: 1rem;
        accent-color: #e11d48;
    }

    .gk-login-submit {
        min-height: 44px;
        border: 0;
        border-radius: 6px;
        background: #e11d48;
        color: #ffffff;
        font-size: 0.85rem;
        font-weight: 900;
        cursor: pointer;
    }

    .gk-login-submit:hover {
        background: #be123c;
    }

    .gk-login-register {
        border-top: 1px solid #e5e7eb;
        margin-top: 1.3rem;
        padding-top: 1rem;
        color: #6b7280;
        font-size: 0.78rem;
        text-align: center;
    }
</style>
@endpush

@section('content')
<div class="gk-login">
    <nav class="gk-login-breadcrumb">
        <div class="gk-login-container gk-login-breadcrumb-inner">
            <a href="{{ route('home') }}">{{ __('general.home') }}</a>
            <span>›</span>
            <span style="color:#111827; font-weight:700;">{{ __('general.login') }}</span>
        </div>
    </nav>

    <div class="gk-login-container">
        <div class="gk-login-wrap">
            <aside class="gk-login-aside">
                <div>
                    <a href="{{ route('home') }}" class="gk-login-brand">
                        <span class="gk-login-brand-mark">G</span>
                        <span class="gk-login-brand-name">Gari Kothay</span>
                    </a>
                    <h2>Your automotive account</h2>
                    <p>Sign in to manage your orders, service bookings, addresses and account details.</p>
                </div>
                <div class="gk-login-benefits">
                    <span>Track your orders</span>
                    <span>Manage service requests</span>
                    <span>Faster checkout</span>
                </div>
            </aside>

            <section class="gk-login-card">
                <div class="gk-login-kicker">My Account</div>
                <h1 class="gk-login-title">{{ __('general.welcome_back') }}</h1>
                <p class="gk-login-copy">Enter your account details to continue.</p>

                @if(session('status'))
                    <div class="gk-login-alert">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="gk-login-form">
                    @csrf

                    <div>
                        <label for="email" class="gk-login-label">{{ __('general.email') }}</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                               autocomplete="email"
                               class="gk-login-input @error('email') is-invalid @enderror">
                        @error('email')<p class="gk-login-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <div class="gk-login-label-row">
                            <label for="password" class="gk-login-label">{{ __('general.password') }}</label>
                            @if(Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="gk-login-link">
                                    {{ __('general.forgot_password') }}
                                </a>
                            @endif
                        </div>
                        <input id="password" type="password" name="password" required
                               autocomplete="current-password"
                               class="gk-login-input @error('password') is-invalid @enderror">
                        @error('password')<p class="gk-login-error">{{ $message }}</p>@enderror
                    </div>

                    <label class="gk-login-remember">
                        <input type="checkbox" name="remember">
                        <span>{{ __('general.remember_me') }}</span>
                    </label>

                    <button type="submit" class="gk-login-submit">{{ __('general.login') }}</button>
                </form>

                <p class="gk-login-register">
                    {{ __('general.no_account') }}
                    <a href="{{ route('register') }}" class="gk-login-link">{{ __('general.register') }}</a>
                </p>
            </section>
        </div>
    </div>
</div>
@endsection
