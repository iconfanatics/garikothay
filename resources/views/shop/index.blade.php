@extends('layouts.app')

@section('title', ($currentCategory ? $currentCategory->name . ' - ' : '') . __('general.shop') . ' | ' . \App\Models\Setting::get('site_name', 'Garikothay'))
@section('meta_description', $currentCategory?->description ?: __('general.meta_description_default'))
@section('og_title', ($currentCategory ? $currentCategory->name . ' - ' : '') . __('general.shop'))
@section('og_description', $currentCategory?->description ?: __('general.meta_description_default'))
@section('og_image', ($currentCategory && $currentCategory->image) ? asset(Storage::url($currentCategory->image)) : null)

@section('content')
<div class="bg-gray-50 min-h-screen">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Breadcrumb --}}
    <nav class="text-sm text-gray-400 mb-6 flex items-center gap-2 flex-wrap">
        <a href="{{ route('home') }}" class="hover:text-primary-600">{{ __('general.home') }}</a>
        <span>/</span>
        <a href="{{ route('shop.index') }}" class="hover:text-primary-600">{{ __('general.shop') }}</a>
        @if($parentCategory)
            <span>/</span>
            <a href="{{ route('shop.index', ['category' => $parentCategory->slug]) }}" class="hover:text-primary-600">
                {{ $parentCategory->name }}
            </a>
            <span>/</span>
            <span class="text-gray-700 font-medium">{{ $currentCategory->name }}</span>
        @elseif($currentCategory)
            <span>/</span>
            <span class="text-gray-700 font-medium">{{ $currentCategory->name }}</span>
        @endif
    </nav>

    {{-- Subcategory tabs (when browsing a parent that has children) --}}
    @if($subcategories->count())
    <div class="mb-6 flex flex-wrap gap-2">
        <a href="{{ route('shop.index', ['category' => $currentCategory->slug]) }}"
           class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium border transition
                  {{ !request('sub') ? 'bg-primary-600 text-white border-primary-600' : 'bg-white text-gray-600 border-gray-200 hover:border-primary-400 hover:text-primary-600' }}">
            {{ __('general.all') }} {{ $currentCategory->name }}
        </a>
        @foreach($subcategories as $sub)
        <a href="{{ route('shop.index', ['category' => $sub->slug]) }}"
           class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium border transition
                  {{ $currentCategory?->slug === $sub->slug ? 'bg-primary-600 text-white border-primary-600' : 'bg-white text-gray-600 border-gray-200 hover:border-primary-400 hover:text-primary-600' }}">
            {{ $sub->name }}
        </a>
        @endforeach
    </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-8" x-data="{ filtersOpen: false }">

        {{-- ===== Sidebar ===== --}}
        <aside class="lg:w-64 shrink-0">
            <button @click="filtersOpen = !filtersOpen"
                class="lg:hidden w-full flex items-center justify-between border border-gray-200 bg-white rounded-xl px-4 py-3 text-sm font-medium mb-4">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M6 8h12M9 12h6"/></svg>
                    {{ __('general.filters') }}
                </span>
                <svg class="w-4 h-4 transition" :class="filtersOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>

            <div :class="filtersOpen ? 'block' : 'hidden lg:block'" class="space-y-4">

                {{-- Categories panel --}}
                <div class="bg-white rounded-2xl shadow-sm p-5">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        {{ __('general.categories') }}
                    </h3>
                    <ul class="space-y-1">
                        {{-- All Products --}}
                        <li>
                            <a href="{{ route('shop.index') }}"
                               class="flex items-center px-3 py-2 rounded-lg text-sm transition
                                      {{ !$currentCategory ? 'bg-primary-600 text-white font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-primary-600' }}">
                                {{ __('general.all_products') }}
                            </a>
                        </li>

                        @foreach($categories as $cat)
                        @php
                            $isParentActive = $currentCategory?->slug === $cat->slug
                                || $currentCategory?->parent_id === $cat->id;
                            $hasChildren    = $cat->children->count() > 0;
                        @endphp

                        <li x-data="{ open: {{ $isParentActive ? 'true' : 'false' }} }">
                            {{-- Parent row --}}
                            <div class="flex items-center rounded-lg transition
                                        {{ $currentCategory?->slug === $cat->slug ? 'bg-primary-600 text-white' : ($isParentActive ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-primary-600') }}">
                                <a href="{{ route('shop.index', ['category' => $cat->slug]) }}"
                                   class="flex-1 flex items-center gap-2 px-3 py-2 text-sm font-medium">
                                    @if($cat->icon)
                                    <span>{{ $cat->icon }}</span>
                                    @endif
                                    {{ $cat->name }}
                                </a>
                                @if($hasChildren)
                                <button @click="open = !open"
                                    class="px-2 py-2 {{ $currentCategory?->slug === $cat->slug ? 'text-white/70 hover:text-white' : 'text-gray-400 hover:text-primary-600' }} transition">
                                    <svg class="w-3.5 h-3.5 transition" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                @endif
                            </div>

                            {{-- Subcategories --}}
                            @if($hasChildren)
                            <ul x-show="open" x-collapse class="mt-1 ml-3 border-l-2 border-gray-100 pl-3 space-y-0.5">
                                @foreach($cat->children as $child)
                                <li>
                                    <a href="{{ route('shop.index', ['category' => $child->slug]) }}"
                                       class="flex items-center gap-1.5 px-2 py-1.5 rounded-lg text-sm transition
                                              {{ $currentCategory?->slug === $child->slug ? 'bg-primary-100 text-primary-700 font-medium' : 'text-gray-500 hover:text-primary-600 hover:bg-gray-50' }}">
                                        <span class="text-gray-300">↳</span>
                                        {{ $child->name }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Filters panel --}}
                <div class="bg-white rounded-2xl shadow-sm p-5">
                    <h3 class="font-bold text-gray-800 mb-4">{{ __('general.filters') }}</h3>

                    <form method="GET" action="{{ route('shop.index') }}" id="filter-form">
                        @if($currentCategory)
                        <input type="hidden" name="category" value="{{ $currentCategory->slug }}">
                        @endif

                        {{-- Price --}}
                        <div class="mb-5">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">{{ __('general.price_range') }}</h4>
                            <div class="flex gap-2">
                                <input type="number" name="min_price" value="{{ $filters['min_price'] ?? '' }}"
                                    placeholder="৳ {{ __('general.min') }}"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-primary-400">
                                <input type="number" name="max_price" value="{{ $filters['max_price'] ?? '' }}"
                                    placeholder="৳ {{ __('general.max') }}"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-primary-400">
                            </div>
                        </div>



                        <button type="submit"
                            class="w-full bg-primary-600 hover:bg-primary-700 text-white py-2.5 rounded-xl text-sm font-medium transition">
                            {{ __('general.apply_filters') }}
                        </button>
                        <a href="{{ route('shop.index', $currentCategory ? ['category' => $currentCategory->slug] : []) }}"
                           class="block text-center text-sm text-gray-400 mt-2 hover:text-red-500 transition">
                            {{ __('general.clear_filters') }}
                        </a>
                    </form>
                </div>
            </div>
        </aside>

        {{-- ===== Products Grid ===== --}}
        <div class="flex-1 min-w-0">

            {{-- Toolbar --}}
            <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
                <div>
                    @if($currentCategory)
                    <h1 class="text-xl font-bold text-gray-900">{{ $currentCategory->name }}</h1>
                    @endif
                    <p class="text-gray-500 text-sm">
                        {{ $products->total() }} {{ __('general.products_found') }}
                    </p>
                </div>
                <select name="sort" form="filter-form" onchange="document.getElementById('filter-form').submit()"
                    class="border border-gray-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-primary-400">
                    <option value="">{{ __('general.default_sort') }}</option>
                    <option value="newest"     {{ ($filters['sort'] ?? '') === 'newest'     ? 'selected' : '' }}>{{ __('general.newest') }}</option>
                    <option value="price_asc"  {{ ($filters['sort'] ?? '') === 'price_asc'  ? 'selected' : '' }}>{{ __('general.price_low_to_high') }}</option>
                    <option value="price_desc" {{ ($filters['sort'] ?? '') === 'price_desc' ? 'selected' : '' }}>{{ __('general.price_high_to_low') }}</option>
                </select>
            </div>

            @if($products->isEmpty())
            <div class="bg-white rounded-2xl py-20 text-center text-gray-400">
                <div class="text-6xl mb-4">🌱</div>
                <h3 class="text-lg font-semibold text-gray-600 mb-1">{{ __('general.no_products_found') }}</h3>
                <p class="text-sm">{{ __('general.try_adjusting_filters') }}</p>
                <a href="{{ route('shop.index') }}"
                   class="mt-4 inline-block text-sm text-primary-600 hover:text-primary-700 font-medium">
                    {{ __('general.clear_all_filters') }} →
                </a>
            </div>
            @else
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
            <div class="mt-8">
                {{ $products->appends(request()->query())->links() }}
            </div>
            @endif
        </div>

    </div>
</div>
</div>
@endsection
