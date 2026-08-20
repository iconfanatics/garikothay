@extends('layouts.app')

@section('title', __('general.wishlist') . ' - ' . config('app.name'))

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="text-2xl font-bold text-gray-900 mb-8">{{ __('general.wishlist') }}</h1>

    @if($wishlists->count())
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-5">
        @foreach($wishlists as $item)
        <div x-data="{ removing: false }" class="relative flex flex-col h-full">
            <div class="flex-1">
                <x-product-card :product="$item->product" />
            </div>
            <button @click="
                removing = true;
                fetch('/wishlist/toggle', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    body: JSON.stringify({product_id: {{ $item->product_id }}})
                }).then(() => window.location.reload());
            " 
            :disabled="removing"
            class="mt-3 flex items-center justify-center gap-1.5 w-full py-2 text-sm font-bold text-rose-600 bg-rose-50 border border-rose-100 rounded-lg hover:bg-rose-600 hover:text-white transition-colors disabled:opacity-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                Remove from Wishlist
            </button>
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
           class="mt-4 inline-block bg-rose-600 text-white px-6 py-2 rounded-xl hover:bg-rose-700 transition text-sm font-medium">
            {{ __('general.start_shopping') }}
        </a>
    </div>
    @endif

</div>
@endsection
