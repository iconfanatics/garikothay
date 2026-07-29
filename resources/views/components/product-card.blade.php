@props(['product'])

<div class="group relative flex h-full flex-col overflow-hidden rounded-lg border border-gray-200 bg-white transition-all duration-300 hover:border-rose-500/40 hover:shadow-lg"
    x-data="{ inWishlist: false, adding: false }">

    <!-- Image -->
    <a href="{{ route('shop.show', $product->slug) }}" class="relative block aspect-square overflow-hidden bg-gray-100">
            <img src="{{ $product->primaryImage?->url ?? asset('images/product-placeholder.svg') }}"
                alt="{{ $product->primaryImage?->alt_text ?? $product->name }}"
                onerror="this.onerror=null;this.src='{{ asset('images/product-placeholder.svg') }}';"
            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105 lazy">

        <!-- Badges -->
        <div class="absolute top-2 left-2 flex flex-col gap-1">
            @if($product->is_new_arrival)
                <span class="rounded bg-emerald-600 px-2 py-0.5 text-[10px] font-bold uppercase text-white">{{ __('general.new') }}</span>
            @endif
            @if($product->discount_percentage > 0)
                <span class="rounded bg-rose-600 px-2 py-0.5 text-[10px] font-bold uppercase text-white">-{{ $product->discount_percentage }}%</span>
            @endif
        </div>
    </a>

        <!-- Wishlist Button -->
        @auth
        <button @click="
            adding = true;
            fetch('/wishlist/toggle', {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                body: JSON.stringify({product_id: {{ $product->id }}})
            }).then(r => r.json()).then(data => {
                inWishlist = data.added;
                adding = false;
            });
        "
        class="absolute right-2 top-2 z-10 grid h-8 w-8 place-items-center rounded-full bg-white/90 text-gray-500 opacity-0 shadow transition hover:bg-rose-600 hover:text-white group-hover:opacity-100">
            <svg class="w-4 h-4" :class="inWishlist ? 'fill-red-500 stroke-red-500' : 'fill-none stroke-gray-400'" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </button>
        @endauth

    <!-- Content -->
    <div class="flex flex-1 flex-col p-3">
        @if($product->category)
            <span class="text-[11px] font-medium uppercase tracking-wider text-gray-500">{{ $product->category->name }}</span>
        @endif

        <a href="{{ route('shop.show', $product->slug) }}">
            <h3 class="mt-1 min-h-[2.5rem] text-sm font-medium leading-5 text-gray-900 transition line-clamp-2 hover:text-rose-600">{{ $product->name }}</h3>
        </a>

        <!-- Rating -->
        <div class="flex items-center gap-1 mt-1">
            @for($i = 1; $i <= 5; $i++)
                <svg class="w-3 h-3 {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            @endfor
        </div>

        <!-- Price -->
        <div class="mt-2 flex items-baseline gap-2">
                <span class="text-lg font-bold text-rose-600">৳{{ number_format($product->selling_price, 0) }}</span>
                @if($product->original_price > $product->selling_price)
                <span class="text-xs text-gray-400 line-through">৳{{ number_format($product->original_price, 0) }}</span>
                @endif
        </div>

        <div class="mt-3 flex items-stretch gap-2">
            <a href="{{ route('shop.show', $product->slug) }}"
                class="flex flex-1 items-center justify-center gap-1.5 whitespace-nowrap rounded-md bg-gray-950 px-2 py-2 text-xs font-semibold text-white transition hover:bg-rose-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z" />
                    <circle cx="12" cy="12" r="3" stroke-width="2" />
                </svg>
                View Details
            </a>
            <!-- Add to Cart -->
            @if($product->isInStock())
            <button @click="
                adding = true;
                fetch('/cart/add', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    body: JSON.stringify({product_id: {{ $product->id }}, quantity: 1})
                }).then(r => r.json()).then(data => {
                    adding = false;
                    document.querySelectorAll('.cart-count').forEach(el => el.textContent = data.cart_count);
                    $dispatch('toast', { message: '{{ __('general.added_to_cart') }}' });
                });
            "
            :disabled="adding"
            aria-label="Add to cart"
            class="grid h-9 w-9 place-items-center rounded-md border border-rose-600 text-rose-600 transition hover:bg-rose-600 hover:text-white disabled:opacity-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13 5.4 5M7 13l-2.3 2.3c-.6.6-.2 1.7.7 1.7H17m0 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8 2a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z" />
                </svg>
            </button>
            @else
            <span class="flex h-9 items-center text-xs font-medium text-red-500">{{ __('general.out_of_stock') }}</span>
            @endif
        </div>
    </div>
</div>
