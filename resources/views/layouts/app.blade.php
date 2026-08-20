<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @inject('seoService', 'App\Services\SeoService')
    @php
        $meta = $seoService->getMetaData();
        $siteName = $meta['siteName'];
    @endphp

    <title>{{ $meta['title'] }}</title>
    <meta name="description" content="{{ $meta['metaDescription'] }}">
    
    @php
        $favicon = \App\Models\Setting::get('theme1_favicon') ?: \App\Models\Setting::get('site_favicon');
    @endphp
    @if($favicon)
        <link rel="icon" href="{{ Storage::url($favicon) }}">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}">
    @endif

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="{{ $meta['ogType'] }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $meta['ogTitle'] }}">
    <meta property="og:description" content="{{ $meta['ogDescription'] }}">
    <meta property="og:image" content="{{ $meta['ogImage'] }}">
    <meta property="og:site_name" content="{{ $siteName }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $meta['ogTitle'] }}">
    <meta property="twitter:description" content="{{ $meta['ogDescription'] }}">
    <meta property="twitter:image" content="{{ $meta['ogImage'] }}">

    <link rel="alternate" hreflang="bn" href="{{ url()->current() }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            background-color: #F8FAF5;
            font-family: 'Inter', system-ui, sans-serif !important;
            color: #1B1B1B;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .font-oswald {
            font-family: 'Inter', system-ui, sans-serif !important;
        }
    </style>

    @php
        $globalScript = \App\Models\Setting::get('google_analytics_code');
    @endphp
    @if($globalScript)
        {!! $globalScript !!}
    @endif
</head>

<body class="antialiased">

    <!-- Announcements -->
    <x-announcements />

    <!-- Navbar -->
    <x-navbar :site-name="$siteName" />

    @php
        $flashTypes = [
            'success' => ['bg' => 'bg-green-50', 'border' => 'border-green-200', 'text' => 'text-green-800', 'btn' => 'text-green-400 hover:text-green-700'],
            'error' => ['bg' => 'bg-red-50', 'border' => 'border-red-200', 'text' => 'text-red-800', 'btn' => 'text-red-400 hover:text-red-700'],
            'warning' => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-200', 'text' => 'text-yellow-800', 'btn' => 'text-yellow-400 hover:text-yellow-700'],
            'info' => ['bg' => 'bg-blue-50', 'border' => 'border-blue-200', 'text' => 'text-blue-800', 'btn' => 'text-blue-400 hover:text-blue-700'],
        ];
    @endphp
    @foreach($flashTypes as $type => $classes)
        @if(session($type))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div
                    class="{{ $classes['bg'] }} {{ $classes['border'] }} {{ $classes['text'] }} border rounded-xl px-4 py-3 flex justify-between items-start gap-3">
                    <span>{{ session($type) }}</span>
                    <button onclick="this.parentElement.parentElement.remove()"
                        class="{{ $classes['btn'] }} text-xl leading-none mt-0.5">&times;</button>
                </div>
            </div>
        @endif
    @endforeach

    <!-- Main Content -->
    <main>
        @yield('content')
        {{ $slot ?? '' }}
    </main>


    <!-- WhatsApp float -->
    @php
        $whatsappNumber = \App\Models\Setting::get('whatsapp');
        $cleanWhatsapp = preg_replace('/[^0-9+]/', '', (string)$whatsappNumber);
    @endphp
    @if($cleanWhatsapp)
    <a href="https://wa.me/{{ $cleanWhatsapp }}?text={{ urlencode(\App\Models\Setting::get('whatsapp_message') ?: __('general.whatsapp_message')) }}" target="_blank" rel="noopener" class="fixed bottom-6 right-6 z-50 hover:scale-110 transition-transform duration-300 drop-shadow-xl" title="Chat on WhatsApp">
        <svg class="w-14 h-14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12.031 0C5.385 0 0 5.385 0 12.031c0 2.115.548 4.177 1.59 5.992L.416 24l6.128-1.606c1.767.973 3.766 1.488 5.82 1.488 6.645 0 12.031-5.385 12.031-12.031C24 5.385 18.676 0 12.031 0z" fill="#25D366"/>
            <path d="M17.534 16.513c-.302.864-1.705 1.579-2.378 1.637-.565.051-1.261.205-3.953-.91-3.237-1.336-5.311-4.577-5.474-4.795-.164-.218-1.309-1.745-1.309-3.328 0-1.583.823-2.364 1.116-2.673.293-.309.638-.387.847-.387.21 0 .419.004.607.014.198.01.465-.078.728.553.273.655.932 2.274 1.014 2.438.082.164.137.355.032.564-.105.209-.159.337-.318.528-.159.191-.332.414-.477.564-.155.159-.319.332-.141.641.177.309.791 1.314 1.701 2.124 1.173 1.045 2.155 1.369 2.473 1.519.318.15.505.123.696-.095.191-.218.823-.955 1.041-1.283.218-.328.437-.273.723-.164.287.109 1.815.855 2.124 1.01.309.155.514.232.587.359.073.128.073.746-.229 1.61z" fill="#FEFEFE"/>
        </svg>
    </a>
    @endif
    <!-- Global Toast / Snack Container -->
    <div x-data="{ 
            toasts: [], 
            addToast(message, type = 'success') {
                const id = Date.now();
                this.toasts.push({ id, message, type });
                setTimeout(() => {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }, 3000);
            } 
         }"
         @toast.window="addToast($event.detail.message, $event.detail.type)"
         class="fixed bottom-6 left-6 z-50 flex flex-col gap-2 pointer-events-none max-w-sm w-full"
         x-cloak>
        
        <template x-for="toast in toasts" :key="toast.id">
            <div x-transition:enter="transition ease-out duration-300 transform translate-y-2 opacity-0"
                 x-transition:enter-start="transform translate-y-2 opacity-0"
                 x-transition:enter-end="transform translate-y-0 opacity-100"
                 x-transition:leave="transition ease-in duration-200 transform translate-y-0 opacity-100"
                 x-transition:leave-start="transform translate-y-0 opacity-100"
                 x-transition:leave-end="transform translate-y-2 opacity-0"
                 class="pointer-events-auto bg-[#2D6A4F] text-white px-4 py-3 rounded-xl shadow-lg flex items-center justify-between gap-3 border border-[#52B788]/20">
                <div class="flex items-center gap-2">
                    <span class="text-lg">🛒</span>
                    <span x-text="toast.message" class="text-sm font-medium"></span>
                </div>
                <button @click="toasts = toasts.filter(t => t.id !== toast.id)" class="text-white/70 hover:text-white">&times;</button>
            </div>
        </template>
    </div>

    <!-- Popup Banner -->
    <x-popup-banner />

    <x-footer />
    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetch('/cart/count', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.json())
                .then(data => {
                    document.querySelectorAll('.cart-count').forEach(el => el.textContent = data.count ?? 0);
                }).catch(() => { });
        });
    </script>
</body>

</html>