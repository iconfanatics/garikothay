@extends('layouts.app')

@section('title', __('general.blog') . ' - ' . config('app.name'))
@section('meta_description', __('general.plant_care_tips'))
@section('og_title', __('general.blog') . ' - ' . config('app.name'))
@section('og_description', __('general.plant_care_tips'))

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-rose-700 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold">{{ __('general.blog') }}</h1>
            <p class="mt-2 text-rose-200">{{ __('general.plant_care_tips') }}</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col lg:flex-row gap-8">

            <!-- Posts grid -->
            <main class="flex-1">
                @if($blogs->count())
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($blogs as $blog)
                        <article class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition group">
                            @if($blog->featured_image)
                            <div class="overflow-hidden h-48">
                                <img src="{{ $blog->featured_image_url }}"
                                     alt="{{ $blog->getTranslation('title') }}"
                                     onerror="this.onerror=null;this.src='{{ asset('images/product-placeholder.svg') }}';"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            </div>
                            @else
                            <div class="h-48 bg-gradient-to-br from-rose-100 to-rose-200 flex items-center justify-center">
                                <svg class="w-16 h-16 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            @endif

                            <div class="p-5">
                                @if($blog->category)
                                <span class="inline-block bg-rose-100 text-rose-700 text-xs font-medium px-2.5 py-1 rounded-full mb-3">
                                    {{ $blog->category->getTranslation('name') }}
                                </span>
                                @endif

                                <h2 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-rose-600 transition">
                                    <a href="{{ route('blog.show', $blog->slug) }}">
                                        {{ $blog->getTranslation('title') }}
                                    </a>
                                </h2>

                                <p class="text-gray-500 text-sm line-clamp-3 mb-4">
                                    {{ $blog->getTranslation('excerpt') }}
                                </p>

                                <div class="flex items-center justify-between text-sm text-gray-400">
                                    <span>{{ $blog->published_at?->format('M d, Y') }}</span>
                                    <a href="{{ route('blog.show', $blog->slug) }}"
                                       class="text-rose-600 hover:text-rose-700 font-medium">
                                        {{ __('general.read_more') }} →
                                    </a>
                                </div>
                            </div>
                        </article>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $blogs->links() }}
                    </div>
                @else
                <div class="text-center py-20 text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <p>{{ __('general.no_posts_yet') }}</p>
                </div>
                @endif
            </main>

            <!-- Sidebar -->
            <aside class="lg:w-64 space-y-6">
                <div class="bg-white rounded-2xl p-5 shadow-sm">
                    <h3 class="font-semibold text-gray-800 mb-4">{{ __('general.categories') }}</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('blog.index') }}"
                               class="flex justify-between items-center text-sm py-1 {{ !request('category') ? 'text-rose-600 font-medium' : 'text-gray-600 hover:text-rose-600' }}">
                                {{ __('general.all_posts') }}
                                <span class="bg-gray-100 text-gray-500 text-xs px-2 py-0.5 rounded-full">{{ $blogs->total() }}</span>
                            </a>
                        </li>
                        @foreach($categories as $cat)
                        <li>
                            <a href="{{ route('blog.index', ['category' => $cat->slug]) }}"
                               class="flex justify-between items-center text-sm py-1 {{ request('category') === $cat->slug ? 'text-rose-600 font-medium' : 'text-gray-600 hover:text-rose-600' }}">
                                {{ $cat->getTranslation('name') }}
                                <span class="bg-gray-100 text-gray-500 text-xs px-2 py-0.5 rounded-full">{{ $cat->blogs_count }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </aside>

        </div>
    </div>
</div>
@endsection
