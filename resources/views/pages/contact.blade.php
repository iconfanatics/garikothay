@extends('layouts.app')

@section('title', __('general.contact_us') . ' | ' . \App\Models\Setting::get('site_name', 'Garikothay'))
@section('meta_description', 'Contact Garikothay for order support, product questions and automotive service assistance.')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Oswald:wght@500;600;700&display=swap" rel="stylesheet">
<style>
    .gk-contact {
        min-height: 100vh;
        background: #f8fafc;
        color: #111827;
        font-family: 'Inter', system-ui, sans-serif;
    }

    .gk-contact h1,
    .gk-contact h2,
    .gk-contact h3 {
        font-family: 'Oswald', 'Inter', sans-serif;
        letter-spacing: 0;
    }

    .gk-contact-container {
        width: 100%;
        max-width: 1504px;
        margin: 0 auto;
        padding-right: 1rem;
        padding-left: 1rem;
    }

    .gk-contact-breadcrumb {
        border-bottom: 1px solid #e5e7eb;
        background: #ffffff;
    }

    .gk-contact-breadcrumb-inner {
        display: flex;
        min-height: 42px;
        align-items: center;
        gap: 0.45rem;
        color: #6b7280;
        font-size: 0.78rem;
    }

    .gk-contact-breadcrumb a {
        color: #6b7280;
        text-decoration: none;
    }

    .gk-contact-breadcrumb a:hover {
        color: #e11d48;
    }

    .gk-contact-head {
        border-bottom: 1px solid #e5e7eb;
        background: #ffffff;
        padding: 2rem 0;
    }

    .gk-contact-kicker {
        color: #e11d48;
        font-size: 0.72rem;
        font-weight: 900;
        text-transform: uppercase;
    }

    .gk-contact-title {
        margin-top: 0.25rem;
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 900;
        line-height: 1;
        text-transform: uppercase;
    }

    .gk-contact-intro {
        max-width: 650px;
        margin-top: 0.65rem;
        color: #6b7280;
        font-size: 0.93rem;
        line-height: 1.65;
    }

    .gk-contact-layout {
        display: grid;
        gap: 1.5rem;
        padding-top: 2rem;
        padding-bottom: 3rem;
    }

    @media (min-width: 960px) {
        .gk-contact-layout {
            grid-template-columns: minmax(280px, 0.72fr) minmax(0, 1.5fr);
        }
    }

    .gk-contact-info {
        overflow: hidden;
        border-radius: 8px;
        background: #111827;
        color: #ffffff;
    }

    .gk-contact-info-head {
        border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        padding: 1.35rem;
    }

    .gk-contact-info-head h2 {
        font-size: 1.4rem;
        font-weight: 800;
        text-transform: uppercase;
    }

    .gk-contact-info-head p {
        margin-top: 0.35rem;
        color: #9ca3af;
        font-size: 0.8rem;
        line-height: 1.55;
    }

    .gk-contact-methods {
        display: grid;
    }

    .gk-contact-method {
        display: grid;
        grid-template-columns: 2.6rem minmax(0, 1fr);
        gap: 0.8rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding: 1.1rem 1.35rem;
    }

    .gk-contact-method:last-child {
        border-bottom: 0;
    }

    .gk-contact-icon {
        display: grid;
        width: 2.6rem;
        height: 2.6rem;
        place-items: center;
        border-radius: 6px;
        background: #e11d48;
        color: #ffffff;
        font-size: 1rem;
    }

    .gk-contact-method-label {
        color: #9ca3af;
        font-size: 0.68rem;
        font-weight: 800;
        text-transform: uppercase;
    }

    .gk-contact-method-value {
        display: block;
        margin-top: 0.2rem;
        color: #ffffff;
        font-size: 0.9rem;
        font-weight: 700;
        line-height: 1.5;
        text-decoration: none;
        overflow-wrap: anywhere;
    }

    .gk-contact-method-value:hover {
        color: #fda4af;
    }

    .gk-contact-note {
        display: block;
        margin-top: 0.15rem;
        color: #9ca3af;
        font-size: 0.72rem;
    }

    .gk-contact-whatsapp {
        display: flex;
        min-height: 48px;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin: 1.35rem;
        border-radius: 6px;
        background: #16a34a;
        color: #ffffff;
        font-size: 0.82rem;
        font-weight: 900;
        text-decoration: none;
    }

    .gk-contact-whatsapp:hover {
        background: #15803d;
    }

    .gk-contact-form-card {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #ffffff;
        padding: 1.35rem;
    }

    @media (min-width: 640px) {
        .gk-contact-form-card {
            padding: 2rem;
        }
    }

    .gk-contact-form-head {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 1rem;
        border-bottom: 2px solid #e11d48;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
    }

    .gk-contact-form-head h2 {
        font-size: 1.55rem;
        font-weight: 900;
        text-transform: uppercase;
    }

    .gk-contact-form-head span {
        color: #6b7280;
        font-size: 0.72rem;
    }

    .gk-contact-alert {
        margin-bottom: 1.25rem;
        border-radius: 6px;
        padding: 0.85rem 1rem;
        font-size: 0.82rem;
        font-weight: 700;
    }

    .gk-contact-alert-success {
        border: 1px solid #86efac;
        background: #f0fdf4;
        color: #166534;
    }

    .gk-contact-alert-error {
        border: 1px solid #fda4af;
        background: #fff1f2;
        color: #be123c;
    }

    .gk-contact-form {
        display: grid;
        gap: 1rem;
    }

    .gk-contact-row {
        display: grid;
        gap: 1rem;
    }

    @media (min-width: 640px) {
        .gk-contact-row {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    .gk-contact-field label {
        display: block;
        margin-bottom: 0.4rem;
        color: #374151;
        font-size: 0.78rem;
        font-weight: 800;
    }

    .gk-contact-required {
        color: #e11d48;
    }

    .gk-contact-input {
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

    textarea.gk-contact-input {
        min-height: 150px;
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
        resize: vertical;
    }

    .gk-contact-input:focus {
        border-color: #e11d48;
        box-shadow: 0 0 0 3px rgba(225, 29, 72, 0.1);
    }

    .gk-contact-input.is-invalid {
        border-color: #f43f5e;
    }

    .gk-contact-error {
        margin-top: 0.3rem;
        color: #e11d48;
        font-size: 0.7rem;
    }

    .gk-contact-submit {
        display: inline-flex;
        min-height: 44px;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        border: 0;
        border-radius: 6px;
        background: #e11d48;
        color: #ffffff;
        padding: 0 1.25rem;
        font-size: 0.82rem;
        font-weight: 900;
        cursor: pointer;
    }

    .gk-contact-submit:hover {
        background: #be123c;
    }

    @media (max-width: 639px) {
        .gk-contact-form-head {
            align-items: flex-start;
            flex-direction: column;
            gap: 0.25rem;
        }

        .gk-contact-submit {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
@php
    $whatsapp = preg_replace('/[^0-9+]/', '', (string) \App\Models\Setting::get('whatsapp', $phone));
@endphp

<div class="gk-contact">
    <nav class="gk-contact-breadcrumb">
        <div class="gk-contact-container gk-contact-breadcrumb-inner">
            <a href="{{ route('home') }}">{{ __('general.home') }}</a>
            <span>›</span>
            <span style="color:#111827; font-weight:700;">{{ __('general.contact_us') }}</span>
        </div>
    </nav>

    <header class="gk-contact-head">
        <div class="gk-contact-container">
            <div class="gk-contact-kicker">Customer Support</div>
            <h1 class="gk-contact-title">{{ __('general.contact_us') }}</h1>
            <p class="gk-contact-intro">
                Need help with an order, product or automotive service? Send us a message and our support team will get back to you.
            </p>
        </div>
    </header>

    <div class="gk-contact-container gk-contact-layout">
        <aside class="gk-contact-info">
            <div class="gk-contact-info-head">
                <h2>Contact Information</h2>
                <p>Reach us through the channel that works best for you.</p>
            </div>

            <div class="gk-contact-methods">
                <div class="gk-contact-method">
                    <span class="gk-contact-icon">☎</span>
                    <div>
                        <span class="gk-contact-method-label">{{ __('general.call_us') }}</span>
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}" class="gk-contact-method-value">{{ $phone }}</a>
                        <span class="gk-contact-note">Saturday - Thursday, 9am - 8pm</span>
                    </div>
                </div>

                <div class="gk-contact-method">
                    <span class="gk-contact-icon">✉</span>
                    <div>
                        <span class="gk-contact-method-label">{{ __('general.email_us') }}</span>
                        <a href="mailto:{{ $email }}" class="gk-contact-method-value">{{ $email }}</a>
                        <span class="gk-contact-note">We usually reply within 24 hours</span>
                    </div>
                </div>

                <div class="gk-contact-method">
                    <span class="gk-contact-icon">⌖</span>
                    <div>
                        <span class="gk-contact-method-label">{{ __('general.visit_us') }}</span>
                        <span class="gk-contact-method-value">{{ $address }}</span>
                        @if(isset($address_2) && $address_2)
                        <span class="gk-contact-method-value mt-1">{{ $address_2 }}</span>
                        @endif
                    </div>
                </div>
            </div>

            @if($whatsapp)
                <a href="https://wa.me/{{ $whatsapp }}?text=Hi%2C%20I%20need%20help%20from%20Garikothay"
                   target="_blank" rel="noopener" class="gk-contact-whatsapp">
                    <span>◉</span>
                    <span>Chat on WhatsApp</span>
                </a>
            @endif
        </aside>

        <section class="gk-contact-form-card">
            <div class="gk-contact-form-head">
                <h2>{{ __('general.send_message') }}</h2>
                <span>Fields marked * are required</span>
            </div>

            @if(session('success'))
                <div class="gk-contact-alert gk-contact-alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="gk-contact-alert gk-contact-alert-error">
                    Please check the highlighted fields and try again.
                </div>
            @endif

            <form action="{{ route('page.contact.submit') }}" method="POST" class="gk-contact-form">
                @csrf

                <div class="gk-contact-row">
                    <div class="gk-contact-field">
                        <label for="contact-name">{{ __('general.your_name') }} <span class="gk-contact-required">*</span></label>
                        <input id="contact-name" type="text" name="name" value="{{ old('name') }}" required
                               autocomplete="name" class="gk-contact-input @error('name') is-invalid @enderror">
                        @error('name')<p class="gk-contact-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="gk-contact-field">
                        <label for="contact-email">{{ __('general.your_email') }} <span class="gk-contact-required">*</span></label>
                        <input id="contact-email" type="email" name="email" value="{{ old('email') }}" required
                               autocomplete="email" class="gk-contact-input @error('email') is-invalid @enderror">
                        @error('email')<p class="gk-contact-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="gk-contact-field">
                    <label for="contact-subject">{{ __('general.subject') }} <span class="gk-contact-required">*</span></label>
                    <input id="contact-subject" type="text" name="subject" value="{{ old('subject') }}" required
                           class="gk-contact-input @error('subject') is-invalid @enderror">
                    @error('subject')<p class="gk-contact-error">{{ $message }}</p>@enderror
                </div>

                <div class="gk-contact-field">
                    <label for="contact-message">{{ __('general.your_message') }} <span class="gk-contact-required">*</span></label>
                    <textarea id="contact-message" name="message" required
                              placeholder="Tell us how we can help..."
                              class="gk-contact-input @error('message') is-invalid @enderror">{{ old('message') }}</textarea>
                    @error('message')<p class="gk-contact-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <button type="submit" class="gk-contact-submit">
                        <span>{{ __('general.send_message') }}</span>
                        <span aria-hidden="true">→</span>
                    </button>
                </div>
            </form>
        </section>
    </div>
</div>
@endsection
