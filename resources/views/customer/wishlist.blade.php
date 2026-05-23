@extends('layouts.app')

@section('title', __('general.wishlist') . ' - ' . config('app.name'))

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="text-2xl font-bold text-gray-900 mb-8">{{ __('general.wishlist') }}</h1>

    @if($wishlists->count())
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-5">
        @foreach($wishlists as $item)
        <div x-data="{ inWishlist: true }" class="relative">
            <x-product-card :product="$item->product" />
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-2xl shadow-sm p-16 text-center text-gray-400">
        <svg class="w-16 h-16 mx-auto mb-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
        </svg>
        <p class="font-medium">{{ __('general.wishlist_is_empty') }}</p>
        <a href="{{ route('shop.index') }}"
           class="mt-4 inline-block bg-primary-600 text-white px-6 py-2 rounded-xl hover:bg-primary-700 transition text-sm font-medium">
            {{ __('general.start_shopping') }}
        </a>
    </div>
    @endif

</div>
@endsection
