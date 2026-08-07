@extends('layouts.app')

@section('title', ($currentCategory ? $currentCategory->name . ' - ' : '') . __('general.shop') . ' | ' . \App\Models\Setting::get('site_name', 'Garikothay'))
@section('meta_description', $currentCategory?->description ?: __('general.meta_description_default'))
@section('og_title', ($currentCategory ? $currentCategory->name . ' - ' : '') . __('general.shop'))
@section('og_description', $currentCategory?->description ?: __('general.meta_description_default'))
@section('og_image', ($currentCategory && $currentCategory->image) ? asset(Storage::url($currentCategory->image)) : null)

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Oswald:wght@500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --shop-red: #e11d48;
        --shop-red-dark: #be123c;
        --shop-ink: #111827;
        --shop-nav: #1f2937;
        --shop-muted: #6b7280;
        --shop-soft: #f3f4f6;
        --shop-line: #e5e7eb;
    }

    .gk-shop {
        min-height: 100vh;
        background: #ffffff;
        color: var(--shop-ink);
        font-family: 'Inter', system-ui, sans-serif;
    }

    .gk-shop h1,
    .gk-shop h2,
    .gk-shop h3 {
        font-family: 'Oswald', 'Inter', sans-serif;
        letter-spacing: 0;
    }

    .gk-shop-container {
        width: 100%;
        max-width: 1504px;
        margin: 0 auto;
        padding-right: 1rem;
        padding-left: 1rem;
    }

    .gk-shop-breadcrumb {
        border-bottom: 1px solid var(--shop-line);
        background: #f8fafc;
    }

    .gk-shop-breadcrumb-inner {
        display: flex;
        min-height: 42px;
        align-items: center;
        gap: 0.45rem;
        overflow: hidden;
        color: var(--shop-muted);
        font-size: 0.78rem;
        white-space: nowrap;
    }

    .gk-shop-breadcrumb a {
        color: var(--shop-muted);
        text-decoration: none;
    }

    .gk-shop-breadcrumb a:hover {
        color: var(--shop-red);
    }

    .gk-shop-intro {
        padding: 1.5rem 0 1.25rem;
    }

    .gk-shop-intro-row {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 1rem;
    }

    .gk-shop-eyebrow {
        color: var(--shop-red);
        font-size: 0.7rem;
        font-weight: 900;
        text-transform: uppercase;
    }

    .gk-shop-title {
        margin-top: 0.15rem;
        font-size: clamp(1.8rem, 4vw, 2.5rem);
        font-weight: 900;
        line-height: 1.05;
        text-transform: uppercase;
    }

    .gk-shop-copy {
        max-width: 650px;
        margin-top: 0.4rem;
        color: var(--shop-muted);
        font-size: 0.9rem;
        line-height: 1.55;
    }

    .gk-category-rail {
        border-top: 1px solid var(--shop-line);
        border-bottom: 1px solid var(--shop-line);
        background: var(--shop-soft);
        padding: 1.1rem 0;
    }

    .gk-category-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 0.65rem;
    }

    @media (min-width: 640px) {
        .gk-category-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
    }

    @media (min-width: 1024px) {
        .gk-category-grid {
            grid-template-columns: repeat(8, minmax(0, 1fr));
        }
    }

    .gk-category-card {
        display: flex;
        min-width: 0;
        align-items: center;
        gap: 0.65rem;
        border: 1px solid var(--shop-line);
        border-radius: 8px;
        background: #ffffff;
        color: var(--shop-ink);
        padding: 0.7rem;
        text-decoration: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, color 0.2s ease;
    }

    .gk-category-card:hover,
    .gk-category-card.is-active {
        border-color: var(--shop-red);
        color: var(--shop-red);
        box-shadow: 0 8px 20px rgba(17, 24, 39, 0.07);
    }

    .gk-category-card.is-active {
        background: #fff1f2;
    }

    .gk-category-card-icon {
        display: grid;
        width: 2.25rem;
        height: 2.25rem;
        flex: 0 0 auto;
        place-items: center;
        overflow: hidden;
        border-radius: 6px;
        background: #ffe4e6;
        color: var(--shop-red);
        font-size: 1rem;
    }

    .gk-category-card-icon img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .gk-category-card-name {
        overflow: hidden;
        font-size: 0.77rem;
        font-weight: 800;
        line-height: 1.2;
        text-overflow: ellipsis;
    }

    .gk-shop-body {
        display: grid;
        gap: 1.5rem;
        padding-top: 1.5rem;
        padding-bottom: 3rem;
    }

    @media (min-width: 1024px) {
        .gk-shop-body {
            grid-template-columns: 240px minmax(0, 1fr);
        }
    }

    .gk-mobile-filter {
        display: flex;
        width: 100%;
        min-height: 40px;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        border: 1px solid var(--shop-line);
        border-radius: 6px;
        background: #ffffff;
        color: var(--shop-ink);
        font-size: 0.8rem;
        font-weight: 800;
        cursor: pointer;
    }

    .gk-mobile-filter:hover {
        border-color: var(--shop-red);
        color: var(--shop-red);
    }

    @media (min-width: 1024px) {
        .gk-mobile-filter {
            display: none;
        }
    }

    .gk-toolbar-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .gk-filter-overlay {
        display: none;
    }

    @media (max-width: 1023px) {
        .gk-filter-overlay {
            display: block;
            position: fixed;
            inset: 0;
            background: rgba(17, 24, 39, 0.6);
            backdrop-filter: blur(2px);
            z-index: 998;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        .gk-filter-overlay.is-open {
            opacity: 1;
            pointer-events: auto;
        }
        .gk-filter-stack {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 85vw;
            max-width: 320px;
            background: var(--shop-soft);
            z-index: 999;
            padding: 1.25rem;
            overflow-y: auto;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .gk-filter-stack.is-open {
            transform: translateX(0);
        }
    }

    @media (min-width: 1024px) {
        .gk-filter-stack {
            display: grid;
            gap: 1rem;
        }
    }

    .gk-filter-panel {
        border: 1px solid var(--shop-line);
        border-radius: 8px;
        background: #ffffff;
    }

    .gk-filter-heading {
        display: flex;
        min-height: 44px;
        align-items: center;
        gap: 0.55rem;
        background: var(--shop-ink);
        color: #ffffff;
        padding: 0 0.9rem;
        font-size: 0.8rem;
        font-weight: 900;
        text-transform: uppercase;
        border-radius: 7px 7px 0 0;
    }

    .gk-filter-content {
        padding: 0.65rem;
    }

    .gk-category-list {
        display: grid;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .gk-category-row {
        border-bottom: 1px solid var(--shop-line);
    }

    .gk-category-row:last-child {
        border-bottom: 0;
    }

    .gk-category-link {
        display: flex;
        min-height: 38px;
        align-items: center;
        gap: 0.55rem;
        border-radius: 5px;
        color: #374151;
        padding: 0.45rem 0.55rem;
        font-size: 0.82rem;
        text-decoration: none;
    }

    .gk-category-row:hover .gk-category-link,
    .gk-category-link.is-active {
        background: #fff1f2;
        color: var(--shop-red);
        font-weight: 800;
    }

    .gk-category-arrow {
        margin-left: auto;
        color: #9ca3af;
    }

    .gk-subcategory-list {
        display: none;
        gap: 0.2rem;
        border-left: 2px solid #ffe4e6;
        margin: 0 0 0.55rem 1rem;
        padding-left: 0.6rem;
    }

    .gk-category-row.is-open .gk-subcategory-list,
    .gk-category-row:hover .gk-subcategory-list {
        display: grid;
    }

    @media (min-width: 1024px) {
        .gk-category-row {
            position: relative;
        }
        .gk-subcategory-list {
            position: absolute;
            top: -1px;
            left: 100%;
            min-width: 220px;
            background: #ffffff;
            border: 1px solid var(--shop-line);
            border-radius: 6px;
            box-shadow: 10px 10px 25px rgba(0,0,0,0.08);
            padding: 0.75rem;
            z-index: 100;
            margin: 0;
            border-left: none;
            /* Give it a tiny gap so it doesn't overlap borders awkwardly */
            margin-left: 2px;
        }
        /* Create a pseudo-element bridge to prevent hover loss when moving mouse to submenu */
        .gk-subcategory-list::before {
            content: '';
            position: absolute;
            top: 0;
            left: -10px;
            bottom: 0;
            width: 10px;
        }
    }

    .gk-subcategory-link {
        display: block;
        border-radius: 4px;
        color: var(--shop-muted);
        padding: 0.4rem 0.6rem;
        font-size: 0.8rem;
        text-decoration: none;
    }

    .gk-subcategory-link:hover,
    .gk-subcategory-link.is-active {
        background: #fff1f2;
        color: var(--shop-red);
        font-weight: 800;
    }

    .gk-price-label {
        display: block;
        margin-bottom: 0.55rem;
        color: #374151;
        font-size: 0.78rem;
        font-weight: 800;
    }

    .gk-price-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.5rem;
    }

    .gk-filter-input,
    .gk-shop-sort {
        width: 100%;
        min-height: 40px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        background: #ffffff;
        color: var(--shop-ink);
        padding: 0 0.7rem;
        font-size: 0.8rem;
        outline: none;
    }

    .gk-filter-input:focus,
    .gk-shop-sort:focus {
        border-color: var(--shop-red);
        box-shadow: 0 0 0 2px rgba(225, 29, 72, 0.1);
    }

    .gk-filter-submit {
        width: 100%;
        min-height: 40px;
        margin-top: 0.75rem;
        border: 0;
        border-radius: 6px;
        background: var(--shop-red);
        color: #ffffff;
        font-size: 0.8rem;
        font-weight: 900;
        cursor: pointer;
    }

    .gk-filter-submit:hover {
        background: var(--shop-red-dark);
    }

    .gk-filter-clear {
        display: block;
        margin-top: 0.6rem;
        color: var(--shop-muted);
        font-size: 0.75rem;
        text-align: center;
        text-decoration: none;
    }

    .gk-filter-clear:hover {
        color: var(--shop-red);
    }

    .gk-subcategory-tabs {
        display: flex;
        gap: 0.5rem;
        overflow-x: auto;
        margin-bottom: 1rem;
        padding-bottom: 0.2rem;
        scrollbar-width: none;
    }

    .gk-subcategory-tabs::-webkit-scrollbar {
        display: none;
    }

    .gk-subcategory-tab {
        display: inline-flex;
        min-height: 36px;
        flex: 0 0 auto;
        align-items: center;
        border: 1px solid var(--shop-line);
        border-radius: 6px;
        background: #ffffff;
        color: #374151;
        padding: 0 0.8rem;
        font-size: 0.76rem;
        font-weight: 800;
        text-decoration: none;
    }

    .gk-subcategory-tab:hover,
    .gk-subcategory-tab.is-active {
        border-color: var(--shop-red);
        background: var(--shop-red);
        color: #ffffff;
    }

    .gk-shop-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        border-bottom: 2px solid var(--shop-red);
        margin-bottom: 1.1rem;
        padding-bottom: 0.75rem;
    }

    .gk-results-title {
        font-size: 1.35rem;
        font-weight: 900;
        line-height: 1.1;
        text-transform: uppercase;
    }

    .gk-results-count {
        margin-top: 0.25rem;
        color: var(--shop-muted);
        font-size: 0.78rem;
    }

    .gk-shop-sort {
        width: auto;
        min-width: 165px;
    }

    .gk-shop-products {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.8rem;
    }

    @media (min-width: 768px) {
        .gk-shop-products {
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
        }
    }

    @media (min-width: 1280px) {
        .gk-shop-products {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
    }

    @media (min-width: 1500px) {
        .gk-shop-products {
            grid-template-columns: repeat(5, minmax(0, 1fr));
        }
    }

    .gk-shop-products > div {
        min-width: 0;
        border-radius: 8px;
        box-shadow: none;
    }

    .gk-shop-products > div:hover {
        border-color: var(--shop-red);
        box-shadow: 0 14px 30px rgba(17, 24, 39, 0.09);
    }

    .gk-shop-empty {
        border: 1px solid var(--shop-line);
        border-radius: 8px;
        background: var(--shop-soft);
        padding: 4rem 1rem;
        text-align: center;
    }

    .gk-shop-empty-icon {
        color: var(--shop-red);
        font-size: 2.5rem;
    }

    .gk-shop-empty h3 {
        margin-top: 0.75rem;
        font-size: 1.2rem;
        font-weight: 900;
        text-transform: uppercase;
    }

    .gk-shop-empty p {
        margin-top: 0.35rem;
        color: var(--shop-muted);
        font-size: 0.85rem;
    }

    .gk-shop-empty a {
        display: inline-flex;
        margin-top: 1rem;
        color: var(--shop-red);
        font-size: 0.82rem;
        font-weight: 900;
        text-decoration: none;
    }

    @media (max-width: 639px) {
        .gk-shop-intro-row,
        .gk-shop-toolbar {
            align-items: stretch;
            flex-direction: column;
        }

        .gk-toolbar-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            width: 100%;
        }

        .gk-shop-sort {
            width: 100%;
        }

        .gk-category-card {
            align-items: center;
            flex-direction: column;
            min-height: 82px;
            justify-content: center;
            padding: 0.55rem 0.35rem;
            text-align: center;
        }

        .gk-category-card-name {
            width: 100%;
            font-size: 0.68rem;
        }
    }
</style>
@endpush

@section('content')
<div class="gk-shop" x-data="{ filtersOpen: false }">
    <div class="gk-filter-overlay" :class="{ 'is-open': filtersOpen }" @click="filtersOpen = false"></div>

    <nav class="gk-shop-breadcrumb">
        <div class="gk-shop-container gk-shop-breadcrumb-inner">
            <a href="{{ route('home') }}">{{ __('general.home') }}</a>
            <span>›</span>
            <a href="{{ route('shop.index') }}">{{ __('general.shop') }}</a>
            @if($parentCategory)
                <span>›</span>
                <a href="{{ route('shop.index', ['category' => $parentCategory->slug]) }}">{{ $parentCategory->name }}</a>
            @endif
            @if($currentCategory)
                <span>›</span>
                <span style="overflow:hidden; color:#111827; font-weight:700; text-overflow:ellipsis;">{{ $currentCategory->name }}</span>
            @endif
        </div>
    </nav>

    <header class="gk-shop-intro">
        <div class="gk-shop-container gk-shop-intro-row">
            <div>
                <div class="gk-shop-eyebrow">Gari Kothay Auto Marketplace</div>
                <h1 class="gk-shop-title">{{ $currentCategory?->name ?? 'Shop Automotive Products' }}</h1>
                <p class="gk-shop-copy">
                    {{ $currentCategory?->description ?: 'Browse trusted car parts, accessories, tools and automotive essentials from Gari Kothay.' }}
                </p>
            </div>
            <a href="{{ route('shop.index') }}" style="color:var(--shop-red); font-size:0.82rem; font-weight:900; text-decoration:none; white-space:nowrap;">
                View all products →
            </a>
        </div>
    </header>

    <section class="gk-category-rail" aria-label="{{ __('general.categories') }}">
        <div class="gk-shop-container">
            <div class="gk-category-grid">
                @foreach($categories->take(8) as $category)
                    @php
                        $categoryIsActive = $currentCategory?->id === $category->id
                            || $currentCategory?->parent_id === $category->id;
                    @endphp
                    <a href="{{ route('shop.index', ['category' => $category->slug]) }}"
                       class="gk-category-card {{ $categoryIsActive ? 'is-active' : '' }}">
                        <span class="gk-category-card-icon">
                            @if($category->image)
                                <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}">
                            @else
                                {{ $category->icon ?? '⚙' }}
                            @endif
                        </span>
                        <span class="gk-category-card-name">{{ $category->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <div class="gk-shop-container gk-shop-body">
        <aside style="position: relative; z-index: 40;">
            <div class="gk-filter-stack" :class="{ 'is-open': filtersOpen }">
                <div class="flex justify-between items-center lg:hidden mb-2">
                    <h2 style="font-size:1.1rem; font-weight:900; margin:0;">Filters</h2>
                    <button type="button" @click="filtersOpen = false" style="font-size:1.5rem; line-height:1; color:var(--shop-muted); background:transparent; border:none; cursor:pointer;">&times;</button>
                </div>
                <section class="gk-filter-panel">
                    <h2 class="gk-filter-heading">☷ {{ __('general.categories') }}</h2>
                    <div class="gk-filter-content">
                        <ul class="gk-category-list">
                            <li class="gk-category-row">
                                <a href="{{ route('shop.index') }}" class="gk-category-link {{ !$currentCategory ? 'is-active' : '' }}">
                                    <span style="color:var(--shop-red);">▦</span>
                                    <span>{{ __('general.all_products') }}</span>
                                </a>
                            </li>
                            @foreach($categories as $category)
                                @php
                                    $parentIsActive = $currentCategory?->id === $category->id
                                        || $currentCategory?->parent_id === $category->id;
                                @endphp
                                <li class="gk-category-row">
                                    <a href="{{ route('shop.index', ['category' => $category->slug]) }}"
                                       class="gk-category-link {{ $parentIsActive ? 'is-active' : '' }}">
                                        <span style="color:var(--shop-red);">{{ $category->icon ?? '⚙' }}</span>
                                        <span>{{ $category->name }}</span>
                                        @if($category->children->isNotEmpty())
                                            <span class="gk-category-arrow">›</span>
                                        @endif
                                    </a>
                                    @if($category->children->isNotEmpty())
                                        <div class="gk-subcategory-list">
                                            @foreach($category->children as $child)
                                                <a href="{{ route('shop.index', ['category' => $child->slug]) }}"
                                                   class="gk-subcategory-link {{ $currentCategory?->id === $child->id ? 'is-active' : '' }}">
                                                    {{ $child->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </section>

                <section class="gk-filter-panel">
                    <h2 class="gk-filter-heading">⌕ {{ __('general.filters') }}</h2>
                    <div class="gk-filter-content">
                        <form method="GET" action="{{ route('shop.index') }}" id="filter-form">
                            @if($currentCategory)
                                <input type="hidden" name="category" value="{{ $currentCategory->slug }}">
                            @endif
                            
                            @if($allBrands->isNotEmpty())
                            <label class="gk-price-label" style="margin-top: 0.2rem;">Brand</label>
                            <div style="margin-bottom: 1rem; max-height: 180px; overflow-y: auto;">
                                @foreach($allBrands as $brand)
                                    <label style="display:flex; align-items:center; gap:0.4rem; font-size:0.8rem; margin-bottom:0.4rem; color:var(--shop-ink); cursor:pointer;">
                                        <input type="checkbox" name="brands[]" value="{{ $brand }}" {{ in_array($brand, $filters['brands'] ?? []) ? 'checked' : '' }}>
                                        <span>{{ $brand }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @endif

                            <label class="gk-price-label">Availability</label>
                            <div style="margin-bottom: 1rem;">
                                @foreach(['in_stock' => 'In Stock', 'pre_order' => 'Pre Order', 'upcoming' => 'Upcoming'] as $val => $label)
                                    <label style="display:flex; align-items:center; gap:0.4rem; font-size:0.8rem; margin-bottom:0.4rem; color:var(--shop-ink); cursor:pointer;">
                                        <input type="checkbox" name="availability[]" value="{{ $val }}" {{ in_array($val, $filters['availability'] ?? []) ? 'checked' : '' }}>
                                        <span>{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                            
                            <label class="gk-price-label">Offers</label>
                            <div style="margin-bottom: 1rem;">
                                <label style="display:flex; align-items:center; gap:0.4rem; font-size:0.8rem; margin-bottom:0.4rem; color:var(--shop-ink); cursor:pointer;">
                                    <input type="checkbox" name="on_sale" value="1" {{ !empty($filters['on_sale']) ? 'checked' : '' }}>
                                    <span>Discount / On Sale</span>
                                </label>
                            </div>

                            <label class="gk-price-label">{{ __('general.price_range') }}</label>
                            <div class="gk-price-grid">
                                <input type="number" name="min_price" value="{{ $filters['min_price'] ?? '' }}"
                                       placeholder="৳ {{ __('general.min') }}" class="gk-filter-input">
                                <input type="number" name="max_price" value="{{ $filters['max_price'] ?? '' }}"
                                       placeholder="৳ {{ __('general.max') }}" class="gk-filter-input">
                            </div>
                            <button type="submit" class="gk-filter-submit">{{ __('general.apply_filters') }}</button>
                            <a href="{{ route('shop.index', $currentCategory ? ['category' => $currentCategory->slug] : []) }}"
                               class="gk-filter-clear">{{ __('general.clear_filters') }}</a>
                        </form>
                    </div>
                </section>
            </div>
        </aside>

        <main style="min-width:0;">
            @if($subcategories->count())
                <nav class="gk-subcategory-tabs" aria-label="Subcategories">
                    <a href="{{ route('shop.index', ['category' => $currentCategory->slug]) }}"
                       class="gk-subcategory-tab is-active">{{ __('general.all') }} {{ $currentCategory->name }}</a>
                    @foreach($subcategories as $subcategory)
                        <a href="{{ route('shop.index', ['category' => $subcategory->slug]) }}"
                           class="gk-subcategory-tab">{{ $subcategory->name }}</a>
                    @endforeach
                </nav>
            @endif

            <div class="gk-shop-toolbar">
                <div>
                    <h2 class="gk-results-title">{{ $currentCategory?->name ?? __('general.all_products') }}</h2>
                    <p class="gk-results-count">{{ $products->total() }} {{ __('general.products_found') }}</p>
                </div>
                <div class="gk-toolbar-actions">
                    <button type="button" class="gk-mobile-filter" @click="filtersOpen = true">
                        <span style="font-size:1.1rem;">☷</span>
                        <span>{{ __('general.filters') }}</span>
                    </button>
                    <select name="sort" form="filter-form" onchange="document.getElementById('filter-form').submit()" class="gk-shop-sort">
                        <option value="">{{ __('general.default_sort') }}</option>
                        <option value="newest" {{ ($filters['sort'] ?? '') === 'newest' ? 'selected' : '' }}>{{ __('general.newest') }}</option>
                        <option value="best_selling" {{ ($filters['sort'] ?? '') === 'best_selling' ? 'selected' : '' }}>Best Selling</option>
                        <option value="a_z" {{ ($filters['sort'] ?? '') === 'a_z' ? 'selected' : '' }}>A–Z</option>
                        <option value="z_a" {{ ($filters['sort'] ?? '') === 'z_a' ? 'selected' : '' }}>Z–A</option>
                        <option value="price_asc" {{ ($filters['sort'] ?? '') === 'price_asc' ? 'selected' : '' }}>{{ __('general.price_low_to_high') }}</option>
                        <option value="price_desc" {{ ($filters['sort'] ?? '') === 'price_desc' ? 'selected' : '' }}>{{ __('general.price_high_to_low') }}</option>
                    </select>
                </div>
            </div>

            @if($products->isEmpty())
                <div class="gk-shop-empty">
                    <div class="gk-shop-empty-icon">⌕</div>
                    <h3>{{ __('general.no_products_found') }}</h3>
                    <p>{{ __('general.try_adjusting_filters') }}</p>
                    <a href="{{ route('shop.index') }}">{{ __('general.clear_all_filters') }} →</a>
                </div>
            @else
                <div class="gk-shop-products">
                    @foreach($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
                <div style="margin-top:2rem;">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @endif
        </main>
    </div>
</div>
@endsection
