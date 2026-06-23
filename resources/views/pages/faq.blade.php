@extends('layouts.app')

@section('title', __('general.faq') . ' | ' . \App\Models\Setting::get('site_name', 'Garikothay'))
@section('meta_description', __('general.faq_meta_description'))

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Oswald:wght@500;600;700&display=swap" rel="stylesheet">
<style>
    .gk-faq {
        min-height: 100vh;
        background: #f8fafc;
        color: #111827;
        font-family: 'Inter', system-ui, sans-serif;
    }

    .gk-faq h1,
    .gk-faq h2,
    .gk-faq h3 {
        font-family: 'Oswald', 'Inter', sans-serif;
        letter-spacing: 0;
    }

    .gk-faq-container {
        width: 100%;
        max-width: 1504px;
        margin: 0 auto;
        padding-right: 1rem;
        padding-left: 1rem;
    }

    .gk-faq-breadcrumb {
        border-bottom: 1px solid #e5e7eb;
        background: #ffffff;
    }

    .gk-faq-breadcrumb-inner {
        display: flex;
        min-height: 42px;
        align-items: center;
        gap: 0.45rem;
        color: #6b7280;
        font-size: 0.78rem;
    }

    .gk-faq-breadcrumb a {
        color: #6b7280;
        text-decoration: none;
    }

    .gk-faq-breadcrumb a:hover {
        color: #e11d48;
    }

    .gk-faq-head {
        border-bottom: 1px solid #e5e7eb;
        background: #ffffff;
        padding: 2rem 0;
    }

    .gk-faq-kicker {
        color: #e11d48;
        font-size: 0.72rem;
        font-weight: 900;
        text-transform: uppercase;
    }

    .gk-faq-title {
        margin-top: 0.25rem;
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 900;
        line-height: 1;
        text-transform: uppercase;
    }

    .gk-faq-intro {
        max-width: 650px;
        margin-top: 0.65rem;
        color: #6b7280;
        font-size: 0.93rem;
        line-height: 1.65;
    }

    .gk-faq-layout {
        display: grid;
        gap: 2rem;
        padding-top: 2rem;
        padding-bottom: 3rem;
    }

    @media (min-width: 960px) {
        .gk-faq-layout {
            grid-template-columns: minmax(0, 1fr) minmax(300px, 0.35fr);
        }
    }

    .gk-faq-content-card {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #ffffff;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    
    @media (min-width: 640px) {
        .gk-faq-content-card {
            padding: 2.5rem;
        }
    }

    .gk-faq-content-card .prose {
        max-width: 100%;
        font-size: 0.95rem;
        color: #374151;
        line-height: 1.7;
    }

    .gk-faq-content-card .prose h2 {
        font-size: 1.75rem;
        margin-top: 2rem;
        margin-bottom: 1rem;
        color: #111827;
        font-weight: 800;
        border-bottom: 2px solid #f3f4f6;
        padding-bottom: 0.5rem;
        font-family: 'Oswald', 'Inter', sans-serif;
    }

    .gk-faq-content-card .prose h3 {
        font-size: 1.25rem;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
        color: #1f2937;
        font-weight: 700;
        font-family: 'Oswald', 'Inter', sans-serif;
    }

    .gk-faq-content-card .prose ul {
        list-style-type: disc;
        padding-left: 1.5rem;
        margin-bottom: 1.25rem;
    }

    .gk-faq-content-card .prose p {
        margin-bottom: 1.25rem;
    }

    .gk-faq-help-box {
        border-radius: 8px;
        background: #111827;
        color: #ffffff;
        padding: 2rem 1.5rem;
        text-align: center;
        position: sticky;
        top: 6rem;
    }

    .gk-faq-help-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }

    .gk-faq-help-title {
        font-size: 1.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
    }

    .gk-faq-help-desc {
        color: #9ca3af;
        font-size: 0.85rem;
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }

    .gk-faq-help-btn {
        display: inline-flex;
        min-height: 44px;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        border-radius: 6px;
        background: #e11d48;
        color: #ffffff;
        padding: 0 1.5rem;
        font-size: 0.85rem;
        font-weight: 800;
        text-decoration: none;
        transition: background 0.2s;
        text-transform: uppercase;
        width: 100%;
    }

    .gk-faq-help-btn:hover {
        background: #be123c;
    }

</style>
@endpush

@section('content')
<div class="gk-faq">
    <nav class="gk-faq-breadcrumb">
        <div class="gk-faq-container gk-faq-breadcrumb-inner">
            <a href="{{ route('home') }}">{{ __('general.home') }}</a>
            <span>›</span>
            <span style="color:#111827; font-weight:700;">{{ __('general.faq') }}</span>
        </div>
    </nav>

    <header class="gk-faq-head">
        <div class="gk-faq-container">
            <div class="gk-faq-kicker">Knowledge Base</div>
            <h1 class="gk-faq-title">{{ __('general.faq') }}</h1>
            <p class="gk-faq-intro">
                {{ __('general.faq_subtitle') }}
            </p>
        </div>
    </header>

    <div class="gk-faq-container gk-faq-layout">
        <!-- Dynamic Content -->
        <main class="gk-faq-content-card">
            <div class="prose">
                {!! $page->content !!}
            </div>
        </main>

        <!-- Still have questions? -->
        <aside>
            <div class="gk-faq-help-box">
                <div class="gk-faq-help-icon">💬</div>
                <h3 class="gk-faq-help-title">{{ __('general.still_have_questions') }}</h3>
                <p class="gk-faq-help-desc">{{ __('general.team_happy_to_help') }}</p>
                <a href="{{ route('page.contact') }}" class="gk-faq-help-btn">
                    {{ __('general.contact_us') }}
                </a>
            </div>
        </aside>
    </div>
</div>
@endsection
