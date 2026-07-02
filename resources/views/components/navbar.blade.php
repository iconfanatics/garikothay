@props(['siteName'])

@php
    $topMenuItems = \Illuminate\Support\Facades\Schema::hasTable('navigation_items') 
        ? \App\Models\NavigationItem::where('group', 'top_nav')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn($item) => ['label' => $item->label, 'href' => url($item->url ?? '#')])
        : collect();

    $searchCategories = \Illuminate\Support\Facades\Schema::hasTable('categories')
        ? \App\Models\Category::query()
            ->with(['translations', 'children' => fn ($query) => $query
                ->with('translations')
                ->active()
                ->orderBy('sort_order')])
            ->whereNull('parent_id')
            ->active()
            ->orderBy('sort_order')
            ->get()
        : collect();

    $requestedSearchCategory = request()->query('category');
    $selectedSearchCategorySlug = is_string($requestedSearchCategory) ? trim($requestedSearchCategory) : '';
    $selectedSearchCategory = $searchCategories->firstWhere('slug', $selectedSearchCategorySlug);

    if (! $selectedSearchCategory) {
        $selectedSearchCategory = $searchCategories
            ->flatMap(fn ($category) => $category->children)
            ->firstWhere('slug', $selectedSearchCategorySlug);
    }

    $selectedSearchCategoryLabel = $selectedSearchCategory?->name ?? 'All Categories';
@endphp

<style>
    .gk-topbar {
        overflow: hidden;
        background: #111827;
        color: #d1d5db;
        font-size: 0.78rem;
    }

    .gk-topbar-track {
        display: flex;
        width: max-content;
        height: 2.25rem;
        align-items: center;
        white-space: nowrap;
        animation: gk-topbar-marquee 45s linear infinite;
    }

    .gk-topbar-item {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0 2rem;
    }

    .gk-topbar-dot {
        width: 0.25rem;
        height: 0.25rem;
        border-radius: 999px;
        background: #e11d48;
    }

    .gk-site-nav {
        --gk-red: #e11d48;
        --gk-red-dark: #be123c;
        --gk-ink: #111827;
        --gk-nav: #1f2937;
    }

    .gk-desktop-header {
        display: grid;
        grid-template-columns: auto minmax(260px, 1fr) auto;
        align-items: center;
        gap: 1.5rem;
    }

    .gk-nav-container {
        max-width: 1504px;
        margin: 0 auto;
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .gk-site-nav .gk-brand-mark {
        display: grid;
        width: 2.5rem;
        height: 2.5rem;
        place-items: center;
        border-radius: 6px;
        background: var(--gk-red);
        color: #ffffff;
        font-family: 'Oswald', 'Inter', sans-serif;
        font-size: 1.25rem;
        font-weight: 900;
    }

    .gk-site-nav .gk-brand-title {
        font-family: 'Oswald', 'Inter', sans-serif;
        color: var(--gk-ink);
        font-size: 1.25rem;
        font-weight: 900;
        line-height: 1;
        text-transform: uppercase;
    }

    .gk-site-nav .gk-brand-subtitle {
        color: #6b7280;
        font-size: 0.62rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }

    .gk-site-nav .gk-search-box {
        border: 2px solid var(--gk-red);
        border-radius: 6px;
    }

    .gk-search-category-dropdown {
        position: absolute;
        z-index: 80;
        top: calc(100% + 0.5rem);
        left: 0;
        width: 290px;
        max-height: min(65vh, 480px);
        overflow-y: auto;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        background: #ffffff;
        box-shadow: 0 18px 38px rgba(17, 24, 39, 0.16);
        padding: 0.45rem;
    }

    .gk-search-category-link {
        display: flex;
        width: 100%;
        min-height: 38px;
        align-items: center;
        gap: 0.55rem;
        border: 0;
        border-radius: 5px;
        background: transparent;
        color: #374151;
        padding: 0.45rem 0.6rem;
        font-size: 0.8rem;
        font-weight: 700;
        text-align: left;
        text-decoration: none;
        cursor: pointer;
    }

    .gk-search-category-link:hover {
        background: #fff1f2;
        color: var(--gk-red);
    }

    .gk-search-category-link-all {
        border-bottom: 1px solid #e5e7eb;
        margin-bottom: 0.3rem;
        color: var(--gk-red);
        font-weight: 900;
    }

    .gk-search-subcategories {
        display: grid;
        gap: 0.1rem;
        border-left: 2px solid #ffe4e6;
        margin: 0 0 0.35rem 1.5rem;
        padding-left: 0.45rem;
    }

    .gk-search-subcategory-link {
        width: 100%;
        border: 0;
        border-radius: 4px;
        background: transparent;
        color: #6b7280;
        padding: 0.32rem 0.5rem;
        font-size: 0.74rem;
        text-align: left;
        text-decoration: none;
        cursor: pointer;
    }

    .gk-search-subcategory-link:hover {
        background: #fff1f2;
        color: var(--gk-red);
    }

    .gk-site-nav .gk-search-button,
    .gk-site-nav .gk-action-button,
    .gk-site-nav .cart-count {
        background: var(--gk-red) !important;
    }

    .gk-site-nav .gk-search-button:hover,
    .gk-site-nav .gk-action-button:hover {
        background: var(--gk-red-dark) !important;
    }

    .gk-site-nav .gk-link:hover,
    .gk-site-nav .gk-icon-link:hover {
        color: var(--gk-red) !important;
    }

    .gk-main-menu {
        background: #1f2937;
        color: #f9fafb;
    }

    .gk-main-menu a {
        color: #f9fafb !important;
    }

    .gk-main-menu a:hover {
        color: #fb7185 !important;
        background: transparent !important;
    }

    .gk-main-menu .gk-menu-head {
        background: #e11d48;
        color: #ffffff !important;
    }

    @keyframes gk-topbar-marquee {
        from { transform: translateX(0); }
        to { transform: translateX(-50%); }
    }

    @media (max-width: 767px) {
        .gk-desktop-header {
            display: none !important;
        }
    }
</style>

@php
    $marqueeItemsDb = \App\Models\Setting::get('theme1_top_ticker');
    
    if ($marqueeItemsDb !== null) {
        $marqueeItems = is_array($marqueeItemsDb) ? array_column($marqueeItemsDb, 'text') : [];
    } else {
        $marqueeItems = [
            'Hard to find reliable garages? Now find trusted services instantly.',
            'Find Car Wash, Garage, Fuel Stations, Driving Schools Near You.',
            'List Your Business on Garikothay.com.',
            'Promote your business through Garikothay.com.',
            'Trusted Vehicle Platform in Bangladesh.',
            'Get More Customers, Grow Faster.',
            'Limited Featured Slots Available.',
            'Discover trusted services today. Start your business journey now.',
        ];
    }
@endphp

<div class="sticky top-0 z-50">
    @if(count($marqueeItems) > 0)
    <div class="gk-topbar">
        <div class="gk-topbar-track">
            @foreach(array_merge($marqueeItems, $marqueeItems) as $item)
                <span class="gk-topbar-item"><span class="gk-topbar-dot"></span>{{ $item }}</span>
            @endforeach
        </div>
    </div>
    @endif

<nav class="gk-site-nav bg-white shadow-sm" x-data="{ mobileOpen: false }">
    <!-- Desktop Header Row -->
    <div class="gk-desktop-header gk-nav-container py-3 md:py-4 hidden md:grid">

        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
            <span class="gk-brand-mark">G</span>
            <span class="hidden sm:block leading-tight">
                <span class="gk-brand-title">Gari Kothay</span>
                <span class="gk-brand-subtitle">Auto Marketplace</span>
            </span>
        </a>
        <!-- Search (desktop) -->
        <form action="{{ route('search.index') }}" method="GET"
            class="relative hidden md:flex w-full max-w-2xl mx-auto"
            x-data="{
                categoryOpen: false,
                selectedCategory: {{ \Illuminate\Support\Js::from($selectedSearchCategorySlug) }},
                selectedCategoryLabel: {{ \Illuminate\Support\Js::from($selectedSearchCategoryLabel) }}
            }">
            <div class="gk-search-box flex w-full transition">
                <!-- Category dropdown removed as requested -->
                <input type="hidden" name="category" x-model="selectedCategory">
                <input type="text" name="q" value="{{ request('q') }}"
                    placeholder="Search for car parts, brands, services..."
                    required minlength="2"
                    oninvalid="this.setCustomValidity('Please type something to search.')"
                    oninput="this.setCustomValidity('')"
                    class="flex-1 px-4 py-2 text-sm outline-none bg-transparent">
                <button type="submit" class="gk-search-button rounded-r-[4px] text-white px-4 py-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </form>

        <!-- Desktop right section -->
        <div class="hidden md:flex items-center justify-end gap-4">
            <!-- Nav links -->
            <a href="{{ route('home') }}"
                class="hidden gk-link text-sm font-medium text-gray-700 transition">
                {{ __('general.home') }}
            </a>
            <a href="{{ route('shop.index') }}"
                class="hidden gk-link text-sm font-medium text-gray-700 transition">
                {{ __('general.shop') }}
            </a>
            <a href="{{ route('blog.index') }}"
                class="hidden gk-link text-sm font-medium text-gray-700 transition">
                {{ __('general.blog') }}
            </a>

            <!-- Language switcher -->
            <x-language-switcher />

            <!-- Divider -->
            <span class="w-px h-5 bg-gray-200"></span>

            <!-- Wishlist -->
            <a href="{{ auth()->check() ? route('wishlist.index') : route('login') }}"
                class="gk-icon-link relative text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </a>

            <!-- Cart -->
            <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-primary-600 transition"
                id="cart-icon">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span
                    class="cart-count absolute -top-2 -right-2 bg-[var(--gk-red)] text-white text-xs rounded-full w-4 h-4 flex items-center justify-center leading-none">0</span>
            </a>

            <!-- User menu -->
            @auth
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center gap-1.5 text-sm text-gray-700 hover:text-primary-600 transition font-medium">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="w-7 h-7 rounded-full object-cover">
                        @else
                            <div class="w-7 h-7 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold text-xs">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <span>{{ auth()->user()->name }}</span>
                        <svg class="w-3 h-3" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-cloak @click.away="open = false" x-transition
                        class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                        <a href="{{ route('customer.dashboard') }}"
                            class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            {{ __('general.dashboard') }}
                        </a>
                        <a href="{{ route('customer.orders') }}"
                            class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            {{ __('general.my_orders') }}
                        </a>
                        <a href="{{ route('customer.profile') }}"
                            class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ __('general.profile') }}
                        </a>
                        <hr class="my-1 border-gray-100">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center gap-2 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                {{ __('general.logout') }}
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}"
                    class="gk-action-button text-white text-sm font-medium px-4 py-2 rounded-md transition">
                    {{ __('general.login') }}
                </a>
            @endauth
        </div>
    </div>

    <!-- Mobile Header Container -->
    <div class="md:hidden flex flex-col">
        <!-- Top Row: Hamburger, Logo, Cart -->
        <div class="flex items-center justify-between px-4 py-3">
            <div class="w-1/3 flex justify-start">
                <button @click="mobileOpen = true" class="text-gray-700 p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
            
            <div class="w-1/3 flex justify-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
                    <span class="gk-brand-mark">G</span>
                    <span class="leading-tight">
                        <span class="gk-brand-title">Gari Kothay</span>
                    </span>
                </a>
            </div>
            
            <div class="w-1/3 flex justify-end">
                <a href="{{ route('cart.index') }}" class="relative text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="cart-count absolute -top-2 -right-2 bg-[var(--gk-red)] text-white text-xs rounded-full w-4 h-4 flex items-center justify-center leading-none">0</span>
                </a>
            </div>
        </div>

        <!-- Mobile Search (Outside) -->
        <div class="px-4 pb-3">
            <form action="{{ route('search.index') }}" method="GET" class="w-full">
                <div class="flex border border-[var(--gk-red)] rounded-md overflow-hidden bg-white">
                    <input type="text" name="q" value="{{ request('q') }}"
                        placeholder="Search for car parts, brands, services..."
                        required minlength="2"
                        class="flex-1 px-3 py-2 text-sm outline-none">
                    <button type="submit" class="gk-search-button text-white px-4 py-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Top menu (Desktop) -->
    <div class="gk-main-menu hidden md:block">
        <div class="gk-nav-container">
            <div class="flex items-center gap-1 overflow-x-auto">
                @foreach($topMenuItems as $item)
                    <a href="{{ $item['href'] }}"
                        class="shrink-0 rounded-md px-3 py-3 text-sm font-medium transition">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Mobile Offcanvas Menu -->
    <div x-show="mobileOpen" x-cloak class="md:hidden relative z-[100]">
        <!-- Backdrop -->
        <div x-show="mobileOpen"
             x-transition.opacity.duration.300ms
             @click="mobileOpen = false"
             class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>

        <!-- Sliding Panel -->
        <div x-show="mobileOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="fixed inset-y-0 left-0 w-[85%] max-w-sm bg-white shadow-2xl flex flex-col h-[100dvh]">
             
            <!-- Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <span class="gk-brand-mark">G</span>
                    <span class="font-bold text-lg text-gray-900">Menu</span>
                </div>
                <button @click="mobileOpen = false" class="p-2 text-gray-500 hover:text-[var(--gk-red)] bg-gray-50 rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Content -->
            <div class="flex-1 overflow-y-auto px-4 py-4 space-y-4">
                <div class="grid grid-cols-2 gap-2">
                    @foreach($topMenuItems as $item)
                        <a href="{{ $item['href'] }}"
                            class="rounded-lg border border-gray-100 px-3 py-2.5 text-sm font-medium text-gray-700 hover:border-red-200 hover:bg-red-50 hover:text-red-700 transition flex items-center justify-center text-center">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>

                <div class="space-y-1 mt-4">
                    <a href="{{ route('home') }}"
                        class="flex items-center gap-3 px-3 py-3 text-gray-700 hover:bg-red-50 hover:text-[var(--gk-red)] rounded-lg text-sm font-medium transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        {{ __('general.home') }}
                    </a>
                    <a href="{{ route('shop.index') }}"
                        class="flex items-center gap-3 px-3 py-3 text-gray-700 hover:bg-red-50 hover:text-[var(--gk-red)] rounded-lg text-sm font-medium transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        {{ __('general.shop') }}
                    </a>
                    <a href="{{ route('blog.index') }}"
                        class="flex items-center gap-3 px-3 py-3 text-gray-700 hover:bg-red-50 hover:text-[var(--gk-red)] rounded-lg text-sm font-medium transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        {{ __('general.blog') }}
                    </a>
                </div>

                <div class="px-2 mt-4">
                    <x-language-switcher />
                </div>
            </div>

            <!-- Footer -->
            <div class="p-4 border-t border-gray-100 bg-gray-50">
                @auth
                    <a href="{{ route('customer.dashboard') }}"
                        class="flex items-center gap-2 px-3 py-2 text-gray-700 hover:text-[var(--gk-red)] text-sm font-medium">{{ __('general.my_account') }}</a>
                    <a href="{{ route('customer.orders') }}"
                        class="flex items-center gap-2 px-3 py-2 text-gray-700 hover:text-[var(--gk-red)] text-sm font-medium">{{ __('general.my_orders') }}</a>
                    <form method="POST" action="{{ route('logout') }}" class="px-3 py-2">
                        @csrf
                        <button type="submit" class="text-sm text-[var(--gk-red)] font-medium w-full text-left">{{ __('general.logout') }}</button>
                    </form>
                @else
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('login') }}"
                            class="bg-[var(--gk-red)] hover:bg-[var(--gk-red-dark)] text-white text-sm font-medium px-4 py-2.5 rounded-lg text-center transition">
                            {{ __('general.login') }}
                        </a>
                        <a href="{{ route('register') }}"
                            class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-medium px-4 py-2.5 rounded-lg text-center transition">
                            {{ __('general.register') }}
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>
</div>
