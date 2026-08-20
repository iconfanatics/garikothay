<style>
    .gk-footer-inner {
        width: 100%;
        max-width: 1504px;
        margin-right: auto;
        margin-left: auto;
        padding-right: 1rem;
        padding-left: 1rem;
    }

    .gk-footer-newsletter {
        margin-top: 1.25rem;
    }

    .gk-footer-newsletter-form {
        display: flex;
        min-height: 42px;
        overflow: hidden;
        border: 1px solid #4b5563;
        border-radius: 6px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .gk-footer-newsletter-form:focus-within {
        border-color: #e11d48;
        box-shadow: 0 0 0 2px rgba(225, 29, 72, 0.16);
    }

    .gk-footer-newsletter-input {
        min-width: 0;
        flex: 1;
        border: 0;
        background: #1f2937;
        color: #ffffff;
        padding: 0 0.75rem;
        font-size: 0.78rem;
        outline: none;
    }

    .gk-footer-newsletter-input::placeholder {
        color: #9ca3af;
    }

    .gk-footer-newsletter-button {
        flex: 0 0 auto;
        border: 0;
        background: #e11d48;
        color: #ffffff;
        padding: 0 0.9rem;
        font-size: 0.78rem;
        font-weight: 800;
        cursor: pointer;
    }

    .gk-footer-newsletter-button:hover {
        background: #be123c;
    }
</style>

<!-- Footer -->
<footer class="bg-gray-900 text-gray-300 mt-16 py-12">
    <div class="gk-footer-inner grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 lg:gap-8">
        <div class="min-w-0">
            <h3 class="text-white text-lg font-bold mb-4">
                @php $logo = \App\Models\Setting::get('theme1_footer_logo'); @endphp
                @if($logo)
                    <img src="{{ asset('storage/' . $logo) }}" alt="{{ config('app.name') }}"
                        class="h-10 w-auto object-contain">
                @else
                    {{ \App\Models\Setting::get('site_name', config('app.name')) }}
                @endif
            </h3>
            <p class="text-sm leading-relaxed text-gray-400 mb-6">
                {{ \App\Models\Setting::get('site_tagline', 'Bangladesh\'s premier online IT & Computer Accessories store.') }}
            </p>
            <h4 class="text-white font-semibold mb-3 text-sm uppercase tracking-wide">Contact Us</h4>
            <ul class="space-y-2 text-sm text-gray-400 mb-6">
                @php
                    $phone1 = \App\Models\Setting::get('phone', '+880 1700-000000');
                    $phone2 = \App\Models\Setting::get('phone_2');
                    $phone3 = \App\Models\Setting::get('phone_3');
                    $email1 = \App\Models\Setting::get('email', 'support@garikothay.com');
                    $email2 = \App\Models\Setting::get('email_2');
                    $email3 = \App\Models\Setting::get('email_3');
                @endphp
                <li>📞 <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone1) }}" class="hover:text-rose-400 transition">{{ $phone1 }}</a></li>
                @if($phone2)
                <li>📞 <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone2) }}" class="hover:text-rose-400 transition">{{ $phone2 }}</a></li>
                @endif
                @if($phone3)
                <li>📞 <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone3) }}" class="hover:text-rose-400 transition">{{ $phone3 }}</a></li>
                @endif
                <li>✉ <a href="mailto:{{ $email1 }}" class="hover:text-rose-400 transition">{{ $email1 }}</a></li>
                @if($email2)
                <li>✉ <a href="mailto:{{ $email2 }}" class="hover:text-rose-400 transition">{{ $email2 }}</a></li>
                @endif
                @if($email3)
                <li>✉ <a href="mailto:{{ $email3 }}" class="hover:text-rose-400 transition">{{ $email3 }}</a></li>
                @endif
                <li>📍 {{ \App\Models\Setting::get('address', 'House 24, Road 7, Banani, Dhaka 1213, Bangladesh') }}</li>
                @if(\App\Models\Setting::get('address_2'))
                <li>📍 {{ \App\Models\Setting::get('address_2') }}</li>
                @endif
                @if(\App\Models\Setting::get('trade_license_number'))
                <li>📝 Trade License: {{ \App\Models\Setting::get('trade_license_number') }}</li>
                @endif
                @if(\App\Models\Setting::get('support_hours'))
                <li>⏰ {{ \App\Models\Setting::get('support_hours') }}</li>
                @endif
            </ul>

        </div>

        @php
            $locale = app()->getLocale();
            $footerLinks = \Illuminate\Support\Facades\Cache::rememberForever("footer_links_{$locale}", function () {
                return \App\Models\NavigationItem::with('translations')
                    ->where('is_active', true)
                    ->whereIn('group', ['footer_company', 'footer_business', 'footer_help', 'footer_legal'])
                    ->orderBy('sort_order')
                    ->get()
                    ->groupBy('group');
            });
        @endphp

        <div>
            <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wide">Company</h4>
            <ul class="space-y-2 text-sm text-gray-400 capitalize">
                @foreach($footerLinks->get('footer_company', []) as $link)
                    <li><a href="{{ url($link->url ?? '#') }}" class="hover:text-rose-400 transition">{{ $link->label }}</a></li>
                @endforeach
            </ul>
        </div>
        
        <div>
            <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wide">Business</h4>
            <ul class="space-y-2 text-sm text-gray-400 capitalize">
                @foreach($footerLinks->get('footer_business', []) as $link)
                    <li><a href="{{ url($link->url ?? '#') }}" class="hover:text-rose-400 transition">{{ $link->label }}</a></li>
                @endforeach
            </ul>
        </div>

        <div>
            <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wide">Help & Information</h4>
            <ul class="space-y-2 text-sm text-gray-400 capitalize">
                @foreach($footerLinks->get('footer_help', []) as $link)
                    <li><a href="{{ url($link->url ?? '#') }}" class="hover:text-rose-400 transition">{{ $link->label }}</a></li>
                @endforeach
                <li>🚚 Delivery Time: Inside Dhaka 5 Days, Outside Dhaka 10 Days</li>
            </ul>
        </div>

        <div>
            <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wide">Legal & Policies</h4>
            <ul class="space-y-2 text-sm text-gray-400 capitalize">
                @foreach($footerLinks->get('footer_legal', []) as $link)
                    <li><a href="{{ url($link->url ?? '#') }}" class="hover:text-rose-400 transition">{{ $link->label }}</a></li>
                @endforeach
            </ul>

            @php $socialLinks = \App\Models\Setting::get('theme1_social_links'); @endphp
            @if($socialLinks && is_array($socialLinks) && count($socialLinks) > 0)
            <div class="mt-8">
                <div class="flex items-center gap-4">
                    @foreach($socialLinks as $link)
                        <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-[var(--gk-red)] transition flex items-center justify-center w-8 h-8">
                            @if($link['platform'] === 'facebook')
                                <svg class="w-[1.4rem] h-[1.4rem]" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                            @elseif($link['platform'] === 'twitter')
                                <svg class="w-[1.2rem] h-[1.2rem]" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            @elseif($link['platform'] === 'instagram')
                                <svg class="w-[1.3rem] h-[1.3rem]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            @elseif($link['platform'] === 'youtube')
                                <svg class="w-[1.4rem] h-[1.4rem]" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            @elseif($link['platform'] === 'linkedin')
                                <svg class="w-[1.25rem] h-[1.25rem]" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            @elseif($link['platform'] === 'tiktok')
                                <svg class="w-[1.2rem] h-[1.2rem]" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.04.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93v7.2c0 1.96-.55 3.91-1.68 5.49-1.25 1.74-3.15 2.87-5.26 3.14-2.14.28-4.38-.13-6.14-1.39-1.76-1.26-2.92-3.18-3.23-5.32-.33-2.31.22-4.73 1.58-6.6 1.34-1.84 3.4-3 5.62-3.37v4.06c-1.42.22-2.73.96-3.64 2.08-.85 1.05-1.26 2.45-1.12 3.8.15 1.48.91 2.8 2.07 3.7.99.78 2.3.99 3.53.86 1.33-.14 2.53-.78 3.39-1.79.8-.95 1.25-2.19 1.27-3.48V.02h-.05z"/></svg>
                            @elseif($link['platform'] === 'whatsapp')
                                <svg class="w-[1.4rem] h-[1.4rem]" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 0C5.385 0 0 5.385 0 12.031c0 2.115.548 4.177 1.59 5.992L.416 24l6.128-1.606c1.767.973 3.766 1.488 5.82 1.488 6.645 0 12.031-5.385 12.031-12.031C24 5.385 18.676 0 12.031 0zm0 21.905c-1.786 0-3.529-.479-5.06-1.385l-.363-.214-3.765.986.999-3.671-.235-.374a10.013 10.013 0 0 1-1.531-5.384c0-5.529 4.498-10.027 10.027-10.027 5.529 0 10.027 4.498 10.027 10.027 0 5.529-4.498 10.027-10.027 10.027zm5.503-7.518c-.302-.151-1.788-.883-2.065-.985-.276-.101-.478-.151-.679.151-.202.302-.78 1.006-.957 1.208-.176.201-.353.226-.655.075-1.516-.762-2.731-1.897-3.541-3.32-.178-.31-.02-.486.13-.637.135-.135.302-.353.453-.53.151-.176.202-.302.302-.504.101-.202.051-.378-.025-.529-.076-.151-.68-1.637-.932-2.241-.245-.589-.494-.509-.679-.518-.176-.008-.378-.01-.579-.01-.202 0-.529.075-.805.378-.277.302-1.057 1.033-1.057 2.518 0 1.485 1.082 2.921 1.233 3.123.151.202 2.128 3.25 5.155 4.557 2.016.871 2.802.736 3.28.604.478-.132 1.536-.63 1.763-1.233.227-.604.227-1.12.151-1.233-.075-.113-.276-.176-.579-.327z"/></svg>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <div class="gk-footer-inner mt-8 border-t border-gray-800 pt-8">
        @php 
            $paymentBanner = \App\Models\Setting::get('theme1_payment_banner'); 
            $hasCustomBanner = $paymentBanner && \Illuminate\Support\Facades\Storage::disk('public')->exists($paymentBanner);
            $bannerUrl = $hasCustomBanner ? asset('storage/' . $paymentBanner) : asset('images/payment-banner.png');
        @endphp
        <img src="{{ $bannerUrl }}" alt="Accepted Payment Methods" class="w-full max-w-5xl mx-auto h-auto object-contain bg-white rounded-xl p-2 shadow-sm">
    </div>
    
    <div class="gk-footer-inner mt-8 pt-8 border-t border-gray-800 text-center text-sm text-gray-500 pb-8">
        © {{ date('Y') }} {{ \App\Models\Setting::get('site_name', config('app.name')) }}. {{ __('general.all_rights_reserved') }}
    </div>
</footer>
