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

    <div class="space-y-4">
        @forelse($activities as $activity)
            <div class="relative">
                <!-- Card -->
                <div class="w-full p-4 rounded-xl border border-slate-200 dark:border-white/10 bg-white dark:bg-gray-900 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-2 mb-1">
                        <div class="font-bold text-slate-900 dark:text-white">{{ $activity->description }}</div>
                        <time class="font-medium text-slate-500 dark:text-gray-400 text-xs whitespace-nowrap">{{ $activity->created_at->diffForHumans() }}</time>
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
