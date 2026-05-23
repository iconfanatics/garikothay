<div x-data="{ open: false }" class="relative">
    <button @click="open = !open"
        class="flex items-center gap-1.5 text-sm text-gray-600 hover:text-primary-600 transition font-medium">
        @if(app()->getLocale() === 'bn')
            <svg class="w-4 h-3 rounded-sm" viewBox="0 0 640 480" xmlns="http://www.w3.org/2000/svg"><path fill="#006a4e" d="M0 0h640v480H0z"/><circle cx="288" cy="240" r="160" fill="#f42a41"/></svg>
            <span>বাংলা</span>
        @else
            <svg class="w-4 h-3 rounded-sm" viewBox="0 0 640 480" xmlns="http://www.w3.org/2000/svg"><path fill="#012169" d="M0 0h640v480H0z"/><path fill="#FFF" d="m75 0 244 181L562 0h78v62L400 241l240 178v61h-80L320 301 81 480H0v-60l239-178L0 64V0h75z"/><path fill="#C8102E" d="m424 281 216 159v40L369 281h55zm-184 20 6 35L22 480H0v-25l240-154zM640 0v3L391 191l2-44L590 0h50zM0 0l239 176h-60L0 42V0z"/><path fill="#FFF" d="M241 0v480h160V0H241zM0 160v160h640V160H0z"/><path fill="#C8102E" d="M0 193v96h640v-96H0zM273 0v480h96V0h-96z"/></svg>
            <span>EN</span>
        @endif
        <svg class="w-3 h-3 transition" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>
    <div x-show="open" x-cloak @click.away="open = false" x-transition
        class="absolute right-0 mt-2 w-36 bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden z-50">
        <a href="{{ route('language.switch', 'bn') }}"
            class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-gray-50 transition
                   {{ app()->getLocale() === 'bn' ? 'text-primary-600 font-semibold bg-primary-50' : 'text-gray-700' }}">
            <svg class="w-4 h-3 rounded-sm" viewBox="0 0 640 480" xmlns="http://www.w3.org/2000/svg"><path fill="#006a4e" d="M0 0h640v480H0z"/><circle cx="288" cy="240" r="160" fill="#f42a41"/></svg>
            বাংলা
        </a>
        <a href="{{ route('language.switch', 'en') }}"
            class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-gray-50 transition
                   {{ app()->getLocale() === 'en' ? 'text-primary-600 font-semibold bg-primary-50' : 'text-gray-700' }}">
            <svg class="w-4 h-3 rounded-sm" viewBox="0 0 640 480" xmlns="http://www.w3.org/2000/svg"><path fill="#012169" d="M0 0h640v480H0z"/><path fill="#FFF" d="m75 0 244 181L562 0h78v62L400 241l240 178v61h-80L320 301 81 480H0v-60l239-178L0 64V0h75z"/><path fill="#C8102E" d="m424 281 216 159v40L369 281h55zm-184 20 6 35L22 480H0v-25l240-154zM640 0v3L391 191l2-44L590 0h50zM0 0l239 176h-60L0 42V0z"/><path fill="#FFF" d="M241 0v480h160V0H241zM0 160v160h640V160H0z"/><path fill="#C8102E" d="M0 193v96h640v-96H0zM273 0v480h96V0h-96z"/></svg>
            English
        </a>
    </div>
</div>
