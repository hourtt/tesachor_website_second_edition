<x-app-layout :hideNav="true">
    {{-- 1. HERO SECTION --}}
    <div class="relative w-full h-[70vh] min-h-125 group">
        {{-- Hero Image --}}
        <div class="absolute inset-0 overflow-hidden">
            @if ($destination->thumbnail)
                <img src="{{ $destination->thumbnail_url }}" alt="{{ $destination->place_name }}"
                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
            @else
                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-400">No Cover Image</span>
                </div>
            @endif
            <div class="absolute inset-0 bg-linear-to-t from-gray-900 via-gray-900/40 to-transparent"></div>
        </div>

        {{-- Hero Content --}}
        <div class="absolute bottom-0 left-0 w-full p-6 md:p-16 text-white max-w-7xl mx-auto z-10">
            <div class="flex flex-col items-start gap-4">
                {{-- Back Button --}}
                <a href="{{ route('public.landing') }}"
                    class="backdrop-blur-md bg-white/10 hover:bg-white/20 border border-white/20 text-white px-4 py-2 rounded-full text-sm font-medium transition flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
                </a>

                {{-- Recommended Badge --}}
                @if ($destination->is_recommended)
                    <span
                        class="bg-yellow-500 text-white text-xs font-bold px-3 py-1 uppercase tracking-widest rounded-sm shadow-sm">
                        <i class="fa-solid fa-star mr-1"></i> Recommended
                    </span>
                @endif

                <h1 class="font-serif text-4xl md:text-6xl lg:text-7xl font-bold leading-tight shadow-sm">
                    {{ $destination->place_name }}
                </h1>

                {{-- Short Description --}}
                <p class="text-gray-200 text-lg md:text-xl max-w-2xl font-light leading-relaxed">
                    {{ $destination->short_description }}
                </p>

                {{-- Quick Meta --}}
                <div class="flex flex-wrap gap-4 mt-2 text-sm font-medium text-gray-300">
                    <span class="flex items-center"><i class="fa-solid fa-location-dot mr-2 text-blue-400"></i>
                        {{ $destination->province->name ?? 'Cambodia' }}</span>
                    @if ($destination->category)
                        <span class="flex items-center"><i class="fa-solid fa-tag mr-2 text-emerald-400"></i>
                            {{ $destination->category->name }}</span>
                    @endif
                    @if ($destination->rating)
                        <span class="flex items-center"><i class="fa-solid fa-star mr-2 text-yellow-400"></i>
                            {{ $destination->rating }} / 5.0</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- 2. MAIN CONTENT --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

            {{-- LEFT COLUMN: Content (Description, History, Gallery, Tips) --}}
            <div class="lg:col-span-2 space-y-12">

                {{-- About --}}
                <section>
                    <h2 class="font-sans text-3xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                        <span class="w-8 h-1 bg-blue-600 rounded-full block"></span> About this Place
                    </h2>
                    <div class="prose prose-lg text-gray-600 leading-relaxed text-justify">
                        {!! nl2br(e($destination->place_description)) !!}
                    </div>
                </section>

                {{-- Gallery Images (The Missing Part) --}}
                @if ($destination->gallery_urls && count($destination->gallery_urls) > 0)
                    <section>
                        <h2 class="font-sans text-2xl font-bold text-gray-900 mb-6">Photo Gallery</h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach ($destination->gallery_urls as $index => $imgUrl)
                                <div
                                    class="relative group overflow-hidden rounded-xl h-48 md:h-64 shadow-sm cursor-pointer">
                                    <img src="{{ $imgUrl }}"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                        alt="Gallery image {{ $index + 1 }}">
                                    <div
                                        class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- History --}}
                @if ($destination->place_history)
                    <section class="bg-gray-50 p-8 rounded-2xl border border-gray-100">
                        <h2 class="font-serif text-2xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-clock-rotate-left text-gray-400"></i> History & Background
                        </h2>
                        <p class="text-gray-600 leading-relaxed">
                            {!! nl2br(e($destination->place_history)) !!}
                        </p>
                    </section>
                @endif

                {{-- General Info / Logistics --}}
                @if ($destination->place_information)
                    <section>
                        <h2 class="font-serif text-2xl font-bold text-gray-900 mb-4">Travel Tips & Logistics</h2>
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-r-lg">
                            <p class="text-blue-900 leading-relaxed">
                                {!! nl2br(e($destination->place_information)) !!}
                            </p>
                        </div>
                    </section>
                @endif
            </div>

            {{-- RIGHT COLUMN: Sticky Sidebar (Map, Key Details, Highlights) --}}
            <div class="lg:col-span-1">
                <div class="sticky top-8 space-y-8">

                    {{-- 1. Map Card --}}
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden p-2">
                        <div class="h-64 w-full rounded-lg overflow-hidden relative">
                            <iframe width="100%" height="100%" frameborder="0" style="border:0" loading="lazy"
                                allowfullscreen
                                src="https://maps.google.com/maps?q={{ urlencode($destination->place_name . ' ' . ($destination->province->name ?? '')) }}&t=&z=13&ie=UTF8&iwloc=&output=embed">
                            </iframe>
                        </div>
                        <div class="p-4">
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($destination->place_name) }}"
                                target="_blank"
                                class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition shadow-md shadow-blue-200">
                                <i class="fa-solid fa-location-arrow mr-2"></i> Get Directions
                            </a>
                        </div>
                    </div>

                    {{-- 2. Details Card --}}
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                        <h3 class="font-sans text-xl font-bold text-gray-800 mb-6 border-b pb-2">At a Glance</h3>
                        <ul class="space-y-4">
                            <li class="flex items-start justify-between">
                                <span class="text-gray-500 text-sm">Location</span>
                                <span
                                    class="text-gray-900 font-semibold text-right text-sm max-w-[60%]">{{ $destination->province->name ?? 'N/A' }}</span>
                            </li>
                            <li class="flex items-start justify-between">
                                <span class="text-gray-500 text-sm">Province</span>
                                <span
                                    class="text-gray-900 font-semibold text-right text-sm">{{ $destination->province->name ?? 'Cambodia' }}</span>
                            </li>
                            <li class="flex items-start justify-between">
                                <span class="text-gray-500 text-sm">Rating</span>
                                <span class="flex items-center text-gray-900 font-bold">
                                    {{ $destination->rating ?? 'N/A' }} <i
                                        class="fa-solid fa-star text-yellow-400 ml-1 text-xs"></i>
                                </span>
                            </li>
                        </ul>
                    </div>

                    {{-- 3. Highlights --}}
                    @if ($destination->highlights && count($destination->highlights) > 0)
                        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                            <h3 class="font-sans text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <i class="fa-regular fa-lightbulb text-yellow-500"></i> Highlights
                            </h3>
                            <ul class="space-y-3">
                                @foreach ($destination->highlights as $highlight)
                                    <li class="flex items-start text-gray-700 text-sm">
                                        <i class="fa-solid fa-check text-green-500 mt-1 mr-3 shrink-0"></i>
                                        <span>{{ $highlight }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    {{-- 3. NEARBY SECTION --}}
    @if (isset($otherPlaces) && count($otherPlaces) > 0)
        <div class="bg-gray-50 py-16 border-t border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-3xl font-serif font-bold text-gray-900">Explore Nearby</h2>
                        <p class="text-gray-500 mt-1">Other destinations you might like</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach ($otherPlaces as $place)
                        <a href="{{ route('public.destinations.show', $place->slug) }}"
                            class="group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100">
                            <div class="h-48 overflow-hidden relative">
                                <img src="{{ $place->thumbnail_url }}" alt="{{ $place->place_name }}"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                @if ($place->is_recommended)
                                    <span
                                        class="absolute top-2 right-2 bg-yellow-400 text-yellow-900 text-[10px] font-bold px-2 py-0.5 rounded">
                                        Recommended
                                    </span>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-gray-900 mb-1 group-hover:text-blue-600 transition-colors">
                                    {{ $place->place_name }}</h3>
                                <p class="text-gray-400 text-xs flex items-center gap-1">
                                    <i class="fa-solid fa-location-dot"></i> {{ Str::limit($place->province->name ?? '', 20) }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
