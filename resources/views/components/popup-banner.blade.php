@php
    $popup = \App\Models\Banner::with('translations')->active()->where('type', 'popup')->orderBy('sort_order')->first();
@endphp

@if($popup)
    <div x-data="{
            show: false,
            popupId: 'banner_popup_{{ $popup->id }}',
            init() {
                // If not dismissed in this session/timeframe, show it after a short delay
                if (!sessionStorage.getItem(this.popupId)) {
                    setTimeout(() => {
                        this.show = true;
                        document.body.style.overflow = 'hidden';
                    }, 500); // 0.5 second delay before showing
                }
            },
            dismiss() {
                this.show = false;
                document.body.style.overflow = '';
                sessionStorage.setItem(this.popupId, 'dismissed');
            }
        }"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
        style="display: none;"
    >
        <!-- Backdrop -->
        <div 
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
            @click="dismiss()"
        ></div>

        <!-- Modal Content -->
        <div 
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden"
            @click.stop
        >
            <!-- Close Button -->
            <button 
                @click="dismiss()"
                class="absolute top-3 right-3 z-10 p-2 bg-white/80 hover:bg-white text-gray-800 rounded-full shadow-sm hover:scale-110 transition-transform focus:outline-none"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            @if($popup->image)
                <div class="w-full relative aspect-video sm:aspect-[2/1] bg-gray-100">
                    <picture>
                        @if($popup->mobile_image)
                            <source media="(max-width: 640px)" srcset="{{ asset('storage/' . $popup->mobile_image) }}">
                        @endif
                        <img src="{{ asset('storage/' . $popup->image) }}" alt="{{ $popup->title }}" class="w-full h-full object-cover">
                    </picture>
                </div>
            @endif

            <div class="p-6 sm:p-8 text-center">
                @if($popup->title)
                    <h3 class="text-2xl font-black text-gray-900 mb-2 leading-tight font-oswald uppercase">{{ $popup->title }}</h3>
                @endif
                
                @if($popup->getTranslation('subtitle', app()->getLocale(), false))
                    <p class="text-gray-600 mb-6 leading-relaxed">{{ $popup->getTranslation('subtitle', app()->getLocale(), false) }}</p>
                @endif

                @if($popup->link)
                    <a href="{{ $popup->link }}" @click="dismiss()" class="inline-block w-full sm:w-auto px-8 py-3 bg-[var(--gk-red)] hover:bg-[var(--gk-red-dark)] text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition-all hover:-translate-y-0.5">
                        {{ $popup->getTranslation('button_text') ?: 'Explore Now' }}
                    </a>
                @endif
            </div>
        </div>
    </div>
@endif
