@extends('layouts.app')
@section('title', __('general.checkout') . ' | ' . \App\Models\Setting::get('site_name', 'Garikothay'))

@push('styles')
<style>
    .gk-checkout {
        min-height: 100vh;
        background: #f8fafc;
        color: #111827;
    }

    .gk-checkout-container {
        width: 100%;
        max-width: 1504px;
        margin: 0 auto;
        padding-right: 1rem;
        padding-left: 1rem;
    }

    .gk-checkout-breadcrumb {
        border-bottom: 1px solid #e5e7eb;
        background: #ffffff;
    }

    .gk-checkout-breadcrumb-inner {
        display: flex;
        min-height: 42px;
        align-items: center;
        gap: 0.45rem;
        color: #6b7280;
        font-size: 0.78rem;
    }

    .gk-checkout-breadcrumb a {
        color: #6b7280;
        text-decoration: none;
    }

    .gk-checkout-breadcrumb a:hover {
        color: #2D6A4F;
    }

    .gk-checkout-head {
        border-bottom: 1px solid #e5e7eb;
        background: #ffffff;
        padding: 1.55rem 0;
    }

    .gk-checkout-kicker {
        color: #2D6A4F;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
    }

    .gk-checkout-title {
        margin-top: 0.15rem;
        font-size: clamp(1.7rem, 3vw, 2.25rem);
        font-weight: 800;
        line-height: 1.1;
    }

    .gk-checkout-subtitle {
        margin-top: 0.4rem;
        color: #6b7280;
        font-size: 0.84rem;
    }

    .gk-checkout-layout {
        display: grid;
        gap: 1.5rem;
        align-items: start;
        padding-top: 2rem;
        padding-bottom: 3rem;
    }

    @media (min-width: 1024px) {
        .gk-checkout-layout {
            grid-template-columns: minmax(0, 1fr) 380px;
        }
    }

    .gk-checkout-stack {
        display: grid;
        gap: 1rem;
    }

    .gk-checkout-panel,
    .gk-checkout-summary {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #ffffff;
    }

    .gk-checkout-panel {
        padding: 1.25rem;
    }

    .gk-checkout-section-head {
        margin-bottom: 1rem;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 0.8rem;
    }

    .gk-checkout-section-head h2 {
        font-size: 1rem;
        font-weight: 800;
    }

    .gk-checkout-section-head p {
        margin-top: 0.2rem;
        color: #6b7280;
        font-size: 0.74rem;
    }

    .gk-checkout-field label {
        display: block;
        margin-bottom: 0.35rem;
        color: #374151;
        font-size: 0.76rem;
        font-weight: 700;
    }

    .gk-checkout-field input,
    .gk-checkout-field select,
    .gk-checkout-field textarea {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        background: #ffffff;
        padding: 0.7rem 0.8rem;
        color: #111827;
        font-size: 0.82rem;
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .gk-checkout-field input:focus,
    .gk-checkout-field select:focus,
    .gk-checkout-field textarea:focus {
        border-color: #2D6A4F;
        box-shadow: 0 0 0 3px rgba(45, 106, 79, 0.1);
    }

    .gk-checkout-address,
    .gk-checkout-payment {
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        background: #ffffff;
        transition: border-color 0.2s ease, background 0.2s ease;
    }

    .gk-checkout-address:hover,
    .gk-checkout-payment:hover {
        border-color: #74a88f;
    }

    .gk-checkout-address:has(input:checked),
    .gk-checkout-payment:has(input:checked) {
        border-color: #2D6A4F;
        background: #f0f7f3;
    }

    .gk-checkout-summary {
        padding: 1.25rem;
    }

    @media (min-width: 1024px) {
        .gk-checkout-summary {
            position: sticky;
            top: 6rem;
        }
    }

    .gk-checkout-item {
        display: grid;
        grid-template-columns: 54px minmax(0, 1fr) auto;
        gap: 0.75rem;
        align-items: center;
        padding: 0.7rem 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .gk-checkout-item img {
        width: 54px;
        height: 54px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        object-fit: cover;
    }

    .gk-checkout-totals {
        display: grid;
        gap: 0.65rem;
        margin-top: 1rem;
        font-size: 0.8rem;
    }

    .gk-checkout-total-row {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        color: #4b5563;
    }

    .gk-checkout-total-row.is-final {
        margin-top: 0.25rem;
        border-top: 1px solid #d1d5db;
        padding-top: 0.85rem;
        color: #111827;
        font-size: 1rem;
        font-weight: 800;
    }

    .gk-checkout-total-row.is-final span:last-child {
        color: #2D6A4F;
        font-size: 1.35rem;
    }

    .gk-checkout-logistics {
        border: 1px solid #dcebe3;
        border-radius: 6px;
        background: #f0f7f3;
        padding: 0.75rem;
        color: #4b5563;
        font-size: 0.72rem;
    }

    .gk-checkout-submit {
        width: 100%;
        min-height: 46px;
        margin-top: 1rem;
        border: 0;
        border-radius: 6px;
        background: #2D6A4F;
        color: #ffffff;
        font-size: 0.85rem;
        font-weight: 800;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .gk-checkout-submit:hover {
        background: #1f513b;
    }
</style>
@endpush

@section('content')
<div class="gk-checkout" x-data="{
    addresses: {{ $addresses->toJson() }},
    selectedAddressId: null,
    orderValue: @js((float) $orderValue),
    freeShippingThreshold: @js((float) $freeShippingThreshold),
    dhakaCityShippingCharge: @js((float) $dhakaCityShippingCharge),
    outsideDhakaShippingCharge: @js((float) $outsideDhakaShippingCharge),
    formData: {
        full_name: @js(old('full_name', auth()->user()?->name ?? '')),
        email: @js(old('email', auth()->user()?->email ?? '')),
        phone: @js(old('phone', auth()->user()?->phone ?? '')),
        address_line_1: @js(old('address_line_1')),
        address_line_2: @js(old('address_line_2')),
        city: @js(old('city')),
        division: @js(old('division', count($divisions) ? $divisions[0] : '')),
        postal_code: @js(old('postal_code'))
    },
    init() {
        const defaultAddr = this.addresses.find(a => a.is_default);
        if (defaultAddr && !@js(old('full_name', ''))) {
            this.selectedAddressId = defaultAddr.id;
            this.selectAddress(defaultAddr.id);
        }
    },
    selectAddress(id) {
        const addr = this.addresses.find(a => a.id == id);
        if (addr) {
            this.formData.full_name = addr.full_name;
            this.formData.phone = addr.phone;
            this.formData.address_line_1 = addr.address_line_1;
            this.formData.address_line_2 = addr.address_line_2 || '';
            this.formData.city = addr.city;
            this.formData.division = addr.division;
            this.formData.postal_code = addr.postal_code || '';
        }
    },
    get qualifiesForFreeShipping() {
        return this.freeShippingThreshold > 0 && this.orderValue >= this.freeShippingThreshold;
    },
    get isDhakaCity() {
        const division = String(this.formData.division || '').trim().toLocaleLowerCase();
        const city = String(this.formData.city || '').trim().toLocaleLowerCase();

        return ['dhaka', 'ঢাকা'].includes(division)
            && ['dhaka', 'dhaka city', 'dacca', 'ঢাকা', 'ঢাকা সিটি'].includes(city);
    },
    get shippingCharge() {
        if (this.qualifiesForFreeShipping) {
            return 0;
        }

        return this.isDhakaCity ? this.dhakaCityShippingCharge : this.outsideDhakaShippingCharge;
    },
    get checkoutTotal() {
        return this.orderValue + this.shippingCharge;
    },
    formatMoney(value) {
        return new Intl.NumberFormat('en-US', { maximumFractionDigits: 0 }).format(value);
    }
}">
    <nav class="gk-checkout-breadcrumb">
        <div class="gk-checkout-container gk-checkout-breadcrumb-inner">
            <a href="{{ route('home') }}">{{ __('general.home') }}</a>
            <span>/</span>
            <a href="{{ route('cart.index') }}">{{ __('general.cart') }}</a>
            <span>/</span>
            <span>{{ __('general.checkout') }}</span>
        </div>
    </nav>

    <header class="gk-checkout-head">
        <div class="gk-checkout-container">
            <div class="gk-checkout-kicker">Secure checkout</div>
            <h1 class="gk-checkout-title">{{ __('general.checkout_title') }}</h1>
            <p class="gk-checkout-subtitle">Confirm your delivery details and complete the order.</p>
        </div>
    </header>

    <div class="gk-checkout-container">
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4 mt-5 text-sm font-medium">
        {{ session('error') }}
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4 mt-5 text-sm">
        <div class="font-bold mb-1">{{ __('general.please_correct_errors', 'Please correct the errors below:') }}</div>
        <ul class="list-disc pl-5 space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('checkout.store') }}" class="gk-checkout-layout">
        @csrf

        <!-- Shipping Info -->
        <div class="gk-checkout-stack">
            <section class="gk-checkout-panel">
                <div class="gk-checkout-section-head">
                    <h2>{{ __('general.shipping_information') }}</h2>
                    <p>Enter the address where you want to receive the order.</p>
                </div>

                @if($addresses->isNotEmpty())
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('general.use_saved_address') }}</label>
                    @foreach($addresses as $address)
                    <label class="gk-checkout-address flex items-start gap-3 p-3 mb-2 cursor-pointer">
                        <input type="radio" name="saved_address" value="{{ $address->id }}" x-model="selectedAddressId" @change="selectAddress($el.value)" class="mt-1 text-[#2D6A4F]">
                        <div class="text-sm">
                            <div class="font-semibold">{{ $address->full_name }} <span class="text-xs bg-gray-100 px-2 py-0.5 rounded-full">{{ $address->label->label() }}</span></div>
                            <div class="text-gray-500">{{ $address->full_address }}</div>
                        </div>
                    </label>
                    @endforeach
                    <div class="text-sm text-[#2D6A4F] font-medium mt-2">{{ __('general.new_address_below') }}</div>
                </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="gk-checkout-field">
                        <label>{{ __('general.full_name') }} *</label>
                        <input type="text" name="full_name" x-model="formData.full_name" required
                            class="@error('full_name') border-red-400 @enderror">
                        @error('full_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    @guest
                    <div class="gk-checkout-field">
                        <label>{{ __('general.email') }} *</label>
                        <input type="email" name="email" x-model="formData.email" required
                            class="@error('email') border-red-400 @enderror">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    @endguest
                    <div class="gk-checkout-field">
                        <label>{{ __('general.phone') }} *</label>
                        <input type="text" name="phone" x-model="formData.phone" required
                            class="@error('phone') border-red-400 @enderror">
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="gk-checkout-field sm:col-span-2">
                        <label>{{ __('general.address_line_1') }} *</label>
                        <input type="text" name="address_line_1" x-model="formData.address_line_1" required
                            >
                    </div>
                    <div class="gk-checkout-field sm:col-span-2">
                        <label>{{ __('general.address_line_2') }}</label>
                        <input type="text" name="address_line_2" x-model="formData.address_line_2"
                            >
                    </div>
                    <div class="gk-checkout-field">
                        <label>{{ __('general.city') }} *</label>
                        <input type="text" name="city" x-model.trim="formData.city" required placeholder="Dhaka"
                            >
                        <p class="mt-1 text-xs text-gray-500" x-show="formData.city">
                            <span x-text="isDhakaCity ? 'Inside Dhaka city rate selected' : 'Outside Dhaka rate selected'"></span>
                        </p>
                    </div>
                    <div class="gk-checkout-field">
                        <label>{{ __('general.division') }} *</label>
                        <select name="division" x-model="formData.division" required>
                            @foreach($divisions as $division)
                            <option value="{{ $division }}">{{ $division }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="gk-checkout-field">
                        <label>{{ __('general.postal_code') }}</label>
                        <input type="text" name="postal_code" x-model="formData.postal_code"
                            >
                    </div>
                </div>
                @auth
                <label class="flex items-center gap-2 mt-4 text-sm text-gray-600 cursor-pointer">
                    <input type="checkbox" name="save_address" value="1" class="text-[#2D6A4F]">
                    {{ __('general.save_address') }}
                </label>
                @endauth
            </section>

            <!-- Payment Method -->
            <section class="gk-checkout-panel">
                <div class="gk-checkout-section-head">
                    <h2>{{ __('general.payment_method') }}</h2>
                    <p>Select how you would like to pay.</p>
                </div>
                <div class="space-y-3">
                    @foreach(\App\Enums\PaymentMethod::cases() as $method)
                    <label class="gk-checkout-payment flex items-center gap-3 p-4 cursor-pointer">
                        <input type="radio" name="payment_method" value="{{ $method->value }}" {{ $loop->first ? 'checked' : '' }} required class="text-[#2D6A4F]">
                        <div>
                            <div class="font-semibold text-sm">{{ $method->label() }}</div>
                            @if($method->value === 'cod')
                            <div class="text-xs text-gray-500">{{ __('general.cod_description') }}</div>
                            @endif
                        </div>
                    </label>
                    @endforeach
                </div>
            </section>

            <!-- Notes -->
            <section class="gk-checkout-panel gk-checkout-field">
                <div class="gk-checkout-section-head">
                    <h2>{{ __('general.order_notes') }}</h2>
                    <p>Add any delivery instruction for this order.</p>
                </div>
                <textarea name="notes" rows="3" placeholder="{{ __('general.special_instructions') }}"
                    ></textarea>
            </section>
        </div>

        <!-- Order Summary -->
        <aside class="gk-checkout-summary">
                <div class="gk-checkout-section-head">
                    <h2>{{ __('general.your_order') }}</h2>
                    <p>{{ $cart->item_count }} item{{ $cart->item_count === 1 ? '' : 's' }} ready to order.</p>
                </div>
                <div>
                    @foreach($cart->items as $item)
                    <div class="gk-checkout-item">
                        <img src="{{ $item->product->primaryImage?->url ?? asset('images/product-placeholder.svg') }}" alt="{{ $item->product->name }}" onerror="this.onerror=null;this.src='{{ asset('images/product-placeholder.svg') }}';">
                        <div class="flex-1 text-sm">
                            <div class="font-medium">{{ $item->product->name }}</div>
                            @if($item->variant) <div class="text-gray-400">{{ $item->variant->name }}</div> @endif
                            <div class="text-gray-500">x{{ $item->quantity }}</div>
                        </div>
                        <span class="font-semibold text-sm">৳{{ number_format($item->total, 0) }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="gk-checkout-totals">
                    <div class="gk-checkout-total-row"><span>{{ __('general.subtotal') }}</span><span>৳{{ number_format($cart->subtotal, 0) }}</span></div>
                    @if($cart->coupon)
                    <div class="gk-checkout-total-row text-green-600"><span>{{ __('general.coupon') }} ({{ $cart->coupon->code }})</span><span>-৳{{ number_format($cart->coupon->calculateDiscount($cart->subtotal), 0) }}</span></div>
                    @endif
                    <div class="gk-checkout-total-row">
                        <span>{{ __('general.shipping') }}</span>
                        <span x-text="shippingCharge > 0 ? '৳' + formatMoney(shippingCharge) : @js(__('general.free'))"></span>
                    </div>
                    <div class="gk-checkout-logistics">
                        <div class="flex justify-between gap-3"><span>Delivery Time</span><strong class="text-gray-800">{{ $deliveryTime }}</strong></div>
                        <div class="mt-1 flex justify-between gap-3"><span>Delivery Partner</span><strong class="text-gray-800">{{ $deliveryPartner }}</strong></div>
                    </div>
                    <div class="gk-checkout-total-row is-final">
                        <span>{{ __('general.total') }}</span>
                        <span x-text="'৳' + formatMoney(checkoutTotal)"></span>
                    </div>
                </div>
                <button type="submit" class="gk-checkout-submit">
                    {{ __('general.place_order') }}
                </button>
        </aside>
    </form>
    </div>
</div>
@endsection
