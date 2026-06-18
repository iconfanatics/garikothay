@extends('layouts.app')

@section('title', $query ? __('general.search_results_for') . ": {$query}" : __('general.search'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    @if($query)
    <p class="text-gray-500 mb-6">
        @if($results instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{ $results->total() }} {{ __('general.products_found') }} "<span class="font-medium text-gray-700">{{ $query }}</span>"
            @if($selectedCategory)
                in <span class="font-medium text-gray-700">{{ $selectedCategory->name }}</span>
            @endif
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
    <div class="mt-8">{{ $results->appends(['q' => $query, 'category' => $categorySlug])->links() }}</div>
    @else
    <div class="text-center py-20 text-gray-400">
        <p>{{ __('general.no_products_found') }}</p>
    </div>
    @endif

    @endif

</div>
@endsection
