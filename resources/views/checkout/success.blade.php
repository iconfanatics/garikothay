@extends('layouts.app')
@section('title', __('Order Confirmed') . ' | ' . \App\Models\Setting::get('site_name', 'Garikothay'))

@section('content')
<div class="min-h-[80vh] bg-gradient-to-br from-rose-50 via-white to-rose-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    
    <!-- Background Decorative Elements -->
    <div class="absolute top-0 left-0 w-64 h-64 bg-rose-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
    <div class="absolute top-0 right-0 w-64 h-64 bg-green-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-8 left-20 w-64 h-64 bg-rose-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>

    <div class="max-w-3xl w-full relative z-10">
        <!-- Main Success Card -->
        <div class="bg-white/80 backdrop-blur-xl border border-white/50 rounded-3xl shadow-xl shadow-gray-200/50 p-8 md:p-12 text-center mb-8 transform transition-all hover:shadow-2xl">
            
            <div class="inline-flex items-center justify-center w-20 h-20 bg-rose-100 text-rose-600 rounded-2xl mb-6 shadow-inner relative overflow-hidden group">
                <div class="absolute inset-0 bg-rose-200 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out"></div>
                <svg class="w-10 h-10 relative z-10 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            
            <h1 class="text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-rose-700 to-rose-500 mb-4">{{ __('Order Placed Successfully!') }}</h1>
            <p class="text-gray-600 text-lg mb-2">{{ __('Thank you for your order. We\'ll start preparing it right away!') }}</p>
            <div class="inline-block bg-rose-50 border border-rose-100 text-rose-800 px-6 py-2 rounded-full font-semibold mt-2 mb-8 shadow-sm">
                {{ __('Order #') }} <span class="font-bold">{{ $order->order_number }}</span>
            </div>

            <div class="grid md:grid-cols-2 gap-8 text-left mt-4">
                <!-- Order Details -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:border-rose-200 transition-colors">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="p-2 bg-rose-50 text-rose-600 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg></div>
                        <h2 class="font-bold text-gray-800 text-lg">{{ __('Order Summary') }}</h2>
                    </div>
                    
                    <div class="space-y-4 max-h-[250px] overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($order->items as $item)
                        <div class="flex items-start gap-4 p-3 rounded-xl hover:bg-gray-50 transition">
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold text-gray-800 line-clamp-2">{{ $item->product_name }}</h4>
                                <p class="text-xs text-gray-500 mt-1">{{ __('Qty') }}: {{ $item->quantity }}</p>
                            </div>
                            <span class="font-bold text-rose-700">৳{{ number_format($item->total_price, 0) }}</span>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-dashed border-gray-200">
                        <div class="flex justify-between font-black text-gray-900 text-lg px-2">
                            <span>{{ __('Total Amount') }}</span>
                            <span class="text-rose-600">৳{{ number_format($order->total, 0) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:border-rose-200 transition-colors relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-rose-50 rounded-full opacity-50"></div>
                    <div class="flex items-center gap-3 mb-6 relative z-10">
                        <div class="p-2 bg-rose-50 text-rose-600 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                        <h2 class="font-bold text-gray-800 text-lg">{{ __('What Happens Next?') }}</h2>
                    </div>
                    
                    <div class="relative pl-3 relative z-10">
                        <div class="absolute left-[15px] top-4 bottom-4 w-0.5 bg-gray-100"></div>
                        <div class="space-y-6">
                            @foreach(['Order Confirmed' => true, 'Being Prepared' => false, 'Shipped' => false, 'Delivered' => false] as $step => $done)
                            <div class="flex items-center gap-4 relative group">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $done ? 'bg-gradient-to-br from-rose-500 to-rose-600 text-white shadow-md shadow-rose-500/40 ring-4 ring-white' : 'bg-gray-100 text-gray-400 ring-4 ring-white' }} text-sm font-bold shrink-0 z-10 transition-transform group-hover:scale-110">
                                    @if($done)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    @else
                                    <div class="w-2 h-2 rounded-full bg-gray-300"></div>
                                    @endif
                                </div>
                                <span class="{{ $done ? 'text-gray-900 font-bold' : 'text-gray-500 font-medium' }}">{{ __($step) }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mt-10">
                @auth
                <a href="{{ route('customer.order.show', $order->order_number) }}" class="bg-rose-600 text-white px-8 py-4 rounded-xl font-bold hover:bg-rose-700 shadow-lg shadow-rose-500/30 transition-all transform hover:-translate-y-1 active:translate-y-0 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    {{ __('Track Order') }}
                </a>
                @endauth
                <a href="{{ route('shop.index') }}" class="bg-white border-2 border-rose-100 text-rose-700 px-8 py-4 rounded-xl font-bold hover:bg-rose-50 hover:border-rose-200 transition-all transform hover:-translate-y-1 active:translate-y-0 flex items-center justify-center gap-2 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    {{ __('Continue Shopping') }}
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    .animation-delay-4000 {
        animation-delay: 4s;
    }
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endsection
