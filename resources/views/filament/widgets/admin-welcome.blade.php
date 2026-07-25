<x-filament-widgets::widget>
    <div class="space-y-6">
        <x-filament::section>
            <div class="flex items-center gap-x-4">
                <div class="flex-1">
                    <h2 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">
                        Welcome back, {{ $adminName }}
                    </h2>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        {{ now()->timezone('Asia/Dhaka')->format('l, jS F Y, h:i A') }} (Dhaka Time) &bull;
                        Last Login: {{ $lastLogin }}
                        @if($lastLogin !== 'First Login')
                            &bull; IP: {{ $lastLoginIp }}
                        @endif
                    </p>
                    <p class="mt-4 max-w-2xl text-sm leading-6 text-gray-500 dark:text-gray-400">
                        Monitor orders, revenue, customers, inventory, and store activity from one calm and focused workspace.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <x-filament::button
                            tag="a"
                            href="{{ \App\Filament\Resources\OrderResource::getUrl('index') }}"
                            icon="heroicon-o-shopping-bag"
                        >
                            View Orders
                        </x-filament::button>

                        <x-filament::button
                            tag="a"
                            href="{{ \App\Filament\Resources\ProductResource::getUrl('create') }}"
                            icon="heroicon-o-plus-circle"
                            color="gray"
                        >
                            Add Product
                        </x-filament::button>
                    </div>
                </div>
            </div>
        </x-filament::section>

        <x-filament::grid default="1" md="2" xl="4" class="gap-4">
            @foreach ($quickActions as $action)
                @php
                    $color = match ($action['color']) {
                        'emerald', 'green' => 'success',
                        'teal' => 'info',
                        default => 'primary',
                    };
                @endphp

                <x-filament::section>
                    <a href="{{ $action['url'] }}" class="flex items-start gap-4">
                        <x-filament::icon-button
                            :icon="$action['icon']"
                            :color="$color"
                            size="lg"
                            class="pointer-events-none"
                        />
                        <div class="min-w-0">
                            <h3 class="font-semibold text-gray-950 dark:text-white">
                                {{ $action['label'] }}
                            </h3>
                            <p class="mt-1 text-sm leading-5 text-gray-500 dark:text-gray-400">
                                {{ $action['description'] }}
                            </p>
                        </div>
                    </a>
                </x-filament::section>
            @endforeach
        </x-filament::grid>
    </div>
</x-filament-widgets::widget>
