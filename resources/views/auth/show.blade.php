<x-app-layout>
    <div class="relative w-full h-[60vh] min-h-[400px]">
        @if ($item->thumbnail)
            <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->place_name }}"
                class="absolute inset-0 w-full h-full object-cover">
        @else
            <div class="absolute inset-0 bg-gray-300 w-full h-full"></div>
        @endif

        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>

        <div class="absolute bottom-0 left-0 w-full p-8 md:p-16 text-white max-w-7xl mx-auto">
            <a href="{{ route('home') }}" class="mb-4 inline-flex items-center text-sm hover:text-blue-300 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Explore
            </a>
            <h1 class="font-serif text-4xl md:text-6xl font-bold mb-4">{{ $item->place_name }}</h1>
            <p class="text-gray-200 text-lg md:text-xl max-w-2xl font-light">
                {{ $item->short_description }}
            </p>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
            {{-- INFO CARD --}}
            <x-info-card class="flex flex-col justify-center">
                <h3 class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-4">At a Glance</h3>

                <div class="space-y-4">
                    <div class="flex items-center justify-between border-b border-gray-200 pb-2">
                        <span class="text-gray-600">Category</span>
                        <div class="flex gap-2">
                            <span
                                class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $item->category }}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between border-b border-gray-200 pb-2">
                        <span class="text-gray-600">Rating</span>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                            <span class="font-bold text-gray-900">{{ $item->rating ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Location</span>
                        <span class="flex items-center text-gray-900 text-sm font-medium">
                            {{ $item->location }}
                        </span>
                    </div>
                </div>
            </x-info-card>

            {{-- MAP CARD --}}
            <x-info-card class="p-0 overflow-hidden h-64 md:h-auto min-h-[250px] relative">
                <iframe width="100%" height="100%" frameborder="0" style="border:0" loading="lazy" allowfullscreen
                    src="https://maps.google.com/maps?q=${{ urlencode($item->place_name . ' ' . $item->location) }}&t=&z=13&ie=UTF8&iwloc=&output=embed">
                </iframe>
            </x-info-card>
        </div>

        <x-section-header title="Why is this place special?" subtitle="Details & History" />

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 text-gray-600 leading-relaxed mb-16">
            <div class="lg:col-span-2 space-y-6">
                @if ($item->place_history)
                    <h3 class="font-serif text-xl font-bold text-gray-800">Place History</h3>
                    <p>{{ $item->place_history }}</p>
                @endif

                <h3 class="font-serif text-xl font-bold text-gray-800 mt-8">What to Expect</h3>
                <p>{{ $item->detail_description }}</p>

                @if ($item->highlight)
                    <div class="bg-blue-50 p-6 rounded-lg border-l-4 border-blue-500 mt-6">
                        <h4 class="font-bold text-blue-900 mb-2">Highlights</h4>
                        <ul class="list-disc list-inside space-y-1 text-blue-800">
                            @foreach ($item->highlight as $highlight)
                                <li>{{ $highlight }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 sticky top-6">
                    <h3 class="font-serif text-xl font-bold text-gray-800 mb-6 text-center">The Logistics</h3>

                    <ul class="space-y-6">
                        <li class="flex items-start">
                            {{-- Location Icon --}}
                            <div class="bg-blue-100 p-2 rounded-full mr-4 text-blue-600 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-gray-400 uppercase">Location</span>
                                <span class="text-gray-800 font-medium">{{ $item->location }}</span>
                            </div>
                        </li>

                        <li class="flex items-start">
                            {{-- Clock Icon --}}
                            <div class="bg-blue-100 p-2 rounded-full mr-4 text-blue-600 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-gray-400 uppercase">Opening Hours</span>
                                <span
                                    class="text-gray-800 font-medium">{{ $item->opening_hours ?? 'Not specified' }}</span>
                            </div>
                        </li>

                        <li class="flex items-start">
                            {{-- Calendar Icon --}}
                            <div class="bg-blue-100 p-2 rounded-full mr-4 text-blue-600 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-gray-400 uppercase">Best Time</span>
                                <span
                                    class="text-gray-800 font-medium">{{ $item->best_time_to_visit ?? 'Anytime' }}</span>
                            </div>
                        </li>

                        <li class="flex items-start">
                            {{-- Money Icon --}}
                            <div class="bg-blue-100 p-2 rounded-full mr-4 text-blue-600 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-gray-400 uppercase">Entrance Fee</span>
                                <span class="text-gray-800 font-medium">{{ $item->entrance_fee ?? 'Free' }}</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        @if (isset($otherPlaces) && count($otherPlaces) > 0)
            <x-section-header title="Explore Nearby" subtitle="You might also like" />

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach ($otherPlaces as $place)
                    {{-- Ensure you have a component or standard HTML for the card --}}
                    <div
                        class="group relative bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300">
                        <div class="h-48 overflow-hidden">
                            <img src="{{ asset('storage/' . $place->thumbnail) }}" alt="{{ $place->place_name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        </div>
                        <div class="p-5">
                            <h3 class="font-serif text-lg font-bold text-gray-900 mb-1">{{ $place->place_name }}</h3>
                            <p class="text-gray-500 text-xs uppercase tracking-wide mb-4">{{ $place->location }}</p>
                            <a href="{{ route('non-user.show', $place->id) }}"
                                class="inline-block px-4 py-2 bg-blue-50 text-blue-600 text-xs font-bold rounded-lg hover:bg-blue-100 transition">EXPLORE</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</x-app-layout>
