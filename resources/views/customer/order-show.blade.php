@extends('layouts.app')

@section('title', __('general.order_details') . ' ' . $order->order_number)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Header -->
    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('customer.orders') }}" class="text-gray-400 hover:text-gray-600">
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
            <div class="w-16 h-16 bg-primary-50 rounded-lg flex items-center justify-center">
                <svg class="w-8 h-8 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <span class="font-semibold text-gray-900">৳{{ number_format($item->total_price, 2) }}</span>
        </div>
        @endforeach
    </div>

    <!-- Summary -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
        <h2 class="font-semibold text-gray-800 mb-4">{{ __('general.order_summary') }}</h2>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between text-gray-600">
                <span>{{ __('general.subtotal') }}</span>
                <span>৳{{ number_format($order->subtotal, 2) }}</span>
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
                <span class="text-primary-700">৳{{ number_format($order->total, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Shipping address -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <h2 class="font-semibold text-gray-800 mb-3">{{ __('general.shipping_information') }}</h2>
        <p class="text-gray-700 font-medium">{{ $order->shipping_name }}</p>
        <p class="text-gray-500 text-sm mt-1">{{ $order->shipping_phone }}</p>
        <p class="text-gray-500 text-sm">{{ $order->shipping_full_address }}</p>
        @if($order->notes)
        <p class="text-gray-400 text-sm mt-2 italic">{{ __('general.note') }}: {{ $order->notes }}</p>
        @endif
    </div>

</div>
@endsection
