@extends('layouts.app')

@section('title', __('general.return_policy') . ' - ' . config('app.name'))
@section('meta_description', 'Garikothay Return Policy — 7-day replacement guarantee, official brand warranties, and simple return process.')

@section('content')
<div class="bg-gray-50 min-h-screen">

    <!-- Header -->
    <div class="bg-rose-700 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold">{{ __('general.return_policy') }}</h1>
            <p class="mt-2 text-rose-200">Our 7-day replacement warranty</p>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-6">

        <!-- Quick Summary Cards -->
        <div class="grid sm:grid-cols-3 gap-4">
            @foreach([
                ['7 Days', 'Replacement Warranty', 'green', '💻'],
                ['48 Hours', 'Report Damaged Delivery', 'blue', '📦'],
                ['Free', 'Replacement or Full Refund', 'purple', '✅'],
            ] as [$num, $label, $color, $icon])
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 text-center">
                <div class="text-3xl mb-2">{{ $icon }}</div>
                <div class="text-xl font-bold text-{{ $color }}-600">{{ $num }}</div>
                <div class="text-xs text-gray-500 mt-1">{{ $label }}</div>
            </div>
            @endforeach
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 md:p-12 prose prose-sm max-w-none text-gray-700">
            {!! $page->content !!}
        </div>

        <!-- Contact CTA -->
        <div class="bg-rose-600 rounded-2xl p-6 text-center text-white">
            <p class="font-medium mb-4">Have an issue with your order? We're here to help.</p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('page.contact') }}"
                   class="inline-block bg-white text-rose-700 font-semibold px-6 py-2.5 rounded-xl hover:bg-rose-50 transition text-sm">
                    {{ __('general.contact_us') }}
                </a>
                <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp') }}"
                   target="_blank" rel="noopener"
                   class="inline-block bg-green-500 hover:bg-green-400 text-white font-semibold px-6 py-2.5 rounded-xl transition text-sm">
                    WhatsApp Us
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
