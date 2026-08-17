@extends('layouts.app')

@section('title', $blog->getTranslation('meta_title') ?: $blog->getTranslation('title'))
@section('meta_description', $blog->getTranslation('meta_description') ?: $blog->getTranslation('excerpt'))
@section('og_title', $blog->getTranslation('title'))
@section('og_description', $blog->getTranslation('excerpt'))
@section('og_image', $blog->featured_image_url)

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Hero image -->
    @if($blog->featured_image)
    <div class="w-full h-72 md:h-96 overflow-hidden">
        <img src="{{ $blog->featured_image_url }}"
             alt="{{ $blog->getTranslation('title') }}"
             onerror="this.onerror=null;this.src='{{ asset('images/product-placeholder.svg') }}';"
             class="w-full h-full object-cover">
    </div>
    @endif

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <!-- Breadcrumb -->
        <nav class="text-sm text-gray-400 mb-6 flex items-center gap-2">
            <a href="{{ route('home') }}" class="hover:text-rose-600">{{ __('general.home') }}</a>
            <span>/</span>
            <a href="{{ route('blog.index') }}" class="hover:text-rose-600">{{ __('general.blog') }}</a>
            @if($blog->category)
            <span>/</span>
            <a href="{{ route('blog.index', ['category' => $blog->category->slug]) }}" class="hover:text-rose-600">
                {{ $blog->category->getTranslation('name') }}
            </a>
            @endif
        </nav>

        <!-- Category badge -->
        @if($blog->category)
        <span class="inline-block bg-rose-100 text-rose-700 text-sm font-medium px-3 py-1 rounded-full mb-4">
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

        <!-- Comments Section -->
        <div class="mt-16 pt-8 border-t border-gray-200">
            <h3 class="text-2xl font-bold text-gray-900 mb-8">{{ __('general.comments') }} ({{ $blog->comments->count() }})</h3>

            @if(session('success'))
                <div class="bg-green-50 text-green-700 p-4 rounded-lg mb-8">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Comments List -->
            <div class="space-y-8 mb-8">
                @forelse($blog->comments as $comment)
                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-gray-200 rounded-full flex-shrink-0 flex items-center justify-center text-gray-500 font-bold text-xl uppercase">
                            {{ substr($comment->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <h4 class="font-bold text-gray-900">{{ $comment->name }}</h4>
                                <span class="text-sm text-gray-400">{{ $comment->created_at->format('M d, Y') }}</span>
                            </div>
                            <p class="text-gray-700">{{ $comment->comment }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 italic">{{ __('general.no_comments_yet') }}</p>
                @endforelse
            </div>

            <!-- Comment Form -->
            <div class="mt-8 bg-white p-6 md:p-8 rounded-xl shadow-sm border border-gray-100">
                <h4 class="text-xl font-bold text-gray-900 mb-6">{{ __('general.leave_a_comment') }}</h4>
                <form action="{{ route('blog.comment', $blog) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('general.name') }} *</label>
                            <input type="text" name="name" id="name" required class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-rose-500 focus:border-rose-500 block p-2.5" value="{{ auth()->check() ? auth()->user()->name : '' }}">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('general.email') }} *</label>
                            <input type="email" name="email" id="email" required class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-rose-500 focus:border-rose-500 block p-2.5" value="{{ auth()->check() ? auth()->user()->email : '' }}">
                        </div>
                    </div>
                    <div>
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">{{ __('general.comment') }} *</label>
                        <textarea name="comment" id="comment" rows="4" required class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-rose-500 focus:border-rose-500 block p-2.5"></textarea>
                    </div>
                    <button type="submit" class="px-6 py-2.5 bg-rose-600 text-white font-medium rounded-lg hover:bg-rose-700 transition-colors">
                        {{ __('general.post_comment') }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Footer nav -->
        <div class="mt-12 pt-8 flex justify-between items-center">
            <a href="{{ route('blog.index') }}"
               class="inline-flex items-center gap-2 text-rose-600 hover:text-rose-700 font-medium">
                ← {{ __('general.blog') }}
            </a>
        </div>

    </div>
</div>
@endsection
