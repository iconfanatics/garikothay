@extends('layouts.app')

@section('title', __('general.profile') . ' - ' . config('app.name'))

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="text-2xl font-bold text-gray-900 mb-8">{{ __('general.profile') }}</h1>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-6">
        {{ session('success') }}
    </div>
    @endif

    <!-- Profile form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <h2 class="font-semibold text-gray-800 mb-5">{{ __('general.personal_info') }}</h2>
        <form method="POST" action="{{ route('customer.profile.update') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div x-data="{ photoName: null, photoPreview: null }" class="flex items-center gap-5 mb-4">
                <div class="w-20 h-20 rounded-full overflow-hidden bg-primary-100 flex items-center justify-center relative">
                    <!-- Current Profile Photo -->
                    <div x-show="!photoPreview" class="w-full h-full">
                        @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                        <span class="w-full h-full flex items-center justify-center text-primary-600 text-2xl font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <!-- New Profile Photo Preview -->
                    <div x-show="photoPreview" style="display: none;" class="w-full h-full">
                        <span class="block w-full h-full bg-cover bg-no-repeat bg-center"
                              x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                        </span>
                    </div>
                </div>
                <div>
                    <input type="file" name="avatar" id="avatar" accept="image/*" class="hidden"
                           @change="
                               const reader = new FileReader();
                               reader.onload = (e) => { photoPreview = e.target.result; };
                               reader.readAsDataURL($refs.photo.files[0]);
                               photoName = $refs.photo.files[0].name;
                           " x-ref="photo">
                    <label for="avatar" class="cursor-pointer text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition">
                        {{ __('general.change_photo') }}
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('general.full_name') }}</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 @error('name') border-red-400 @enderror">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('general.phone') }}</label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 @error('phone') border-red-400 @enderror">
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('general.email_label') }}</label>
                <input type="email" value="{{ $user->email }}" disabled
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-gray-400 cursor-not-allowed">
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="bg-primary-600 hover:bg-primary-700 text-white font-medium px-6 py-2.5 rounded-xl transition">
                    {{ __('general.save_changes') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Change password -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="font-semibold text-gray-800 mb-5">{{ __('general.change_password') }}</h2>
        <form method="POST" action="{{ route('customer.password.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('general.current_password') }}</label>
                <input type="password" name="current_password" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 @error('current_password') border-red-400 @enderror">
                @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('general.new_password') }}</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 @error('password') border-red-400 @enderror">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('general.confirm_password') }}</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit"
                    class="bg-gray-800 hover:bg-gray-900 text-white font-medium px-6 py-2.5 rounded-xl transition">
                    {{ __('general.update_password') }}
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
