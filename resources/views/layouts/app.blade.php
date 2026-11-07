@props(['hideNav' => false]) {{-- Default is FALSE (Show Nav) --}}

<x-root-layout bodyClass="font-sans antialiased overflow-x-hidden">
    <div class="min-h-screen bg-gray-100">

        {{-- Only show Navigation if hideNav is FALSE and not on a profile page --}}
        @if (!$hideNav && !request()->routeIs('admins.profile.*'))
            @include('layouts.navigation')
        @endif

        <main>
            {{ $slot }}
        </main>
    </div>
</x-root-layout>
