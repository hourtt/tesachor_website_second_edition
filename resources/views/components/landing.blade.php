<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tesachor - Explore Cambodia</title>

    {{-- * Bootstrap + font awsome styling link --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Hanuman:wght@100;300;400;700;900&family=Kantumruy+Pro:ital,wght@0,100..700;1,100..700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
       html, body {
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
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-800 bg-transparent overflow-x-hidden">
    @include('layouts.navigation')

    <main>
        {{ $slot }}
    </main>


    {{-- SCRIPTS --}}
    @push('scripts')
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // 1. Initialize Swiper
                var swiper = new Swiper(".mySwiper", {
                    slidesPerView: 1,
                    spaceBetween: 0,
                    loop: true,
                    speed: 1000,
                    centeredSlides: false, // Changed to false for better left alignment
                    autoplay: {
                        delay: 3500,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: ".swiper-pagination",
                        clickable: true,
                    },
                    breakpoints: {
                        640: {
                            slidesPerView: 2
                        },
                        1024: {
                            slidesPerView: 3
                        }, // Show 3 full slides
                    },
                });

                // 2. Initialize AOS with correct settings
                AOS.init({
                    duration: 500,
                    easing: 'ease-in-out',
                    once: true,
                    mirror: false,
                    offset: 50,
                });
            });
        </script>
    @endpush
</body>

</html>
