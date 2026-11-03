<x-app-layout :hideNav="true">
    @php
        // Helper to cleanly split multi-line text into trimmed, capital-first paragraphs
        $splitParagraphs = function (?string $text): array {
            if (! $text) {
                return [];
            }
            $lines = preg_split('/\r\n|\r|\n/', trim($text));

            return array_values(array_filter(array_map('trim', $lines), fn ($line) => $line !== ''));
        };

        $galleryUrls = $destination->gallery_urls ?? [];
    @endphp

    {{-- 1. RESPONSIVE HERO SECTION --}}
    <div class="relative w-full h-[45vh] sm:h-[55vh] lg:h-[65vh] min-h-[320px] max-h-[600px] group overflow-hidden">
        {{-- Hero Background Image --}}
        <div class="absolute inset-0">
            @if ($destination->thumbnail)
                <img src="{{ $destination->thumbnail_url }}" alt="{{ $destination->place_name }}"
                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
            @else
                <div class="w-full h-full bg-gray-900 flex items-center justify-center">
                    <span class="text-gray-400 text-sm">No cover image available</span>
                </div>
            @endif
            {{-- Dark Gradient Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-900/60 to-black/30"></div>
        </div>

        {{-- Hero Content Overlay --}}
        <div class="absolute inset-0 flex flex-col justify-end p-4 sm:p-8 md:p-12 lg:p-16 z-10">
            <div class="max-w-7xl mx-auto w-full flex flex-col items-start gap-3 sm:gap-4">
                {{-- Back Button --}}
                <a href="{{ route('public.landing') }}"
                    class="backdrop-blur-md bg-white/15 hover:bg-white/25 border border-white/20 text-white px-3.5 sm:px-4 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-medium transition inline-flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-arrow-left text-xs"></i> Back to Home
                </a>

                {{-- Recommended Badge (Title case, no uppercase) --}}
                @if ($destination->is_recommended)
                    <span class="inline-flex items-center bg-amber-500/90 backdrop-blur-xs text-white text-xs sm:text-sm font-semibold px-3 py-1 rounded-full shadow-sm capitalize">
                        Recommended
                    </span>
                @endif

                {{-- Main Title --}}
                <h1 class="text-2xl sm:text-4xl md:text-5xl lg:text-6xl font-bold font-serif text-white leading-tight tracking-tight drop-shadow-sm">
                    {{ $destination->place_name }}
                </h1>

                {{-- Short Description --}}
                @if ($destination->short_description)
                    <div class="max-w-3xl space-y-1">
                        @foreach ($splitParagraphs($destination->short_description) as $paragraph)
                            <p class="text-sm sm:text-base md:text-lg text-gray-200 font-light leading-relaxed first-letter:capitalize">
                                {{ ucfirst($paragraph) }}
                            </p>
                        @endforeach
                    </div>
                @endif

                {{-- Quick Meta Pills --}}
                <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-xs sm:text-sm text-gray-200 font-medium pt-1">
                    @if ($destination->province)
                        <span class="bg-black/35 backdrop-blur-md px-3 py-1 rounded-full border border-white/10">
                            {{ $destination->province->name }}
                        </span>
                    @endif
                    @if ($destination->category)
                        <span class="bg-black/35 backdrop-blur-md px-3 py-1 rounded-full border border-white/10">
                            {{ $destination->category->name }}
                        </span>
                    @endif
                    @if ($destination->rating)
                        <span class="bg-black/35 backdrop-blur-md px-3 py-1 rounded-full border border-white/10">
                            {{ number_format($destination->rating, 1) }} / 5.0 Rating
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- 2. MAIN CONTENT SECTION --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 sm:gap-12">

            {{-- LEFT COLUMN: Details, Gallery, History, Logistics --}}
            <div class="lg:col-span-2 space-y-10 sm:space-y-14">

                {{-- About This Place --}}
                <section>
                    <h4 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-4 sm:mb-6 capitalize">
                        About This Place
                    </h4>
                    <div class="text-gray-600 leading-relaxed text-justify space-y-4">
                        @forelse ($splitParagraphs($destination->place_description) as $paragraph)
                            <p class="text-sm sm:text-base md:text-lg first-letter:capitalize">
                                {{ ucfirst($paragraph) }}
                            </p>
                        @empty
                            <p class="text-sm sm:text-base text-gray-400 italic">No description available for this destination.</p>
                        @endforelse
                    </div>
                </section>

                {{-- Interactive Lightbox Photo Gallery --}}
                @if (!empty($galleryUrls) && count($galleryUrls) > 0)
                    <section x-data="{
                        isOpen: false,
                        currentIndex: 0,
                        images: {{ json_encode(array_values($galleryUrls)) }},
                        open(index) {
                            this.currentIndex = index;
                            this.isOpen = true;
                            document.body.classList.add('overflow-hidden');
                        },
                        close() {
                            this.isOpen = false;
                            document.body.classList.remove('overflow-hidden');
                        },
                        next() {
                            if (this.images.length > 0) {
                                this.currentIndex = (this.currentIndex + 1) % this.images.length;
                            }
                        },
                        prev() {
                            if (this.images.length > 0) {
                                this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
                            }
                        }
                    }"
                    @keydown.window.escape="if (isOpen) close()"
                    @keydown.window.arrow-right="if (isOpen) next()"
                    @keydown.window.arrow-left="if (isOpen) prev()">

                        <div class="flex items-center justify-between mb-4 sm:mb-6">
                            <h4 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 capitalize">
                                Photo Gallery
                            </h4>
                            <span class="text-xs sm:text-sm text-gray-500 font-medium">
                                {{ count($galleryUrls) }} {{ count($galleryUrls) === 1 ? 'Photo' : 'Photos' }}
                            </span>
                        </div>

                        {{-- Gallery Grid --}}
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4">
                            @foreach ($galleryUrls as $index => $imgUrl)
                                <button type="button" @click="open({{ $index }})"
                                    class="group relative h-36 sm:h-48 md:h-56 w-full overflow-hidden rounded-2xl bg-gray-100 shadow-sm border border-gray-100 transition focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer text-left"
                                    aria-label="Open photo {{ $index + 1 }}">
                                    <img src="{{ $imgUrl }}" alt="Gallery photo {{ $index + 1 }}"
                                        class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                        loading="lazy">
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/25 transition-colors duration-300 flex items-center justify-center">
                                        <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-white/90 text-gray-800 text-xs font-semibold px-3 py-1.5 rounded-full shadow-sm backdrop-blur-xs">
                                            View Photo
                                        </span>
                                    </div>
                                </button>
                            @endforeach
                        </div>

                        {{-- Lightbox Modal --}}
                        <div x-show="isOpen" x-cloak
                            x-transition:enter="transition ease-out duration-250"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black/95 backdrop-blur-md p-4 sm:p-8"
                            @click.self="close()"
                            role="dialog" aria-modal="true" aria-label="Photo gallery lightbox">

                            {{-- Top Navigation Bar --}}
                            <div class="absolute top-4 left-4 right-4 sm:top-6 sm:left-6 sm:right-6 flex items-center justify-between text-white z-20 pointer-events-none">
                                {{-- Counter --}}
                                <div class="pointer-events-auto bg-white/10 backdrop-blur-md px-3.5 py-1.5 rounded-full text-xs sm:text-sm font-medium tracking-wide">
                                    <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
                                </div>

                                {{-- Close Button --}}
                                <button type="button" @click="close()"
                                    class="pointer-events-auto w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition cursor-pointer"
                                    aria-label="Close lightbox">
                                    <i class="fa-solid fa-xmark text-lg"></i>
                                </button>
                            </div>

                            {{-- Previous Control --}}
                            <button type="button" @click="prev()"
                                class="absolute left-2 sm:left-6 z-20 w-11 h-11 rounded-full bg-white/10 hover:bg-white/25 text-white flex items-center justify-center transition cursor-pointer backdrop-blur-xs focus:outline-none"
                                aria-label="Previous photo">
                                <i class="fa-solid fa-chevron-left text-base sm:text-lg"></i>
                            </button>

                            {{-- Main Displayed Image --}}
                            <div class="relative max-w-5xl max-h-[85vh] w-full h-full flex items-center justify-center" @click.self="close()">
                                <img :src="images[currentIndex]"
                                    class="max-h-[80vh] max-w-full rounded-xl object-contain shadow-2xl transition duration-200 select-none"
                                    :alt="'Gallery photo ' + (currentIndex + 1)">
                            </div>

                            {{-- Next Control --}}
                            <button type="button" @click="next()"
                                class="absolute right-2 sm:right-6 z-20 w-11 h-11 rounded-full bg-white/10 hover:bg-white/25 text-white flex items-center justify-center transition cursor-pointer backdrop-blur-xs focus:outline-none"
                                aria-label="Next photo">
                                <i class="fa-solid fa-chevron-right text-base sm:text-lg"></i>
                            </button>
                        </div>
                    </section>
                @endif

                {{-- History & Background --}}
                @if ($destination->place_history)
                    <section class="bg-gray-50 p-6 sm:p-8 rounded-2xl border border-gray-100">
                        <h4 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-4 capitalize">
                            History & Background
                        </h4>
                        <div class="text-gray-600 leading-relaxed space-y-4">
                            @foreach ($splitParagraphs($destination->place_history) as $paragraph)
                                <p class="text-sm sm:text-base md:text-lg first-letter:capitalize">
                                    {{ ucfirst($paragraph) }}
                                </p>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- Travel Tips & Logistics --}}
                @if ($destination->place_information)
                    <section>
                        <h4 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-4 capitalize">
                            Travel Tips & Logistics
                        </h4>
                        <div class="bg-blue-50/70 border-l-4 border-blue-500 p-5 sm:p-6 rounded-r-2xl space-y-3">
                            @foreach ($splitParagraphs($destination->place_information) as $paragraph)
                                <p class="text-sm sm:text-base md:text-lg text-blue-950 leading-relaxed first-letter:capitalize">
                                    {{ ucfirst($paragraph) }}
                                </p>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            {{-- RIGHT COLUMN: Sticky Sidebar (Map, At a Glance, Highlights) --}}
            <div class="lg:col-span-1">
                <div class="sticky top-8 space-y-6 sm:space-y-8">

                    {{-- 1. Map Card --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-2">
                        <div class="h-56 sm:h-64 w-full rounded-xl overflow-hidden relative bg-gray-100">
                            <iframe width="100%" height="100%" frameborder="0" style="border:0" loading="lazy"
                                allowfullscreen
                                src="https://maps.google.com/maps?q={{ urlencode($destination->place_name . ' ' . ($destination->province->name ?? '')) }}&t=&z=13&ie=UTF8&iwloc=&output=embed">
                            </iframe>
                        </div>
                        <div class="p-3 sm:p-4">
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($destination->place_name) }}"
                                target="_blank" rel="noopener noreferrer"
                                class="inline-flex items-center justify-center gap-2 w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm sm:text-base py-3 px-4 rounded-xl transition shadow-sm">
                                <i class="fa-solid fa-location-arrow"></i> Get Directions
                            </a>
                        </div>
                    </div>

                    {{-- 2. At A Glance Card --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h4 class="text-lg sm:text-xl font-bold text-gray-900 mb-5 border-b border-gray-100 pb-3 capitalize">
                            At A Glance
                        </h4>
                        <ul class="space-y-3 sm:space-y-4">
                            <li class="flex items-start justify-between py-1 border-b border-gray-50">
                                <span class="text-gray-500 text-sm sm:text-base">Province</span>
                                <span class="text-gray-900 font-semibold text-right text-sm sm:text-base">
                                    {{ $destination->province->name ?? 'Cambodia' }}
                                </span>
                            </li>
                            @if ($destination->category)
                                <li class="flex items-start justify-between py-1 border-b border-gray-50">
                                    <span class="text-gray-500 text-sm sm:text-base">Category</span>
                                    <span class="text-gray-900 font-semibold text-right text-sm sm:text-base">
                                        {{ $destination->category->name }}
                                    </span>
                                </li>
                            @endif
                            <li class="flex items-start justify-between py-1">
                                <span class="text-gray-500 text-sm sm:text-base">Rating</span>
                                <span class="text-gray-900 font-semibold text-sm sm:text-base">
                                    {{ $destination->rating ? number_format($destination->rating, 1) . ' / 5.0' : 'N/A' }}
                                </span>
                            </li>
                        </ul>
                    </div>

                    {{-- 3. Highlights (Minimalist, subtle bullet points, no decorative lightbulb/checkmarks) --}}
                    @if ($destination->highlights && count($destination->highlights) > 0)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <h4 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 capitalize">
                                Highlights
                            </h4>
                            <ul class="space-y-3">
                                @foreach ($destination->highlights as $highlight)
                                    <li class="flex items-start gap-3 text-sm sm:text-base text-gray-700 leading-relaxed">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600 mt-2 shrink-0"></span>
                                        <span class="first-letter:capitalize">{{ ucfirst(trim($highlight)) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    {{-- 3. EXPLORE NEARBY SECTION --}}
    @if (isset($otherPlaces) && count($otherPlaces) > 0)
        <div class="bg-gray-50 py-12 sm:py-16 border-t border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-8">
                    <h4 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 capitalize">
                        Explore Nearby
                    </h4>
                    <p class="text-gray-500 text-sm sm:text-base mt-1">
                        Other destinations you might like
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach ($otherPlaces as $place)
                        <a href="{{ route('public.destinations.show', $place->slug) }}"
                            class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 flex flex-col">
                            <div class="h-44 sm:h-48 overflow-hidden relative bg-gray-100">
                                @if ($place->thumbnail)
                                    <img src="{{ $place->thumbnail_url }}" alt="{{ $place->place_name }}"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                        loading="lazy">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">
                                        No image
                                    </div>
                                @endif
                                @if ($place->is_recommended)
                                    <span class="absolute top-2.5 right-2.5 bg-amber-500/95 backdrop-blur-xs text-white text-[11px] font-semibold px-2.5 py-0.5 rounded-full capitalize shadow-sm">
                                        Recommended
                                    </span>
                                @endif
                            </div>
                            <div class="p-4 flex-1 flex flex-col justify-between">
                                <h4 class="font-bold text-gray-900 text-base sm:text-lg mb-1 group-hover:text-blue-600 transition-colors">
                                    {{ $place->place_name }}
                                </h4>
                                <p class="text-gray-500 text-xs sm:text-sm">
                                    {{ Str::limit($place->province->name ?? 'Cambodia', 25) }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
