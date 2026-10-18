<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tesachor - Explore Cambodia</title>

    <!-- Preload Fonts -->
    <link rel="preload" href="{{ asset('fonts/english/Roboto-Regular.ttf') }}" as="font" type="font/ttf"
        crossorigin="anonymous">
    <link rel="preload" href="{{ asset('fonts/english/Roboto-Bold.ttf') }}" as="font" type="font/ttf"
        crossorigin="anonymous">
    <link rel="preload" href="{{ asset('fonts/khmer/kantumruy_pro/KantumruyPro-Regular.ttf') }}" as="font" type="font/ttf"
        crossorigin="anonymous">
    <link rel="preload" href="{{ asset('fonts/khmer/kantumruy_pro/KantumruyPro-Bold.ttf') }}" as="font" type="font/ttf"
        crossorigin="anonymous">

    {{-- Fonts & Icons --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Hanuman:wght@100;300;400;700;900&family=Kantumruy+Pro:ital,wght@0,100..700;1,100..700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <style>
        html,
        body {
            max-width: 100%;
            overflow-x: hidden;
        }

        html {
            scroll-behavior: smooth;
        }

        /* Custom easing for smoother interactions */
        .transition-all {
            transition-timing-function: cubic-bezier(0.4, 0.2, 0.2, 1);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 0.8;
            }

            /* Matches your class opacity-80 */
        }

        /* Smooth Cross-Fade Transition for Language Switching */
        [x-text],
        [x-html] {
            transition: opacity 160ms ease-in-out, transform 160ms ease-in-out;
            will-change: opacity, transform;
        }

        body.lang-switching [x-text],
        body.lang-switching [x-html] {
            opacity: 0 !important;
            transform: translateY(-2px);
        }
    </style>

    @php
        $translations = [
            'en' => [
                'navbar' => trans('navbar', [], 'en'),
                'landing' => trans('landing/landing', [], 'en'),
                'landing/landing' => trans('landing/landing', [], 'en'),
                'auth' => trans('auth', [], 'en'),
            ],
            'km' => [
                'navbar' => trans('navbar', [], 'km'),
                'landing' => trans('landing/landing', [], 'km'),
                'landing/landing' => trans('landing/landing', [], 'km'),
                'auth' => trans('auth', [], 'km'),
            ],
        ];
        foreach (['en', 'km'] as $loc) {
            $landingArr = trans('landing/landing', [], $loc);
            if (is_array($landingArr)) {
                $translations[$loc] += $landingArr;
            }
        }
    @endphp
    <script>
        window.translations = @json($translations);
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body x-data class="font-sans antialiased text-gray-800 bg-transparent overflow-x-hidden">
    @include('layouts.navigation')

    <main x-data>
        {{ $slot }}
    </main>

</body>

</html>
