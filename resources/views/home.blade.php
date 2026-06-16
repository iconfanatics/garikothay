@extends('layouts.app')

@section('title', 'Gari Kothay - Auto Marketplace')
@section('meta_description', 'Gari Kothay - car parts, garages, drivers, GPS tracker, car wash, fuel stations and automotive services in Bangladesh.')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Oswald:wght@500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --gk-primary: #e11d48;
        --gk-primary-dark: #be123c;
        --gk-ink: #111827;
        --gk-nav: #1f2937;
        --gk-muted: #6b7280;
        --gk-soft: #f3f4f6;
        --gk-line: #e5e7eb;
        --gk-card: #ffffff;
        --gk-warn: #f59e0b;
        --gk-sky: #0284c7;
        --gk-green: #16a34a;
    }

    body {
        background: #ffffff !important;
        color: var(--gk-ink) !important;
        font-family: 'Inter', system-ui, sans-serif !important;
    }

    h1, h2, h3, h4 {
        font-family: 'Oswald', 'Inter', sans-serif !important;
        letter-spacing: 0 !important;
    }

    .gk-page {
        background: #ffffff;
        color: var(--gk-ink);
    }

    .gk-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    .gk-section {
        padding: 2.75rem 0;
    }

    .gk-section-muted {
        background: var(--gk-soft);
    }

    .gk-section-head {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 1rem;
        border-bottom: 2px solid var(--gk-primary);
        padding-bottom: 0.8rem;
        margin-bottom: 1.5rem;
    }

    .gk-section-title {
        font-size: clamp(1.55rem, 3vw, 2rem);
        line-height: 1.1;
        font-weight: 800;
        text-transform: uppercase;
    }

    .gk-section-subtitle {
        margin-top: 0.35rem;
        color: var(--gk-muted);
        font-size: 0.92rem;
    }

    .gk-view-link {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        color: var(--gk-primary);
        font-weight: 700;
        font-size: 0.9rem;
        white-space: nowrap;
        text-decoration: none;
    }

    .gk-view-link:hover {
        color: var(--gk-primary-dark);
    }

    .gk-hero-wrap {
        background: #f8fafc;
        border-bottom: 1px solid var(--gk-line);
    }

    .gk-hero-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
        padding: 1.5rem 1rem;
    }

    @media (min-width: 1024px) {
        .gk-hero-grid {
            grid-template-columns: 240px minmax(0, 1fr) 280px;
        }
    }

    .gk-sidebar {
        display: none;
        overflow: hidden;
        border: 1px solid var(--gk-line);
        border-radius: 8px;
        background: var(--gk-card);
    }

    @media (min-width: 1024px) {
        .gk-sidebar {
            display: block;
        }
    }

    .gk-sidebar-title {
        background: var(--gk-ink);
        color: #ffffff;
        padding: 0.85rem 1rem;
        font-size: 0.82rem;
        font-weight: 800;
        text-transform: uppercase;
    }

    .gk-sidebar-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        border-bottom: 1px solid var(--gk-line);
        padding: 0.72rem 1rem;
        font-size: 0.86rem;
        color: var(--gk-ink);
        text-decoration: none;
    }

    .gk-sidebar-link:hover {
        background: var(--gk-soft);
        color: var(--gk-primary);
    }

    .gk-sidebar-icon {
        display: grid;
        place-items: center;
        width: 1.45rem;
        height: 1.45rem;
        color: var(--gk-primary);
    }

    .gk-hero {
        position: relative;
        min-height: 340px;
        overflow: hidden;
        border-radius: 8px;
        background: #000000;
        color: #ffffff;
    }

    @media (min-width: 768px) {
        .gk-hero {
            min-height: 430px;
        }
    }

    .gk-hero-slide {
        position: absolute;
        inset: 0;
        opacity: 0;
        transition: opacity 0.55s ease;
    }

    .gk-hero-slide.is-active {
        opacity: 1;
    }

    .gk-hero-slide img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .gk-hero-shade {
        position: absolute;
        inset: 0;
        background: linear-gradient(120deg, rgba(17,24,39,0.9), rgba(190,18,60,0.68), rgba(0,0,0,0.78));
    }

    .gk-hero-content {
        position: relative;
        z-index: 2;
        display: flex;
        min-height: 340px;
        flex-direction: column;
        justify-content: center;
        padding: 2rem;
    }

    @media (min-width: 768px) {
        .gk-hero-content {
            min-height: 430px;
            padding: 3rem;
        }
    }

    .gk-eyebrow {
        width: fit-content;
        border-radius: 999px;
        background: rgba(255,255,255,0.15);
        padding: 0.35rem 0.8rem;
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
    }

    .gk-hero-title {
        max-width: 560px;
        margin-top: 1rem;
        font-size: clamp(2rem, 5vw, 4.2rem);
        line-height: 1.02;
        font-weight: 900;
        text-transform: uppercase;
    }

    .gk-hero-copy {
        max-width: 500px;
        margin-top: 0.9rem;
        color: rgba(255,255,255,0.82);
        line-height: 1.7;
    }

    .gk-hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }

    .gk-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        border-radius: 6px;
        padding: 0.78rem 1.2rem;
        font-weight: 800;
        font-size: 0.9rem;
        text-decoration: none;
        transition: transform 0.2s ease, background 0.2s ease, color 0.2s ease;
    }

    .gk-btn:hover {
        transform: translateY(-1px);
    }

    .gk-btn-light {
        background: #ffffff;
        color: #000000;
    }

    .gk-btn-light:hover {
        background: var(--gk-primary);
        color: #ffffff;
    }

    .gk-btn-dark {
        background: rgba(17,24,39,0.72);
        color: #ffffff;
        border: 1px solid rgba(255,255,255,0.28);
    }

    .gk-hero-dots {
        position: absolute;
        z-index: 5;
        left: 50%;
        bottom: 1rem;
        transform: translateX(-50%);
        display: flex;
        gap: 0.38rem;
    }

    .gk-hero-dot {
        width: 0.45rem;
        height: 0.45rem;
        border: 0;
        border-radius: 999px;
        background: rgba(255,255,255,0.45);
        cursor: pointer;
    }

    .gk-hero-dot.is-active {
        width: 2rem;
        background: #ffffff;
    }

    .gk-promo-stack {
        display: none;
        flex-direction: column;
        gap: 0.75rem;
    }

    @media (min-width: 1024px) {
        .gk-promo-stack {
            display: flex;
        }
    }

    .gk-mini-promo {
        min-height: 90px;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        border-radius: 8px;
        padding: 1rem;
        color: #ffffff;
        text-decoration: none;
    }

    .gk-mini-promo span {
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        opacity: 0.8;
    }

    .gk-mini-promo strong {
        font-family: 'Oswald', 'Inter', sans-serif;
        font-size: 1.25rem;
        font-weight: 800;
        line-height: 1.1;
    }

    .gk-trust {
        border-top: 1px solid var(--gk-line);
        border-bottom: 1px solid var(--gk-line);
        background: #ffffff;
    }

    .gk-trust-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        padding: 1.25rem 1rem;
    }

    @media (min-width: 768px) {
        .gk-trust-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    .gk-trust-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .gk-trust-icon {
        display: grid;
        place-items: center;
        width: 2.75rem;
        height: 2.75rem;
        flex: 0 0 auto;
        border-radius: 999px;
        background: #fee2e2;
        color: var(--gk-primary);
        font-size: 1.25rem;
    }

    .gk-trust-title {
        font-size: 0.9rem;
        font-weight: 800;
    }

    .gk-trust-text {
        margin-top: 0.1rem;
        color: var(--gk-muted);
        font-size: 0.76rem;
    }

    .gk-category-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.8rem;
    }

    @media (min-width: 640px) {
        .gk-category-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (min-width: 1024px) {
        .gk-category-grid {
            grid-template-columns: repeat(6, 1fr);
        }
    }

    .gk-category-card {
        display: flex;
        min-height: 135px;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.65rem;
        border: 1px solid var(--gk-line);
        border-radius: 8px;
        background: #ffffff;
        padding: 1rem;
        text-align: center;
        color: var(--gk-ink);
        text-decoration: none;
    }

    .gk-category-card:hover {
        border-color: var(--gk-primary);
        box-shadow: 0 12px 28px rgba(17,24,39,0.08);
    }

    .gk-category-icon {
        display: grid;
        place-items: center;
        width: 3.5rem;
        height: 3.5rem;
        overflow: hidden;
        border-radius: 999px;
        background: var(--gk-soft);
        font-size: 1.55rem;
    }

    .gk-category-icon img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .gk-category-name {
        font-size: 0.9rem;
        font-weight: 800;
    }

    .gk-category-count {
        color: var(--gk-muted);
        font-size: 0.74rem;
    }

    .gk-product-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
    }

    @media (min-width: 768px) {
        .gk-product-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (min-width: 1024px) {
        .gk-product-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
    }

    @media (min-width: 1280px) {
        .gk-product-grid {
            grid-template-columns: repeat(5, minmax(0, 1fr));
        }
    }

    .gk-product-grid > div {
        border: 1px solid var(--gk-line);
        border-radius: 8px !important;
        box-shadow: none !important;
    }

    .gk-product-grid > div:hover {
        border-color: var(--gk-primary);
        box-shadow: 0 14px 30px rgba(17,24,39,0.1) !important;
    }

    .gk-service-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    @media (min-width: 768px) {
        .gk-service-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    .gk-service-card {
        position: relative;
        min-height: 180px;
        overflow: hidden;
        border: 1px solid var(--gk-line);
        border-radius: 8px;
        background: #ffffff;
        padding: 1.35rem;
        color: var(--gk-ink);
        text-decoration: none;
    }

    .gk-service-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 16px 34px rgba(17,24,39,0.1);
    }

    .gk-service-icon {
        display: grid;
        place-items: center;
        width: 3rem;
        height: 3rem;
        border-radius: 8px;
        color: #ffffff;
        font-size: 1.45rem;
    }

    .gk-service-card h3 {
        margin-top: 1rem;
        font-size: 1.15rem;
        font-weight: 800;
        text-transform: uppercase;
    }

    .gk-service-card p {
        margin-top: 0.35rem;
        color: var(--gk-muted);
        font-size: 0.88rem;
        line-height: 1.55;
    }

    .gk-service-more {
        margin-top: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        color: var(--gk-primary);
        font-weight: 800;
        font-size: 0.85rem;
    }

    .gk-review-grid, .gk-blog-grid {
        display: grid;
        gap: 1rem;
    }

    @media (min-width: 768px) {
        .gk-review-grid, .gk-blog-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    .gk-review-card, .gk-blog-card {
        border: 1px solid var(--gk-line);
        border-radius: 8px;
        background: #ffffff;
        padding: 1.2rem;
        color: var(--gk-ink);
        text-decoration: none;
    }

    .gk-review-card:hover, .gk-blog-card:hover {
        border-color: var(--gk-primary);
        box-shadow: 0 14px 28px rgba(17,24,39,0.08);
    }

    .gk-stars {
        display: flex;
        gap: 0.16rem;
        color: var(--gk-warn);
    }

    .gk-review-text {
        margin-top: 0.8rem;
        color: #374151;
        font-size: 0.92rem;
        line-height: 1.65;
    }

    .gk-avatar {
        display: grid;
        place-items: center;
        width: 2.55rem;
        height: 2.55rem;
        border-radius: 999px;
        background: var(--gk-primary);
        color: #ffffff;
        font-weight: 900;
    }

    .gk-stats {
        background: var(--gk-ink);
        color: #ffffff;
    }

    .gk-stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        padding: 3rem 1rem;
        text-align: center;
    }

    @media (min-width: 768px) {
        .gk-stats-grid {
            grid-template-columns: repeat(5, 1fr);
        }
    }

    .gk-stat-value {
        color: var(--gk-primary);
        font-family: 'Oswald', 'Inter', sans-serif;
        font-size: clamp(1.9rem, 4vw, 2.5rem);
        font-weight: 900;
    }

    .gk-stat-label {
        margin-top: 0.3rem;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        color: rgba(255,255,255,0.7);
    }

    .gk-app-promo {
        display: grid;
        gap: 2rem;
        border-radius: 8px;
        background: linear-gradient(135deg, #111827, #374151, #be123c);
        color: #ffffff;
        padding: 2rem;
    }

    @media (min-width: 768px) {
        .gk-app-promo {
            grid-template-columns: 1.2fr 0.8fr;
            padding: 3rem;
        }
    }

    .gk-store-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }

    .gk-store-button {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        border-radius: 8px;
        background: #000000;
        color: #ffffff;
        padding: 0.85rem 1rem;
        text-decoration: none;
    }

    .gk-newsletter {
        border-top: 1px solid var(--gk-line);
        border-bottom: 1px solid var(--gk-line);
        background: #f8fafc;
    }

    .gk-newsletter-grid {
        display: grid;
        align-items: center;
        gap: 1.5rem;
        padding: 2.5rem 1rem;
    }

    @media (min-width: 768px) {
        .gk-newsletter-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    .gk-newsletter-form {
        display: flex;
        overflow: hidden;
        border: 2px solid var(--gk-primary);
        border-radius: 6px;
        background: #ffffff;
    }

    .gk-newsletter-form input {
        min-width: 0;
        flex: 1;
        border: 0;
        outline: 0;
        padding: 0.9rem 1rem;
        font-size: 0.95rem;
    }

    .gk-newsletter-form button {
        background: var(--gk-primary);
        color: #ffffff;
        padding: 0 1.25rem;
        font-weight: 900;
    }

    .gk-blog-image {
        height: 180px;
        margin: -1.2rem -1.2rem 1rem;
        overflow: hidden;
        border-radius: 8px 8px 0 0;
        background: var(--gk-soft);
    }

    .gk-blog-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .gk-blog-tag {
        color: var(--gk-primary);
        font-size: 0.74rem;
        font-weight: 900;
        text-transform: uppercase;
    }

    .gk-blog-title {
        margin-top: 0.35rem;
        font-family: 'Inter', system-ui, sans-serif !important;
        font-size: 1rem;
        font-weight: 800;
        line-height: 1.45;
    }

    .gk-blog-excerpt {
        margin-top: 0.5rem;
        color: var(--gk-muted);
        font-size: 0.86rem;
        line-height: 1.6;
    }

    @media (max-width: 640px) {
        .gk-section-head {
            align-items: start;
            flex-direction: column;
        }

        .gk-hero-content {
            padding: 1.35rem;
        }

        .gk-trust-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
@php
    $fallbackSlides = collect([
        [
            'tag' => 'Mega Sale',
            'title' => 'Car Parts Mega Sale',
            'subtitle' => 'Up to 50% off on genuine engine, brake and suspension parts.',
            'button' => 'Shop Sale',
            'link' => route('shop.index'),
            'image' => asset('images/hero-banner.png'),
        ],
        [
            'tag' => 'Genuine Only',
            'title' => '100% Genuine Parts',
            'subtitle' => 'Original parts, accessories, GPS tracker and car care items in one marketplace.',
            'button' => 'Explore Products',
            'link' => route('shop.index'),
            'image' => asset('images/products-banner.png'),
        ],
        [
            'tag' => 'Service',
            'title' => 'Garage Services Near You',
            'subtitle' => 'Find trusted garages, drivers, car wash and fuel support across Bangladesh.',
            'button' => 'See Services',
            'link' => '#vehicle-services',
            'image' => asset('images/services-banner.png'),
        ],
    ]);

    $slides = $banners->isNotEmpty()
        ? $banners->map(fn ($banner) => [
            'tag' => 'Gari Kothay',
            'title' => $banner->translate()?->title ?? 'Gari Kothay Auto Marketplace',
            'subtitle' => $banner->translate()?->subtitle ?? 'Car parts, services and vehicle solutions in one place.',
            'button' => $banner->translate()?->button_text ?? 'Shop Now',
            'link' => $banner->link ?? route('shop.index'),
            'image' => Storage::url($banner->image),
        ])
        : $fallbackSlides;

    $saleProducts = $featured->filter(fn ($product) => (float) ($product->compare_price ?? 0) > (float) ($product->price ?? 0))->take(5);
    if ($saleProducts->isEmpty()) {
        $saleProducts = $featured->take(5);
    }

    $serviceCards = [
        ['icon' => '🔧', 'name' => 'Garage Kothay', 'desc' => 'Find trusted garages near you with booking support.', 'href' => '#', 'bg' => 'linear-gradient(135deg,#ef4444,#be123c)'],
        ['icon' => '💦', 'name' => 'CarWash Kothay', 'desc' => 'Book car wash and detailing packages online.', 'href' => '#', 'bg' => 'linear-gradient(135deg,#0ea5e9,#2563eb)'],
        ['icon' => '⛽', 'name' => 'Fuel Kothay', 'desc' => 'Discover nearby fuel stations and route support.', 'href' => '#', 'bg' => 'linear-gradient(135deg,#f59e0b,#ea580c)'],
        ['icon' => '👤', 'name' => 'Driver Kothay', 'desc' => 'Hire verified drivers by the hour, day or month.', 'href' => '#', 'bg' => 'linear-gradient(135deg,#10b981,#16a34a)'],
        ['icon' => '📍', 'name' => 'GPS Tracker', 'desc' => 'Devices, installation and live monitoring plans.', 'href' => '#', 'bg' => 'linear-gradient(135deg,#06b6d4,#0f766e)'],
        ['icon' => '🎫', 'name' => 'Ticket Kothay', 'desc' => 'Bus, train and launch ticket support in one place.', 'href' => '#', 'bg' => 'linear-gradient(135deg,#6366f1,#2563eb)'],
        ['icon' => '🏫', 'name' => 'Driving School', 'desc' => 'Compare driving schools, courses and reviews.', 'href' => '#', 'bg' => 'linear-gradient(135deg,#8b5cf6,#7c3aed)'],
        ['icon' => '🧮', 'name' => 'Fare Calculator', 'desc' => 'Estimate distance, fuel cost and fare quickly.', 'href' => '#', 'bg' => 'linear-gradient(135deg,#eab308,#d97706)'],
        ['icon' => '🛒', 'name' => 'Auto Shop', 'desc' => 'Shop parts, accessories, oils, lights and tools.', 'href' => route('shop.index'), 'bg' => 'linear-gradient(135deg,#ec4899,#be123c)'],
    ];

    $stats = [
        ['value' => '500+', 'label' => 'Total Products'],
        ['value' => '10+', 'label' => 'Service Types'],
        ['value' => '64', 'label' => 'District Reach'],
        ['value' => '24/7', 'label' => 'Support'],
        ['value' => '10K+', 'label' => 'Happy Customers'],
    ];
@endphp

<div class="gk-page">
    <section class="gk-hero-wrap">
        <div class="gk-container gk-hero-grid">
            <aside class="gk-sidebar">
                <div class="gk-sidebar-title">Categories</div>
                @foreach($categories->take(10) as $category)
                    <a href="{{ route('shop.index', ['category' => $category->slug]) }}" class="gk-sidebar-link">
                        <span class="gk-sidebar-icon">{{ $category->icon ?? '⚙' }}</span>
                        <span style="flex:1;">{{ $category->name }}</span>
                        <span aria-hidden="true">›</span>
                    </a>
                @endforeach
            </aside>

            <div class="gk-hero" x-data="{
                current: 0,
                total: {{ $slides->count() }},
                init() {
                    if (this.total > 1) {
                        setInterval(() => this.current = (this.current + 1) % this.total, 5000);
                    }
                }
            }">
                @foreach($slides as $index => $slide)
                    <article class="gk-hero-slide" :class="{ 'is-active': current === {{ $index }} }">
                        <img src="{{ $slide['image'] }}" alt="{{ $slide['title'] }}">
                        <div class="gk-hero-shade"></div>
                        <div class="gk-hero-content">
                            <span class="gk-eyebrow">{{ $slide['tag'] }}</span>
                            <h1 class="gk-hero-title">{{ $slide['title'] }}</h1>
                            <p class="gk-hero-copy">{{ $slide['subtitle'] }}</p>
                            <div class="gk-hero-actions">
                                <a href="{{ $slide['link'] }}" class="gk-btn gk-btn-light">
                                    {{ $slide['button'] }}
                                    <span aria-hidden="true">→</span>
                                </a>
                                <a href="#vehicle-services" class="gk-btn gk-btn-dark">
                                    Services
                                    <span aria-hidden="true">→</span>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach

                @if($slides->count() > 1)
                    <div class="gk-hero-dots">
                        @foreach($slides as $index => $slide)
                            <button type="button" class="gk-hero-dot" :class="{ 'is-active': current === {{ $index }} }" @click="current = {{ $index }}" aria-label="Show slide {{ $index + 1 }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="gk-promo-stack">
                <a href="{{ route('shop.index') }}" class="gk-mini-promo" style="background: linear-gradient(135deg, #e11d48, #be123c);">
                    <span>All Brake Parts</span>
                    <strong>Up to 50% Off</strong>
                </a>
                <a href="{{ route('shop.index') }}" class="gk-mini-promo" style="background: linear-gradient(135deg, #374151, #000000);">
                    <span>Orders over ৳5,000</span>
                    <strong>Free Shipping</strong>
                </a>
                <a href="{{ route('shop.index') }}" class="gk-mini-promo" style="background: linear-gradient(135deg, #0891b2, #1d4ed8);">
                    <span>Device + Install</span>
                    <strong>GPS Tracker</strong>
                </a>
                <a href="#vehicle-services" class="gk-mini-promo" style="background: linear-gradient(135deg, #f59e0b, #c2410c);">
                    <span>Service Bundles</span>
                    <strong>15% Off Packs</strong>
                </a>
            </div>
        </div>
    </section>

    <section class="gk-trust">
        <div class="gk-container gk-trust-grid">
            <div class="gk-trust-item">
                <div class="gk-trust-icon">🚚</div>
                <div>
                    <div class="gk-trust-title">Free Delivery</div>
                    <div class="gk-trust-text">Orders over ৳5,000</div>
                </div>
            </div>
            <div class="gk-trust-item">
                <div class="gk-trust-icon">🛡</div>
                <div>
                    <div class="gk-trust-title">Genuine Products</div>
                    <div class="gk-trust-text">100% authentic items</div>
                </div>
            </div>
            <div class="gk-trust-item">
                <div class="gk-trust-icon">↻</div>
                <div>
                    <div class="gk-trust-title">Easy Returns</div>
                    <div class="gk-trust-text">7-day return policy</div>
                </div>
            </div>
            <div class="gk-trust-item">
                <div class="gk-trust-icon">☎</div>
                <div>
                    <div class="gk-trust-title">24/7 Support</div>
                    <div class="gk-trust-text">Call us anytime</div>
                </div>
            </div>
        </div>
    </section>

    <section class="gk-section">
        <div class="gk-container">
            <div class="gk-section-head">
                <div>
                    <h2 class="gk-section-title">Mega Sale</h2>
                    <p class="gk-section-subtitle">Best prices on selected automotive products.</p>
                </div>
                <a href="{{ route('shop.index') }}" class="gk-view-link">View All <span aria-hidden="true">→</span></a>
            </div>
            <div class="gk-product-grid">
                @foreach($saleProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>

    <section class="gk-section gk-section-muted">
        <div class="gk-container">
            <div class="gk-section-head">
                <div>
                    <h2 class="gk-section-title">Top Categories</h2>
                    <p class="gk-section-subtitle">Find products by type, brand and vehicle needs.</p>
                </div>
                <a href="{{ route('shop.index') }}" class="gk-view-link">View All <span aria-hidden="true">→</span></a>
            </div>
            <div class="gk-category-grid">
                @foreach($categories->take(12) as $category)
                    <a href="{{ route('shop.index', ['category' => $category->slug]) }}" class="gk-category-card">
                        <span class="gk-category-icon">
                            @if($category->image)
                                <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}">
                            @else
                                {{ $category->icon ?? '🔩' }}
                            @endif
                        </span>
                        <span class="gk-category-name">{{ $category->name }}</span>
                        @if(isset($category->products_count))
                            <span class="gk-category-count">{{ $category->products_count }} items</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="gk-section">
        <div class="gk-container">
            <div class="gk-section-head">
                <div>
                    <h2 class="gk-section-title">New Arrivals</h2>
                    <p class="gk-section-subtitle">Freshly added parts and accessories.</p>
                </div>
                <a href="{{ route('shop.index') }}" class="gk-view-link">Shop Now <span aria-hidden="true">→</span></a>
            </div>
            <div class="gk-product-grid">
                @foreach($newArrivals->take(5) as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>

    <section class="gk-section gk-section-muted">
        <div class="gk-container">
            <div class="gk-section-head">
                <div>
                    <h2 class="gk-section-title">Featured Products</h2>
                    <p class="gk-section-subtitle">Hand-picked products for your vehicle.</p>
                </div>
                <a href="{{ route('shop.index') }}" class="gk-view-link">View All <span aria-hidden="true">→</span></a>
            </div>
            <div class="gk-product-grid">
                @foreach($featured->take(5) as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>

    <section class="gk-section" id="vehicle-services">
        <div class="gk-container">
            <div style="max-width: 720px; margin: 0 auto 2rem; text-align: center;">
                <div style="color: var(--gk-primary); font-size: 0.75rem; font-weight: 900; text-transform: uppercase;">All-in-One</div>
                <h2 class="gk-section-title" style="margin-top: 0.35rem;">Automotive Services</h2>
                <p class="gk-section-subtitle">Everything your vehicle needs. Discover, book and manage services across Bangladesh.</p>
            </div>
            <div class="gk-service-grid">
                @foreach($serviceCards as $service)
                    <a href="{{ $service['href'] }}" class="gk-service-card">
                        <div class="gk-service-icon" style="background: {{ $service['bg'] }};">{{ $service['icon'] }}</div>
                        <h3>{{ $service['name'] }}</h3>
                        <p>{{ $service['desc'] }}</p>
                        <span class="gk-service-more">Explore <span aria-hidden="true">→</span></span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    @if($reviews->isNotEmpty())
        <section class="gk-section gk-section-muted">
            <div class="gk-container">
                <div class="gk-section-head">
                    <div>
                        <h2 class="gk-section-title">What Customers Say</h2>
                        <p class="gk-section-subtitle">Real experiences from Gari Kothay customers.</p>
                    </div>
                </div>
                <div class="gk-review-grid">
                    @foreach($reviews->take(3) as $review)
                        <article class="gk-review-card">
                            <div class="gk-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <span>{{ $i <= $review->rating ? '★' : '☆' }}</span>
                                @endfor
                            </div>
                            <p class="gk-review-text">"{{ $review->comment }}"</p>
                            <div style="display:flex; align-items:center; gap:0.75rem; margin-top:1rem; border-top:1px solid var(--gk-line); padding-top:1rem;">
                                <div class="gk-avatar">{{ strtoupper(substr($review->user->name, 0, 1)) }}</div>
                                <div style="min-width:0;">
                                    <div style="font-weight:800; font-size:0.9rem;">{{ $review->user->name }}</div>
                                    <div style="color:var(--gk-muted); font-size:0.78rem; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $review->product?->name }}</div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="gk-stats">
        <div class="gk-container gk-stats-grid">
            @foreach($stats as $stat)
                <div>
                    <div class="gk-stat-value">{{ $stat['value'] }}</div>
                    <div class="gk-stat-label">{{ $stat['label'] }}</div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="gk-section">
        <div class="gk-container">
            <div class="gk-app-promo">
                <div>
                    <div style="font-size:0.75rem; font-weight:900; text-transform:uppercase; color:rgba(255,255,255,0.72);">Mobile App</div>
                    <h2 class="gk-section-title" style="margin-top:0.4rem; color:#ffffff;">Take Gari Kothay With You</h2>
                    <p style="max-width:520px; margin-top:0.75rem; color:rgba(255,255,255,0.78); line-height:1.7;">Order parts, book services, track your GPS device and discover trusted vehicle services from your phone.</p>
                    <div class="gk-store-buttons">
                        <a href="#" class="gk-store-button">
                            <span style="font-size:1.4rem;">▣</span>
                            <span><span style="display:block; font-size:0.65rem; opacity:0.72;">GET IT ON</span><strong>Google Play</strong></span>
                        </a>
                        <a href="#" class="gk-store-button">
                            <span style="font-size:1.4rem;">◉</span>
                            <span><span style="display:block; font-size:0.65rem; opacity:0.72;">DOWNLOAD ON</span><strong>App Store</strong></span>
                        </a>
                    </div>
                </div>
                <div style="display:flex; align-items:center; justify-content:center;">
                    <div style="border-radius:8px; background:#ffffff; color:#000000; padding:1rem; text-align:center;">
                        <div style="display:grid; place-items:center; width:10rem; height:10rem; border-radius:6px; background:repeating-conic-gradient(#000 0 25%, #fff 0 50%) 0 / 14px 14px;">
                            <span style="background:#ffffff; padding:0.25rem 0.5rem; font-size:0.78rem; font-weight:900;">QR</span>
                        </div>
                        <div style="margin-top:0.55rem; font-size:0.8rem; font-weight:800;">Scan to Download</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($blogs->isNotEmpty())
        <section class="gk-section gk-section-muted">
            <div class="gk-container">
                <div class="gk-section-head">
                    <div>
                        <h2 class="gk-section-title">Automotive Tips</h2>
                        <p class="gk-section-subtitle">Guides, updates and practical advice for vehicle owners.</p>
                    </div>
                    <a href="{{ route('blog.index') }}" class="gk-view-link">Read All <span aria-hidden="true">→</span></a>
                </div>
                <div class="gk-blog-grid">
                    @foreach($blogs as $blog)
                        <a href="{{ route('blog.show', $blog->slug) }}" class="gk-blog-card">
                            <div class="gk-blog-image">
                                @if($blog->featured_image)
                                    <img src="{{ Storage::url($blog->featured_image) }}" alt="{{ $blog->title }}">
                                @else
                                    <div style="display:grid; place-items:center; height:100%; font-size:3rem;">🚗</div>
                                @endif
                            </div>
                            <span class="gk-blog-tag">{{ $blog->category?->name ?? 'Automotive' }}</span>
                            <h3 class="gk-blog-title">{{ $blog->title }}</h3>
                            <p class="gk-blog-excerpt">{{ $blog->excerpt }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="gk-newsletter" x-data="{ email: '', msg: '' }">
        <div class="gk-container gk-newsletter-grid">
            <div>
                <h2 class="gk-section-title">Get Deals in Your Inbox</h2>
                <p class="gk-section-subtitle">Subscribe for exclusive offers, new arrivals and service discounts.</p>
            </div>
            <div>
                <div class="gk-newsletter-form">
                    <input x-model="email" type="email" placeholder="your@email.com" aria-label="Email address">
                    <button type="button" @click="
                        fetch('/newsletter/subscribe', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                            body: JSON.stringify({email})
                        }).then(r => r.json()).then(d => { msg = d.message; email = ''; });
                    ">Subscribe</button>
                </div>
                <p x-show="msg" x-text="msg" style="margin-top:0.65rem; color:var(--gk-green); font-size:0.9rem;"></p>
            </div>
        </div>
    </section>
</div>
@endsection
