<script>
    if (!window.translations) {
        window.translations = @json($navTranslations);
    }
</script>

{{-- Main Navigation Bar --}}
<nav x-data="{
    open: false,
    scrolled: false,
    forceSolid: {{ $isProfilePage ? 'true' : 'false' }},
    activeSection: 'home',
    init() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.activeSection = entry.target.id;
                }
            });
        }, { rootMargin: '-80px 0px -60% 0px' });

        document.querySelectorAll('section[id]').forEach(section => {
            observer.observe(section);
        });
    }
}" @scroll.window="scrolled = (window.pageYOffset > 20)"
    :class="{
        'bg-white/90 backdrop-blur-md shadow-sm': scrolled || forceSolid,
        'bg-transparent text-white': !(scrolled || forceSolid)
    }"
    class="fixed w-full z-50 transition-all duration-300 top-0 left-0">

    {{-- Navbar Container (Wider on 2xl and 3xl screens) --}}
    <div class="max-w-7xl 2xl:max-w-[1700px] 3xl:max-w-[2100px] mx-auto px-4 sm:px-6 lg:px-8 3xl:px-16">
        <div class="flex items-center justify-between h-16 lg:h-20 2xl:h-24 3xl:h-32 gap-3 lg:gap-6">
            
            {{-- 1. Brand Logo --}}
            <div class="flex shrink-0">
                <a href="{{ route($logoRoute) }}" class="flex items-center gap-2.5 group">
                    <img src="{{ asset('storage/tesachor_transparent_logo.png') }}" alt="Tesachor Logo"
                        class="h-8 lg:h-10 2xl:h-12 3xl:h-16 w-auto object-contain transition-transform duration-300 group-hover:scale-105 drop-shadow-sm">
                </a>
            </div>

            {{-- 2. Center Menu Links (Tablet, Desktop & 4K) --}}
            <div class="hidden md:flex items-center md:gap-4 lg:gap-8 2xl:gap-12 3xl:gap-16">
                @foreach ($navItems as $navItem)
                    <a href="#{{ $navItem['id'] }}" @click="activeSection = '{{ $navItem['id'] }}'"
                        class="text-xs lg:text-sm 2xl:text-base 3xl:text-xl font-medium whitespace-nowrap shrink-0 transition-colors duration-200 relative group"
                        :class="activeSection == '{{ $navItem['id'] }}' ? 'text-blue-500' : ((scrolled || forceSolid) ?
                            'text-gray-900' : 'text-gray-100')">
                        <span x-text="$store.i18n.t('navbar.{{ $navItem['id'] }}')">{{ $navItem['label'] }}</span>
                        {{-- Active / Hover Underline --}}
                        <span
                            class="absolute -bottom-1 3xl:-bottom-2 left-0 w-0 h-0.5 3xl:h-1 bg-blue-500 transition-all duration-300 group-hover:w-full"
                            :class="{ 'w-full': activeSection === '{{ $navItem['id'] }}' }"></span>
                    </a>
                @endforeach
            </div>

            {{-- 3. Right Side: Language Switcher (Tablet, Desktop & 4K) --}}
            <div class="hidden md:flex md:items-center md:ms-4 lg:ms-8 md:gap-3 lg:gap-6 2xl:gap-8 3xl:gap-10 shrink-0">
                {{-- Language Switcher (EN / KM) --}}
                <div class="inline-flex items-center p-1 3xl:p-1.5 rounded-xl 3xl:rounded-2xl transition-all duration-300 shrink-0"
                    :class="(scrolled || forceSolid) ? 'bg-gray-100 text-gray-700 border border-gray-200/60' :
                    'bg-white/15 backdrop-blur-md text-white border border-white/20'">
                    {{-- Translate Icon --}}
                    <span class="px-1.5 lg:px-2.5 3xl:px-3.5 py-1 flex items-center opacity-80"
                        :class="(scrolled || forceSolid) ? 'text-gray-500' : 'text-white'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 lg:w-4 lg:h-4 3xl:w-6 3xl:h-6" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m5 8 6 6" />
                            <path d="m4 14 6-6 2-3" />
                            <path d="M2 5h12" />
                            <path d="M7 2h1" />
                            <path d="m22 22-5-10-5 10" />
                            <path d="M14 18h6" />
                        </svg>
                    </span>

                    {{-- English Button --}}
                    <button type="button" @click="$store.i18n.switchLang('en')"
                        class="px-2 lg:px-3 3xl:px-4 py-1 3xl:py-1.5 text-[11px] lg:text-xs 2xl:text-sm 3xl:text-lg rounded-md 3xl:rounded-xl transition-all duration-300 ease-out cursor-pointer select-none whitespace-nowrap"
                        :class="$store.i18n.activeButton === 'en' ?
                            'bg-white text-gray-900 shadow-sm font-semibold' :
                            ((scrolled || forceSolid) ? 'text-gray-500 hover:text-gray-900 font-medium' :
                                'text-white/80 hover:text-white font-medium')">
                        En
                    </button>

                    {{-- Khmer Button --}}
                    <button type="button" @click="$store.i18n.switchLang('km')"
                        class="px-2 lg:px-3 3xl:px-4 py-1 3xl:py-1.5 text-[11px] lg:text-xs 2xl:text-sm 3xl:text-lg rounded-md 3xl:rounded-xl transition-all duration-300 ease-out cursor-pointer select-none whitespace-nowrap"
                        :class="$store.i18n.activeButton === 'km' ?
                            'bg-white text-gray-900 shadow-sm font-semibold' :
                            ((scrolled || forceSolid) ? 'text-gray-500 hover:text-gray-900 font-medium' :
                                'text-white/80 hover:text-white font-medium')">
                        Km
                    </button>
                </div>
            </div>

            {{-- 4. Mobile Hamburger Button (Shows only on Mobile) --}}
            <div class="-mr-2 flex items-center md:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md focus:outline-none transition duration-150 ease-in-out"
                    :class="{ 'text-gray-600': scrolled || forceSolid, 'text-gray-100': !(scrolled || forceSolid) }">

                    {{-- 3-Line Hamburger Icon (Animates into X when clicked) --}}
                    <div class="relative w-6 h-5">
                        <span
                            class="absolute top-1/2 left-0 block w-full h-0.5 bg-current transform transition-all duration-300 ease-in-out"
                            style="transform-origin: center;"
                            :class="{ 'rotate-45': open, '-translate-y-2': !open, '-translate-y-1/2': open }"></span>
                        <span
                            class="absolute top-1/2 left-0 block w-full h-0.5 bg-current transform -translate-y-1/2 transition-all duration-300 ease-in-out"
                            :class="{ 'opacity-0': open }"></span>
                        <span
                            class="absolute top-1/2 left-0 block w-full h-0.5 bg-current transform transition-all duration-300 ease-in-out"
                            style="transform-origin: center;"
                            :class="{ '-rotate-45': open, 'translate-y-1.5': !open, '-translate-y-1/2': open }"></span>
                    </div>
                </button>
            </div>
        </div>
    </div>

    {{-- 5. Mobile Dropdown Menu --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="md:hidden bg-white text-gray-800 shadow-lg border-b border-gray-100" style="display: none;">

        {{-- Mobile Navigation Links --}}
        <div class="pb-3 py-2">
            @foreach ($navItems as $navItem)
                <a href="#{{ $navItem['id'] }}" @click="open = false"
                    class="block w-full ps-3 pe-4 py-2 border-l-4 text-start text-base transition duration-150 ease-in-out"
                    :class="activeSection === '{{ $navItem['id'] }}'
                        ?
                        'border-blue-500 text-blue-700 bg-blue-50' :
                        'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300'">
                    <span x-text="$store.i18n.t('navbar.{{ $navItem['id'] }}')">{{ $navItem['label'] }}</span>
                </a>
            @endforeach
        </div>

        {{-- Mobile Language Switcher --}}
        <div class="border-t border-gray-100 px-4 py-3 flex items-center justify-between">
            <span class="text-sm font-medium text-gray-600"
                x-text="$store.i18n.t('navbar.language')">{{ __('navbar.language') }}</span>
            <div class="inline-flex items-center p-1 rounded-xl bg-gray-100 text-gray-700 border border-gray-200/60">
                <span class="px-2 py-1 flex items-center text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m5 8 6 6" />
                        <path d="m4 14 6-6 2-3" />
                        <path d="M2 5h12" />
                        <path d="M7 2h1" />
                        <path d="m22 22-5-10-5 10" />
                        <path d="M14 18h6" />
                    </svg>
                </span>
                <button type="button" @click="$store.i18n.switchLang('en')"
                    class="px-2.5 py-1 text-xs rounded-md transition-all duration-300 ease-out cursor-pointer select-none"
                    :class="$store.i18n.activeButton === 'en' ? 'bg-white text-gray-900 shadow-sm font-semibold' :
                        'text-gray-500 hover:text-gray-900 font-medium'">
                    EN
                </button>
                <button type="button" @click="$store.i18n.switchLang('km')"
                    class="px-2.5 py-1 text-xs rounded-md transition-all duration-300 ease-out cursor-pointer select-none"
                    :class="$store.i18n.activeButton === 'km' ? 'bg-white text-gray-900 shadow-sm font-semibold' :
                        'text-gray-500 hover:text-gray-900 font-medium'">
                    KM
                </button>
            </div>
        </div>
    </div>

</nav>