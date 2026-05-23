@extends('layouts.app')

@section('title', __('general.login') . ' - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-lg p-8">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="h-12 mx-auto mb-4" onerror="this.style.display='none'">
            </a>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('general.welcome_back') }}</h1>
            <p class="text-gray-500 mt-1">{{ __('general.login') }}</p>
        </div>

        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 mb-6">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('general.email') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('email') border-red-400 @enderror">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="flex justify-between items-center mb-1">
                    <label for="password" class="block text-sm font-medium text-gray-700">{{ __('general.password') }}</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-primary-600 hover:text-primary-700">
                            {{ __('general.forgot_password') }}
                        </a>
                    @endif
                </div>
                <input id="password" type="password" name="password" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('password') border-red-400 @enderror">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center">
                <input id="remember" type="checkbox" name="remember" class="w-4 h-4 text-primary-600 border-gray-300 rounded">
                <label for="remember" class="ml-2 text-sm text-gray-600">{{ __('general.remember_me') }}</label>
            </div>

            <button type="submit"
                class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 rounded-xl transition">
                {{ __('general.login') }}
            </button>
        </form>

        <p class="text-center text-gray-500 mt-6 text-sm">
            {{ __('general.no_account') }}
            <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                {{ __('general.register') }}
            </a>
        </p>
    </div>
</div>
@endsection
