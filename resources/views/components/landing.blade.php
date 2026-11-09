<x-root-layout
    title="Tesachor - Explore Cambodia"
    htmlClass="scroll-smooth"
    bodyClass="font-sans antialiased text-gray-800 bg-transparent overflow-x-hidden"
    x-data
>
    <x-slot:head>
        <style>
            html,
            body {
                max-width: 100%;
                overflow-x: hidden;
            }

            html {
                scroll-behavior: smooth;
                scroll-padding-top: 5rem;
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
                    'landing' => trans('landing', [], 'en'),
                    'landing' => trans('landing', [], 'en'),
                    'auth' => trans('auth', [], 'en'),
                ],
                'km' => [
                    'navbar' => trans('navbar', [], 'km'),
                    'landing' => trans('landing', [], 'km'),
                    'landing' => trans('landing', [], 'km'),
                    'auth' => trans('auth', [], 'km'),
                ],
            ];
            foreach (['en', 'km'] as $loc) {
                $landingArr = trans('landing', [], $loc);
                if (is_array($landingArr)) {
                    $translations[$loc] += $landingArr;
                }
            }
        @endphp
        <script>
            window.translations = @json($translations);
        </script>
    </x-slot:head>

    @include('layouts.navigation')

    <main x-data>
        {{ $slot }}
    </main>
</x-root-layout>
