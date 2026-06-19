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
        font-size: clamp(1.8rem, 4vw, 2.3rem);
        font-weight: 900;
        line-height: 1.1;
        text-transform: uppercase;
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
        font-size: 2rem;
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
        font-size: 0.95rem;
        line-height: 1.7;
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
        grid-template-columns: repeat(3, 1fr);
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
        font-size: 0.9rem;
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
        font-size: 0.93rem;
        line-height: 1.75;
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
        font-size: 1.7rem;
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
</style>
@endpush

@section('content')
@php
    $imageUrl = $product->primaryImage?->url ?? asset('images/product-placeholder.svg');
    $approvedReviews = $product->reviews->where('is_approved', true);
    $categoryName = $product->category?->name ?? 'Garikothay';
    $brandName = $categoryName ?: 'Garikothay';
    $freeShippingThreshold = (float) \App\Models\Setting::get('free_shipping_threshold', 1500);
    $shippingCharge = (float) \App\Models\Setting::get('shipping_charge', 120);
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
            <div class="gk-side-card">
                <h3 class="gk-side-title">☷ Categories</h3>
                <ul class="gk-side-list">
                    @foreach($categories ?? collect() as $category)
                        <li>
                            <a href="{{ route('shop.index', ['category' => $category->slug]) }}" class="{{ $category->id === $product->category_id ? 'is-active' : '' }}">
                                <span>{{ $category->name }}</span>
                                <span style="color:#9ca3af; font-size:0.75rem;">{{ $category->products_count ?? '' }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="gk-side-card">
                <h3 class="gk-side-title">🏷 Top Brands</h3>
                <ul class="gk-side-list">
                    @forelse($topBrands ?? collect() as $brand)
                        <li><a href="{{ route('shop.index') }}" class="{{ $brand === $brandName ? 'is-active' : '' }}">{{ $brand }}</a></li>
                    @empty
                        <li><a href="{{ route('shop.index') }}">Garikothay</a></li>
                    @endforelse
                </ul>
            </div>
        </aside>

        <main style="min-width:0;">
            <div class="gk-product-main-grid" x-data="{
                activeImage: '{{ $imageUrl }}',
                quantity: 1,
                selectedVariant: null,
                adding: false,
                tab: 'desc',
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
            }">
                <div>
                    <div class="gk-gallery-main" @mousemove="setZoom($event)">
                        <img :src="activeImage" alt="{{ $product->name }}" onerror="this.onerror=null;this.src='{{ asset('images/product-placeholder.svg') }}';">
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
                    <div class="gk-product-brand">{{ $brandName }}</div>
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
                        @if($product->isInStock())
                            <span style="color:#16a34a; font-weight:800;">In Stock ({{ $product->stock_quantity }})</span>
                        @else
                            <span style="color:#e11d48; font-weight:800;">Out of Stock</span>
                        @endif
                        <span>·</span>
                        <span>SKU: {{ $product->sku }}</span>
                    </div>

                    <div class="gk-price-box">
                        <span class="gk-price">৳{{ number_format($product->price, 0) }}</span>
                        @if($product->compare_price)
                            <span class="gk-old-price">৳{{ number_format($product->compare_price, 0) }}</span>
                            <span class="gk-save">SAVE {{ $product->discount_percentage }}%</span>
                        @endif
                    </div>

                    <p class="gk-short-copy">
                        {{ $product->short_description ?: 'Premium quality ' . strtolower($product->name) . ' from Garikothay. Engineered for reliability, durability and optimum performance.' }}
                    </p>

                    @if($product->variants->isNotEmpty())
                        <div style="margin-top:1.2rem;">
                            <label style="display:block; margin-bottom:0.55rem; font-size:0.85rem; font-weight:900;">{{ __('general.select_variant') }}</label>
                            <div style="display:flex; flex-wrap:wrap; gap:0.5rem;">
                                @foreach($product->variants->where('is_active', true) as $variant)
                                    <button type="button" @click="selectedVariant = {{ $variant->id }}"
                                        :class="{ 'is-active': selectedVariant === {{ $variant->id }} }"
                                        class="gk-variant-button">
                                        {{ $variant->name }}
                                        @if($variant->price_modifier != 0)
                                            ({{ $variant->price_modifier > 0 ? '+' : '' }}৳{{ number_format($variant->price_modifier, 0) }})
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="gk-actions">
                        <div class="gk-qty">
                            <button type="button" @click="quantity = Math.max(1, quantity - 1)">−</button>
                            <span x-text="quantity"></span>
                            <button type="button" @click="quantity++">+</button>
                        </div>

                        @if($product->isInStock())
                            <button type="button" @click="addToCart(false)" :disabled="adding" class="gk-product-btn gk-product-btn-primary">
                                🛒 <span x-text="adding ? '{{ __('general.adding') }}' : '{{ __('general.add_to_cart') }}'"></span>
                            </button>
                        @else
                            <span class="gk-product-btn gk-product-btn-primary" style="background:#9ca3af;">{{ __('general.out_of_stock') }}</span>
                        @endif

                        @auth
                            <button type="button" class="gk-product-btn gk-product-btn-icon" @click="
                                fetch('/wishlist/toggle', {
                                    method: 'POST',
                                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                                    body: JSON.stringify({product_id: {{ $product->id }}})
                                }).then(r => r.json()).then(() => this.$dispatch('toast', { message: 'Wishlist updated' }));
                            ">♡</button>
                        @else
                            <a href="{{ route('login') }}" class="gk-product-btn gk-product-btn-icon">♡</a>
                        @endauth
                    </div>

                    @if($product->isInStock())
                        <button type="button" @click="addToCart(true)" class="gk-product-btn gk-buy-now">Buy Now</button>
                    @endif

                    <div class="gk-trust-grid">
                        <div>
                            <span>🚚</span>
                            {{ $freeShippingThreshold > 0 ? 'Free delivery over ৳' . number_format($freeShippingThreshold, 0) : 'Delivery ৳' . number_format($shippingCharge, 0) }}
                        </div>
                        <div><span>🛡</span>100% Genuine</div>
                        <div><span>↻</span>7-day returns</div>
                    </div>
                </div>

                <div class="gk-product-tabs" style="grid-column:1 / -1;">
                    <div class="gk-tab-list">
                        @foreach(['desc' => 'Description', 'specs' => 'Specifications', 'reviews' => 'Reviews (' . $approvedReviews->count() . ')', 'shipping' => 'Shipping'] as $key => $label)
                            <button type="button" class="gk-tab-button" :class="{ 'is-active': tab === '{{ $key }}' }" @click="tab = '{{ $key }}'">{{ $label }}</button>
                        @endforeach
                    </div>

                    <div class="gk-tab-panel" :class="{ 'is-active': tab === 'desc' }">
                        {!! $product->description ?: '<p>The ' . e($product->name) . ' delivers reliable performance and lasting durability. Includes quality support from Garikothay.</p>' !!}
                    </div>

                    <div class="gk-tab-panel" :class="{ 'is-active': tab === 'specs' }">
                        <table class="gk-spec-table">
                            <tbody>
                                <tr><td>Brand</td><td>{{ $brandName }}</td></tr>
                                <tr><td>SKU</td><td>{{ $product->sku }}</td></tr>
                                <tr><td>Category</td><td>{{ $categoryName }}</td></tr>
                                <tr><td>Stock</td><td>{{ $product->stock_quantity }} units</td></tr>
                                <tr><td>Warranty</td><td>Manufacturer warranty where applicable</td></tr>
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

                    <div class="gk-tab-panel" :class="{ 'is-active': tab === 'shipping' }">
                        <p>
                            Shipping charge: {{ $shippingCharge > 0 ? '৳' . number_format($shippingCharge, 0) : 'Free' }}.
                            @if($freeShippingThreshold > 0)
                                Orders over ৳{{ number_format($freeShippingThreshold, 0) }} qualify for free shipping.
                            @endif
                            Estimated delivery: {{ $deliveryTime }} via {{ $deliveryPartner }}.
                        </p>
                    </div>
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
