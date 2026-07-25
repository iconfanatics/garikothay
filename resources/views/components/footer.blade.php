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
    <div class="gk-footer-inner grid grid-cols-1 md:grid-cols-4 gap-8">
        <div>
            <h3 class="text-white text-lg font-bold mb-4">
                @php $logo = \App\Models\Setting::get('site_logo'); @endphp
                @if($logo)
                    <img src="{{ asset('storage/' . $logo) }}" alt="{{ config('app.name') }}"
                        class="h-8 w-auto object-contain brightness-0 invert">
                @else
                    {{ \App\Models\Setting::get('site_name', config('app.name')) }}
                @endif
            </h3>
            <p class="text-sm leading-relaxed text-gray-400">
                {{ \App\Models\Setting::get('site_tagline', 'Bangladesh\'s premier online IT & Computer Accessories store.') }}
            </p>
        </div>
        <div>
            <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wide">
                {{ __('general.quick_links') }}
            </h4>
            <ul class="space-y-2 text-sm">
                @php
                    $quickLinks = \Illuminate\Support\Facades\Schema::hasTable('navigation_items') 
                        ? \App\Models\NavigationItem::where('group', 'footer_quick_links')->where('is_active', true)->orderBy('sort_order')->get()
                        : collect();
                @endphp
                @foreach($quickLinks as $link)
                    <li><a href="{{ url($link->url ?? '#') }}" class="hover:text-rose-400 transition">{{ $link->label }}</a></li>
                @endforeach
            </ul>
        </div>
        <div>
            <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wide">
                {{ __('general.customer_service') }}
            </h4>
            <ul class="space-y-2 text-sm">
                @php
                    $customerServiceLinks = \Illuminate\Support\Facades\Schema::hasTable('navigation_items') 
                        ? \App\Models\NavigationItem::where('group', 'footer_customer_service')->where('is_active', true)->orderBy('sort_order')->get()
                        : collect();
                @endphp
                @foreach($customerServiceLinks as $link)
                    <li><a href="{{ url($link->url ?? '#') }}" class="hover:text-rose-400 transition">{{ $link->label }}</a></li>
                @endforeach
            </ul>
        </div>
        <div>
            <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wide">{{ __('general.contact') }}
            </h4>
            <ul class="space-y-2 text-sm text-gray-400">
                <li>📞 {{ \App\Models\Setting::get('phone', '+880 1700-000000') }}</li>
                <li>✉ {{ \App\Models\Setting::get('email', 'support@garikothay.com') }}</li>
                <li>📍 {{ \App\Models\Setting::get('address', 'House 24, Road 7, Banani, Dhaka 1213, Bangladesh') }}</li>
            </ul>
            <div x-data="{ email: '', msg: '' }" class="gk-footer-newsletter">
                <div class="gk-footer-newsletter-form">
                    <input type="email" x-model="email" placeholder="{{ __('general.newsletter_placeholder') }}"
                        class="gk-footer-newsletter-input">
                    <button @click="
                            if (!email) return;
                            fetch('{{ route('newsletter.subscribe') }}', {
                                method: 'POST',
                                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                                body: JSON.stringify({email: email})
                            }).then(r => r.json()).then(d => { msg = d.message; email = ''; }).catch(() => { msg = '{{ __('general.something_went_wrong') }}'; });
                        " class="gk-footer-newsletter-button">
                        {{ __('general.subscribe') }}
                    </button>
                </div>
                <p x-show="msg" x-text="msg" class="text-sm text-green-400 mt-2"></p>
            </div>
        </div>
    </div>
    <div class="gk-footer-inner mt-8 pt-8 border-t border-gray-800 text-center text-sm text-gray-500">
        © {{ date('Y') }} {{ \App\Models\Setting::get('site_name', config('app.name')) }}. {{ __('general.all_rights_reserved') }} {{ __('general.made_with_love') }}
    </div>
</footer>
