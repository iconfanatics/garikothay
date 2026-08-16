@props(['location' => 'site_wide'])

@php
    $announcements = \App\Models\Announcement::where('is_active', true)
        ->where(function($q) use ($location) {
            $q->where('display_location', $location)
              ->orWhere('display_location', 'site_wide');
        })
        ->where(function($q) {
            $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
        })
        ->where(function($q) {
            $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
        })
        ->orderBy('priority', 'desc')
        ->orderBy('created_at', 'desc')
        ->get();
@endphp

@if($announcements->isNotEmpty())
    <div class="w-full relative z-50">
        @foreach($announcements as $announcement)
            @php
                $bgClass = match($announcement->type) {
                    'warning' => 'bg-amber-500 text-amber-950',
                    'promo'   => 'bg-rose-500 text-white',
                    default   => 'bg-blue-600 text-white',
                };
            @endphp
            <div x-data="{ 
                    show: false,
                    announcementId: 'announcement_{{ $announcement->id }}',
                    init() {
                        if (!localStorage.getItem(this.announcementId)) {
                            this.show = true;
                        }
                    },
                    dismiss() {
                        this.show = false;
                        localStorage.setItem(this.announcementId, 'dismissed');
                    }
                }" 
                x-show="show" 
                class="{{ $bgClass }} px-4 py-2.5 flex flex-col sm:flex-row justify-center items-center gap-2 sm:gap-4 text-sm sm:text-base font-medium shadow-sm transition-all duration-300 relative"
                style="display: none;"
            >
                <div class="flex-1 text-center pr-8 sm:pr-4 flex flex-col sm:flex-row items-center justify-center gap-2">
                    <span>
                        @if($announcement->title)
                            <strong class="mr-1">{{ $announcement->title }}:</strong>
                        @endif
                        {!! strip_tags($announcement->content) !!}
                    </span>
                    @if($announcement->button_url && $announcement->button_text)
                        <a href="{{ $announcement->button_url }}" 
                           @if($announcement->open_in_new_tab) target="_blank" rel="noopener noreferrer" @endif
                           class="inline-block px-3 py-1 bg-black/20 hover:bg-black/30 rounded text-sm transition-colors whitespace-nowrap">
                            {{ $announcement->button_text }}
                        </a>
                    @endif
                </div>
                <button @click="dismiss()" class="absolute right-2 sm:relative sm:right-auto opacity-80 hover:opacity-100 transition-opacity focus:outline-none p-1 rounded-full hover:bg-black/10" aria-label="Close Announcement">
                    <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        @endforeach
    </div>
@endif
