@extends('layouts.app')

@section('title', $product->getTranslation('meta_title') ?: ($product->name . ' | ' . \App\Models\Setting::get('site_name', 'Garikothay')))
@section('meta_description', $product->getTranslation('meta_description') ?: $product->short_description)
@section('og_title', $product->name)
@section('og_description', $product->short_description)
@section('og_type', 'product')
@section('og_image', $product->primaryImage ? asset($product->primaryImage->url) : null)

@push('styles')
<style>
    .gk-product-page {
        background: #ffffff;
        color: #111827;
    }

    .gk-product-container {
        max-width: 1504px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    .gk-product-breadcrumb {
        border-bottom: 1px solid #e5e7eb;
        background: #f8fafc;
    }

    .gk-product-breadcrumb-inner {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.85rem 1rem;
        color: #6b7280;
        font-size: 0.78rem;
    }

    .gk-product-breadcrumb-inner a {
        color: #6b7280;
        text-decoration: none;
    }

    .gk-product-breadcrumb-inner a:hover {
        color: #e11d48;
    }

    .gk-product-shell {
        display: grid;
        gap: 1.5rem;
        padding: 2rem 1rem;
    }

    @media (min-width: 1024px) {
        .gk-product-shell {
            grid-template-columns: 260px minmax(0, 1fr);
        }
    }

    .gk-product-sidebar {
        display: none;
    }

    @media (min-width: 1024px) {
        .gk-product-sidebar {
            display: block;
        }
    }

    .gk-side-card {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #ffffff;
        padding: 1rem;
    }

    .gk-side-card + .gk-side-card {
        margin-top: 1.5rem;
    }

    .gk-side-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.8rem;
        font-size: 0.82rem;
        font-weight: 900;
        text-transform: uppercase;
    }

    .gk-side-list {
        display: grid;
        gap: 0.45rem;
        margin: 0;
        padding: 0;
        list-style: none;
        font-size: 0.88rem;
    }

    .gk-side-list a {
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: #374151;
        text-decoration: none;
    }

    .gk-side-list a:hover,
    .gk-side-list a.is-active {
        color: #e11d48;
        font-weight: 800;
    }

    .gk-filter-panel {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #ffffff;
    }

    .gk-filter-panel + .gk-side-card {
        margin-top: 1.5rem;
    }

    .gk-filter-heading {
        display: flex;
        min-height: 44px;
        align-items: center;
        gap: 0.55rem;
        background: #111827;
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
        border-bottom: 1px solid #e5e7eb;
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
        color: #e11d48;
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
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            box-shadow: 10px 10px 25px rgba(0,0,0,0.08);
            padding: 0.75rem;
            z-index: 100;
            margin: 0;
            border-left: none;
            margin-left: 2px;
        }
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
        color: #6b7280;
        padding: 0.4rem 0.6rem;
        font-size: 0.8rem;
        text-decoration: none;
    }

    .gk-subcategory-link:hover,
    .gk-subcategory-link.is-active {
        background: #fff1f2;
        color: #e11d48;
        font-weight: 800;
    }

    .gk-product-main-grid {
        display: grid;
        gap: 2rem;
    }

    @media (min-width: 1024px) {
        .gk-product-main-grid {
            grid-template-columns: minmax(0, 1fr) minmax(380px, 1fr);
        }
    }

    .gk-gallery-main {
        position: relative;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #ffffff;
        cursor: zoom-in;
    }

    .gk-gallery-main img {
        width: 100%;
        aspect-ratio: 1 / 1;
        object-fit: contain;
        padding: 1.5rem;
        transition: transform 0.2s ease;
    }

    @media (hover: hover) {
        .gk-gallery-main:hover img {
            transform: scale(1.35);
        }
    }

    .gk-thumbs {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.55rem;
        margin-top: 0.75rem;
    }

    .gk-thumb {
        overflow: hidden;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        background: #ffffff;
        cursor: pointer;
    }

    .gk-thumb:hover {
        border-color: #e11d48;
    }

    .gk-thumb img {
        width: 100%;
        aspect-ratio: 1 / 1;
        object-fit: cover;
    }

    .gk-product-brand {
        color: #e11d48;
        font-size: 0.72rem;
        font-weight: 900;
        letter-spacing: 0.1em;
        text-transform: uppercase;
    }

    .gk-product-title {
        margin-top: 0.35rem;
        font-family: 'Oswald', 'Inter', sans-serif;
        font-size: clamp(1.5rem, 3vw, 1.9rem);
        font-weight: 900;
        line-height: 1.1;
    }

    .gk-product-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.6rem;
        margin-top: 0.75rem;
        color: #6b7280;
        font-size: 0.88rem;
    }

    .gk-stars {
        display: inline-flex;
        gap: 0.12rem;
        color: #f59e0b;
    }

    .gk-price-box {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        margin-top: 1.25rem;
        border-radius: 8px;
        background: #f3f4f6;
        padding: 0.9rem 1rem;
    }

    .gk-price {
        color: #e11d48;
        font-size: 1.65rem;
        font-weight: 900;
    }

    .gk-old-price {
        color: #6b7280;
        text-decoration: line-through;
    }

    .gk-save {
        margin-left: auto;
        border-radius: 4px;
        background: #e11d48;
        color: #ffffff;
        padding: 0.22rem 0.5rem;
        font-size: 0.72rem;
        font-weight: 900;
    }

    .gk-short-copy {
        margin-top: 1rem;
        color: #6b7280;
        font-size: 0.86rem;
        line-height: 1.6;
    }

    .gk-variant-button {
        border: 1px solid #d1d5db;
        border-radius: 6px;
        background: #ffffff;
        color: #374151;
        padding: 0.55rem 0.8rem;
        font-size: 0.85rem;
        font-weight: 800;
    }

    .gk-variant-button.is-active {
        border-color: #e11d48;
        background: #e11d48;
        color: #ffffff;
    }

    .gk-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.75rem;
        margin-top: 1.4rem;
    }

    .gk-qty {
        display: flex;
        align-items: center;
        overflow: hidden;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        height: 2.75rem;
    }

    .gk-qty button {
        display: grid;
        place-items: center;
        width: 2.5rem;
        height: 100%;
        border: 0;
        background: #ffffff;
        font-size: 1.1rem;
        cursor: pointer;
    }

    .gk-qty button:hover {
        background: #f3f4f6;
    }

    .gk-qty span {
        min-width: 3rem;
        text-align: center;
        font-weight: 900;
    }

    .gk-product-btn {
        display: inline-flex;
        min-height: 2.75rem;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        border-radius: 6px;
        border: 1px solid transparent;
        padding: 0.75rem 1.1rem;
        font-size: 0.9rem;
        font-weight: 900;
        text-decoration: none;
        cursor: pointer;
        transition: background 0.2s ease, color 0.2s ease, border-color 0.2s ease;
    }

    .gk-product-btn-primary {
        flex: 1;
        background: #e11d48;
        color: #ffffff;
    }

    .gk-product-btn-primary:hover {
        background: #be123c;
    }

    .gk-product-btn-icon {
        width: 2.75rem;
        padding: 0;
        border-color: #d1d5db;
        background: #ffffff;
        color: #111827;
    }

    .gk-product-btn-icon:hover {
        background: #fee2e2;
        color: #e11d48;
    }

    .gk-buy-now {
        width: 100%;
        margin-top: 0.75rem;
        border: 2px solid #111827;
        background: #ffffff;
        color: #111827;
        text-transform: uppercase;
    }

    .gk-buy-now:hover {
        background: #111827;
        color: #ffffff;
    }

    .gk-trust-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
        margin-top: 1.3rem;
        border-top: 1px solid #e5e7eb;
        padding-top: 1.2rem;
        color: #374151;
        font-size: 0.76rem;
        text-align: center;
    }

    .gk-trust-grid span {
        display: grid;
        place-items: center;
        color: #e11d48;
        font-size: 1.25rem;
        margin-bottom: 0.25rem;
    }

    .gk-product-tabs {
        padding-top: 2.5rem;
    }

    .gk-tab-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.15rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .gk-tab-button {
        border: 0;
        border-bottom: 2px solid transparent;
        background: transparent;
        color: #6b7280;
        margin-bottom: -1px;
        padding: 0.85rem 1rem;
        font-size: 0.82rem;
        font-weight: 900;
        cursor: pointer;
    }

    .gk-tab-button.is-active {
        border-color: #e11d48;
        color: #e11d48;
    }

    .gk-tab-panel {
        display: none;
        color: #4b5563;
        font-size: 0.85rem;
        line-height: 1.65;
        padding: 1.4rem 0;
    }

    .gk-tab-panel.is-active {
        display: block;
    }

    .gk-spec-table {
        width: 100%;
        max-width: 720px;
        border-collapse: collapse;
    }

    .gk-spec-table td {
        border-bottom: 1px solid #e5e7eb;
        padding: 0.7rem 0;
    }

    .gk-spec-table td:first-child {
        color: #111827;
        font-weight: 900;
        width: 35%;
    }

    .gk-review-card {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        background: #ffffff;
        padding: 1rem;
    }

    .gk-review-card + .gk-review-card {
        margin-top: 0.8rem;
    }

    .gk-related {
        padding-top: 1.5rem;
    }

    .gk-related-title {
        margin-bottom: 1.25rem;
        border-bottom: 2px solid #e11d48;
        padding-bottom: 0.6rem;
        font-family: 'Oswald', 'Inter', sans-serif;
        font-size: 1.4rem;
        font-weight: 900;
        text-transform: uppercase;
    }

    .gk-related-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
    }

    @media (min-width: 640px) {
        .gk-related-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (min-width: 1280px) {
        .gk-related-grid {
            grid-template-columns: repeat(5, minmax(0, 1fr));
        }
    }

    @media (max-width: 640px) {
        .gk-product-shell {
            padding-top: 1rem;
        }

        .gk-actions {
            align-items: stretch;
            flex-direction: column;
        }

        .gk-product-btn-primary,
        .gk-product-btn-icon {
            width: 100%;
        }

        .gk-qty {
            width: 100%;
            justify-content: space-between;
        }
    }

    .gk-share-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 1.25rem;
    }
    .gk-share-label {
        font-size: 0.82rem;
        font-weight: 800;
        color: #374151;
        margin-right: 0.25rem;
    }
    .gk-share-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.2rem;
        height: 2.2rem;
        border-radius: 50%;
        color: #ffffff;
        font-size: 1rem;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: transform 0.2s ease, opacity 0.2s ease;
    }
    .gk-share-btn:hover {
        transform: translateY(-2px);
        opacity: 0.9;
    }
    .gk-share-wa { background: #25d366; }
    .gk-share-fb { background: #1877f2; }
    .gk-share-copy { background: #4b5563; }
</style>
@endpush

@section('content')
@php
    $imageUrl = $product->primaryImage?->url ?? asset('images/product-placeholder.svg');
    $approvedReviews = $product->reviews->where('is_approved', true);
    $categoryName = $product->category?->name ?? 'Garikothay';
    $brandName = $product->brand;
    $shippingService = app(\App\Services\ShippingService::class);
    $freeShippingThreshold = (float) \App\Models\Setting::get('free_shipping_threshold', 1500);
    $dhakaCityShippingCharge = $shippingService->getDhakaCityCharge();
    $outsideDhakaShippingCharge = $shippingService->getOutsideDhakaCharge();
    $deliveryTime = \App\Models\Setting::get('delivery_time', '2-5 business days');
    $deliveryPartner = \App\Models\Setting::get('delivery_partner', 'Steadfast');
@endphp

<div class="gk-product-page">
    <div class="gk-product-breadcrumb">
        <div class="gk-product-container gk-product-breadcrumb-inner">
            <a href="{{ route('home') }}">Home</a>
            <span>›</span>
            <a href="{{ route('shop.index') }}">Shop</a>
            <span>›</span>
            <span style="color:#111827; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $product->name }}</span>
        </div>
    </div>

    <div class="gk-product-container gk-product-shell">
        <aside class="gk-product-sidebar">
            <div class="gk-filter-panel">
                <h3 class="gk-filter-heading">☷ Categories</h3>
                <div class="gk-filter-content">
                    <ul class="gk-category-list">
                        <li class="gk-category-row">
                            <a href="{{ route('shop.index') }}" class="gk-category-link">
                                <span style="color:#e11d48;">▦</span>
                                <span>{{ __('general.all_products') }}</span>
                            </a>
                        </li>
                        @foreach($categories ?? collect() as $category)
                            @php
                                $parentIsActive = $product->category?->id === $category->id
                                    || $product->category?->parent_id === $category->id;
                            @endphp
                            <li class="gk-category-row">
                                <a href="{{ route('shop.index', ['category' => $category->slug]) }}"
                                   class="gk-category-link {{ $parentIsActive ? 'is-active' : '' }}">
                                    <span style="color:#e11d48; flex-shrink: 0;">
                                        @if($category->icon)
                                            <img src="{{ Storage::url($category->icon) }}" class="w-4 h-4 object-contain" alt="">
                                        @else
                                            ⚙
                                        @endif
                                    </span>
                                    <span class="truncate">{{ $category->name }}</span>
                                    @if($category->children->isNotEmpty())
                                        <span class="gk-category-arrow">›</span>
                                    @endif
                                </a>
                                @if($category->children->isNotEmpty())
                                    <div class="gk-subcategory-list">
                                        @foreach($category->children as $child)
                                            <a href="{{ route('shop.index', ['category' => $child->slug]) }}"
                                               class="gk-subcategory-link {{ $product->category?->id === $child->id ? 'is-active' : '' }}">
                                                {{ $child->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="gk-side-card">
                <h3 class="gk-side-title">🏷 Top Brands</h3>
                <ul class="gk-side-list">
                    @foreach($topBrands ?? collect() as $brand)
                        <li><a href="{{ route('shop.index') }}" class="{{ $brand === $brandName ? 'is-active' : '' }}">{{ $brand }}</a></li>
                    @endforeach
                </ul>
            </div>
        </aside>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('productPage', () => ({
                    activeImage: '{!! addslashes($imageUrl) !!}',
                    quantity: 1,
                    selectedVariant: null,
                    adding: false,
                    tab: 'desc',
                    basePrice: {{ $product->selling_price }},
                    baseOriginalPrice: {{ $product->original_price }},
                    variants: {!! json_encode($product->variants->where('is_active', true)->values()->map(function($v) {
                        return [
                            'id'             => $v->id,
                            'selling_price'  => (float) $v->selling_price,
                            'original_price' => (float) $v->original_price,
                            'sku'            => $v->sku,
                            'has_own_price'  => $v->price > 0,
                            'price_modifier' => (float) $v->price_modifier,
                            'stock_quantity' => (int) $v->stock_quantity,
                            'images'         => $v->image_gallery ?? [],
                        ];
                    })) !!},
                    activeSku() {
                        if (this.selectedVariant) {
                            const variant = this.variants.find(v => v.id === this.selectedVariant);
                            if (variant && variant.sku) {
                                return variant.sku;
                            }
                        }
                        return '{{ addslashes($product->sku) }}';
                    },
                    activePrice() {
                        if (this.selectedVariant) {
                            const variant = this.variants.find(v => v.id === this.selectedVariant);
                            if (variant) {
                                // If variant has its own absolute price, use it directly
                                if (variant.has_own_price) return variant.selling_price;
                                // Otherwise base + modifier
                                return this.basePrice + variant.price_modifier;
                            }
                        }
                        return this.basePrice;
                    },
                    activeOriginalPrice() {
                        if (this.selectedVariant) {
                            const variant = this.variants.find(v => v.id === this.selectedVariant);
                            if (variant) {
                                if (variant.has_own_price) return variant.original_price;
                                return this.baseOriginalPrice + variant.price_modifier;
                            }
                        }
                        return this.baseOriginalPrice;
                    },
                    activeDiscountPct() {
                        const orig = this.activeOriginalPrice();
                        const sell = this.activePrice();
                        if (orig > 0 && orig > sell) {
                            return Math.round(((orig - sell) / orig) * 100);
                        }
                        return 0;
                    },
                    activeStock() {
                        if (this.selectedVariant) {
                            const variant = this.variants.find(v => v.id === this.selectedVariant);
                            if (variant) {
                                return variant.stock_quantity;
                            }
                        }
                        return {{ $product->stock_quantity }};
                    },
                    setZoom(event) {
                        const rect = event.currentTarget.getBoundingClientRect();
                        const x = ((event.clientX - rect.left) / rect.width) * 100;
                        const y = ((event.clientY - rect.top) / rect.height) * 100;
                        event.currentTarget.querySelector('img').style.transformOrigin = `${x}% ${y}%`;
                    },
                    addToCart(redirect = false) {
                        this.adding = true;
                        fetch('/cart/add', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                            body: JSON.stringify({product_id: {{ $product->id }}, quantity: this.quantity, variant_id: this.selectedVariant})
                        }).then(r => r.json()).then(d => {
                            this.adding = false;
                            document.querySelectorAll('.cart-count').forEach(el => el.textContent = d.cart_count);
                            this.$dispatch('toast', { message: '{{ __('general.added_to_cart') }}' });
                            if (redirect) window.location.href = '{{ route('cart.index') }}';
                        }).catch(() => { this.adding = false; });
                    }
                }));
            });
        </script>
        <main style="min-width:0;">
            <div class="gk-product-main-grid" x-data="productPage()">
                <div>
                    <div class="gk-gallery-main" @mousemove="setZoom($event)">
                        <img :src="activeImage" alt="{{ $product->name }}" onerror="this.onerror=null;this.src='{{ asset('images/product-placeholder.svg') }}';">
                        @if(!$product->isInStock())
                            <div style="position:absolute; top:0; left:0; right:0; bottom:0; background:rgba(255,255,255,0.75); display:grid; place-items:center; z-index:10; pointer-events:none;">
                                <span style="background:#e11d48; color:#fff; padding:0.6rem 1.25rem; font-weight:900; border-radius:6px; font-size:1.35rem; transform:rotate(-15deg); box-shadow: 0 4px 6px rgba(0,0,0,0.1); letter-spacing:0.05em;">OUT OF STOCK</span>
                            </div>
                        @endif
                    </div>
                    <div class="gk-thumbs">
                        @forelse($product->images as $image)
                            <button type="button" class="gk-thumb" @click="activeImage = '{{ $image->url }}'">
                                <img src="{{ $image->url }}" alt="{{ $image->alt_text ?? $product->name }}" onerror="this.onerror=null;this.src='{{ asset('images/product-placeholder.svg') }}';">
                            </button>
                        @empty
                            @for($i = 0; $i < 4; $i++)
                                <button type="button" class="gk-thumb" @click="activeImage = '{{ asset('images/product-placeholder.svg') }}'">
                                    <img src="{{ asset('images/product-placeholder.svg') }}" alt="{{ $product->name }}">
                                </button>
                            @endfor
                        @endforelse
                    </div>
                </div>

                <div>
                    <h1 class="gk-product-title">{{ $product->name }}</h1>

                    <div class="gk-product-meta">
                        <span class="gk-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <span>{{ $i <= round($product->average_rating) ? '★' : '☆' }}</span>
                            @endfor
                        </span>
                        <strong>{{ number_format($product->average_rating, 1) }}</strong>
                        <span>({{ $approvedReviews->count() }} reviews)</span>
                        <span>·</span>
                        <template x-if="activeStock() > 0">
                            <span style="color:#16a34a; font-weight:800;">In Stock (<span x-text="activeStock()"></span> Available)</span>
                        </template>
                        <template x-if="activeStock() <= 0">
                            <span style="color:#e11d48; font-weight:800;">Out of Stock (<span x-text="activeStock()"></span> Available)</span>
                        </template>
                        <span>·</span>
                        @if($product->brand)
                            <span>Brand: {{ $product->brand }}</span>
                            <span>·</span>
                        @endif
                        <span>SKU: <span x-text="activeSku()"></span></span>
                    </div>

                    <div class="gk-price-box">
                        <span class="gk-price">৳<span x-text="activePrice().toLocaleString('en-US', {minimumFractionDigits: 0, maximumFractionDigits: 0})"></span></span>
                        <template x-if="activeOriginalPrice() > activePrice()">
                            <span class="gk-old-price">৳<span x-text="activeOriginalPrice().toLocaleString('en-US', {minimumFractionDigits: 0, maximumFractionDigits: 0})"></span></span>
                        </template>
                        <template x-if="activeDiscountPct() > 0">
                            <span class="gk-save" x-text="'SAVE ' + activeDiscountPct() + '%'"></span>
                        </template>
                    </div>

                    <p class="gk-short-copy">
                        {{ $product->short_description ?: 'Premium quality ' . strtolower($product->name) . ' from Garikothay. Engineered for reliability, durability and optimum performance.' }}
                    </p>

                    @if($product->variants->isNotEmpty())
                        <div style="margin-top:1.2rem;">
                            <label style="display:block; margin-bottom:0.55rem; font-size:0.85rem; font-weight:900;">{{ __('general.select_variant') }}</label>
                            <div style="display:flex; flex-wrap:wrap; gap:0.5rem;">
                                @foreach($product->variants->where('is_active', true) as $variant)
                                    <button type="button"
                                        @click="selectedVariant = (selectedVariant === {{ $variant->id }} ? null : {{ $variant->id }})"
                                        :class="{ 'is-active': selectedVariant === {{ $variant->id }} }"
                                        class="gk-variant-button">
                                        @php
                                            $displayName = $variant->name;
                                            if ($variant->variantValue) {
                                                $displayName = $variant->variantValue->name;
                                            } else if (str_contains($displayName, ':')) {
                                                $displayName = trim(explode(':', $displayName)[1]);
                                            }
                                        @endphp
                                        {{ $displayName }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="gk-actions">
                        <div class="gk-qty">
                            <button type="button" @click="quantity = Math.max(1, quantity - 1)">−</button>
                            <span x-text="quantity"></span>
                            <button type="button" @click="quantity++" :disabled="quantity >= activeStock()">+</button>
                        </div>

                        <template x-if="activeStock() > 0">
                            <button type="button" @click="addToCart(false)" :disabled="adding" class="gk-product-btn gk-product-btn-primary">
                                🛒 <span x-text="adding ? '{{ __('general.adding') }}' : '{{ __('general.add_to_cart') ?? 'Add to cart' }}'"></span>
                            </button>
                        </template>
                        <template x-if="activeStock() <= 0 && {{ $product->is_preorder ? 'true' : 'false' }}">
                            <button type="button" @click="addToCart(false)" :disabled="adding" class="gk-product-btn gk-product-btn-primary">
                                🛒 <span x-text="adding ? '{{ __('general.adding') }}' : 'Pre-order'"></span>
                            </button>
                        </template>
                        <template x-if="activeStock() <= 0 && {{ !$product->is_preorder ? 'true' : 'false' }}">
                            <span class="gk-product-btn gk-product-btn-primary" style="background:#9ca3af; cursor:not-allowed;">{{ __('general.out_of_stock') ?? 'Out of stock' }}</span>
                        </template>

                        @auth
                            <button type="button" class="gk-product-btn gk-product-btn-icon" 
                                x-data="{ inWishlist: {{ auth()->user()->wishlists()->where('product_id', $product->id)->exists() ? 'true' : 'false' }} }"
                                @click="
                                    fetch('/wishlist/toggle', {
                                        method: 'POST',
                                        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                                        body: JSON.stringify({product_id: {{ $product->id }}})
                                    }).then(r => r.json()).then(data => {
                                        inWishlist = data.added;
                                        $dispatch('toast', { message: data.message });
                                        $dispatch('wishlist-updated', { count: data.count });
                                    });
                                "
                                :style="inWishlist ? 'color: var(--gk-red);' : ''"
                                x-text="inWishlist ? '♥' : '♡'"
                            >
                                {{ auth()->user()->wishlists()->where('product_id', $product->id)->exists() ? '♥' : '♡' }}
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="gk-product-btn gk-product-btn-icon">♡</a>
                        @endauth
                    </div>

                    <template x-if="activeStock() > 0">
                        <button type="button" @click="addToCart(true)" class="gk-product-btn gk-buy-now">{{ __('general.buy_now') ?? 'Buy Now' }}</button>
                    </template>
                    <template x-if="activeStock() <= 0 && {{ $product->is_preorder ? 'true' : 'false' }}">
                        <button type="button" @click="addToCart(true)" class="gk-product-btn gk-buy-now">Pre-order Now</button>
                    </template>

                    <div class="gk-share-actions">
                        <span class="gk-share-label">Share:</span>
                        <a href="https://wa.me/?text={{ urlencode($product->name . ' - ' . route('shop.show', $product->slug)) }}" target="_blank" rel="noopener" class="gk-share-btn gk-share-wa" title="Share on WhatsApp">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/></svg>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('shop.show', $product->slug)) }}" target="_blank" rel="noopener" class="gk-share-btn gk-share-fb" title="Share on Facebook">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/></svg>
                        </a>
                        <button type="button" class="gk-share-btn gk-share-copy" title="Copy Link" @click="
                            navigator.clipboard.writeText('{{ route('shop.show', $product->slug) }}')
                            .then(() => $dispatch('toast', { message: 'Link copied to clipboard!' }));
                        ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1 1 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4 4 0 0 1-.128-1.287z"/><path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243z"/></svg>
                        </button>
                    </div>

                    <div class="gk-trust-grid">
                        <div><span>🛡</span>100% Genuine</div>
                        <div><span>↻</span>7-day returns</div>
                    </div>

                    @if(!empty($product->highlights) || !empty($product->certifications) || !empty($product->collections))
                        <div style="margin-top:1.5rem; display:flex; flex-direction:column; gap:1rem;">
                            @if(!empty($product->collections))
                                @php
                                    $colls = is_string($product->collections) ? (json_decode($product->collections, true) ?? [$product->collections]) : (is_iterable($product->collections) ? $product->collections : [$product->collections]);
                                @endphp
                                @if(is_iterable($colls) && count((array)$colls) > 0)
                                <div style="display:flex; flex-wrap:wrap; gap:0.4rem;">
                                    @foreach($colls as $collection)
                                        @if(is_string($collection))
                                        <span style="background:#e11d48; color:#fff; font-size:0.7rem; font-weight:800; padding:0.2rem 0.5rem; border-radius:4px; text-transform:uppercase;">{{ $collection }}</span>
                                        @endif
                                    @endforeach
                                </div>
                                @endif
                            @endif

                            @if(!empty($product->highlights))
                                @php
                                    $hls = is_string($product->highlights) ? (json_decode($product->highlights, true) ?? []) : (is_iterable($product->highlights) ? $product->highlights : []);
                                @endphp
                                @if(is_iterable($hls) && count((array)$hls) > 0)
                                <div style="display:flex; flex-wrap:wrap; gap:0.75rem;">
                                    @foreach($hls as $hl)
                                        @if(is_array($hl))
                                        <div style="display:flex; align-items:center; gap:0.35rem; background:#f9fafb; border:1px solid #e5e7eb; padding:0.4rem 0.6rem; border-radius:6px;">
                                            @if(!empty($hl['icon']))
                                                <img src="{{ asset('storage/' . $hl['icon']) }}" alt="{{ $hl['text'] ?? '' }}" style="width:1.5rem; height:1.5rem; object-fit:contain;">
                                            @endif
                                            @if(!empty($hl['text']))
                                                <span style="font-size:0.75rem; font-weight:800; color:#374151;">{{ $hl['text'] }}</span>
                                            @endif
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                                @endif
                            @endif

                            @if(!empty($product->certifications))
                                @php
                                    $certs = is_string($product->certifications) ? (json_decode($product->certifications, true) ?? []) : (is_iterable($product->certifications) ? $product->certifications : []);
                                @endphp
                                @if(is_iterable($certs) && count((array)$certs) > 0)
                                <div style="display:flex; flex-wrap:wrap; gap:0.75rem;">
                                    @foreach($certs as $cert)
                                        @if(is_array($cert))
                                        <div style="display:flex; align-items:center; gap:0.35rem; background:#fff; border:1px solid #e5e7eb; padding:0.4rem 0.6rem; border-radius:6px;" title="{{ $cert['name'] ?? '' }}">
                                            @if(!empty($cert['image']))
                                                <img src="{{ asset('storage/' . $cert['image']) }}" alt="{{ $cert['name'] ?? '' }}" style="height:1.5rem; width:auto; object-fit:contain;">
                                            @endif
                                            @if(!empty($cert['name']))
                                                <span style="font-size:0.75rem; font-weight:800; color:#374151;">{{ $cert['name'] }}</span>
                                            @endif
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                                @endif
                            @endif
                        </div>
                    @endif
                </div>

                <div class="gk-product-tabs" style="grid-column:1 / -1;">
                    @php
                        $tabs = [
                            'desc' => 'Description',
                            'features' => empty($product->features) ? null : 'Features',
                            'specs' => 'Specifications',
                            'shipping' => ($product->supplier_shipping_charge || $product->supplier_delivery_time || $product->shipping_restriction || $product->has_return_support || !empty($product->shipping_returns)) ? 'Shipping & Returns' : null,
                            'video' => empty($product->video_url) ? null : 'Video',
                            'docs' => empty($product->documents) ? null : 'Documents',
                            'faqs' => empty($product->faqs) ? null : 'Product FAQ',
                            'reviews' => 'Reviews (' . $approvedReviews->count() . ')',
                        ];
                        $tabs = array_filter($tabs);
                    @endphp
                    <div class="gk-tab-list">
                        @foreach($tabs as $key => $label)
                            <button type="button" class="gk-tab-button" :class="{ 'is-active': tab === '{{ $key }}' }" @click="tab = '{{ $key }}'">{{ $label }}</button>
                        @endforeach
                    </div>

                    <div class="gk-tab-panel" :class="{ 'is-active': tab === 'desc' }">
                        {!! $product->description ?: '<p>The ' . e($product->name) . ' delivers reliable performance and lasting durability. Includes quality support from Garikothay.</p>' !!}
                    </div>

                    @if(!empty($product->features))
                    @php
                        $features = is_string($product->features) ? (json_decode($product->features, true) ?? []) : (is_iterable($product->features) ? $product->features : []);
                    @endphp
                    @if(is_iterable($features) && count((array)$features) > 0)
                    <div class="gk-tab-panel" :class="{ 'is-active': tab === 'features' }">
                        <ul style="list-style-type:disc; padding-left:1.5rem; display:flex; flex-direction:column; gap:0.5rem; color:#4b5563; font-size:0.85rem;">
                            @foreach($features as $f)
                                <li>{{ is_array($f) ? ($f['feature'] ?? $f['text'] ?? '') : $f }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    @endif

                    <div class="gk-tab-panel" :class="{ 'is-active': tab === 'specs' }">
                        @if($product->specifications)
                            <div class="prose max-w-none text-sm text-gray-700 mb-6">
                                {!! $product->specifications !!}
                            </div>
                        @endif
                        
                        <table class="gk-spec-table">
                            <tbody>
                                @if($brandName)
                                    <tr><td>Brand</td><td>{{ $brandName }}</td></tr>
                                @endif
                                <tr><td>SKU</td><td x-text="activeSku()"></td></tr>
                                <tr><td>Category</td><td>{{ $categoryName }}</td></tr>
                                @if($product->weight_grams)
                                    <tr><td>Weight</td><td>{{ $product->weight_grams >= 1000 ? ($product->weight_grams / 1000) . ' kg' : $product->weight_grams . ' g' }}</td></tr>
                                @endif
                                @if($product->warranty_type && $product->warranty_type !== 'none')
                                    <tr><td>Warranty</td><td>{{ ucwords(str_replace('_', ' ', $product->warranty_type)) }} {{ $product->warranty_duration ? ' - ' . $product->warranty_duration : '' }}</td></tr>
                                    @if($product->warranty_claim_process)
                                        <tr><td>Warranty Claim</td><td>{{ $product->warranty_claim_process }}</td></tr>
                                    @endif
                                @endif
                                @if(!empty($product->custom_fields))
                                    @php
                                        $customFields = is_string($product->custom_fields) ? (json_decode($product->custom_fields, true) ?? []) : (is_iterable($product->custom_fields) ? $product->custom_fields : []);
                                    @endphp
                                    @if(is_iterable($customFields) && count((array)$customFields) > 0)
                                    @foreach($customFields as $cf)
                                        @if(is_array($cf) && isset($cf['key']) && isset($cf['value']))
                                            <tr><td>{{ $cf['key'] }}</td><td>{{ $cf['value'] }}</td></tr>
                                        @elseif(is_array($cf))
                                            @foreach($cf as $key => $val)
                                                <tr><td>{{ $key }}</td><td>{{ $val }}</td></tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                    @endif
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="gk-tab-panel" :class="{ 'is-active': tab === 'reviews' }">
                        @forelse($approvedReviews as $review)
                            <div class="gk-review-card">
                                <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:1rem;">
                                    <div>
                                        <strong>{{ $review->user->name }}</strong>
                                        <div style="color:#9ca3af; font-size:0.78rem;">{{ $review->created_at->diffForHumans() }}</div>
                                    </div>
                                    <div class="gk-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span>{{ $i <= $review->rating ? '★' : '☆' }}</span>
                                        @endfor
                                    </div>
                                </div>
                                @if($review->title)
                                    <h4 style="margin-top:0.75rem; font-weight:900;">{{ $review->title }}</h4>
                                @endif
                                <p style="margin-top:0.25rem;">{{ $review->comment }}</p>
                            </div>
                        @empty
                            <p>{{ __('general.no_reviews_yet') }}</p>
                        @endforelse
                    </div>

                    @if(!empty($product->faqs))
                    <div class="gk-tab-panel" :class="{ 'is-active': tab === 'faqs' }">
                        <div style="display:flex; flex-direction:column; gap:1.2rem;">
                            @foreach($product->faqs as $faq)
                                <div>
                                    <h4 style="font-weight:900; color:#111827; margin-bottom:0.3rem;">Q: {{ $faq['question'] ?? '' }}</h4>
                                    <p style="margin:0;">A: {{ $faq['answer'] ?? '' }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($product->supplier_shipping_charge || $product->supplier_delivery_time || $product->shipping_restriction || $product->has_return_support || !empty($product->shipping_returns))
                    <div class="gk-tab-panel" :class="{ 'is-active': tab === 'shipping' }">
                        <div class="prose max-w-none text-sm text-gray-700">
                            @if(!empty($product->shipping_returns))
                                {!! $product->shipping_returns !!}
                            @else
                                <ul style="list-style-type: disc; padding-left: 1.5rem; display: flex; flex-direction: column; gap: 0.5rem; color: #4b5563; font-size: 0.85rem;">
                                    @if($product->supplier_delivery_time)
                                        <li><strong>Delivery Time:</strong> {{ $product->supplier_delivery_time }}</li>
                                    @endif
                                    @if($product->supplier_shipping_charge)
                                        <li><strong>Shipping Charge:</strong> ৳{{ $product->supplier_shipping_charge }}</li>
                                    @elseif($product->is_free_shipping_eligible)
                                        <li><strong>Shipping Charge:</strong> Free Shipping</li>
                                    @endif
                                    @if($product->supplier_delivery_partner)
                                        <li><strong>Delivery Partner:</strong> {{ $product->supplier_delivery_partner }}</li>
                                    @endif
                                    @if($product->shipping_restriction)
                                        <li><strong>Shipping Restrictions:</strong> {{ $product->shipping_restriction }}</li>
                                    @endif
                                    <li><strong>Returns:</strong> {{ $product->has_return_support ? 'Eligible for return within the specified period.' : 'Not eligible for return.' }}</li>
                                    @if($product->has_special_handling)
                                        <li><strong>Special Handling:</strong> {{ $product->handling_type }}</li>
                                    @endif
                                </ul>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if(!empty($product->video_url))
                    <div class="gk-tab-panel" :class="{ 'is-active': tab === 'video' }">
                        <div style="position:relative; padding-bottom:56.25%; height:0; overflow:hidden; border-radius:8px; max-width:800px; margin:0 auto;">
                            @php
                                $videoUrl = $product->video_url;
                                if (str_contains($videoUrl, 'youtube.com/watch?v=')) {
                                    $videoUrl = str_replace('youtube.com/watch?v=', 'youtube.com/embed/', $videoUrl);
                                    // Remove extra params if needed
                                    $videoUrl = explode('&', $videoUrl)[0];
                                } elseif (str_contains($videoUrl, 'youtu.be/')) {
                                    $videoUrl = str_replace('youtu.be/', 'youtube.com/embed/', $videoUrl);
                                }
                            @endphp
                            <iframe src="{{ $videoUrl }}" style="position:absolute; top:0; left:0; width:100%; height:100%;" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    </div>
                    @endif

                    @if(!empty($product->documents))
                    <div class="gk-tab-panel" :class="{ 'is-active': tab === 'docs' }">
                        <div style="display:flex; flex-direction:column; gap:0.75rem;">
                            @foreach($product->documents as $doc)
                                <a href="{{ asset('storage/' . $doc) }}" target="_blank" rel="noopener" style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.75rem 1rem; border:1px solid #e5e7eb; border-radius:6px; text-decoration:none; color:#111827; font-weight:800; font-size:0.85rem; max-width:max-content; background:#f9fafb;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                                    {{ basename($doc) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>
            </div>

            @if($related->isNotEmpty())
                <section class="gk-related">
                    <h2 class="gk-related-title">{{ __('general.related_products') }}</h2>
                    <div class="gk-related-grid">
                        @foreach($related->take(10) as $relatedProduct)
                            <x-product-card :product="$relatedProduct" />
                        @endforeach
                    </div>
                </section>
            @endif
        </main>
    </div>
</div>
@endsection
