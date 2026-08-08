@extends('layouts.app')
@section('title', __('general.checkout') . ' | ' . \App\Models\Setting::get('site_name', 'Garikothay'))

@push('styles')
<style>
    .gk-checkout {
        min-height: 100vh;
        background: #f3f4f6;
        color: #111827;
        font-family: 'Inter', system-ui, sans-serif;
    }

    .gk-checkout h1,
    .gk-checkout h2 {
        font-family: 'Oswald', 'Inter', sans-serif;
        letter-spacing: 0;
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
        color: #e11d48;
    }

    .gk-checkout-head {
        border-bottom: 1px solid #e5e7eb;
        background: #ffffff;
        padding: 0.9rem 0;
    }

    .gk-checkout-kicker {
        color: #e11d48;
        font-size: 0.64rem;
        font-weight: 800;
        text-transform: uppercase;
    }

    .gk-checkout-title {
        margin-top: 0.1rem;
        font-size: 1.55rem;
        font-weight: 800;
        line-height: 1.1;
    }

    .gk-checkout-subtitle {
        margin-top: 0.2rem;
        color: #6b7280;
        font-size: 0.76rem;
    }

    .gk-checkout-layout {
        display: grid;
        gap: 1rem;
        align-items: start;
        padding-top: 1rem;
        padding-bottom: 2rem;
    }

    @media (min-width: 1024px) {
        .gk-checkout-layout {
            grid-template-columns: minmax(0, 1fr) 350px;
        }
    }

    .gk-checkout-stack {
        display: grid;
        gap: 0.75rem;
    }

    .gk-checkout-panel,
    .gk-checkout-summary {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #ffffff;
    }

    .gk-checkout-panel {
        padding: 0.9rem;
    }

    .gk-checkout-section-head {
        margin-bottom: 0.75rem;
        border-bottom: 2px solid #e11d48;
        padding-bottom: 0.55rem;
    }

    .gk-checkout-section-head h2 {
        font-size: 0.92rem;
        font-weight: 800;
    }

    .gk-checkout-section-head p {
        margin-top: 0.1rem;
        color: #6b7280;
        font-size: 0.68rem;
    }

    .gk-checkout-field label {
        display: block;
        margin-bottom: 0.25rem;
        color: #374151;
        font-size: 0.7rem;
        font-weight: 700;
    }

    .gk-checkout-field input,
    .gk-checkout-field select,
    .gk-checkout-field textarea {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        background: #ffffff;
        min-height: 36px;
        padding: 0.45rem 0.65rem;
        color: #111827;
        font-size: 0.76rem;
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .gk-checkout-field textarea {
        min-height: 64px;
    }

    .gk-checkout-field input:focus,
    .gk-checkout-field select:focus,
    .gk-checkout-field textarea:focus {
        border-color: #e11d48;
        box-shadow: 0 0 0 3px rgba(225, 29, 72, 0.09);
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
        border-color: #fda4af;
    }

    .gk-checkout-address:has(input:checked),
    .gk-checkout-payment:has(input:checked) {
        border-color: #e11d48;
        background: #fff1f2;
    }

    .gk-checkout-summary {
        padding: 0.9rem;
    }

    @media (min-width: 1024px) {
        .gk-checkout-summary {
            position: sticky;
            top: 6rem;
        }
    }

    .gk-checkout-item {
        display: grid;
        grid-template-columns: 48px minmax(0, 1fr) auto;
        gap: 0.75rem;
        align-items: center;
        padding: 0.55rem 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .gk-checkout-item img {
        width: 48px;
        height: 48px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        object-fit: cover;
    }

    .gk-checkout-totals {
        display: grid;
        gap: 0.5rem;
        margin-top: 0.75rem;
        font-size: 0.74rem;
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
        padding-top: 0.65rem;
        color: #111827;
        font-size: 1rem;
        font-weight: 800;
    }

    .gk-checkout-total-row.is-final span:last-child {
        color: #e11d48;
        font-size: 1.2rem;
    }

    .gk-checkout-logistics {
        border: 1px solid #e5e7eb;
        border-radius: 4px;
        background: #f9fafb;
        padding: 0.6rem;
        color: #4b5563;
        font-size: 0.72rem;
    }

    .gk-checkout-submit {
        width: 100%;
        min-height: 40px;
        margin-top: 0.75rem;
        border: 0;
        border-radius: 4px;
        background: #111827;
        color: #ffffff;
        font-size: 0.78rem;
        font-weight: 800;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .gk-checkout-submit:hover {
        background: #e11d48;
    }

    .gk-checkout-coupon {
        border-top: 1px solid #e5e7eb;
        border-bottom: 1px solid #e5e7eb;
        padding: 1rem 0;
        margin: 1rem 0;
    }

    .gk-checkout-coupon label {
        display: block;
        margin-bottom: 0.45rem;
        color: #374151;
        font-size: 0.75rem;
        font-weight: 800;
    }

    .gk-checkout-coupon-row {
        display: flex;
        min-height: 40px;
        overflow: hidden;
        border: 1px solid #d1d5db;
        border-radius: 6px;
    }

    .gk-checkout-coupon-row:focus-within {
        border-color: #e11d48;
        box-shadow: 0 0 0 2px rgba(225, 29, 72, 0.1);
    }

    .gk-checkout-coupon-row input {
        min-width: 0;
        flex: 1;
        border: 0;
        padding: 0 0.7rem;
        font-size: 0.78rem;
        outline: none;
    }

    .gk-checkout-coupon-row button {
        border: 0;
        background: #111827;
        color: #ffffff;
        padding: 0 0.9rem;
        font-size: 0.75rem;
        font-weight: 800;
        cursor: pointer;
    }

    .gk-checkout-coupon-row button:hover {
        background: #e11d48;
    }

    .gk-checkout-coupon-message {
        margin-top: 0.4rem;
        color: #6b7280;
        font-size: 0.7rem;
    }
</style>
@endpush

@section('content')
<div class="gk-checkout" x-data="{
    addresses: {{ $addresses->toJson() }},
    selectedAddressId: null,
    orderValue: @js((float) $orderValue),
    freeShippingThreshold: @js((float) $freeShippingThreshold),
    shippingZones: @js($shippingZones),
    shippingMethodId: null,
    locations: {
        'Dhaka': ['Dhaka', 'Faridpur', 'Gazipur', 'Gopalganj', 'Kishoreganj', 'Madaripur', 'Manikganj', 'Munshiganj', 'Narayanganj', 'Narsingdi', 'Rajbari', 'Shariatpur', 'Tangail'],
        'Chattogram': ['Bandarban', 'Brahmanbaria', 'Chandpur', 'Chattogram', 'Comilla', 'Cox\'s Bazar', 'Feni', 'Khagrachhari', 'Lakshmipur', 'Noakhali', 'Rangamati'],
        'Rajshahi': ['Bogra', 'Chapainawabganj', 'Joypurhat', 'Naogaon', 'Natore', 'Pabna', 'Rajshahi', 'Sirajganj'],
        'Khulna': ['Bagerhat', 'Chuadanga', 'Jessore', 'Jhenaidah', 'Khulna', 'Kushtia', 'Magura', 'Meherpur', 'Narail', 'Satkhira'],
        'Barishal': ['Barguna', 'Barishal', 'Bhola', 'Jhalokati', 'Patuakhali', 'Pirojpur'],
        'Sylhet': ['Habiganj', 'Moulvibazar', 'Sunamganj', 'Sylhet'],
        'Rangpur': ['Dinajpur', 'Gaibandha', 'Kurigram', 'Lalmonirhat', 'Nilphamari', 'Panchagarh', 'Rangpur', 'Thakurgaon'],
        'Mymensingh': ['Jamalpur', 'Mymensingh', 'Netrokona', 'Sherpur']
    },
    formData: {
        full_name: @js(old('full_name', auth()->user()?->name ?? '')),
        email: @js(old('email', auth()->user()?->email ?? '')),
        phone: @js(old('phone', auth()->user()?->phone ?? '')),
        address_line_1: @js(old('address_line_1')),
        upazila: @js(old('upazila')),
        division: @js(old('division')),
        city: @js(old('city')),
        postal_code: @js(old('postal_code'))
    },
    get availableDistricts() {
        return this.formData.division ? this.locations[this.formData.division] || [] : [];
    },
    onDivisionChange() {
        this.formData.city = '';
        this.shippingMethodId = null;
    },
    onDistrictChange() {
        this.shippingMethodId = null;
    },
    couponCode: '',
    couponMsg: '',
    applyingCoupon: false,
    applyCoupon() {
        if (!this.couponCode.trim()) {
            this.couponMsg = 'Please enter a coupon code.';
            return;
        }

        this.applyingCoupon = true;
        sessionStorage.setItem('gk_checkout_data', JSON.stringify(this.formData));
        
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
    },
    removeCoupon() {
        sessionStorage.setItem('gk_checkout_data', JSON.stringify(this.formData));
        fetch('/cart/coupon', {
            method: 'DELETE', 
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        }).then(() => window.location.reload());
    },
    init() {
        const savedData = sessionStorage.getItem('gk_checkout_data');
        if (savedData) {
            try {
                this.formData = { ...this.formData, ...JSON.parse(savedData) };
            } catch (e) {}
            sessionStorage.removeItem('gk_checkout_data');
        } else {
            const defaultAddr = this.addresses.find(a => a.is_default);
            if (defaultAddr && !@js(old('full_name', ''))) {
                this.selectedAddressId = defaultAddr.id;
                this.selectAddress(defaultAddr.id);
            }
        }
    },
    selectAddress(id) {
        const addr = this.addresses.find(a => a.id == id);
        if (addr) {
            this.formData.full_name = addr.full_name;
            this.formData.phone = addr.phone;
            this.formData.address_line_1 = addr.address_line_1;
            this.formData.upazila = addr.upazila || '';
            this.formData.division = addr.division;
            this.formData.city = addr.city;
            this.formData.postal_code = addr.postal_code || '';
        }
    },
    get qualifiesForFreeShipping() {
        return this.freeShippingThreshold > 0 && this.orderValue >= this.freeShippingThreshold;
    },
    get availableShippingMethods() {
        if (!this.formData.city) return [];
        const zone = this.shippingZones.find(z => z.districts && z.districts.includes(this.formData.city));
        if (zone) {
            return zone.shipping_methods;
        }
        return [];
    },
    get selectedShippingMethod() {
        return this.availableShippingMethods.find(m => m.id == this.shippingMethodId);
    },
    get shippingCharge() {
        if (this.qualifiesForFreeShipping) return 0;
        
        if (this.selectedShippingMethod) {
            if (this.selectedShippingMethod.free_shipping_threshold > 0 && this.orderValue >= this.selectedShippingMethod.free_shipping_threshold) {
                return 0;
            }
            return parseFloat(this.selectedShippingMethod.base_charge);
        }
        return 0;
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
        <div class="font-bold mb-1">{{ __('general.please_correct_errors') }}</div>
        <ul class="list-disc pl-5 space-y-1">
            @foreach($errors->all() as $error)
                <li>{!! $error !!}</li>
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
                        <input type="radio" name="saved_address" value="{{ $address->id }}" x-model="selectedAddressId" @change="selectAddress($el.value)" class="mt-1 text-[#e11d48]">
                        <div class="text-sm">
                            <div class="font-semibold">{{ $address->full_name }} <span class="text-xs bg-gray-100 px-2 py-0.5 rounded-full">{{ $address->label->label() }}</span></div>
                            <div class="text-gray-500">{{ $address->full_address }}</div>
                        </div>
                    </label>
                    @endforeach
                    <div class="text-xs text-[#e11d48] font-medium mt-2">{{ __('general.new_address_below') }}</div>
                </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3">
                    <div class="gk-checkout-field">
                        <label>{{ __('general.full_name') }} *</label>
                        <input type="text" name="full_name" x-model="formData.full_name" required
                            class="@error('full_name') border-red-400 @enderror">
                        @error('full_name') <p class="text-red-500 text-xs mt-1">{!! $message !!}</p> @enderror
                    </div>
                    <div class="gk-checkout-field">
                        <label>Mobile Number *</label>
                        <input type="tel" name="phone" x-model="formData.phone" required placeholder="01XXXXXXXXX"
                            class="@error('phone') border-red-400 @enderror">
                        @error('phone') <p class="text-red-500 text-xs mt-1">{!! $message !!}</p> @enderror
                    </div>
                    @guest
                    <div class="gk-checkout-field">
                        <label>{{ __('general.email') }} (Optional)</label>
                        <input type="email" name="email" x-model="formData.email"
                            class="@error('email') border-red-400 @enderror">
                        @error('email') <p class="text-red-500 text-xs mt-1">{!! $message !!}</p> @enderror
                    </div>
                    @endguest
                    <div class="gk-checkout-field sm:col-span-2 xl:col-span-2">
                        <label>{{ __('general.address_line_1') }} *</label>
                        <input type="text" name="address_line_1" x-model="formData.address_line_1" required
                            >
                    </div>
                    <div class="gk-checkout-field">
                        <label>Upazila / Thana *</label>
                        <input type="text" name="upazila" x-model="formData.upazila" required class="@error('upazila') border-red-400 @enderror">
                        @error('upazila') <p class="text-red-500 text-xs mt-1">{!! $message !!}</p> @enderror
                    </div>
                    <div class="gk-checkout-field">
                        <label>Division *</label>
                        <select name="division" x-model="formData.division" @change="onDivisionChange()" required class="@error('division') border-red-400 @enderror">
                            <option value="">Select Division</option>
                            <template x-for="(_, div) in locations" :key="div">
                                <option :value="div" x-text="div"></option>
                            </template>
                        </select>
                        @error('division') <p class="text-red-500 text-xs mt-1">{!! $message !!}</p> @enderror
                    </div>
                    <div class="gk-checkout-field">
                        <label>{{ __('general.city') }} (District) *</label>
                        <select name="city" x-model="formData.city" @change="onDistrictChange()" required class="@error('city') border-red-400 @enderror" :disabled="!formData.division">
                            <option value="">Select District</option>
                            <template x-for="district in availableDistricts" :key="district">
                                <option :value="district" x-text="district"></option>
                            </template>
                        </select>
                        @error('city') <p class="text-red-500 text-xs mt-1">{!! $message !!}</p> @enderror
                    </div>
                    <div class="gk-checkout-field">
                        <label>{{ __('general.postal_code') }}</label>
                        <input type="text" name="postal_code" x-model="formData.postal_code"
                            >
                    </div>
                </div>
                @auth
                <label class="flex items-center gap-2 mt-3 text-xs text-gray-600 cursor-pointer">
                    <input type="checkbox" name="save_address" value="1" class="text-[#e11d48]">
                    {{ __('general.save_address') }}
                </label>
                @endauth
            </section>

            <!-- Shipping Method -->
            <section class="gk-checkout-panel" x-show="availableShippingMethods.length > 0">
                <div class="gk-checkout-section-head">
                    <h2>Shipping Method</h2>
                    <p>Select your preferred shipping option.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <template x-for="method in availableShippingMethods" :key="method.id">
                        <label class="gk-checkout-payment flex items-center gap-3 p-3 cursor-pointer">
                            <input type="radio" name="shipping_method_id" :value="method.id" x-model="shippingMethodId" required class="text-[#e11d48]">
                            <div class="flex-1">
                                <div class="font-semibold text-sm" x-text="method.name"></div>
                            </div>
                            <div class="text-sm font-bold" x-text="method.base_charge == 0 ? 'Free' : '৳' + formatMoney(method.base_charge)"></div>
                        </label>
                    </template>
                </div>
            </section>
            <div x-show="formData.city && availableShippingMethods.length === 0" class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg p-3 text-sm">
                No shipping methods are available for the selected district. Please contact support.
            </div>

            <!-- Payment Method -->
            <section class="gk-checkout-panel">
                <div class="gk-checkout-section-head">
                    <h2>{{ __('general.payment_method') }}</h2>
                    <p>Select how you would like to pay.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    @php 
                        $codEnabled = (bool) \App\Models\Setting::get('cod_enabled', 1); 
                        $activeGateways = \App\Models\PaymentGateway::where('is_active', true)->pluck('slug')->toArray();
                    @endphp
                    @foreach(\App\Enums\PaymentMethod::cases() as $method)
                    @if($method->value === 'cod' && !$codEnabled)
                        @continue
                    @endif
                    @if(!in_array($method->value, $activeGateways))
                        @continue
                    @endif
                    <label class="gk-checkout-payment flex items-center gap-3 p-3 cursor-pointer">
                        <input type="radio" name="payment_method" value="{{ $method->value }}" {{ $loop->first ? 'checked' : '' }} required class="text-[#e11d48]">
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
            @if((bool) \App\Models\Setting::get('order_notes_enabled', 1))
            <section class="gk-checkout-panel gk-checkout-field">
                <div class="gk-checkout-section-head">
                    <h2>{{ __('general.order_notes') }}</h2>
                    <p>Add any delivery instruction for this order.</p>
                </div>
                <textarea name="notes" rows="2" placeholder="{{ __('general.special_instructions') }}"
                    ></textarea>
            </section>
            @endif
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
                
                <div class="gk-checkout-coupon">
                    @if($cart->coupon)
                        <div class="flex items-center justify-between bg-green-50 text-green-700 p-3 rounded border border-green-200">
                            <div>
                                <span class="font-bold text-sm">Coupon Applied:</span>
                                <span class="text-sm ml-1">{{ $cart->coupon->code }}</span>
                            </div>
                            <button type="button" class="text-sm font-bold text-red-600 hover:text-red-800" @click="removeCoupon()">Remove</button>
                        </div>
                    @else
                        <label for="checkout-coupon">{{ __('general.coupon_code') }}</label>
                        <div class="gk-checkout-coupon-row">
                            <input id="checkout-coupon" x-model="couponCode" type="text" placeholder="{{ __('general.enter_code') }}">
                            <button type="button" @click="applyCoupon()" :disabled="applyingCoupon">
                                <span x-text="applyingCoupon ? 'Applying...' : '{{ __('general.apply') }}'"></span>
                            </button>
                        </div>
                        <p x-show="couponMsg" x-text="couponMsg" class="gk-checkout-coupon-message"></p>
                    @endif
                </div>

                <div class="gk-checkout-totals">
                    <div class="gk-checkout-total-row"><span>{{ __('general.subtotal') }}</span><span>৳{{ number_format($cart->subtotal, 0) }}</span></div>
                    @if($cart->coupon)
                    <div class="gk-checkout-total-row text-green-600"><span>{{ __('general.coupon') }} ({{ $cart->coupon->code }})</span><span>-৳{{ number_format($cart->coupon->calculateDiscount($cart), 0) }}</span></div>
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
