<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    @php
        $record = $getRecord();
        $activities = \Spatie\Activitylog\Models\Activity::where(function($query) use ($record) {
                $query->where('causer_id', $record->id)->where('causer_type', get_class($record));
            })
            ->orWhere(function($query) use ($record) {
                $query->where('subject_id', $record->id)->where('subject_type', get_class($record));
            })
            // We also want to include Orders and Payments for this user
            ->orWhere(function($query) use ($record) {
                $query->where('subject_type', \App\Models\Order::class)
                      ->whereIn('subject_id', $record->orders()->pluck('id'));
            })
            ->orWhere(function($query) use ($record) {
                $query->where('subject_type', \App\Models\Payment::class)
                      ->whereIn('subject_id', $record->payments()->pluck('payments.id')); // need to specify table for id since hasManyThrough
            })
            ->latest()
            ->limit(20)
            ->get();
    @endphp

    <div class="relative pl-4 space-y-6 before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-300 before:to-transparent">
        @forelse($activities as $activity)
            <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                <!-- Icon -->
                <div class="flex items-center justify-center w-8 h-8 rounded-full border border-white bg-primary-500 text-slate-100 shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 shadow">
                    @if($activity->event === 'login')
                        <x-heroicon-o-arrow-right-on-rectangle class="w-4 h-4"/>
                    @elseif($activity->event === 'created')
                        <x-heroicon-o-plus class="w-4 h-4"/>
                    @elseif($activity->event === 'updated')
                        <x-heroicon-o-pencil class="w-4 h-4"/>
                    @else
                        <x-heroicon-o-bolt class="w-4 h-4"/>
                    @endif
                </div>
                <!-- Card -->
                <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] p-4 rounded-xl border border-slate-200 dark:border-white/10 bg-white dark:bg-gray-900 shadow-sm">
                    <div class="flex items-center justify-between space-x-2 mb-1">
                        <div class="font-bold text-slate-900 dark:text-white">{{ $activity->description }}</div>
                        <time class="font-caveat font-medium text-slate-500 dark:text-gray-400 text-xs">{{ $activity->created_at->diffForHumans() }}</time>
                    </div>
                    <div class="text-sm text-slate-500 dark:text-gray-400">
                        @if($activity->subject_type === \App\Models\Order::class)
                            Order Activity <span class="text-primary-500 font-semibold">#{{ $activity->subject?->order_number ?? $activity->subject_id }}</span>
                        @elseif($activity->subject_type === \App\Models\Payment::class)
                            Payment Activity
                        @else
                            Profile Activity
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-sm text-slate-500 dark:text-gray-400 py-4">No recent activity found for this customer.</div>
        @endforelse
    </div>
</x-dynamic-component>
