@php
    // 1. Initialize variables
    $user = null;
    $dashboard = 'users.login';
    $profile = 'profile.edit';
    $logout = 'logout';

    // 2. Check for Admin first
    if (Auth::check() && Auth::user()->hasRole('admin')) {
        $user = Auth::user();
        $dashboard = 'admins.dashboard';
        $profile = 'admins.profile.edit';
        $logout = 'logout';
    }
    // 3. Check for Regular User second
    elseif (Auth::check()) {
        $user = Auth::user();
        $dashboard = 'users.dashboard';
        $profile = 'profile.edit';
        $logout = 'logout';
    }
    $isProfilePage = request()->routeIs('*.profile.*', 'profile.*');
@endphp

{{-- *Navigation bar* --}}
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
}" @scroll.window="scrolled = (window.pageYOffset > 20)" :class="{
        'bg-white/90 backdrop-blur-md shadow-sm': scrolled || forceSolid,
        'bg-transparent text-white': !(scrolled || forceSolid)
    }" class="fixed w-full z-50 transition-all duration-300 top-0 left-0">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            {{-- *Logo* --}}
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    @php
                        // Determine the logo link based on authentication status
                        $logoRoute = 'guests.dashboard'; // Default for non-users (guests)
                        if (Auth::check() && Auth::user()->hasRole('admin')) {
                            $logoRoute = 'admins.dashboard';
                        } elseif (Auth::check()) {
                            $logoRoute = 'users.dashboard';
                        }
                    @endphp
                    <a href="{{ route($logoRoute) }}" class="font-serif text-2xl font-bold tracking-wider"
                        :class="{ 'text-gray-900': scrolled || forceSolid, 'text-gray-100': !(scrolled || forceSolid) }">
                        Tesachor
                    </a>
                </div>
            </div>

            {{-- *Center Menu Items (Desktop)* --}}
            <div class="hidden sm:flex space-x-8 items-center">
                @foreach (['Home', 'About', 'Travel', 'Provinces', 'Life Style'] as $item)
                    @php $targetId = strtolower(str_replace(' ', '', $item)); @endphp
                    <a href="#{{ $targetId }}" @click="activeSection = '{{ $targetId }}'"
                        class="text-sm transition-colors duration-200 relative group" :class="activeSection == '{{ $targetId }}' ? 'text-red-500' : ((scrolled || forceSolid) ?
                                'text-grey-900' : 'text-grey-100')">
                        {{ $item }} <span
                            class="absolute -bottom-1 left-0 w-0 h-0.5 bg-red-500 transition-all duration-300 group-hover:w-full"
                            :class="{ 'w-full': activeSection === '{{ $targetId }}' }"></span>
                    </a>
                @endforeach
            </div>

            {{-- *Right Side (Desktop)* --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @if ($user)
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center gap-2 px-3 py-1.5 text-sm leading-4 hover:text-red-500 focus:outline-none transition ease-in-out duration-150"
                                :class="{ 'text-gray-600': scrolled || forceSolid, 'text-gray-100': !(scrolled || forceSolid) }">
                                @if ($user->avatar_url)
                                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-7 h-7 rounded-full object-cover border border-gray-200">
                                @else
                                    <span class="w-7 h-7 rounded-full bg-red-100 text-red-600 text-xs font-bold flex items-center justify-center">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                @endif
                                <div>{{ $user->name }}</div>
                                <div class="ms-0.5">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route($profile)">{{ __('Profile') }}</x-dropdown-link>
                            <form method="POST" action="{{ route($logout) }}">
                                @csrf
                                <x-dropdown-link :href="route($logout)"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-4">
                        <a href="{{ route('login') }}" class="text-sm hover:underline"
                            :class="{ 'text-gray-800': scrolled || forceSolid, 'text-white': !(scrolled || forceSolid) }">
                            Log in
                        </a>
                        <a href="{{ route('register') }}"
                            class="px-4 py-2 text-sm rounded-full bg-red-500 text-white hover:bg-red-600 transition">
                            Register
                        </a>
                    </div>
                @endif
            </div>

            {{-- *Mobile Hamburger (Fixed Animation)* --}}
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md focus:outline-none transition duration-150 ease-in-out"
                    :class="{ 'text-gray-600': scrolled || forceSolid, 'text-gray-100': !(scrolled || forceSolid) }">

                    {{-- Icon Container --}}
                    <div class="relative w-6 h-5">
                        {{-- Top Line --}}
                        <span
                            class="absolute top-1/2 left-0 block w-full h-0.5 bg-current transform transition-all duration-300 ease-in-out"
                            style="transform-origin: center;"
                            :class="{ 'rotate-45': open, '-translate-y-2': !open, '-translate-y-1/2': open }"></span>

                        {{-- Middle Line --}}
                        <span
                            class="absolute top-1/2 left-0 block w-full h-0.5 bg-current transform -translate-y-1/2 transition-all duration-300 ease-in-out"
                            :class="{ 'opacity-0': open }"></span>

                        {{-- Bottom Line --}}
                        <span
                            class="absolute top-1/2 left-0 block w-full h-0.5 bg-current transform transition-all duration-300 ease-in-out"
                            style="transform-origin: center;"
                            :class="{ '-rotate-45': open, 'translate-y-1.5': !open, '-translate-y-1/2': open }"></span>
                    </div>
                </button>
            </div>
        </div>
    </div>

    {{-- *Mobile Menu* --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="sm:hidden bg-white text-gray-800 shadow-lg border-b border-gray-100" style="display: none;">

        {{-- Links --}}
        <div class="pb-3 py-2">
            @foreach (['Home', 'About', 'Travel', 'Provinces', 'Life Style'] as $item)
                @php $targetId = strtolower(str_replace(' ', '', $item)); @endphp
                <a href="#{{ $targetId }}" @click="open = false"
                    class="block w-full ps-3 pe-4 py-2 border-l-4 text-start text-base transition duration-150 ease-in-out"
                    :class="activeSection === '{{ $targetId }}'
                            ?
                            'border-red-500 text-red-700 bg-red-50' :
                            'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300'">
                    {{ $item }}
                </a>
            @endforeach
        </div>

        {{-- User Info --}}
        <div class="pb-1 border-gray-200">
            @if ($user)
                <div class="px-4 py-3 bg-gray-50 flex items-center gap-3 border-b border-gray-100">
                    @if ($user->avatar_url)
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover border border-gray-200 shrink-0">
                    @else
                        <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 text-sm font-bold flex items-center justify-center shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="min-w-0">
                        <div class="text-base font-medium text-gray-800 truncate">{{ $user->name }}</div>
                        <div class="text-sm font-medium text-gray-500 truncate">{{ $user->email }}</div>
                    </div>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route($dashboard)">{{ __('Dashboard') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route($profile)">{{ __('Profile') }}</x-responsive-nav-link>
                    <form method="POST" action="{{ route($logout) }}">
                        @csrf
                        <x-responsive-nav-link :href="route($logout)"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('login')">{{ __('Log in') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">{{ __('Register') }}</x-responsive-nav-link>
                </div>
            @endif
        </div>
    </div>

</nav>