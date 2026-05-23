@extends('layouts.app')

@section('title', __('general.verify_email') . ' - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-lg p-8 text-center">
        <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-5">
            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-gray-900 mb-3">{{ __('general.check_your_email') }}</h1>
        <p class="text-gray-500 mb-6">
            {{ __('general.verify_email_sent_msg') }}
        </p>

        @if(session('status') === 'verification-link-sent')
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 mb-5 text-sm">
            {{ __('general.verification_link_resent') }}
        </div>
        @endif

        <form method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit"
                class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 rounded-xl transition">
                {{ __('general.resend_verification_email') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button type="submit" class="text-sm text-gray-400 hover:text-gray-600">
                {{ __('general.logout') }}
            </button>
        </form>
    </div>
</div>
@endsection
