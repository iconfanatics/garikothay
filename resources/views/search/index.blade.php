@extends('layouts.app')

@section('title', $query ? __('general.search_results_for') . ": {$query}" : __('general.search'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Search bar -->
    <form method="GET" action="{{ route('search.index') }}" class="mb-8">
        <div class="flex gap-3 max-w-xl">
            <input type="text" name="q" value="{{ $query }}"
                   placeholder="{{ __('general.search_placeholder') }}"
                   class="flex-1 px-5 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
            <button type="submit"
                    class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-xl transition font-medium">
                {{ __('general.search') }}
            </button>
        </div>
    </form>

    @if($query)
    <p class="text-gray-500 mb-6">
        @if($results instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{ $results->total() }} {{ __('general.products_found') }} "<span class="font-medium text-gray-700">{{ $query }}</span>"
        @else
            {{ __('general.no_results_for') }} "<span class="font-medium text-gray-700">{{ $query }}</span>"
        @endif
    </p>

    @if($results->count())
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
        @foreach($results as $product)
        <x-product-card :product="$product" />
        @endforeach
    </div>
    <div class="mt-8">{{ $results->appends(['q' => $query])->links() }}</div>
    @else
    <div class="text-center py-20 text-gray-400">
        <p>{{ __('general.no_products_found') }}</p>
    </div>
    @endif

    @endif

</div>
@endsection
