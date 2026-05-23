@extends('layouts.app')

@section('title', __('general.my_orders') . ' - ' . config('app.name'))

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ __('general.my_orders') }}</h1>

    @if($orders->count())
    <div class="space-y-4">
        @foreach($orders as $order)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <span class="font-mono font-semibold text-gray-900">{{ $order->order_number }}</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $order->status->color() === 'success' ? 'bg-green-100 text-green-700' :
                               ($order->status->color() === 'warning' ? 'bg-yellow-100 text-yellow-700' :
                               ($order->status->color() === 'danger' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600')) }}">
                            {{ $order->status->label() }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500">
                        {{ $order->created_at->format('M d, Y') }} &bull;
                        {{ $order->items_count ?? $order->items->count() }} {{ __('general.items') }}
                    </p>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-lg font-bold text-primary-700">৳{{ number_format($order->total, 2) }}</span>
                    <a href="{{ route('customer.order.show', $order->order_number) }}"
                       class="text-sm bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg transition">
                        {{ __('general.view_details') }}
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if(method_exists($orders, 'links'))
    <div class="mt-6">{{ $orders->links() }}</div>
    @endif

    @else
    <div class="bg-white rounded-2xl shadow-sm p-16 text-center text-gray-400">
        <svg class="w-16 h-16 mx-auto mb-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
        </svg>
        <p class="font-medium">{{ __('general.no_orders_yet') }}</p>
        <a href="{{ route('shop.index') }}"
           class="mt-4 inline-block bg-primary-600 text-white px-6 py-2 rounded-xl hover:bg-primary-700 transition text-sm font-medium">
            {{ __('general.start_shopping') }}
        </a>
    </div>
    @endif

</div>
@endsection
