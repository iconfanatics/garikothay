<!-- Footer -->
<footer class="bg-gray-900 text-gray-300 mt-16 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
        <div>
            <h3 class="text-white text-lg font-bold mb-4">
                @php $logo = \App\Models\Setting::get('site_logo'); @endphp
                @if($logo)
                    <img src="{{ asset('storage/' . $logo) }}" alt="{{ config('app.name') }}"
                        class="h-8 w-auto object-contain brightness-0 invert">
                @else
                    💻 Dinajpur IT Park
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
                <li><a href="{{ route('shop.index') }}"
                        class="hover:text-primary-400 transition">{{ __('general.shop') }}</a></li>
                <li><a href="{{ route('blog.index') }}"
                        class="hover:text-primary-400 transition">{{ __('general.blog') }}</a></li>
                <li><a href="{{ route('page.about') }}"
                        class="hover:text-primary-400 transition">{{ __('general.about_us') }}</a></li>
                <li><a href="{{ route('page.terms') }}"
                        class="hover:text-primary-400 transition">{{ __('general.terms') }}</a></li>
                <li><a href="{{ route('page.privacy') }}"
                        class="hover:text-primary-400 transition">{{ __('general.privacy_policy') }}</a></li>
            </ul>
        </div>
        <div>
            <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wide">
                {{ __('general.customer_service') }}
            </h4>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('page.contact') }}"
                        class="hover:text-primary-400 transition">{{ __('general.contact_us') }}</a></li>
                <li><a href="{{ route('page.faq') }}"
                        class="hover:text-primary-400 transition">{{ __('general.faq') }}</a></li>
                <li><a href="{{ route('page.return-policy') }}"
                        class="hover:text-primary-400 transition">{{ __('general.return_policy') }}</a></li>
            </ul>
        </div>
        <div>
            <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wide">{{ __('general.contact') }}
            </h4>
            <ul class="space-y-2 text-sm text-gray-400">
                <li>📞 {{ \App\Models\Setting::get('phone', '+880 1700-000000') }}</li>
                <li>✉ {{ \App\Models\Setting::get('email', 'support@dinajpuritpark.com') }}</li>
                <li>📍 {{ \App\Models\Setting::get('address', 'Dhaka, Bangladesh') }}</li>
            </ul>
            <div x-data="{ email: '', msg: '' }">
                <div
                    class="flex rounded-xl overflow-hidden border border-gray-700 focus-within:border-primary-500 transition">
                    <input type="email" x-model="email" placeholder="{{ __('general.newsletter_placeholder') }}"
                        class="flex-1 bg-gray-800 text-white text-sm px-3 py-2 outline-none placeholder-gray-500">
                    <button @click="
                            if (!email) return;
                            fetch('{{ route('newsletter.subscribe') }}', {
                                method: 'POST',
                                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                                body: JSON.stringify({email: email})
                            }).then(r => r.json()).then(d => { msg = d.message; email = ''; }).catch(() => { msg = '{{ __('general.something_went_wrong') }}'; });
                        " class="bg-primary-600 hover:bg-primary-500 text-white text-sm px-4 py-2 transition">
                        {{ __('general.subscribe') }}
                    </button>
                </div>
                <p x-show="msg" x-text="msg" class="text-sm text-green-400 mt-2"></p>
            </div>
        </div>
    </div>
    <div
        class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 pt-8 border-t border-gray-800 text-center text-sm text-gray-500">
        © {{ date('Y') }} Dinajpur IT Park. {{ __('general.all_rights_reserved') }} {{ __('general.made_with_love') }}
    </div>
</footer>