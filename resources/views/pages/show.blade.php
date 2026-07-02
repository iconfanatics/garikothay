@extends('layouts.app')

@section('title', $page->title . ' - ' . config('app.name'))

@section('content')
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 md:p-12">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">{{ $page->title }}</h1>
                
                <div class="prose prose-blue max-w-none text-gray-600">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
