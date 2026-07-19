<x-filament-widgets::widget>
    <x-filament::section heading="Recent Activities" description="Latest events across the store">
        <div class="space-y-4">
            @forelse($activities as $activity)
                @php
                    $colorClass = match($activity['color']) {
                        'success' => 'text-success-600 bg-success-50 dark:bg-success-500/10 dark:text-success-400',
                        'danger' => 'text-danger-600 bg-danger-50 dark:bg-danger-500/10 dark:text-danger-400',
                        'warning' => 'text-warning-600 bg-warning-50 dark:bg-warning-500/10 dark:text-warning-400',
                        'info' => 'text-info-600 bg-info-50 dark:bg-info-500/10 dark:text-info-400',
                        default => 'text-primary-600 bg-primary-50 dark:bg-primary-500/10 dark:text-primary-400',
                    };
                @endphp
                <div class="flex items-start gap-4">
                    <div @class(['p-2 rounded-lg', $colorClass])>
                        <x-dynamic-component :component="$activity['icon']" class="w-5 h-5" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $activity['title'] }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                            {{ $activity['description'] }}
                        </p>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 shrink-0">
                        {{ $activity['time']->diffForHumans() }}
                    </div>
                </div>
            @empty
                <div class="text-center text-sm text-gray-500 py-4">
                    No recent activities found.
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
