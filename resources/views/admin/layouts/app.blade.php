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

<body class="min-h-full bg-gray-50 font-sans antialiased" x-data="{ sidebarOpen: false, logoutModalOpen: false }"
    @keydown.escape.window="sidebarOpen = false; logoutModalOpen = false">

    {{-- MOBILE SIDEBAR OVERLAY --}}
    <div x-cloak x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-xs lg:hidden"
        @click="sidebarOpen = false" style="display:none">
    </div>

    <div class="flex min-h-screen">

        {{--  SIDEBAR  --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 flex flex-col
                   transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:sticky lg:top-0 lg:h-screen lg:self-start lg:flex-shrink-0 shadow-xl lg:shadow-none">

            {{-- Logo & Mobile Close --}}
            <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center shadow-sm">
                        <span class="text-white font-bold text-sm font-serif">T</span>
                    </div>
                    <div>
                        <span class="font-serif font-bold text-gray-900 text-lg tracking-wide">Tesachor</span>
                        <p class="text-[10px] text-gray-500 font-semibold capitalize tracking-widest -mt-0.5">Admin Panel
                        </p>
                    </div>
                </div>

                {{-- Close button for mobile drawer --}}
                <button type="button" @click="sidebarOpen = false"
                    class="lg:hidden p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors"
                    aria-label="Close sidebar">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
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

                {{-- Settings Category --}}
                <div class="pt-4 pb-2">
                    <p class="text-[10px] font-semibold text-gray-400 capitalize tracking-widest px-3">Settings</p>
                </div>

                @php $isProfile = request()->routeIs('admins.profile.*'); @endphp
                <a href="{{ route('admins.profile.edit') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all
                           {{ $isProfile ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    <i
                        class="fa-solid fa-user-gear w-4 text-center {{ $isProfile ? 'text-blue-600' : 'text-gray-400' }}"></i>
                    Profile
                </a>
            </nav>

            {{-- User Profile Footer --}}
            <div class="border-t border-gray-100 p-3">
                <div class="flex items-center gap-2">
                    <a href="{{ route('admins.profile.edit') }}"
                        class="flex items-center gap-3 flex-1 min-w-0 p-2 rounded-xl transition-all duration-200 group cursor-pointer focus:outline-hidden {{ $isProfile ? 'bg-blue-50/80 ring-1 ring-blue-100' : 'hover:bg-gray-50' }}"
                        title="Edit profile settings">
                        <div class="relative flex-shrink-0">
                            @if (Auth::user()->avatar_url)
                                <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}"
                                    class="w-10 h-10 rounded-full object-cover border-2 {{ $isProfile ? 'border-blue-500 ring-2 ring-blue-100' : 'border-blue-100' }} shadow-2xs group-hover:border-blue-500 group-hover:ring-2 group-hover:ring-blue-100 transition-all duration-200">
                            @else
                                <div
                                    class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 font-bold text-sm flex items-center justify-center border-2 {{ $isProfile ? 'border-blue-500 ring-2 ring-blue-100' : 'border-blue-100' }} shadow-2xs group-hover:border-blue-500 group-hover:ring-2 group-hover:ring-blue-100 transition-all duration-200">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-1.5">
                                <p class="text-sm font-semibold {{ $isProfile ? 'text-blue-700' : 'text-gray-900' }} truncate group-hover:text-blue-600 transition-colors">
                                    {{ Auth::user()->name }}
                                </p>
                                <i
                                    class="fa-solid fa-pen-to-square text-[10px] text-blue-500 {{ $isProfile ? 'opacity-100' : 'opacity-0 -translate-x-1' }} group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-200 flex-shrink-0"></i>
                            </div>
                            <p class="text-xs text-gray-400 truncate">Administrator</p>
                        </div>
                    </a>

                    <button type="button" @click="logoutModalOpen = true" title="Sign out"
                        class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-2.5 rounded-xl transition-all duration-200 flex-shrink-0 focus:outline-hidden focus:ring-2 focus:ring-red-100 cursor-pointer">
                        <i class="fa-solid fa-right-from-bracket text-sm"></i>
                    </button>
                </div>
            </div>
        </aside>

        {{--  MAIN CONTENT AREA  --}}
        <div class="flex flex-col flex-1 min-w-0">

            {{-- TOP NAVIGATION BAR --}}
            <header class="sticky top-0 z-30 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">

                    <div class="flex items-center gap-3">
                        {{-- Mobile Hamburger --}}
                        <button @click="sidebarOpen = !sidebarOpen"
                            class="lg:hidden p-2 rounded-xl text-gray-600 hover:bg-gray-100 focus:outline-hidden focus:ring-2 focus:ring-blue-100 transition-colors cursor-pointer"
                            aria-label="Toggle Navigation Sidebar">
                            <i class="fa-solid fa-bars text-lg"></i>
                        </button>

                        {{-- Mobile Page Title --}}
                        <div class="lg:hidden flex items-center gap-2">
                            <span class="text-base font-bold text-gray-900 truncate">{{ $title ?? 'Admin Panel' }}</span>
                        </div>
                    </div>

                    {{-- Desktop Page Title --}}
                    <div class="hidden lg:block">
                        <h1 class="text-lg font-semibold text-gray-900">{{ $title ?? 'Dashboard' }}</h1>
                    </div>

                    {{-- Mobile Quick Profile Avatar --}}
                    <div class="flex items-center gap-2 lg:hidden">
                        <a href="{{ route('admins.profile.edit') }}"
                            class="p-0.5 rounded-full border-2 {{ request()->routeIs('admins.profile.*') ? 'border-blue-600 ring-2 ring-blue-100' : 'border-gray-200 hover:border-blue-400' }} transition-all"
                            title="Profile Settings">
                            @if (Auth::user()?->avatar_url)
                                <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full object-cover">
                            @else
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-bold text-xs flex items-center justify-center">
                                    {{ strtoupper(substr(Auth::user()?->name ?? 'A', 0, 1)) }}
                                </div>
                            @endif
                        </a>
                    </div>
                </div>
            </header>

            {{-- FLASH MESSAGES --}}
            @if (session('success') || session('error') || session('status'))
                <div class="px-4 sm:px-6 lg:px-8 pt-4 space-y-2">
                    @if (session('success'))
                        <div x-data="{ show: true }" x-show="show" x-transition
                            class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm shadow-2xs">
                            <i class="fa-solid fa-circle-check text-green-500 flex-shrink-0"></i>
                            <span class="font-medium">{{ session('success') }}</span>
                            <button @click="show = false"
                                class="ml-auto text-green-400 hover:text-green-600 flex-shrink-0 cursor-pointer">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    @endif
                    @if (session('status') && !session('success'))
                        @php
                            $statusMessages = [
                                'profile-updated' => 'Profile information updated successfully.',
                                'avatar-updated' => 'Profile picture updated successfully.',
                                'avatar-deleted' => 'Profile picture removed successfully.',
                                'password-updated' => 'Password updated successfully.',
                                'verification-link-sent' => 'A new verification link has been sent to your email address.',
                            ];
                            $statusText = $statusMessages[session('status')] ?? session('status');
                        @endphp
                        <div x-data="{ show: true }" x-show="show" x-transition
                            class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm shadow-2xs">
                            <i class="fa-solid fa-circle-check text-emerald-500 flex-shrink-0"></i>
                            <span class="font-medium">{{ $statusText }}</span>
                            <button @click="show = false"
                                class="ml-auto text-emerald-400 hover:text-emerald-600 flex-shrink-0 cursor-pointer">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div x-data="{ show: true }" x-show="show" x-transition
                            class="flex items-center gap-3 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm shadow-2xs">
                            <i class="fa-solid fa-circle-exclamation text-red-500 flex-shrink-0"></i>
                            <span class="font-medium">{{ session('error') }}</span>
                            <button @click="show = false"
                                class="ml-auto text-red-400 hover:text-red-600 flex-shrink-0 cursor-pointer">
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

    {{--  LOGOUT CONFIRMATION MODAL  --}}
    <div x-cloak x-show="logoutModalOpen" class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="logout-modal-title" role="dialog" aria-modal="true" style="display: none;">

        {{-- Backdrop --}}
        <div x-show="logoutModalOpen" x-transition:enter="transition-opacity ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/50 backdrop-blur-xs transition-opacity"
            @click="logoutModalOpen = false">
        </div>

        {{-- Modal Dialog --}}
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="logoutModalOpen" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                @click.outside="logoutModalOpen = false"
                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-gray-100">

                <div class="p-6">
                    <div class="flex items-start gap-4">
                        {{-- Warning Icon Container --}}
                        <div
                            class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center ring-8 ring-red-50/60 shadow-xs flex-shrink-0">
                            <i class="fa-solid fa-right-from-bracket text-lg"></i>
                        </div>

                        {{-- Text Content --}}
                        <div class="flex-1 min-w-0">
                            <h3 id="logout-modal-title" class="text-lg font-bold text-gray-900 leading-tight">
                                Sign Out Confirmation
                            </h3>
                            <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                                Are you sure you want to sign out from the admin dashboard? You can cancel to keep working or proceed to sign out.
                            </p>
                        </div>

                        {{-- Close Button --}}
                        <button type="button" @click="logoutModalOpen = false"
                            class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="fa-solid fa-xmark text-base"></i>
                        </button>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-2.5">
                        <button type="button" @click="logoutModalOpen = false"
                            class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-700 font-semibold text-sm hover:bg-gray-50 hover:text-gray-900 shadow-2xs transition-all text-center">
                            Cancel
                        </button>
                        <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto m-0">
                            @csrf
                            <button type="submit"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm shadow-xs hover:shadow-sm transition-all focus:outline-hidden focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                <i class="fa-solid fa-right-from-bracket text-xs"></i>
                                Yes, Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
