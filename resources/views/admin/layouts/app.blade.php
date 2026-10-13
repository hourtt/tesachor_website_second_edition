<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Panel' }} — Tesachor</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Hanuman:wght@100;300;400;700;900&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full bg-gray-50 font-sans antialiased" x-data="{ sidebarOpen: false }">

    {{-- MOBILE SIDEBAR OVERLAY --}}
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden"
        @click="sidebarOpen = false" style="display:none">
    </div>

    <div class="flex min-h-screen">

        {{-- ===================== SIDEBAR ===================== --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 flex flex-col
                   transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 lg:flex-shrink-0">

            {{-- Logo --}}
            <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-100">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center shadow-sm">
                    <span class="text-white font-bold text-sm font-serif">T</span>
                </div>
                <div>
                    <span class="font-serif font-bold text-gray-900 text-lg tracking-wide">Tesachor</span>
                    <p class="text-[10px] text-gray-500 font-semibold capitalize tracking-widest -mt-0.5">Admin Panel
                    </p>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">

                {{-- Dashboard --}}
                @php $isDash = request()->routeIs('admins.dashboard'); @endphp
                <a href="{{ route('admins.dashboard') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all
                           {{ $isDash ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    <i
                        class="fa-solid fa-gauge-high w-4 text-center {{ $isDash ? 'text-blue-600' : 'text-gray-400' }}"></i>
                    Dashboard
                </a>

                <div class="pt-4 pb-2">
                    <p class="text-[10px] font-semibold text-gray-400 capitalize tracking-widest px-3">Content</p>
                </div>

                @php $isProvinces = request()->routeIs('admins.provinces.*'); @endphp
                <a href="{{ route('admins.provinces.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all
                           {{ $isProvinces ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    <i
                        class="fa-solid fa-map w-4 text-center {{ $isProvinces ? 'text-blue-600' : 'text-gray-400' }}"></i>
                    Provinces
                </a>

                @php $isDest = request()->routeIs('admins.destinations.*'); @endphp
                <a href="{{ route('admins.destinations.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all
                           {{ $isDest ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    <i
                        class="fa-solid fa-location-dot w-4 text-center {{ $isDest ? 'text-blue-600' : 'text-gray-400' }}"></i>
                    Destinations
                </a>

                <div class="pt-4 pb-2">
                    <p class="text-[10px] font-semibold text-gray-400 capitalize tracking-widest px-3">Users</p>
                </div>

                @php $isUsers = request()->routeIs('admins.users.*'); @endphp
                <a href="{{ route('admins.users.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all
                           {{ $isUsers ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    <i
                        class="fa-solid fa-users w-4 text-center {{ $isUsers ? 'text-blue-600' : 'text-gray-400' }}"></i>
                    Users
                </a>
            </nav>

            {{-- User Profile Footer --}}
            <div class="border-t border-gray-100 p-4">
                <div class="flex items-center gap-3">
                    @if (Auth::user()->avatar_url)
                        <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}"
                            class="w-9 h-9 rounded-full object-cover border-2 border-blue-100 flex-shrink-0">
                    @else
                        <div
                            class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 font-bold text-sm flex items-center justify-center border-2 border-blue-100 flex-shrink-0">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-400 truncate">Administrator</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Log out"
                            class="text-gray-400 hover:text-red-500 transition-colors p-1 flex-shrink-0">
                            <i class="fa-solid fa-right-from-bracket text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- ===================== MAIN CONTENT AREA ===================== --}}
        <div class="flex flex-col flex-1 min-w-0">

            {{-- TOP NAVIGATION BAR --}}
            <header class="sticky top-0 z-30 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">

                    {{-- Mobile Hamburger --}}
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>

                    {{-- Page Title --}}
                    <div class="hidden lg:block">
                        <h1 class="text-lg font-semibold text-gray-900">{{ $title ?? 'Dashboard' }}</h1>
                    </div>
                </div>
            </header>

            {{-- FLASH MESSAGES --}}
            @if (session('success') || session('error'))
                <div class="px-4 sm:px-6 lg:px-8 pt-4 space-y-2">
                    @if (session('success'))
                        <div x-data="{ show: true }" x-show="show" x-transition
                            class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm">
                            <i class="fa-solid fa-circle-check text-green-500 flex-shrink-0"></i>
                            <span>{{ session('success') }}</span>
                            <button @click="show = false"
                                class="ml-auto text-green-400 hover:text-green-600 flex-shrink-0">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div x-data="{ show: true }" x-show="show" x-transition
                            class="flex items-center gap-3 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm">
                            <i class="fa-solid fa-circle-exclamation text-red-500 flex-shrink-0"></i>
                            <span>{{ session('error') }}</span>
                            <button @click="show = false"
                                class="ml-auto text-red-400 hover:text-red-600 flex-shrink-0">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    @endif
                </div>
            @endif

            {{-- PAGE CONTENT --}}
            <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6">
                {{ $slot }}
            </main>
        </div>
    </div>

</body>

</html>
