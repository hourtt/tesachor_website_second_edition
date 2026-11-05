@props(['hideNav' => false]) {{-- Default is FALSE (Show Nav) --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preload" href="{{ asset('fonts/english/roboto/Roboto-Regular.ttf') }}" as="font" type="font/ttf"
        crossorigin="anonymous">
    <link rel="preload" href="{{ asset('fonts/english/roboto/Roboto-Bold.ttf') }}" as="font" type="font/ttf"
        crossorigin="anonymous">
    <link rel="preload" href="{{ asset('fonts/khmer/kantumruy_pro/KantumruyPro-Regular.ttf') }}" as="font" type="font/ttf"
        crossorigin="anonymous">
    <link rel="preload" href="{{ asset('fonts/khmer/kantumruy_pro/KantumruyPro-Bold.ttf') }}" as="font" type="font/ttf"
        crossorigin="anonymous">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased overflow-x-hidden">
    <div class="min-h-screen bg-gray-100">

        {{-- Only show Navigation if hideNav is FALSE and not on a profile page --}}
        @if (!$hideNav && !request()->routeIs('admins.profile.*'))
            @include('layouts.navigation')
        @endif

        <main>
            {{ $slot }}
        </main>
    </div>
</body>

</html>
