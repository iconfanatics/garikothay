@extends('layouts.app')

@section('title', __('general.cart') . ' | ' . \App\Models\Setting::get('site_name', 'Garikothay'))

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Oswald:wght@500;600;700&display=swap" rel="stylesheet">
<style>
    .gk-cart {
        min-height: 100vh;
        background: #f8fafc;
        color: #111827;
        font-family: 'Inter', system-ui, sans-serif;
    }

    .gk-cart h1,
    .gk-cart h2,
    .gk-cart h3 {
        font-family: 'Oswald', 'Inter', sans-serif;
        letter-spacing: 0;
    }

    .gk-cart-container {
        width: 100%;
        max-width: 1504px;
        margin: 0 auto;
        padding-right: 1rem;
        padding-left: 1rem;
    }

    .gk-cart-breadcrumb {
        border-bottom: 1px solid #e5e7eb;
        background: #ffffff;
    }

    .gk-cart-breadcrumb-inner {
        display: flex;
        min-height: 42px;
        align-items: center;
        gap: 0.45rem;
        color: #6b7280;
        font-size: 0.78rem;
    }

    .gk-cart-breadcrumb a {
        color: #6b7280;
        text-decoration: none;
    }

    .gk-cart-breadcrumb a:hover {
        color: #e11d48;
    }

    .gk-cart-head {
        border-bottom: 1px solid #e5e7eb;
        background: #ffffff;
        padding: 1.7rem 0;
    }

    .gk-cart-kicker {
        color: #e11d48;
        font-size: 0.7rem;
        font-weight: 900;
        text-transform: uppercase;
    }

    .gk-cart-title {
        margin-top: 0.2rem;
        font-size: clamp(1.8rem, 4vw, 2.5rem);
        font-weight: 900;
        line-height: 1;
        text-transform: uppercase;
    }

    .gk-cart-copy {
        margin-top: 0.45rem;
        color: #6b7280;
        font-size: 0.85rem;
    }

    .gk-cart-layout {
        display: grid;
        gap: 1.5rem;
        padding-top: 2rem;
        padding-bottom: 3rem;
    }

    @media (min-width: 1024px) {
        .gk-cart-layout {
            grid-template-columns: minmax(0, 1fr) 360px;
            align-items: start;
        }
    }

    .gk-cart-list {
        display: grid;
        gap: 0.75rem;
    }

    .gk-cart-item {
        display: grid;
        grid-template-columns: 92px minmax(0, 1fr) auto;
        gap: 1rem;
        align-items: center;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #ffffff;
        padding: 0.85rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .gk-cart-item:hover {
        border-color: #fda4af;
        box-shadow: 0 10px 24px rgba(17, 24, 39, 0.06);
    }

    .gk-cart-image {
        display: block;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        background: #f3f4f6;
    }

    .gk-cart-image img {
        width: 100%;
        aspect-ratio: 1 / 1;
        object-fit: cover;
    }

    .gk-cart-category {
        color: #e11d48;
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
    }

    .gk-cart-product-name {
        display: inline-block;
        margin-top: 0.2rem;
        color: #111827;
        font-size: 0.92rem;
        font-weight: 800;
        line-height: 1.4;
        text-decoration: none;
    }

    .gk-cart-product-name:hover {
        color: #e11d48;
    }

    .gk-cart-variant {
        margin-top: 0.2rem;
        color: #6b7280;
        font-size: 0.72rem;
    }

    .gk-cart-unit-price {
        margin-top: 0.45rem;
        color: #e11d48;
        font-size: 0.95rem;
        font-weight: 900;
    }

    .gk-cart-item-actions {
        display: grid;
        justify-items: end;
        gap: 0.65rem;
    }

    .gk-cart-item-total {
        font-family: 'Oswald', 'Inter', sans-serif;
        font-size: 1.15rem;
        font-weight: 900;
    }

    .gk-cart-control-row {
        display: flex;
        align-items: center;
        gap: 0.55rem;
    }

    .gk-cart-quantity {
        display: flex;
        min-height: 36px;
        align-items: center;
        overflow: hidden;
        border: 1px solid #d1d5db;
        border-radius: 6px;
    }

    .gk-cart-quantity button {
        display: grid;
        width: 2rem;
        height: 36px;
        place-items: center;
        border: 0;
        background: #ffffff;
        color: #374151;
        font-size: 1rem;
        cursor: pointer;
    }

    .gk-cart-quantity button:hover {
        background: #fff1f2;
        color: #e11d48;
    }

    .gk-cart-quantity button:disabled {
        cursor: wait;
        opacity: 0.45;
    }

    .gk-cart-quantity span {
        min-width: 2.3rem;
        text-align: center;
        font-size: 0.8rem;
        font-weight: 900;
    }

    .gk-cart-remove {
        display: grid;
        width: 36px;
        height: 36px;
        place-items: center;
        border: 1px solid #fecdd3;
        border-radius: 6px;
        background: #ffffff;
        color: #e11d48;
        cursor: pointer;
    }

    .gk-cart-remove:hover {
        background: #e11d48;
        color: #ffffff;
    }

    .gk-cart-summary {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #ffffff;
        padding: 1.25rem;
    }

    @media (min-width: 1024px) {
        .gk-cart-summary {
            position: sticky;
            top: 9.5rem;
        }
    }

    .gk-cart-summary-head {
        border-bottom: 2px solid #e11d48;
        padding-bottom: 0.7rem;
    }

    .gk-cart-summary-title {
        font-size: 1.35rem;
        font-weight: 900;
        text-transform: uppercase;
    }

    .gk-cart-summary-count {
        margin-top: 0.2rem;
        color: #6b7280;
        font-size: 0.72rem;
    }

    .gk-cart-coupon {
        border-bottom: 1px solid #e5e7eb;
        padding: 1rem 0;
    }

    .gk-cart-coupon label {
        display: block;
        margin-bottom: 0.45rem;
        color: #374151;
        font-size: 0.75rem;
        font-weight: 800;
    }

    .gk-cart-coupon-row {
        display: flex;
        min-height: 40px;
        overflow: hidden;
        border: 1px solid #d1d5db;
        border-radius: 6px;
    }

    .gk-cart-coupon-row:focus-within {
        border-color: #e11d48;
        box-shadow: 0 0 0 2px rgba(225, 29, 72, 0.1);
    }

    .gk-cart-coupon-row input {
        min-width: 0;
        flex: 1;
        border: 0;
        padding: 0 0.7rem;
        font-size: 0.78rem;
        outline: none;
    }

    .gk-cart-coupon-row button {
        border: 0;
        background: #111827;
        color: #ffffff;
        padding: 0 0.9rem;
        font-size: 0.75rem;
        font-weight: 800;
        cursor: pointer;
    }

    .gk-cart-coupon-row button:hover {
        background: #e11d48;
    }

    .gk-cart-coupon-message {
        margin-top: 0.4rem;
        color: #6b7280;
        font-size: 0.7rem;
    }

    .gk-cart-totals {
        display: grid;
        gap: 0.65rem;
        padding: 1rem 0;
        font-size: 0.8rem;
    }

    .gk-cart-total-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        color: #6b7280;
    }

    .gk-cart-total-row.is-discount {
        color: #16a34a;
    }

    .gk-cart-total-row.is-final {
        border-top: 1px solid #e5e7eb;
        margin-top: 0.2rem;
        color: #111827;
        padding-top: 0.8rem;
        font-size: 1rem;
        font-weight: 900;
    }

    .gk-cart-total-row.is-final strong {
        color: #e11d48;
        font-family: 'Oswald', 'Inter', sans-serif;
        font-size: 1.35rem;
    }

    .gk-cart-checkout {
        display: flex;
        min-height: 46px;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        border-radius: 6px;
        background: #e11d48;
        color: #ffffff;
        font-size: 0.82rem;
        font-weight: 900;
        text-decoration: none;
    }

    .gk-cart-checkout:hover {
        background: #be123c;
    }

    .gk-cart-continue {
        display: block;
        margin-top: 0.8rem;
        color: #6b7280;
        font-size: 0.75rem;
        font-weight: 700;
        text-align: center;
        text-decoration: none;
    }

    .gk-cart-continue:hover {
        color: #e11d48;
    }

    .gk-cart-trust {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.4rem;
        border-top: 1px solid #e5e7eb;
        margin-top: 1rem;
        padding-top: 0.9rem;
        color: #6b7280;
        font-size: 0.64rem;
        text-align: center;
    }

    .gk-cart-trust strong {
        display: block;
        color: #111827;
        font-size: 0.68rem;
    }

    .gk-cart-empty {
        max-width: 720px;
        margin: 0 auto;
        padding-top: 4rem;
        padding-bottom: 5rem;
        text-align: center;
    }

    .gk-cart-empty-icon {
        display: grid;
        width: 5rem;
        height: 5rem;
        place-items: center;
        border-radius: 999px;
        margin: 0 auto;
        background: #ffe4e6;
        color: #e11d48;
        font-size: 2rem;
    }

    .gk-cart-empty h2 {
        margin-top: 1.2rem;
        font-size: 1.7rem;
        font-weight: 900;
        text-transform: uppercase;
    }

    .gk-cart-empty p {
        margin-top: 0.45rem;
        color: #6b7280;
        font-size: 0.85rem;
    }

    .gk-cart-empty a {
        display: inline-flex;
        min-height: 44px;
        align-items: center;
        margin-top: 1.2rem;
        border-radius: 6px;
        background: #e11d48;
        color: #ffffff;
        padding: 0 1.2rem;
        font-size: 0.8rem;
        font-weight: 900;
        text-decoration: none;
    }

    .gk-cart-empty a:hover {
        background: #be123c;
    }

    @media (max-width: 639px) {
        .gk-cart-item {
            grid-template-columns: 76px minmax(0, 1fr);
            align-items: start;
        }

        .gk-cart-item-actions {
            grid-column: 1 / -1;
            grid-template-columns: 1fr auto;
            width: 100%;
            align-items: center;
            justify-items: stretch;
        }

        .gk-cart-item-total {
            grid-column: 1 / -1;
            grid-row: 1;
            text-align: right;
        }

        .gk-cart-control-row {
            grid-column: 1 / -1;
            justify-content: flex-end;
        }
    }
</style>
@endpush

@section('content')
<div class="gk-cart">
    <nav class="gk-cart-breadcrumb">
        <div class="gk-cart-container gk-cart-breadcrumb-inner">
            <a href="{{ route('home') }}">{{ __('general.home') }}</a>
            <span>›</span>
            <span style="color:#111827; font-weight:700;">{{ __('general.cart') }}</span>
        </div>
    </nav>

    <header class="gk-cart-head">
        <div class="gk-cart-container">
            <div class="gk-cart-kicker">Gari Kothay Auto Marketplace</div>
            <h1 class="gk-cart-title">{{ __('general.shopping_cart') }}</h1>
            <p class="gk-cart-copy">Review your selected products before proceeding to checkout.</p>
        </div>
    </header>

    <div class="gk-cart-container">
        @if($cart->items->isEmpty())
            <div class="gk-cart-empty">
                <div class="gk-cart-empty-icon">🛒</div>
                <h2>{{ __('general.your_cart_is_empty') }}</h2>
                <p>{{ __('general.no_plants_added') }}</p>
                <a href="{{ route('shop.index') }}">{{ __('general.start_shopping') }} →</a>
            </div>
        @else
            <div class="gk-cart-layout" x-data="{
                couponCode: '',
                couponMsg: '',
                applyingCoupon: false,
                applyCoupon() {
                    if (!this.couponCode.trim()) {
                        this.couponMsg = 'Please enter a coupon code.';
                        return;
                    }

                    this.applyingCoupon = true;
                    fetch('/cart/coupon', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                        body: JSON.stringify({code: this.couponCode})
                    }).then(async response => {
                        const data = await response.json();
                        this.couponMsg = data.message;
                        if (data.success) window.location.reload();
                    }).catch(() => {
                        this.couponMsg = 'Unable to apply the coupon right now.';
                    }).finally(() => {
                        this.applyingCoupon = false;
                    });
                }
            }">
                <section class="gk-cart-list" aria-label="Cart items">
                    @foreach($cart->items as $item)
                        <article class="gk-cart-item" x-data="{
                            qty: {{ $item->quantity }},
                            updating: false,
                            updateQuantity(nextQuantity) {
                                if (this.updating) return;
                                this.qty = Math.max(1, Math.min(99, nextQuantity));
                                this.updating = true;
                                fetch('/cart/item/{{ $item->id }}', {
                                    method: 'PATCH',
                                    headers: {'Content-Type':'application/json', 'X-CSRF-TOKEN':'{{ csrf_token() }}'},
                                    body: JSON.stringify({quantity: this.qty})
                                }).then(() => window.location.reload());
                            },
                            removeItem() {
                                if (this.updating) return;
                                this.updating = true;
                                fetch('/cart/item/{{ $item->id }}', {
                                    method: 'DELETE',
                                    headers: {'X-CSRF-TOKEN':'{{ csrf_token() }}'}
                                }).then(() => window.location.reload());
                            }
                        }">
                            <a href="{{ route('shop.show', $item->product->slug) }}" class="gk-cart-image">
                                <img src="{{ $item->product->primaryImage?->url ?? asset('images/product-placeholder.svg') }}"
                                     alt="{{ $item->product->name }}"
                                     onerror="this.onerror=null;this.src='{{ asset('images/product-placeholder.svg') }}';">
                            </a>

                            <div>
                                @if($item->product->category)
                                    <div class="gk-cart-category">{{ $item->product->category->name }}</div>
                                @endif
                                <a href="{{ route('shop.show', $item->product->slug) }}" class="gk-cart-product-name">
                                    {{ $item->product->name }}
                                </a>
                                @if($item->variant)
                                    <div class="gk-cart-variant">{{ $item->variant->name }}</div>
                                @endif
                                <div class="gk-cart-unit-price">৳{{ number_format($item->unit_price, 0) }} each</div>
                            </div>

                            <div class="gk-cart-item-actions">
                                <div class="gk-cart-item-total">৳{{ number_format($item->total, 0) }}</div>
                                <div class="gk-cart-control-row">
                                    <div class="gk-cart-quantity">
                                        <button type="button" :disabled="updating" @click="updateQuantity(qty - 1)" aria-label="Decrease quantity">−</button>
                                        <span x-text="qty"></span>
                                        <button type="button" :disabled="updating" @click="updateQuantity(qty + 1)" aria-label="Increase quantity">+</button>
                                    </div>
                                    <button type="button" class="gk-cart-remove" :disabled="updating" @click="removeItem()" aria-label="Remove item">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7 18.1 20H5.9L5 7m4 4v5m6-5v5M8 7l1-3h6l1 3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </section>

                <aside class="gk-cart-summary">
                    <div class="gk-cart-summary-head">
                        <h2 class="gk-cart-summary-title">{{ __('general.order_summary') }}</h2>
                        <p class="gk-cart-summary-count">{{ $cart->item_count }} item{{ $cart->item_count === 1 ? '' : 's' }} in your cart</p>
                    </div>

                    <div class="gk-cart-coupon">
                        <label for="cart-coupon">{{ __('general.coupon_code') }}</label>
                        <div class="gk-cart-coupon-row">
                            <input id="cart-coupon" x-model="couponCode" type="text" placeholder="{{ __('general.enter_code') }}">
                            <button type="button" @click="applyCoupon()" :disabled="applyingCoupon">
                                <span x-text="applyingCoupon ? 'Applying...' : '{{ __('general.apply') }}'"></span>
                            </button>
                        </div>
                        <p x-show="couponMsg" x-text="couponMsg" class="gk-cart-coupon-message"></p>
                    </div>

                    <div class="gk-cart-totals">
                        <div class="gk-cart-total-row">
                            <span>{{ __('general.subtotal') }}</span>
                            <strong>৳{{ number_format($cart->subtotal, 0) }}</strong>
                        </div>

                        @if($cart->coupon)
                            <div class="gk-cart-total-row is-discount">
                                <span>{{ __('general.discount') }} ({{ $cart->coupon->code }})</span>
                                <strong>-৳{{ number_format($cart->coupon->calculateDiscount($cart->subtotal), 0) }}</strong>
                            </div>
                        @endif

                        <div class="gk-cart-total-row">
                            <span>{{ __('general.shipping') }}</span>
                            <span>{{ __('general.calculated_at_checkout') }}</span>
                        </div>

                        @php
                            $cartDiscount = $cart->coupon ? $cart->coupon->calculateDiscount($cart->subtotal) : 0;
                            $cartTotal = max(0, $cart->subtotal - $cartDiscount);
                        @endphp
                        <div class="gk-cart-total-row is-final">
                            <span>{{ __('general.total') }}</span>
                            <strong>৳{{ number_format($cartTotal, 0) }}</strong>
                        </div>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="gk-cart-checkout">
                        {{ __('general.proceed_to_checkout') }}
                        <span aria-hidden="true">→</span>
                    </a>
                    <a href="{{ route('shop.index') }}" class="gk-cart-continue">← {{ __('general.continue_shopping') }}</a>

                    <div class="gk-cart-trust">
                        <span><strong>Genuine</strong>Products</span>
                        <span><strong>Secure</strong>Checkout</span>
                        <span><strong>7-day</strong>Returns</span>
                    </div>
                </aside>
            </div>
        @endif
    </div>
</div>
@endsection
