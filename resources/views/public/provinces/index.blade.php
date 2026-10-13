<x-app-layout :hideNav="true">
    {{-- Font Awesome (Ensuring icons load) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row justify-between items-end mb-8 gap-4 border-b border-gray-100 pb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Provinces & Cities</h1>
                <p class="text-gray-500 mt-2">Explore every province and city in Cambodia ({{$provinces->total() - 1}}
                    Provinces & {{$provinces->total() - 24}} City).</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('public.landing') }}"
                    class="text-gray-500 hover:text-gray-700 font-medium text-sm transition-colors mr-2">
                    Back to Dashboard
                </a>

            </div>
        </div>

        {{-- GRID LAYOUT --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
            @forelse($provinces as $province)
                <div
                    class="group relative aspect-[3/4] rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 bg-gray-100">

                    {{-- 1. Full Background Image --}}
                    <img src="{{ asset('storage/' . $province->thumbnail) }}" alt="{{ $province->name }}"
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">

                    {{-- 2. Gradient Overlay --}}
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent opacity-80 group-hover:opacity-90 transition-opacity">
                    </div>

                    {{-- 4. Destination Count Badge (Top Left) --}}
                    <div class="absolute top-3 left-3 z-20">
                        <span
                            class="bg-black/50 backdrop-blur-md text-white text-[10px] font-bold px-2.5 py-1 rounded-full border border-white/20 shadow-sm flex items-center gap-1">
                            <i class="fa-solid fa-map-location-dot text-blue-400"></i>
                            @if ($province->destinations->count() == 0)
                                {{-- * One place only --}}
                                {{ $province->destinations->count() }} Place
                            @else
                                {{-- *More than one place* --}}
                                {{ $province->destinations->count() }} Places
                            @endif
                        </span>
                    </div>

                    {{-- 5. Bottom Content --}}
                    <div class="absolute bottom-0 inset-x-0 p-6 text-center z-10 flex flex-col items-center">
                        <x-province-card-title :province="$province" />

                        {{-- Clickable Area Link --}}
                        <a href="{{ route('public.provinces.show', $province->slug) }}" class="absolute inset-0 z-0"
                            title="View details"></a>
                    </div>
                </div>
            @empty
                {{-- Empty State --}}
                <div class="col-span-full py-16 text-center bg-white rounded-xl border-2 border-dashed border-gray-200">
                    <div
                        class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4 text-blue-500">
                        <i class="fa-regular fa-map text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">No provinces found</h3>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($provinces->hasPages())
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                {{ $provinces->links() }}
            </div>
        @endif
    </div>
</x-app-layout>