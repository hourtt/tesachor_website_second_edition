<x-root-layout
    title="404 - Page Not Found"
    htmlClass="h-full"
    bodyClass="min-h-full bg-gray-50 font-sans text-gray-800 antialiased flex flex-col justify-between"
>
    {{-- Header / Brand --}}
    <header class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex items-center justify-between">
        <a href="{{ route('public.landing') }}" class="flex items-center gap-3 group">
            <img src="{{ asset('storage/tesachor_transparent_logo.png') }}" alt="Tesachor Logo"
                class="w-10 h-10 object-contain drop-shadow-sm transition-transform duration-200 group-hover:scale-105">
            <span class="text-xl font-bold tracking-tight text-gray-900">Tesachor</span>
        </a>
    </header>

    {{-- Main Error Content --}}
    <main class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-xl w-full text-center">
            {{-- 404 Responsive Image --}}
            <div class="mb-8 flex justify-center">
                <img src="{{ asset('storage/404.png') }}"
                    alt="Page Not Found illustration"
                    class="w-full max-w-md sm:max-w-lg h-auto object-contain mx-auto drop-shadow-sm">
            </div>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('public.landing') }}"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-sm hover:shadow transition-all duration-200">
                    <x-icon name="arrow-left" class="w-4 h-4 text-xs" />
                    Back to Homepage
                </a>
            </div>
        </div>
    </main>
</x-root-layout>
