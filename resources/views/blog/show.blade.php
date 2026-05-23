@extends('layouts.app')

@section('title', $blog->getTranslation('meta_title') ?: $blog->getTranslation('title'))
@section('meta_description', $blog->getTranslation('meta_description') ?: $blog->getTranslation('excerpt'))
@section('og_title', $blog->getTranslation('title'))
@section('og_description', $blog->getTranslation('excerpt'))
@section('og_image', $blog->featured_image ? asset('storage/' . $blog->featured_image) : null)

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Hero image -->
    @if($blog->featured_image)
    <div class="w-full h-72 md:h-96 overflow-hidden">
        <img src="{{ asset('storage/' . $blog->featured_image) }}"
             alt="{{ $blog->getTranslation('title') }}"
             class="w-full h-full object-cover">
    </div>
    @endif

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <!-- Breadcrumb -->
        <nav class="text-sm text-gray-400 mb-6 flex items-center gap-2">
            <a href="{{ route('home') }}" class="hover:text-primary-600">{{ __('general.home') }}</a>
            <span>/</span>
            <a href="{{ route('blog.index') }}" class="hover:text-primary-600">{{ __('general.blog') }}</a>
            @if($blog->category)
            <span>/</span>
            <a href="{{ route('blog.index', ['category' => $blog->category->slug]) }}" class="hover:text-primary-600">
                {{ $blog->category->getTranslation('name') }}
            </a>
            @endif
        </nav>

        <!-- Category badge -->
        @if($blog->category)
        <span class="inline-block bg-primary-100 text-primary-700 text-sm font-medium px-3 py-1 rounded-full mb-4">
            {{ $blog->category->getTranslation('name') }}
        </span>
        @endif

        <!-- Title -->
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 leading-tight mb-4">
            {{ $blog->getTranslation('title') }}
        </h1>

        <!-- Meta -->
        <div class="flex items-center gap-4 text-sm text-gray-400 mb-8 pb-8 border-b border-gray-200">
            @if($blog->author)
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                {{ $blog->author->name }}
            </span>
            @endif
            @if($blog->published_at)
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ $blog->published_at->format('M d, Y') }}
            </span>
            @endif
        </div>

        <!-- Content -->
        <div class="prose prose-lg prose-primary max-w-none text-gray-700 leading-relaxed">
            {!! $blog->getTranslation('content') !!}
        </div>

        <!-- Footer nav -->
        <div class="mt-12 pt-8 border-t border-gray-200 flex justify-between items-center">
            <a href="{{ route('blog.index') }}"
               class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium">
                ← {{ __('general.blog') }}
            </a>
        </div>

    </div>
</div>
@endsection
