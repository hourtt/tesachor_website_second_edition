@props(['hideNav' => false]) {{-- Default is FALSE (Show Nav) --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Preload Fonts -->
    <link rel="preload" href="{{ asset('fonts/english/Roboto-Regular.ttf') }}" as="font" type="font/ttf"
        crossorigin="anonymous">
    <link rel="preload" href="{{ asset('fonts/english/Roboto-Bold.ttf') }}" as="font" type="font/ttf"
        crossorigin="anonymous">
    <link rel="preload" href="{{ asset('fonts/khmer/kantumruy_pro/KantumruyPro-Regular.ttf') }}" as="font" type="font/ttf"
        crossorigin="anonymous">
    <link rel="preload" href="{{ asset('fonts/khmer/kantumruy_pro/KantumruyPro-Bold.ttf') }}" as="font" type="font/ttf"
        crossorigin="anonymous">

    <link
        href="https://fonts.googleapis.com/css2?family=Hanuman:wght@100;300;400;700;900&family=Kantumruy+Pro:ital,wght@0,100..700;1,100..700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased overflow-x-hidden">
    <div class="min-h-screen bg-gray-100">

        {{-- Only show Navigation if hideNav is FALSE and not on a profile page --}}
        @if (!$hideNav && !request()->routeIs('*.profile.*', 'profile.*'))
            @include('layouts.navigation')
        @endif

        <main>
            {{ $slot }}
        </main>
    </div>
</body>

</html>
