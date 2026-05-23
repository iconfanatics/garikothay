@extends('layouts.app')

@section('title', __('general.register') . ' - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-lg p-8">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="h-12 mx-auto mb-4" onerror="this.style.display='none'">
            </a>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('general.register') }}</h1>
            <p class="text-gray-500 mt-1">{{ __('general.create_account_subtitle') }}</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('general.full_name') }}</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 @error('name') border-red-400 @enderror">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('general.email') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 @error('email') border-red-400 @enderror">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">{{ __('general.phone') }}</label>
                <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" placeholder="+880 1700-000000"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 @error('phone') border-red-400 @enderror">
                @error('phone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('general.password') }}</label>
                <input id="password" type="password" name="password" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 @error('password') border-red-400 @enderror">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">{{ __('general.confirm_password') }}</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>

            <button type="submit"
                class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 rounded-xl transition">
                {{ __('general.register') }}
            </button>
        </form>

        <p class="text-center text-gray-500 mt-6 text-sm">
            {{ __('general.already_have_account') }}
            <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                {{ __('general.login') }}
            </a>
        </p>
    </div>
</div>
@endsection
