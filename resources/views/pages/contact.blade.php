@extends('layouts.app')

@section('title', __('general.contact_us') . ' - ' . config('app.name'))
@section('meta_description', 'Get in touch with Dinajpur IT Park. We\'re here to help with your tech accessory questions, orders, and hardware feedback.')

@section('content')
<div class="bg-gray-50 min-h-screen">

    <!-- Header -->
    <div class="bg-primary-700 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold">{{ __('general.contact_us') }}</h1>
            <p class="mt-2 text-primary-200">{{ __('general.get_in_touch') }}</p>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid lg:grid-cols-3 gap-10">

            <!-- Contact Info -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="text-3xl mb-3">📞</div>
                    <h3 class="font-semibold text-gray-900 mb-1">{{ __('general.call_us') }}</h3>
                    <p class="text-primary-600 font-medium">{{ $phone }}</p>
                    <p class="text-gray-400 text-sm mt-1">Sat – Thu, 9am – 8pm</p>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="text-3xl mb-3">✉️</div>
                    <h3 class="font-semibold text-gray-900 mb-1">{{ __('general.email_us') }}</h3>
                    <a href="mailto:{{ $email }}" class="text-primary-600 font-medium hover:underline">{{ $email }}</a>
                    <p class="text-gray-400 text-sm mt-1">We reply within 24 hours</p>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="text-3xl mb-3">📍</div>
                    <h3 class="font-semibold text-gray-900 mb-1">{{ __('general.visit_us') }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $address }}</p>
                </div>

                <!-- WhatsApp -->
                <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp') }}?text=Hi%2C+I+need+help+with+my+order"
                   target="_blank" rel="noopener"
                   class="flex items-center gap-3 bg-green-500 hover:bg-green-600 text-white rounded-2xl p-4 transition font-medium">
                    <svg class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    <span>Chat on WhatsApp</span>
                </a>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2 bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('general.send_message') }}</h2>

                <form action="{{ route('page.contact.submit') }}" method="POST" class="space-y-5">
                    @csrf

                    <div class="grid sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                {{ __('general.your_name') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary-400 focus:ring-1 focus:ring-primary-400 transition @error('name') border-red-400 @enderror">
                            @error('name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                {{ __('general.your_email') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary-400 focus:ring-1 focus:ring-primary-400 transition @error('email') border-red-400 @enderror">
                            @error('email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            {{ __('general.subject') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="subject" value="{{ old('subject') }}" required
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary-400 focus:ring-1 focus:ring-primary-400 transition @error('subject') border-red-400 @enderror">
                        @error('subject')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            {{ __('general.your_message') }} <span class="text-red-500">*</span>
                        </label>
                        <textarea name="message" rows="6" required
                                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary-400 focus:ring-1 focus:ring-primary-400 transition resize-none @error('message') border-red-400 @enderror"
                                  placeholder="Tell us how we can help...">{{ old('message') }}</textarea>
                        @error('message')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-6 rounded-xl transition text-sm">
                        {{ __('general.send_message') }}
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
