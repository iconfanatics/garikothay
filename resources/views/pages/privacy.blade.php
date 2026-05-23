@extends('layouts.app')

@section('title', __('general.privacy_policy') . ' - ' . config('app.name'))
@section('meta_description', \App\Models\Setting::get('site_name', 'Dinajpur IT Park') . ' Privacy Policy — how we collect, use, and protect your personal information.')

@section('content')
<div class="bg-gray-50 min-h-screen">

    <!-- Header -->
    <div class="bg-primary-700 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold">{{ __('general.privacy_policy') }}</h1>
            <p class="mt-2 text-primary-200">Last updated: January 1, 2025</p>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 md:p-12 prose prose-sm max-w-none text-gray-700">
            {!! $page->content !!}
        </div>
    </div>
</div>
@endsection
