@extends('layouts.app')
@section('title', __('general.checkout') . ' | ' . \App\Models\Setting::get('site_name', 'Garikothay'))

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8" x-data="{
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
        district: @js(old('district')),
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
            this.formData.district = addr.district;
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
    <h1 class="text-3xl font-bold text-gray-800 mb-8">{{ __('general.checkout_title') }}</h1>

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-6 text-sm font-medium">
        ⚠️ {{ session('error') }}
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-6 text-sm">
        <div class="font-bold mb-1">⚠️ {{ __('general.please_correct_errors', 'Please correct the errors below:') }}</div>
        <ul class="list-disc pl-5 space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('checkout.store') }}" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        @csrf

        <!-- Shipping Info -->
        <div class="space-y-5">
            <div class="bg-white rounded-2xl p-6 shadow-sm">
                <h2 class="font-bold text-gray-800 text-xl mb-5">{{ __('general.shipping_information') }}</h2>

                @if($addresses->isNotEmpty())
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('general.use_saved_address') }}</label>
                    @foreach($addresses as $address)
                    <label class="flex items-start gap-3 p-3 border rounded-lg mb-2 cursor-pointer hover:border-[#2D6A4F] transition has-[:checked]:border-[#2D6A4F] has-[:checked]:bg-green-50">
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
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('general.full_name') }} *</label>
                        <input type="text" name="full_name" x-model="formData.full_name" required
                            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-[#2D6A4F] @error('full_name') border-red-400 @enderror">
                        @error('full_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    @guest
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('general.email') }} *</label>
                        <input type="email" name="email" x-model="formData.email" required
                            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-[#2D6A4F] @error('email') border-red-400 @enderror">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    @endguest
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('general.phone') }} *</label>
                        <input type="text" name="phone" x-model="formData.phone" required
                            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-[#2D6A4F] @error('phone') border-red-400 @enderror">
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('general.address_line_1') }} *</label>
                        <input type="text" name="address_line_1" x-model="formData.address_line_1" required
                            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-[#2D6A4F]">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('general.address_line_2') }}</label>
                        <input type="text" name="address_line_2" x-model="formData.address_line_2"
                            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-[#2D6A4F]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('general.city') }} *</label>
                        <input type="text" name="city" x-model.trim="formData.city" required placeholder="Dhaka"
                            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-[#2D6A4F]">
                        <p class="mt-1 text-xs text-gray-500" x-show="formData.city">
                            <span x-text="isDhakaCity ? 'Inside Dhaka city rate selected' : 'Outside Dhaka rate selected'"></span>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('general.district') }} *</label>
                        <input type="text" name="district" x-model="formData.district" required
                            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-[#2D6A4F]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('general.division') }} *</label>
                        <select name="division" x-model="formData.division" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-[#2D6A4F]">
                            @foreach($divisions as $division)
                            <option value="{{ $division }}">{{ $division }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('general.postal_code') }}</label>
                        <input type="text" name="postal_code" x-model="formData.postal_code"
                            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-[#2D6A4F]">
                    </div>
                </div>
                @auth
                <label class="flex items-center gap-2 mt-4 text-sm text-gray-600 cursor-pointer">
                    <input type="checkbox" name="save_address" value="1" class="text-[#2D6A4F]">
                    {{ __('general.save_address') }}
                </label>
                @endauth
            </div>

            <!-- Payment Method -->
            <div class="bg-white rounded-2xl p-6 shadow-sm">
                <h2 class="font-bold text-gray-800 text-xl mb-5">{{ __('general.payment_method') }}</h2>
                <div class="space-y-3">
                    @foreach(\App\Enums\PaymentMethod::cases() as $method)
                    <label class="flex items-center gap-3 p-4 border rounded-xl cursor-pointer hover:border-[#2D6A4F] transition has-[:checked]:border-[#2D6A4F] has-[:checked]:bg-green-50">
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
            </div>

            <!-- Notes -->
            <div class="bg-white rounded-2xl p-6 shadow-sm">
                <h2 class="font-bold text-gray-800 text-xl mb-3">{{ __('general.order_notes') }}</h2>
                <textarea name="notes" rows="3" placeholder="{{ __('general.special_instructions') }}"
                    class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-[#2D6A4F]"></textarea>
            </div>
        </div>

        <!-- Order Summary -->
        <div>
            <div class="bg-white rounded-2xl p-6 shadow-sm sticky top-24">
                <h2 class="font-bold text-gray-800 text-xl mb-5">{{ __('general.your_order') }}</h2>
                <div class="space-y-3 mb-5">
                    @foreach($cart->items as $item)
                    <div class="flex items-center gap-3">
                        <img src="{{ $item->product->primaryImage?->url ?? asset('images/product-placeholder.svg') }}" alt="{{ $item->product->name }}" onerror="this.onerror=null;this.src='{{ asset('images/product-placeholder.svg') }}';" class="w-14 h-14 object-cover rounded-lg">
                        <div class="flex-1 text-sm">
                            <div class="font-medium">{{ $item->product->name }}</div>
                            @if($item->variant) <div class="text-gray-400">{{ $item->variant->name }}</div> @endif
                            <div class="text-gray-500">x{{ $item->quantity }}</div>
                        </div>
                        <span class="font-semibold text-sm">৳{{ number_format($item->total, 0) }}</span>
                    </div>
                    @endforeach
                </div>
                <hr class="mb-4">
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between text-gray-600"><span>{{ __('general.subtotal') }}</span><span>৳{{ number_format($cart->subtotal, 0) }}</span></div>
                    @if($cart->coupon)
                    <div class="flex justify-between text-green-600"><span>{{ __('general.coupon') }} ({{ $cart->coupon->code }})</span><span>-৳{{ number_format($cart->coupon->calculateDiscount($cart->subtotal), 0) }}</span></div>
                    @endif
                    <div class="flex justify-between text-gray-600">
                        <span>{{ __('general.shipping') }}</span>
                        <span x-text="shippingCharge > 0 ? '৳' + formatMoney(shippingCharge) : @js(__('general.free'))"></span>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-3 text-xs text-gray-600">
                        <div class="flex justify-between gap-3"><span>Delivery Time</span><strong class="text-gray-800">{{ $deliveryTime }}</strong></div>
                        <div class="mt-1 flex justify-between gap-3"><span>Delivery Partner</span><strong class="text-gray-800">{{ $deliveryPartner }}</strong></div>
                    </div>
                    <hr>
                    <div class="flex justify-between font-bold text-gray-800 text-base">
                        <span>{{ __('general.total') }}</span>
                        <span x-text="'৳' + formatMoney(checkoutTotal)"></span>
                    </div>
                </div>
                <button type="submit" class="w-full mt-6 bg-[#2D6A4F] text-white py-4 rounded-xl font-bold text-lg hover:bg-[#52B788] transition">
                    🛒 {{ __('general.place_order') }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
