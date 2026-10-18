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

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Hanuman:wght@100;300;400;700;900&family=Kantumruy+Pro:ital,wght@0,100..700;1,100..700&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div>
            <a href="/">
                <img src="{{ asset('storage/tesachor_transparent_logo.png') }}" alt="Tesachor Logo"
                    class="w-16 h-16 object-contain drop-shadow-md transition-transform duration-300 group-hover:scale-105">
            </a>
        </div>
        <!-- Updated Form Container -->
        <div class="w-11/12 sm:w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden rounded-lg">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
