@extends('layouts.app')

@section('title', \App\Models\Setting::get('home_meta_title') ?: 'Gari Kothay - Auto Marketplace')
@section('meta_description', \App\Models\Setting::get('home_meta_description') ?: 'Gari Kothay - car parts, garages, drivers, GPS tracker, car wash, fuel stations and automotive services in Bangladesh.')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Oswald:wght@500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --gk-primary: #e11d48;
        --gk-rose-dark: #be123c;
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
        overflow-x: hidden;
    }

    h1, h2, h3, h4 {
        font-family: 'Oswald', 'Inter', sans-serif !important;
        letter-spacing: 0 !important;
    }

    .gk-page {
        background: #ffffff;
        color: var(--gk-ink);
        overflow-x: hidden;
    }

    nav .overflow-x-auto {
        scrollbar-width: none;
    }

    nav .overflow-x-auto::-webkit-scrollbar {
        display: none;
    }

    .gk-container {
        max-width: 1504px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    .gk-section {
        padding: 2.5rem 0;
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

    .gk-section-head .gk-section-subtitle {
        display: none;
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
        color: var(--gk-rose-dark);
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
            grid-template-columns: minmax(0, 1fr) 280px;
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
        height: 44px;
        display: flex;
        align-items: center;
        padding: 0 1rem;
        font-size: 0.82rem;
        font-weight: 800;
        text-transform: uppercase;
    }

    .gk-sidebar-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        height: 41px;
        border-bottom: 1px solid var(--gk-line);
        padding: 0 1rem;
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
        max-width: 720px;
        margin-top: 1rem;
        font-size: clamp(2rem, 4.2vw, 3.6rem);
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

    .gk-hero-nav {
        position: absolute;
        top: 50%;
        z-index: 6;
        display: grid;
        place-items: center;
        width: 2.25rem;
        height: 2.25rem;
        border: 0;
        border-radius: 999px;
        background: rgba(255,255,255,0.12);
        color: #ffffff;
        cursor: pointer;
        transform: translateY(-50%);
        backdrop-filter: blur(10px);
    }

    .gk-hero-nav:hover {
        background: rgba(255,255,255,0.22);
    }

    .gk-hero-nav-prev {
        left: 0.75rem;
    }

    .gk-hero-nav-next {
        right: 0.75rem;
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
        min-height: 78px;
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
        padding: 1.5rem 1rem;
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
        overflow: hidden;
    }

    .gk-product-grid > div:hover {
        border-color: var(--gk-primary);
        box-shadow: 0 14px 30px rgba(17,24,39,0.1) !important;
    }

    .gk-product-grid > div img {
        aspect-ratio: 1 / 1;
        height: auto !important;
        min-height: 0;
        background: var(--gk-soft);
    }

    .gk-product-grid > div span[class*="text-[#52B788]"] {
        color: var(--gk-muted) !important;
        font-size: 0.68rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .gk-product-grid > div h3 {
        min-height: 2.6rem;
        font-family: 'Inter', system-ui, sans-serif !important;
        font-size: 0.9rem;
        font-weight: 700;
        line-height: 1.45;
    }

    .gk-product-grid > div span[class*="text-[#2D6A4F]"] {
        color: var(--gk-primary) !important;
    }

    .gk-product-grid > div button[class*="bg-[#2D6A4F]"] {
        background: var(--gk-ink) !important;
        border-radius: 6px !important;
    }

    .gk-product-grid > div button[class*="bg-[#2D6A4F]"]:hover {
        background: var(--gk-primary) !important;
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
        min-height: 190px;
        overflow: hidden;
        border: 1px solid var(--gk-line);
        border-radius: 8px;
        background: #ffffff;
        padding: 1.5rem;
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

    @media (min-width: 1024px) {
        .gk-review-grid {
            grid-template-columns: repeat(4, 1fr);
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
        border-radius: 16px;
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

    .gk-partners {
        overflow: hidden;
        border-top: 1px solid var(--gk-line);
        border-bottom: 1px solid var(--gk-line);
        background: #f9fafb;
        padding: 2rem 0;
    }

    .gk-partners-head {
        margin-bottom: 1rem;
        text-align: center;
    }

    .gk-partners-kicker {
        color: var(--gk-primary);
        font-size: 0.72rem;
        font-weight: 900;
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }

    .gk-partners-title {
        margin-top: 0.25rem;
        font-size: clamp(1.35rem, 3vw, 1.7rem);
        font-weight: 900;
        text-transform: uppercase;
    }

    .gk-partner-track {
        display: flex;
        width: max-content;
        gap: 3rem;
        animation: gk-marquee 30s linear infinite;
    }

    .gk-partner-track:hover {
        animation-play-state: paused;
    }

    .gk-partner {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        flex: 0 0 auto;
        color: var(--gk-muted);
        font-family: 'Oswald', 'Inter', sans-serif;
        font-size: 1.15rem;
        font-weight: 800;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .gk-partner:hover {
        color: var(--gk-primary);
    }

    @keyframes gk-marquee {
        from { transform: translateX(0); }
        to { transform: translateX(-50%); }
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
        .gk-hero-content {
            padding: 1.35rem;
        }

        .gk-hero-nav {
            display: none;
        }
    }

    @media (max-width: 380px) {
        .gk-product-grid,
        .gk-service-grid {
            grid-template-columns: 1fr;
        }

        .gk-newsletter-form {
            flex-direction: column;
        }

        .gk-newsletter-form button {
            padding: 0.85rem 1rem;
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
            'subtitle' => 'Up to 50% off on genuine engine, brake & suspension parts.',
            'button' => 'Shop Sale',
            'link' => route('shop.index'),
            'image' => 'https://loremflickr.com/1200/600/car%2Cparts%2Cgarage?lock=1567',
        ],
        [
            'tag' => 'Genuine Only',
            'title' => '100% Genuine Parts Collection',
            'subtitle' => 'Original parts from Brembo, Bosch, NGK, Mobil 1 & more.',
            'button' => 'Explore Brands',
            'link' => route('shop.index'),
            'image' => 'https://loremflickr.com/1200/600/car%2Cengine%2Cparts?lock=1582',
        ],
        [
            'tag' => 'Service',
            'title' => 'Garage Services Near You',
            'subtitle' => 'Book trusted garages with verified ratings & live availability.',
            'button' => 'Find Garages',
            'link' => '#vehicle-services',
            'image' => 'https://loremflickr.com/1200/600/car%2Cmechanic%2Cgarage?lock=1837',
        ],
        [
            'tag' => 'Track Live',
            'title' => 'GPS Tracking Solutions',
            'subtitle' => 'Devices + installation + 24/7 monitoring plans.',
            'button' => 'See Plans',
            'link' => '#vehicle-services',
            'image' => 'https://loremflickr.com/1200/600/car%2Cdashboard%2Ctracker?lock=2082',
        ],
        [
            'tag' => 'Detailing',
            'title' => 'Car Wash & Detailing',
            'subtitle' => 'Book a wash, get a sparkle. Packages from ৳299.',
            'button' => 'Book Wash',
            'link' => '#vehicle-services',
            'image' => 'https://loremflickr.com/1200/600/car%2Cwash%2Cdetailing?lock=1778',
        ],
    ]);

    $dbSlides = \App\Models\Setting::get('theme1_hero_slides');
    $slides = empty($dbSlides) ? $fallbackSlides : collect($dbSlides)->map(function($slide) {
        return [
            'tag' => $slide['eyebrow'] ?? '',
            'title' => $slide['title'] ?? '',
            'subtitle' => $slide['copy'] ?? '',
            'button' => $slide['btn_primary_text'] ?? 'Shop Now',
            'link' => $slide['btn_primary_url'] ?? '#',
            'image' => isset($slide['image']) ? asset('storage/' . $slide['image']) : '',
            'btn2' => $slide['btn_secondary_text'] ?? null,
            'link2' => $slide['btn_secondary_url'] ?? '#',
        ];
    });

    $fallbackPromos = collect([
        ['kicker' => 'All Brake Parts', 'title' => 'Up to 50% Off', 'link' => route('shop.index'), 'bg_start' => '#e11d48', 'bg_end' => '#be123c'],
        ['kicker' => 'Orders over ৳5,000', 'title' => 'Free Shipping', 'link' => route('shop.index'), 'bg_start' => '#374151', 'bg_end' => '#000000'],
        ['kicker' => 'Device + Install', 'title' => 'GPS Tracker', 'link' => route('shop.index'), 'bg_start' => '#0891b2', 'bg_end' => '#1d4ed8'],
        ['kicker' => 'Service Bundles', 'title' => '15% Off Packs', 'link' => '#vehicle-services', 'bg_start' => '#f59e0b', 'bg_end' => '#c2410c'],
    ]);
    
    $dbPromos = \App\Models\Setting::get('theme1_promo_banners');
    $promos = empty($dbPromos) ? $fallbackPromos : collect($dbPromos);

    $fallbackTrustFeatures = collect([
        ['icon' => '🚚', 'title' => 'Free Delivery', 'subtitle' => 'Orders over ৳5,000'],
        ['icon' => '🛡', 'title' => 'Genuine Products', 'subtitle' => '100% authentic items'],
        ['icon' => '↻', 'title' => 'Easy Returns', 'subtitle' => '7-day return policy'],
        ['icon' => '☎', 'title' => '24/7 Support', 'subtitle' => 'Call us anytime'],
    ]);
    $dbTrustFeatures = \App\Models\Setting::get('theme1_trust_features');
    $trustFeatures = empty($dbTrustFeatures) ? $fallbackTrustFeatures : collect($dbTrustFeatures);

    $saleProducts = $featured->filter(fn ($product) => $product->original_price > $product->selling_price)->take(5);
    if ($saleProducts->isEmpty()) {
        $saleProducts = $featured->take(5);
    }
    $bestSellers = $featured->sortByDesc(fn ($product) => (float) ($product->reviews_count ?? $product->average_rating ?? 0))->take(5);
    if ($bestSellers->isEmpty()) {
        $bestSellers = $newArrivals->take(5);
    }

    $fallbackServiceCards = [
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
    $dbServiceCards = \App\Models\Setting::get('theme1_service_cards');
    $serviceCards = empty($dbServiceCards) ? $fallbackServiceCards : $dbServiceCards;

    $fallbackCategories = collect([
        (object) ['name' => 'Engine Parts', 'slug' => 'engine-parts', 'icon' => '⚙', 'image' => null, 'products_count' => 248],
        (object) ['name' => 'Brake Parts', 'slug' => 'brake-parts', 'icon' => '◎', 'image' => null, 'products_count' => 132],
        (object) ['name' => 'Suspension', 'slug' => 'suspension', 'icon' => '〽', 'image' => null, 'products_count' => 64],
        (object) ['name' => 'Electrical', 'slug' => 'electrical', 'icon' => '⚡', 'image' => null, 'products_count' => 174],
        (object) ['name' => 'Tires & Wheels', 'slug' => 'tires-wheels', 'icon' => '◉', 'image' => null, 'products_count' => 96],
        (object) ['name' => 'Oils & Lubricants', 'slug' => 'oils-lubricants', 'icon' => '💧', 'image' => null, 'products_count' => 64],
        (object) ['name' => 'Accessories', 'slug' => 'accessories', 'icon' => '✦', 'image' => null, 'products_count' => 312],
        (object) ['name' => 'GPS Tracker', 'slug' => 'gps-tracker', 'icon' => '⌖', 'image' => null, 'products_count' => 28],
        (object) ['name' => 'Car Care', 'slug' => 'car-care', 'icon' => '▧', 'image' => null, 'products_count' => 78],
        (object) ['name' => 'Tools', 'slug' => 'tools', 'icon' => '🔧', 'image' => null, 'products_count' => 154],
        (object) ['name' => 'Batteries', 'slug' => 'batteries', 'icon' => '▣', 'image' => null, 'products_count' => 42],
        (object) ['name' => 'Lights', 'slug' => 'lights', 'icon' => '◌', 'image' => null, 'products_count' => 121],
    ]);

    $displayCategories = $fallbackCategories->concat($categories)->unique('slug')->take(12);

    $fallbackStats = [
        ['value' => '500+', 'label' => 'Total Products'],
        ['value' => '10+', 'label' => 'Service Types'],
        ['value' => '64', 'label' => 'District Reach'],
        ['value' => '24/7', 'label' => 'Support'],
        ['value' => '10K+', 'label' => 'Happy Customers'],
    ];
    $dbStats = \App\Models\Setting::get('theme1_stats');
    $stats = empty($dbStats) ? $fallbackStats : collect($dbStats);

    $fallbackDeliveryPartners = [
        ['name' => '🚲 Pathao', 'image' => null],
        ['name' => '✈ Paperfly', 'image' => null],
        ['name' => '🚚 RedX', 'image' => null],
        ['name' => '▣ Sundarban', 'image' => null],
        ['name' => '📦 SA Paribahan', 'image' => null],
        ['name' => '🚀 Steadfast', 'image' => null],
        ['name' => '➤ eCourier', 'image' => null],
        ['name' => '⛴ Janani', 'image' => null]
    ];
    $dbDeliveryPartners = collect(\App\Models\Setting::get('theme1_delivery_partners'))->filter(fn($p) => !empty($p['name']))->toArray();
    $deliveryPartners = empty($dbDeliveryPartners) ? $fallbackDeliveryPartners : $dbDeliveryPartners;
    $partnerLoop = array_merge($deliveryPartners, $deliveryPartners);
@endphp

<div class="gk-page">
    @if(\App\Models\Setting::get('theme1_show_hero', true))
    <section class="gk-hero-wrap">
        <div class="gk-container gk-hero-grid">
            <!-- Sidebar categories removed -->

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

                        </div>
                    </article>
                @endforeach

                @if($slides->count() > 1)
                    <button type="button" class="gk-hero-nav gk-hero-nav-prev" @click="current = (current - 1 + total) % total" aria-label="Previous slide">‹</button>
                    <button type="button" class="gk-hero-nav gk-hero-nav-next" @click="current = (current + 1) % total" aria-label="Next slide">›</button>
                    <div class="gk-hero-dots">
                        @foreach($slides as $index => $slide)
                            <button type="button" class="gk-hero-dot" :class="{ 'is-active': current === {{ $index }} }" @click="current = {{ $index }}" aria-label="Show slide {{ $index + 1 }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="gk-promo-stack">
                @foreach($promos as $promo)
                <a href="{{ $promo['link'] ?? '#' }}" class="gk-mini-promo" style="background: linear-gradient(135deg, {{ $promo['bg_start'] ?? '#e11d48' }}, {{ $promo['bg_end'] ?? '#be123c' }});">
                    <span>{{ $promo['kicker'] ?? '' }}</span>
                    <strong>{{ $promo['title'] ?? '' }}</strong>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if(\App\Models\Setting::get('theme1_show_trust_features', true))
    <section class="gk-trust">
        <div class="gk-container gk-trust-grid">
            @foreach($trustFeatures as $feature)
                <div class="gk-trust-item">
                    <div class="gk-trust-icon">{!! $feature['icon'] !!}</div>
                    <div>
                        <div class="gk-trust-title">{{ $feature['title'] }}</div>
                        <div class="gk-trust-text">{{ $feature['subtitle'] ?? '' }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    @if(\App\Models\Setting::get('theme1_show_mega_sale', true))
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
    @endif

    <!-- Top Categories section removed -->
    @if(\App\Models\Setting::get('theme1_show_new_arrivals', true))
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
    @endif

    @if(\App\Models\Setting::get('theme1_show_featured', true))
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
    @endif

    @if(\App\Models\Setting::get('theme1_show_best_sellers', true))
    <section class="gk-section">
        <div class="gk-container">
            <div class="gk-section-head">
                <div>
                    <h2 class="gk-section-title">Best Sellers</h2>
                    <p class="gk-section-subtitle">Popular picks from Gari Kothay customers.</p>
                </div>
                <a href="{{ route('shop.index') }}" class="gk-view-link">View All <span aria-hidden="true">→</span></a>
            </div>
            <div class="gk-product-grid">
                @foreach($bestSellers as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if(\App\Models\Setting::get('theme1_show_services', true))
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
    @endif

    @if(\App\Models\Setting::get('theme1_show_reviews', true) && $reviews->isNotEmpty())
        <section class="gk-section gk-section-muted">
            <div class="gk-container">
                <div class="gk-section-head">
                    <div>
                        <h2 class="gk-section-title">What Customers Say</h2>
                        <p class="gk-section-subtitle">Real experiences from Gari Kothay customers.</p>
                    </div>
                </div>
                <div class="gk-review-grid">
                    @foreach($reviews->take(4) as $review)
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

    @if(\App\Models\Setting::get('theme1_show_stats', true))
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
    @endif

    @if(\App\Models\Setting::get('theme1_show_app', true))
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
    @endif

    @if(\App\Models\Setting::get('theme1_show_blogs', true) && $blogs->isNotEmpty())
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
                                    <img src="{{ $blog->featured_image_url }}" alt="{{ $blog->title }}" onerror="this.onerror=null;this.src='{{ asset('images/product-placeholder.svg') }}';">
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

    @if(\App\Models\Setting::get('theme1_show_newsletter', true))
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
    @endif

    @if(\App\Models\Setting::get('theme1_show_partners', true))
    <section class="gk-partners">
        <div class="gk-partners-head">
            <span class="gk-partners-kicker">Trusted by</span>
            <h2 class="gk-partners-title">Our Delivery Partners</h2>
        </div>
        <div style="position:relative;">
            <div class="gk-partner-track">
                @foreach($partnerLoop as $partner)
                    <span class="gk-partner">
                        @if(!empty($partner['image']))
                            <img src="{{ asset('storage/' . $partner['image']) }}" alt="{{ $partner['name'] }}" style="height: 32px; width: auto; object-fit: contain;">
                        @endif
                        <span>{{ $partner['name'] }}</span>
                    </span>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</div>
@endsection
