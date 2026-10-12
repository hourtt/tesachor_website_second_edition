<x-app-layout :hideNav="true">
    @php
        $dashboardRoute = (Auth::check() && Auth::user()->hasRole('admin')) 
            ? route('admins.dashboard') 
            : (Auth::check() ? route('users.dashboard') : route('guests.dashboard'));

        $currentUser = Auth::user();
        $avatarUrl = null;
        if ($currentUser && !empty($currentUser->avatar_path)) {
            try {
                $avatarUrl = \Illuminate\Support\Facades\Storage::disk('supabase')->url($currentUser->avatar_path);
            } catch (\Throwable $e) {
                $avatarUrl = null;
            }
        }
    @endphp

    {{-- Top Header Bar with Responsive "Back to Dashboard" Button --}}
    <div class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-gray-200/80 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative flex items-center justify-between h-16 sm:h-20">
                {{-- Left: Back to Dashboard Button --}}
                <div class="flex items-center z-10">
                    <a href="{{ $dashboardRoute }}"
                        class="group inline-flex items-center gap-1.5 sm:gap-2.5 px-3 py-1.5 sm:px-4 sm:py-2.5 rounded-xl sm:rounded-2xl text-xs sm:text-sm font-semibold text-gray-700 bg-gray-50 hover:bg-gray-100 hover:text-red-600 border border-gray-200/80 hover:border-gray-300 shadow-2xs transition-all duration-200 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="w-4 h-4 text-gray-500 transition-transform duration-200 group-hover:-translate-x-1 group-hover:text-red-600 flex-shrink-0"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span class="hidden sm:inline">Back to Dashboard</span>
                    </a>
                </div>

                {{-- Center: Tesachor Logo --}}
                <div class="absolute inset-x-0 flex items-center justify-center pointer-events-none">
                    <a href="{{ $dashboardRoute }}"
                        class="pointer-events-auto font-serif text-2xl sm:text-3xl font-bold tracking-wider text-gray-900 hover:text-red-600 transition-colors">
                        Tesachor
                    </a>
                </div>

                {{-- User Info / Badge --}}
                <div class="flex items-center gap-3">
                    <div class="hidden sm:flex flex-col text-right">
                        <span class="text-sm font-bold text-gray-900 leading-tight">{{ $currentUser->name ?? 'User' }}</span>
                        <span class="text-xs text-gray-500">
                            {{ $currentUser && $currentUser->hasRole('admin') ? 'Administrator' : 'Member' }}
                        </span>
                    </div>

                    @if ($avatarUrl)
                        <img src="{{ $avatarUrl }}"
                            alt="{{ $currentUser->name ?? 'User' }}"
                            class="w-9 h-9 sm:w-10 sm:h-10 rounded-full object-cover border border-gray-200 shadow-2xs">
                    @else
                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-red-100 text-red-600 font-bold text-sm sm:text-base flex items-center justify-center border border-red-200/60 shadow-2xs">
                            {{ strtoupper(substr($currentUser->name ?? 'U', 0, 1)) }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Main Profile Content --}}
    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="p-5 sm:p-8 bg-white shadow-sm rounded-2xl border border-gray-100">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-5 sm:p-8 bg-white shadow-sm rounded-2xl border border-gray-100">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-5 sm:p-8 bg-white shadow-sm rounded-2xl border border-gray-100">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
