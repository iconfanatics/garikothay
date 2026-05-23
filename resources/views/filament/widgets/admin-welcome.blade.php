<x-filament-widgets::widget>
    <div class="space-y-6">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-700 via-green-700 to-lime-700 p-6 text-white shadow-xl ring-1 ring-white/10 md:p-8">
            <div class="absolute -right-10 -top-10 h-44 w-44 rounded-full bg-white/10 blur-2xl"></div>
            <div class="absolute -bottom-16 left-1/3 h-48 w-48 rounded-full bg-lime-300/20 blur-3xl"></div>

            <div class="relative grid gap-8 lg:grid-cols-[1.35fr_1fr] lg:items-center">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-50 ring-1 ring-white/20">
                        <x-heroicon-o-sparkles class="h-4 w-4" />
                        {{ \App\Models\Setting::get('site_name', 'Dinajpur IT Park') }} Admin
                    </div>

                    <h1 class="mt-5 text-3xl font-bold tracking-tight md:text-4xl">
                        Welcome back, {{ $adminName }}
                    </h1>

                    <p class="mt-3 max-w-2xl text-sm leading-6 text-emerald-50 md:text-base">
                        Monitor orders, revenue, customers, inventory, and store activity from one calm and focused workspace.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ \App\Filament\Resources\OrderResource::getUrl('index') }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-emerald-700 shadow-sm transition hover:bg-emerald-50">
                            <x-heroicon-o-shopping-bag class="h-5 w-5" />
                            View Orders
                        </a>
                        <a href="{{ \App\Filament\Resources\ProductResource::getUrl('create') }}" class="inline-flex items-center gap-2 rounded-xl bg-white/15 px-4 py-2.5 text-sm font-semibold text-white ring-1 ring-white/25 transition hover:bg-white/20">
                            <x-heroicon-o-plus-circle class="h-5 w-5" />
                            Add Product
                        </a>
                    </div>
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <div class="rounded-2xl bg-white/15 p-4 ring-1 ring-white/20 backdrop-blur">
                        <p class="text-xs font-medium uppercase tracking-wide text-emerald-100">Today Orders</p>
                        <p class="mt-2 text-3xl font-bold">{{ number_format($todayOrders) }}</p>
                    </div>
                    <div class="rounded-2xl bg-white/15 p-4 ring-1 ring-white/20 backdrop-blur">
                        <p class="text-xs font-medium uppercase tracking-wide text-emerald-100">Pending Orders</p>
                        <p class="mt-2 text-3xl font-bold">{{ number_format($pendingOrders) }}</p>
                    </div>
                    <div class="rounded-2xl bg-white/15 p-4 ring-1 ring-white/20 backdrop-blur">
                        <p class="text-xs font-medium uppercase tracking-wide text-emerald-100">Month Revenue</p>
                        <p class="mt-2 text-2xl font-bold">৳{{ number_format((float) $monthlyRevenue, 2) }}</p>
                    </div>
                    <div class="rounded-2xl bg-white/15 p-4 ring-1 ring-white/20 backdrop-blur">
                        <p class="text-xs font-medium uppercase tracking-wide text-emerald-100">Low Stock</p>
                        <p class="mt-2 text-3xl font-bold">{{ number_format($lowStockProducts) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($quickActions as $action)
                @php
                    $colorClasses = match ($action['color']) {
                        'emerald' => 'bg-emerald-50 text-emerald-700 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-500/20',
                        'green' => 'bg-green-50 text-green-700 ring-green-200 dark:bg-green-500/10 dark:text-green-300 dark:ring-green-500/20',
                        'teal' => 'bg-teal-50 text-teal-700 ring-teal-200 dark:bg-teal-500/10 dark:text-teal-300 dark:ring-teal-500/20',
                        default => 'bg-lime-50 text-lime-700 ring-lime-200 dark:bg-lime-500/10 dark:text-lime-300 dark:ring-lime-500/20',
                    };
                @endphp

                <a href="{{ $action['url'] }}" class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 transition hover:-translate-y-0.5 hover:shadow-md dark:bg-gray-900 dark:ring-white/10">
                    <div class="flex items-start gap-4">
                        <div @class(['rounded-xl p-3 ring-1', $colorClasses])>
                            <x-dynamic-component :component="$action['icon']" class="h-6 w-6" />
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-semibold text-gray-950 group-hover:text-emerald-700 dark:text-white dark:group-hover:text-emerald-300">
                                {{ $action['label'] }}
                            </h3>
                            <p class="mt-1 text-sm leading-5 text-gray-500 dark:text-gray-400">
                                {{ $action['description'] }}
                            </p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</x-filament-widgets::widget>
