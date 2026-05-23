@extends('layouts.app')

@section('title', __('general.faq') . ' - ' . config('app.name'))
@section('meta_description', __('general.faq_meta_description'))

@section('content')
<div class="bg-gray-50 min-h-screen">

    <!-- Header -->
    <div class="bg-primary-700 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold">{{ __('general.faq') }}</h1>
            <p class="mt-2 text-primary-200">{{ __('general.faq_subtitle') }}</p>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-4">

        <!-- Dynamic Content -->
        <div class="prose prose-sm max-w-none text-gray-700">
            {!! $page->content !!}
        </div>

        <!-- Still have questions? -->
        <div class="bg-primary-600 rounded-2xl p-8 text-center text-white mt-8">
            <div class="text-3xl mb-3">💬</div>
            <h3 class="text-xl font-bold mb-2">{{ __('general.still_have_questions') }}</h3>
            <p class="text-primary-100 mb-6 text-sm">{{ __('general.team_happy_to_help') }}</p>
            <a href="{{ route('page.contact') }}"
               class="inline-block bg-white text-primary-700 font-semibold px-6 py-2.5 rounded-xl hover:bg-primary-50 transition text-sm">
                {{ __('general.contact_us') }}
            </a>
        </div>

    </div>
</div>
@endsection
