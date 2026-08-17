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
        $favicon = \App\Models\Setting::get('theme1_favicon');
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
    <a href="https://wa.me/{{ $cleanWhatsapp }}?text={{ urlencode(\App\Models\Setting::get('whatsapp_message') ?: __('general.whatsapp_message')) }}" target="_blank" rel="noopener" class="fixed bottom-6 right-6 bg-green-500 hover:bg-green-600 text-white p-3.5 rounded-full shadow-lg hover:scale-110 transition z-50" title="Chat on WhatsApp">
        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.573-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.082 19.165s-1.844-.002-3.612-.947l-4.148 1.089 1.107-4.045c-1.042-1.796-1.593-3.864-1.593-5.965 0-6.442 5.241-11.683 11.683-11.683 6.442 0 11.683 5.241 11.683 11.683 0 6.442-5.241 11.683-11.683 11.683z"/>
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