<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">

        {{-- Breadcrumb / Header (Public Navigation Only - No Admin Actions) --}}
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('public.landing') }}" class="hover:text-blue-600 transition">Home</a>
                <x-icon name="chevron-right" class="text-[10px] text-gray-400" />
                @if ($destination->province)
                    <a href="{{ route('public.provinces.show', $destination->province->slug) }}"
                        class="hover:text-blue-600 transition">{{ $destination->province->name }}</a>
                    <x-icon name="chevron-right" class="text-[10px] text-gray-400" />
                @endif
                <span class="text-gray-900 font-medium">{{ $destination->place_name }}</span>
            </div>

            <a href="{{ route('public.landing') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 font-medium text-sm rounded-xl shadow-xs hover:bg-gray-50 transition">
                <x-icon name="arrow-left" class="text-xs" /> Back to Home
            </a>
        </div>

        {{-- Destination Hero Preview --}}
        <div class="relative w-full h-80 rounded-2xl overflow-hidden mb-8 shadow-sm">
            @if ($destination->thumbnail)
                <img src="{{ $destination->thumbnail_url }}" alt="{{ $destination->place_name }}"
                    class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400">
                    <x-icon name="image" class="text-3xl" />
                </div>
            @endif
            <div class="absolute inset-0 bg-linear-to-t from-black/80 via-black/30 to-transparent"></div>
            <div class="absolute bottom-0 left-0 p-6 sm:p-8 text-white">
                @if ($destination->is_recommended)
                    <span
                        class="bg-amber-500 text-white text-[10px] font-bold px-2.5 py-1 rounded uppercase tracking-wider mb-2 inline-block">
                        Recommended
                    </span>
                @endif
                <div class="flex items-baseline gap-3">
                    @if ($destination->place_name_in_khmer)
                        <h2 class="font-khmer text-4xl font-bold text-khmer">
                            {{ $destination->place_name_in_khmer }}
                        </h2>
                    @endif

                    <h2 class="font-sans text-3xl font-bold">
                        ({{ $destination->place_name }})
                    </h2>
                </div>
                <div class="flex flex-wrap items-center gap-4 mt-2 text-sm text-gray-200">
                    @if ($destination->province)
                        <a href="{{ route('public.provinces.show', $destination->province->slug) }}"
                            class="flex items-center gap-1.5 hover:text-white transition">
                            <x-icon name="map-pin" class="text-blue-400" /> {{ $destination->province->name }}
                        </a>
                    @endif
                    @if ($destination->category)
                        <span class="flex items-center gap-1.5"><x-icon name="tag" class="text-emerald-400" />
                            {{ $destination->category->name }}</span>
                    @endif
                    @if ($destination->rating)
                        <span class="flex items-center gap-1.5"><x-icon name="star" class="text-yellow-400" />
                            {{ number_format($destination->rating, 1) }} / 5.0</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left 2 cols: Descriptions & Details --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Short Description --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-gray-400 capitalize tracking-wider mb-2">Short Description</h3>
                    @if ($destination->short_description_in_khmer)
                        <div class="prose max-w-none font-khmer leading-loose text-gray-600 mt-1 pt-3 border-t border-gray-100">
                            {!! !preg_match('/<[a-z][\s\S]*>/i', $destination->short_description_in_khmer) ? nl2br(e($destination->short_description_in_khmer)) : $destination->short_description_in_khmer !!}
                        </div>
                    @endif
                    <div class="prose max-w-none text-gray-700 leading-relaxed mt-4">
                        {!! !preg_match('/<[a-z][\s\S]*>/i', $destination->short_description) ? nl2br(e($destination->short_description)) : $destination->short_description !!}
                    </div>
                </div>

                {{-- Full Description --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-gray-400 capitalize tracking-wider mb-2">Full Description</h3>
                    @if ($destination->place_description_in_khmer)
                        <div class="prose max-w-none font-khmer leading-loose text-gray-600 mt-1 pt-3 border-t border-gray-100">
                            {!! !preg_match('/<[a-z][\s\S]*>/i', $destination->place_description_in_khmer) ? nl2br(e($destination->place_description_in_khmer)) : $destination->place_description_in_khmer !!}
                        </div>
                    @endif
                    <div class="prose max-w-none text-gray-700 leading-relaxed mt-4">
                        {!! !preg_match('/<[a-z][\s\S]*>/i', $destination->place_description) ? nl2br(e($destination->place_description)) : $destination->place_description !!}
                    </div>
                </div>

                @if ($destination->place_history || $destination->place_history_in_khmer)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-gray-400 capitalize tracking-wider mb-2">History & Background</h3>
                        @if ($destination->place_history_in_khmer)
                            <div class="prose max-w-none font-khmer leading-loose text-gray-600 mt-1 pt-3 border-t border-gray-100">
                                {!! !preg_match('/<[a-z][\s\S]*>/i', $destination->place_history_in_khmer) ? nl2br(e($destination->place_history_in_khmer)) : $destination->place_history_in_khmer !!}
                            </div>
                        @endif
                        @if ($destination->place_history)
                            <div class="prose max-w-none text-gray-700 leading-relaxed mt-4">
                                {!! !preg_match('/<[a-z][\s\S]*>/i', $destination->place_history) ? nl2br(e($destination->place_history)) : $destination->place_history !!}
                            </div>
                        @endif
                    </div>
                @endif

                @if ($destination->place_information || $destination->place_information_in_khmer)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-gray-400 capitalize tracking-wider mb-2">Logistics & Information</h3>
                        @if ($destination->place_information_in_khmer)
                            <div class="prose max-w-none font-khmer leading-loose text-gray-600 mt-1 pt-3 border-t border-gray-100">
                                {!! !preg_match('/<[a-z][\s\S]*>/i', $destination->place_information_in_khmer) ? nl2br(e($destination->place_information_in_khmer)) : $destination->place_information_in_khmer !!}
                            </div>
                        @endif
                        @if ($destination->place_information)
                            <div class="prose max-w-none text-gray-700 leading-relaxed mt-4">
                                {!! !preg_match('/<[a-z][\s\S]*>/i', $destination->place_information) ? nl2br(e($destination->place_information)) : $destination->place_information !!}
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Right 1 col: Meta, Highlights & Gallery --}}
            <div class="space-y-6">
                {{-- Highlights --}}
                @php
                    $enHighlights = is_array($destination->highlights) ? array_filter($destination->highlights) : [];
                    $kmHighlights = is_array($destination->highlights_in_khmer)
                        ? array_filter($destination->highlights_in_khmer)
                        : [];
                    $hasHighlights = count($enHighlights) > 0 || count($kmHighlights) > 0;
                @endphp
                @if ($hasHighlights)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-3">Highlights</h3>
                        <ul class="space-y-2">
                            @foreach ($enHighlights as $i => $hl)
                                <li class="flex flex-col gap-0.5">
                                    <span class="flex items-center gap-2 text-sm text-gray-700">
                                        <x-icon name="check" class="text-green-500 text-xs shrink-0" />
                                        {{ $hl }}
                                    </span>
                                    @if (!empty($kmHighlights[$i]))
                                        <span
                                            class="font-khmer text-sm text-gray-500 pl-5">{{ $kmHighlights[$i] }}</span>
                                    @endif
                                </li>
                            @endforeach
                            {{-- Show any extra Khmer-only highlights --}}
                            @foreach (array_slice($kmHighlights, count($enHighlights)) as $kmExtra)
                                <li class="flex items-center gap-2 font-khmer text-sm text-gray-500">
                                    <x-icon name="check" class="text-green-500 text-xs shrink-0" />
                                    {{ $kmExtra }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Gallery Images with Lightbox --}}
                @if (is_array($destination->gallery_images) && count($destination->gallery_images) > 0)
                    @php
                        $galleryUrls = $destination->gallery_urls ?? [];
                    @endphp
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6" x-data="{
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
                        @keydown.window.escape="if (isOpen) close()" @keydown.window.arrow-right="if (isOpen) next()"
                        @keydown.window.arrow-left="if (isOpen) prev()">

                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Gallery
                                ({{ count($destination->gallery_images) }})</h3>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            @foreach ($galleryUrls as $index => $gUrl)
                                <button type="button" @click="open({{ $index }})"
                                    class="group relative h-24 rounded-xl overflow-hidden border border-gray-100 bg-gray-50 cursor-pointer focus:outline-none">
                                    <img src="{{ $gUrl }}"
                                        class="w-full h-full object-cover transition duration-300 group-hover:scale-105"
                                        loading="lazy">
                                    <div
                                        class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                                        <x-icon name="eye"
                                            class="text-white opacity-0 group-hover:opacity-100 transition-opacity text-sm drop-shadow" />
                                    </div>
                                </button>
                            @endforeach
                        </div>

                        {{-- Lightbox Modal --}}
                        <div x-show="isOpen" x-cloak x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 backdrop-blur-sm p-4"
                            @click.self="close()" role="dialog" aria-modal="true">

                            <div class="absolute top-4 right-4 flex items-center gap-3 text-white z-20">
                                <span class="text-sm font-medium bg-white/10 px-3 py-1 rounded-full"><span
                                        x-text="currentIndex + 1"></span> / <span
                                        x-text="images.length"></span></span>
                                <button type="button" @click="close()"
                                    class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition cursor-pointer">
                                    <x-icon name="xmark" class="text-base" />
                                </button>
                            </div>

                            <button type="button" @click="prev()"
                                class="absolute left-4 z-20 w-10 h-10 rounded-full bg-white/10 hover:bg-white/25 text-white flex items-center justify-center transition cursor-pointer">
                                <x-icon name="chevron-left" class="text-base" />
                            </button>

                            <div class="relative max-w-4xl max-h-[85vh] w-full flex items-center justify-center"
                                @click.self="close()">
                                <img :src="images[currentIndex]"
                                    class="max-h-[80vh] max-w-full rounded-xl object-contain shadow-2xl"
                                    :alt="'Photo ' + (currentIndex + 1)">
                            </div>

                            <button type="button" @click="next()"
                                class="absolute right-4 z-20 w-10 h-10 rounded-full bg-white/10 hover:bg-white/25 text-white flex items-center justify-center transition cursor-pointer">
                                <x-icon name="chevron-right" class="text-base" />
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
