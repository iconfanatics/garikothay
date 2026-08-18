@extends('layouts.app')

@section('title', __('general.order_details') . ' ' . $order->order_number)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data>

    <!-- Header -->
    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('customer.dashboard') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-900">{{ __('general.order_details') }}</h1>
            <p class="text-sm text-gray-500 font-mono">{{ $order->order_number }}</p>
        </div>
        <span class="ml-auto inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
            {{ $order->status->color() === 'success' ? 'bg-green-100 text-green-700' :
               ($order->status->color() === 'warning' ? 'bg-yellow-100 text-yellow-700' :
               ($order->status->color() === 'danger' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600')) }}">
            {{ $order->status->label() }}
        </span>
    </div>

    <!-- Items -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">{{ __('general.items_list') }}</h2>
        </div>
        @foreach($order->items as $item)
        <div class="px-5 py-4 flex items-center gap-4 border-b border-gray-50 last:border-0">
            @if($item->product?->primaryImage)
            <img src="{{ asset('storage/' . $item->product->primaryImage->path) }}"
                 alt="{{ $item->product->name }}"
                 class="w-16 h-16 object-cover rounded-lg">
            @else
            <div class="w-16 h-16 bg-rose-50 rounded-lg flex items-center justify-center">
                <svg class="w-8 h-8 text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            @endif
            <div class="flex-1">
                <p class="font-medium text-gray-900">{{ $item->product_name }}</p>
                @if($item->variant)
                <p class="text-sm text-gray-500">{{ $item->variant->name }}</p>
                @endif
                <p class="text-sm text-gray-400">{{ __('general.qty') }}: {{ $item->quantity }}</p>
            </div>
            <div class="text-right">
                <span class="font-semibold text-gray-900 block">৳{{ number_format($item->total_price, 2) }}</span>
                @if($order->status === \App\Enums\OrderStatus::Delivered && $item->product)
                    @php
                        $hasReviewed = \App\Models\Review::where('user_id', auth()->id())->where('product_id', $item->product_id)->exists();
                    @endphp
                    @if(!$hasReviewed)
                        <button type="button" @click="$dispatch('open-review-modal', { productId: {{ $item->product_id }}, productName: {{ \Illuminate\Support\Js::from($item->product_name) }} })" class="mt-2 inline-flex items-center justify-center px-4 py-1.5 text-sm font-medium text-white bg-rose-600 border border-transparent rounded-md shadow-sm hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500">Write Review</button>
                    @else
                        <span class="text-sm text-green-600 mt-1 inline-block">Reviewed</span>
                    @endif
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Summary -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
        <h2 class="font-semibold text-gray-800 mb-4">{{ __('general.order_summary') }}</h2>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between text-gray-600">
                <span>{{ __('general.subtotal') }}</span>
                @if($order->discount_amount > 0)
                    <div class="flex items-center gap-2">
                        <span class="line-through text-gray-400" style="text-decoration: line-through;">৳{{ number_format($order->subtotal, 2) }}</span>
                        <span>৳{{ number_format($order->subtotal - $order->discount_amount, 2) }}</span>
                    </div>
                @else
                    <span>৳{{ number_format($order->subtotal, 2) }}</span>
                @endif
            </div>
            @if($order->discount_amount > 0)
            <div class="flex justify-between text-green-600">
                <span>{{ __('general.discount') }}</span>
                <span>-৳{{ number_format($order->discount_amount, 2) }}</span>
            </div>
            @endif
            <div class="flex justify-between text-gray-600">
                <span>{{ __('general.shipping') }}</span>
                <span>{{ $order->shipping_amount > 0 ? '৳' . number_format($order->shipping_amount, 2) : __('general.free') }}</span>
            </div>
            <div class="flex justify-between font-bold text-gray-900 text-base pt-2 border-t border-gray-100">
                <span>{{ __('general.total') }}</span>
                <span class="text-rose-700">৳{{ number_format($order->total, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Shipping address -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <h2 class="font-semibold text-gray-800 mb-3">{{ __('general.shipping_information') }}</h2>
        <p class="text-gray-700 font-medium">{{ $order->shipping_name }}</p>
        <p class="text-gray-500 text-sm mt-1">{{ $order->shipping_phone }}</p>
        <p class="text-gray-500 text-sm">{{ $order->shipping_full_address }}</p>
        @if($order->delivery_method || $order->tracking_number)
        <div class="mt-3 grid gap-1 rounded-lg bg-gray-50 p-3 text-sm text-gray-600">
            @if($order->delivery_method)
                <div><span class="font-medium text-gray-800">{{ __('general.delivery_method') }}:</span> {{ $order->delivery_method }}</div>
            @endif
            @if($order->tracking_number)
                <div><span class="font-medium text-gray-800">{{ __('general.tracking_number') }}:</span> {{ $order->tracking_number }}</div>
            @endif
        </div>
        @endif
        @if($order->notes)
        <p class="text-gray-400 text-sm mt-2 italic">{{ __('general.note') }}: {{ $order->notes }}</p>
        @endif
    </div>

    <!-- Review Modal -->
    <div x-data="{ open: {{ $errors->any() ? 'true' : 'false' }}, productId: '{{ old('product_id') }}', productName: '{{ old('product_name') }}', rating: {{ old('rating', 5) }} }" 
         @open-review-modal.window="open = true; productId = $event.detail.productId; productName = $event.detail.productName; rating = 5"
         x-show="open" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900/50 backdrop-blur-sm z-0" @click="open = false" aria-hidden="true"></div>
            
            <div class="relative z-10 inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6" role="dialog" aria-modal="true">
                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <button type="button" @click="open = false" class="text-gray-400 bg-white rounded-md hover:text-gray-500 focus:outline-none">
                        <span class="sr-only">Close</span>
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Write a Review</h3>
                        <p class="text-sm text-gray-500" x-text="productName"></p>
                        
                        @if ($errors->any())
                            <div class="mt-2 bg-red-50 text-red-600 p-3 rounded text-sm">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <form action="{{ route('reviews.store') }}" method="POST" class="mt-4">
                            @csrf
                            <input type="hidden" name="product_id" :value="productId">
                            <input type="hidden" name="product_name" :value="productName">
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Rating</label>
                                <div class="flex gap-2 mt-1">
                                    <template x-for="i in 5">
                                        <button type="button" @click="rating = i" class="text-2xl focus:outline-none" :class="rating >= i ? 'text-yellow-400' : 'text-gray-300'">★</button>
                                    </template>
                                </div>
                                <input type="hidden" name="rating" :value="rating">
                            </div>
                            
                            <div class="mb-4">
                                <label for="title" class="block text-sm font-medium text-gray-700">Title (optional)</label>
                                <input type="text" name="title" id="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-rose-500 focus:ring-rose-500 sm:text-sm">
                            </div>
                            
                            <div class="mb-4">
                                <label for="comment" class="block text-sm font-medium text-gray-700">Review</label>
                                <textarea name="comment" id="comment" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-rose-500 focus:ring-rose-500 sm:text-sm"></textarea>
                            </div>
                            
                            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                <button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-rose-600 border border-transparent rounded-md shadow-sm hover:bg-rose-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                    Submit Review
                                </button>
                                <button type="button" @click="open = false" class="mt-3 inline-flex justify-center w-full px-4 py-2 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
