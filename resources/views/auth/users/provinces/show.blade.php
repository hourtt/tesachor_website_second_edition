<x-app-layout :hideNav="true">
    <div class="max-w-7xl mx-auto py-10 px-4">
        {{-- Header --}}
        <div class="flex justify-between items-center mb-8">
            <a href="{{ route('users.provinces.index') }}"
                class="text-gray-500 hover:text-blue-600 flex items-center gap-2 transition group">
                <i class="fa-solid fa-angle-left group-hover:-translate-x-1 transition-transform"></i> Back to List
            </a>
        </div>

        {{-- Province Details Card (Kept identical as requested) --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-12">
            <div class="md:flex">
                {{-- Image --}}
                <div class="md:w-1/3 h-64 md:h-auto relative group">
                    <img src="{{ asset('storage/' . $province->thumbnail) }}" alt="{{ $province->name }}"
                        class="w-full h-full object-cover transition duration-700 group-hover:scale-105">
                    <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition"></div>
                </div>

                {{-- Info --}}
                <div class="p-8 md:w-2/3 flex flex-col justify-center">
                    <h1 class="text-4xl font-bold font-khmer text-gray-800 mb-2">{{ $province->name_in_khmer }}</h1>
                    <p class="text-gray-500 text-lg mb-6">{{ $province->name }}</p>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                            <span class="block text-xs font-bold text-blue-400 uppercase tracking-wider mb-1">Total
                                Destinations</span>
                            <span class="text-3xl font-bold text-blue-700">{{ $province->destinations->count() }}</span>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <span
                                class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Slug</span>
                            <span class="text-sm font-medium text-gray-600 font-mono">{{ $province->slug }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- GRID LAYOUT (Replaces Table) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($province->destinations as $destination)
                <div
                    class="group relative aspect-[4/5] rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 cursor-pointer">

                    {{-- 1. Background Image --}}
                    <img src="{{ asset('storage/' . $destination->thumbnail) }}" alt="{{ $destination->place_name }}"
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">

                    {{-- 2. Gradient Overlay (Bottom Up) --}}
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-80 group-hover:opacity-90 transition-opacity">
                    </div>

                    {{-- 3. Top Action Buttons (Edit / View) --}}

                    {{-- 4. Badges (Recommended) --}}
                    @if ($destination->is_recommended)
                        <div class="absolute top-4 left-4">
                            <span
                                class="bg-yellow-500/90 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm uppercase tracking-wider">
                                Recommended
                            </span>
                        </div>
                    @endif

                    {{-- 5. Bottom Text Content --}}
                    <div class="absolute bottom-0 left-0 w-full p-6 text-center">
                        <h3 class="text-2xl font-bold text-white mb-1 font-serif tracking-wide drop-shadow-md">
                            {{ $destination->place_name }}
                        </h3>

                        {{-- Subtitle / Rating --}}
                        <div class="flex items-center justify-center gap-3 text-gray-300 text-sm">
                            @if ($destination->rating)
                                <span class="flex items-center gap-1">
                                    <i class="fa-solid fa-star text-yellow-400 text-xs"></i> {{ $destination->rating }}
                                </span>
                                <span class="w-1 h-1 bg-gray-500 rounded-full"></span>
                            @endif
                            {{-- Since Destination model might not have Khmer name, we use 'View Details' or Location --}}
                            <span class="uppercase text-[10px] tracking-widest hover:text-white transition">
                                View Details
                            </span>
                        </div>

                        {{-- Hover Decoration --}}
                        <div
                            class="w-8 h-0.5 bg-blue-500 mx-auto mt-3 rounded-full w-0 group-hover:w-12 transition-all duration-500">
                        </div>
                    </div>

                    {{-- Clickable Overlay (Optional: makes whole card clickable) --}}
                    <a href="{{ route('users.destinations.show', $destination->slug) }}" class="absolute inset-0 z-0"></a>
                </div>
            @empty
                {{-- Empty State --}}
                <div class="col-span-full py-16 text-center bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                    <div
                        class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                        <i class="fa-regular fa-map"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">No destinations yet</h3>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>