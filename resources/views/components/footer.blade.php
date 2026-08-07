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
    <div class="gk-footer-inner grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-8">
        <div class="lg:col-span-2">
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

        <div>
            <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wide">Company</h4>
            <ul class="space-y-2 text-sm text-gray-400 capitalize">
                <li><a href="#" class="hover:text-rose-400 transition">About Us</a></li>
                <li><a href="#" class="hover:text-rose-400 transition">Our Team</a></li>
                <li><a href="#" class="hover:text-rose-400 transition">Careers</a></li>
                <li><a href="#" class="hover:text-rose-400 transition">Contact Us</a></li>
                <li><a href="#" class="hover:text-rose-400 transition">Press & Media+</a></li>
                <li><a href="#" class="hover:text-rose-400 transition">Partners+</a></li>
            </ul>
        </div>
        
        <div>
            <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wide">Business</h4>
            <ul class="space-y-2 text-sm text-gray-400 capitalize">
                <li><a href="#" class="hover:text-rose-400 transition">Why List With Us</a></li>
                <li><a href="#" class="hover:text-rose-400 transition">Advertise With Us</a></li>
                <li><a href="#" class="hover:text-rose-400 transition">Trust & Verification</a></li>
                <li><a href="#" class="hover:text-rose-400 transition">Business Listing Plans+</a></li>
                <li><a href="#" class="hover:text-rose-400 transition">Partner With Us+</a></li>
            </ul>
        </div>

        <div>
            <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wide">Help & Information</h4>
            <ul class="space-y-2 text-sm text-gray-400 capitalize">
                <li><a href="#" class="hover:text-rose-400 transition">Feedback</a></li>
                <li><a href="#" class="hover:text-rose-400 transition">Tips & Guide</a></li>
                <li><a href="#" class="hover:text-rose-400 transition uppercase">FAQ</a></li>
                <li><a href="#" class="hover:text-rose-400 transition">Help Center</a></li>
                <li><a href="#" class="hover:text-rose-400 transition">Report A Problem</a></li>
                <li><a href="#" class="hover:text-rose-400 transition">Safety Guidelines</a></li>
            </ul>
        </div>

        <div>
            <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wide">Legal & Policies</h4>
            <ul class="space-y-2 text-sm text-gray-400 capitalize">
                <li><a href="{{ route('page.show', 'privacy-policy') }}" class="hover:text-rose-400 transition">Privacy Policy</a></li>
                <li><a href="{{ route('page.show', 'terms-and-conditions') }}" class="hover:text-rose-400 transition">Terms & Condition</a></li>
                <li><a href="{{ route('page.show', 'delivery-policy') }}" class="hover:text-rose-400 transition">Delivery Policy</a></li>
                <li><a href="{{ route('page.show', 'refund-and-return-policy') }}" class="hover:text-rose-400 transition">Refund & Return Policy</a></li>
                <li><a href="#" class="hover:text-rose-400 transition">Exchange Policy</a></li>
                <li><a href="#" class="hover:text-rose-400 transition">Cancellation Policy</a></li>
                <li><a href="#" class="hover:text-rose-400 transition">Warranty Policy</a></li>
                <li><a href="#" class="hover:text-rose-400 transition uppercase">EMI & Payment Policy</a></li>
            </ul>
        </div>
    </div>
    <div class="gk-footer-inner mt-8 pt-8 border-t border-gray-800 text-center text-sm text-gray-500">
        © {{ date('Y') }} {{ \App\Models\Setting::get('site_name', config('app.name')) }}. {{ __('general.all_rights_reserved') }} {{ __('general.made_with_love') }}
    </div>
</footer>
