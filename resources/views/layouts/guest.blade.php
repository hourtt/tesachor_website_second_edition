<x-root-layout bodyClass="font-sans text-gray-900 antialiased">
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
</x-root-layout>
