<x-landing>
    {{-- 1. HERO SECTION --}}
    <section id="home" class="relative h-screen w-full overflow-hidden flex items-center justify-center group">
        <div class="absolute inset-0 z-0 bg-gray-900" data-aos="zoom-out" data-aos-duration="1500">
            <video autoplay loop muted playsinline
                class="absolute inset-0 w-full h-full object-cover object-center opacity-80">
                <source src="{{ url('storage/cambodia-flag-wind-waving.mp4') }}" type="video/mp4">
            </video>
            <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-black/20 to-black/60"></div>
        </div>

        <div class="relative z-10 text-center text-white px-4">
            <h1 class="font-serif text-5xl md:text-7xl lg:text-9xl tracking-tight mb-4 drop-shadow-2xl"
                data-aos="fade-down" data-aos-duration="1000">
                Tesachor
            </h1>
            <p class="text-lg md:text-2xl font-light tracking-[0.2em] font-variant: small-caps mb-8 text-gray-100"
                data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000">
                Discover the Kingdom of Wonder
            </p>
        </div>
    </section>

    {{-- 2. FEATURED GRID --}}
    <section class="relative z-20 mt-10 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Main Featured Card --}}
            <div class="relative group h-[500px] rounded-3xl overflow-hidden shadow-2xl cursor-pointer"
                data-aos="zoom-in" data-aos-duration="500">
                <img src="/storage/container-images/1500-mountains.jpg"
                    class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110"
                    alt="Koh Rong">
                <div
                    class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-80 group-hover:opacity-100 transition-opacity duration-500">
                </div>
                <div
                    class="absolute bottom-0 left-0 p-8 text-white transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                    <span
                        class="inline-block px-3 py-1 bg-red-500/90 backdrop-blur rounded-full text-[10px] font-bold tracking-widest uppercase mb-3">Featured</span>
                    <h3 class="font-serif text-4xl mb-3 group-hover:text-red-200 transition-colors">Coastal Journey</h3>
                    <p class="font-serif text-xs text-gray-200 mb-3">Embark on a journey where the horizon has no end...
                    </p>
                    {{-- <button
                        class="flex items-center gap-2 text-sm font-semibold border-b border-white/50 pb-1 hover:border-white hover:text-red-300 transition-all">
                        Explore Now <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                    </button> --}}
                </div>
            </div>

            {{-- Side Cards --}}
            <div class="flex flex-col gap-6">
                <div class="relative group h-[240px] rounded-3xl overflow-hidden shadow-xl cursor-pointer"
                    data-aos="slide-left" data-aos-delay="200">
                    <img src="/storage/container-images/independence-monument.jpg"
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                        alt="Monument">
                    <div class="absolute inset-0 bg-black/40 group-hover:bg-black/30 transition-colors"></div>
                    <div class="absolute bottom-6 left-6 text-white">
                        <h3 class="font-serif text-2xl group-hover:text-red-200 transition-colors">Life Style</h3>
                        <p class="text-xs text-gray-200 mt-1 opacity-80 group-hover:opacity-100">Ancient traditions</p>
                    </div>
                </div>
                {{-- Bottom Card --}}
                <div class="relative group h-[240px] rounded-3xl overflow-hidden shadow-xl cursor-pointer"
                    data-aos="fade-left" data-aos-delay="200" data-aos-once="true">

                    <img src="/storage/container-images/phnom-penh-royal-palace.jpg"
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 will-change-transform"
                        alt="Royal Palace">
                    <div class="absolute inset-0 bg-black/40 group-hover:bg-black/30 transition-colors"></div>

                    <div
                        class="absolute bottom-0 left-0 p-8 text-white transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                        <h3 class="font-serif text-2xl group-hover:text-red-200 transition-colors">Nation Soul</h3>
                        <p class="font-serif text-xs text-gray-200 mb-2 line-clamp-2">
                            The Royal Palace stands as the nation’s soul, symbolizing Cambodia’s monarchy.
                        </p>
                        <p class="text-xs text-red-200 mt-1 opacity-90 group-hover:opacity-100 font-semibold">
                            Royal Palace
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. WHO WE ARE --}}
    <section id="about" class="py-10 bg-transparent overflow-hidden">
        <div class="max-w-6xl mx-auto px-4 grid md:grid-cols-2 gap-16 items-center">
            <div class="space-y-8" data-aos="fade-right" data-aos-duration="1000">
                <div class="space-y-2">
                    <span class="text-red-500 font-bold tracking-widest text-sm capitalize">Who we are</span>
                    <h2 class="font-serif text-5xl text-gray-900 leading-tight">Authentic Cambodian <br><span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-orange-500">Adventures</span>
                    </h2>
                </div>
                <p class="text-gray-600 leading-relaxed text-lg text-center">
                    <span class="font-bold text-blue-600"><i class="font-playfair">Tesachor</i></span> website is your
                    guide to discovering the true beauty of Cambodia. From ancient temple to a
                    peaceful villages, explore Khmer nature, culture, and history in one place. Start your travel
                    journey and make an unforgettable moment with your friend, family and your lover in the land of
                    beautiful culture, Cambodia.
                </p>
            </div>
            <div class="bg-white p-8 rounded-3xl shadow-xl border border-gray-100" data-aos="fade-left"
                data-aos-duration="1000">
                <div class="flex items-center gap-6 mb-6">
                    <img src="https://i.pravatar.cc/150?img=32"
                        class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-lg" alt="Author">
                    <div>
                        <h4 class="font-bold text-xl text-gray-900">Jessica Kim</h4>
                        <p class="text-sm text-red-500 font-medium">Travel Blogger</p>
                    </div>
                </div>
                <p class="text-gray-600 italic text-lg text-center">" I came to Cambodia with curiosity, but I left with
                    deep
                    respect and love for this country. The temples are powerful, the nature is peaceful, and the culture
                    feels real and alive. What touched me most were the people. They are warm, kind, and always smiling.
                    Don’t worry about exploring Cambodia. You will never forget what you experience in this country. 😊"
                </p>
            </div>
        </div>
    </section>

    {{-- 4. RECOMMENDED TRAVEL --}}
    <section id="travel" class="py-14 bg-transparent">
        <div class="max-w-7xl mx-auto px-4 mb-16 text-center">
            <h2 class="font-serif text-2xl md:text-5xl text-gray-900" data-aos="fade-up">Recommended for You</h2>
        </div>

        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            @forelse ($destinations as $index => $tour)
                <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500"
                    data-aos="zoom-in-up" data-aos-delay="{{ $index * 100 }}">

                    <div class="relative h-64 overflow-hidden">
                        <img src="{{ asset('storage/' . $tour->thumbnail) }}" alt="{{ $tour->place_name }}"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    </div>

                    <div class="p-8">
                        <h3 class="font-serif text-2xl font-semibold mb-2">{{ $tour->place_name }}</h3>
                        <p class="text-l text-black mb-3 line-clamp-2">{{ $tour->short_description }}</p>

                        {{-- DYNAMIC LINK: Checks if User is Admin --}}
                        <a href="{{ Auth::guard('admins')->check() ? route('admins.destinations.show', $tour->slug) : route('destinations.show', $tour->slug) }}"
                            class="inline-flex items-center text-sm font-bold  tracking-wider text-gray-900 hover:text-red-600 transition-colors">
                            Read More
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-1 md:col-span-3 text-center py-12">
                    <div class="bg-white rounded-2xl p-8 shadow-sm border border-dashed border-gray-300">
                        <p class="text-gray-500 italic mb-4">No recommended places found yet.</p>
                        @if (Auth::guard('admins')->check())
                            <p class="text-sm text-gray-400">Add a destination and check "Recommended" to see it here.
                            </p>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>
    </section>

    {{-- 5. Provinces & Cities Section --}}
    <section id="stories" class="py-14 bg-transparent">
        <div class="max-w-7xl mx-auto px-4 mb-16">
            <div
                class="flex flex-col md:flex-row justify-between items-end md:items-center text-center md:text-left gap-4">
                <div class="w-full md:w-auto">
                    <span class="text-red-500 font-bold tracking-widest text-sm capitalize mb-2 block"
                        data-aos="fade-down">Explore</span>
                    <h2 class="font-serif text-4xl italic text-gray-800" data-aos="fade-up">Provinces & Cities</h2>
                </div>

                {{-- ADMIN ONLY: Add Button --}}
                @if (Auth::guard('admins')->check())
                    <div data-aos="fade-left" class="flex flex-wrap justify-center gap-3">
                        {{-- * See province/city list* --}}
                        <a href="{{ route('admins.provinces.index') }}"
                            class="inline-flex items-center px-6 py-3 bg-white text-gray-900 border border-gray-200 rounded-full hover:bg-gray-50 hover:text-red-600 transition shadow-md text-sm font-bold uppercase tracking-wider">
                            <i class="fa-solid fa-list-ul mr-2"></i>
                            See more Provinces/City
                        </a>
                        {{-- *Add province/city* --}}
                        <a href="{{ route('admins.provinces.create') }}"
                            class="inline-flex items-center px-6 py-3 bg-gray-900 text-white rounded-full hover:bg-red-600 transition shadow-lg text-sm font-bold uppercase tracking-wider">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            Add Province/City
                        </a>
                    </div>
                @endif

            </div>
        </div>
        {{-- * Province as grid layout* --}}
        {{-- Changed Grid Layout: Added lg:grid-cols-4 to make cards smaller on desktop --}}
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            {{-- * $provinces is came from AdminDashboardController* --}}
            @forelse($provinces as $index => $province)
                <a href="{{ route('admins.provinces.show', $province->slug) }}"
                    class="block rounded-2xl overflow-hidden shadow-lg group hover:-translate-y-2 transition-transform cursor-pointer relative aspect-[3/4] px-5 py-2.5 text-xs md:px-8 md:py-3 md:text-sm"
                    data-aos="flip-left" data-aos-delay="{{ $index * 50 }}">

                    <img src="{{ asset('storage/' . $province->thumbnail) }}" alt="{{ $province->name }}"
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />

                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent opacity-80 group-hover:opacity-100 transition-opacity">
                    </div>

                    <div
                        class="absolute bottom-0 left-0 w-full p-4 text-center z-10 flex flex-col items-center justify-end h-full pointer-events-none">
                        <x-province-card-title :province="$province" />
                    </div>

                    {{-- ADMIN ONLY: Quick Edit Button --}}
                    @if (Auth::guard('admins')->check())
                        <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity z-20">
                            <object class="relative">
                                <a href="{{ route('admins.provinces.edit', $province->slug) }}"
                                    class="bg-white/20 backdrop-blur-md text-white p-2 rounded-full shadow-lg hover:bg-white hover:text-blue-600 transition-all block text-sm">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </object>
                        </div>
                    @endif
                </a>
            @empty
                <div class="col-span-full text-center py-16 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-300">
                    <p class="text-gray-500 mb-4">No provinces added yet.</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- 6. Discover Cambodia (Static) --}}
    <section id="stories" class="py-24 bg-transparent">
        <div class="max-w-7xl mx-auto px-4 text-center mb-16">
            <h2 class="font-serif text-4xl italic text-gray-800">Discover Cambodia</h2>
        </div>

        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Animation: flip-left (Cards flip into view) --}}
            <div class="rounded-3xl overflow-hidden shadow-lg group hover:-translate-y-2 transition-transform"
                data-aos="flip-left" data-aos-delay="100">
                <!-- image -->
                <div class="relative h-56">
                    <img src="{{ asset('storage/khmer-food-collection.jpg') }}" alt="Cambodia Culture"
                        class="w-full h-full object-cover" />

                    <!-- dark overlay -->
                    <div class="absolute inset-0 bg-black/40"></div>

                    <!-- icon -->
                    <div
                        class="absolute top-6 left-6 w-12 h-12 bg-white/20 backdrop-blur rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                </div>

                <!-- text below -->
                <div class="p-6 bg-white">
                    <h4 class="font-serif text-2xl mb-2">Food & Drink</h4>
                    <p class="text-black">Taste the flavor of Khmer cuisine.</p>
                </div>
            </div>

            <div class="rounded-3xl overflow-hidden shadow-lg group hover:-translate-y-2 transition-transform"
                data-aos="flip-left" data-aos-delay="100">
                <!-- image -->
                <div class="relative h-56">
                    <img src="{{ asset('storage/siem-reap-local-life.png') }}" alt="Cambodia Culture"
                        class="w-full h-full object-cover" />

                    <!-- dark overlay -->
                    <div class="absolute inset-0 bg-black/40"></div>

                    <!-- icon -->
                    <div
                        class="absolute top-6 left-6 w-12 h-12 bg-white/20 backdrop-blur rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                </div>

                <!-- text below -->
                <div class="p-6 bg-white">
                    <h4 class="font-serif text-2xl mb-2 text-gray-900">Local Life</h4>
                    <p class="text-black">Meet the friendly people everywhere that always bring smiling face for you.
                    </p>
                </div>
            </div>

            <div class="rounded-3xl overflow-hidden shadow-lg group hover:-translate-y-2 transition-transform"
                data-aos="flip-left" data-aos-delay="100">
                <!-- image -->
                <div class="relative h-56">
                    <img src="{{ asset('storage/cambodia-culture.png') }}" alt="Cambodia Culture"
                        class="w-full h-full object-cover" />

                    <!-- dark overlay -->
                    <div class="absolute inset-0 bg-black/40"></div>

                    <!-- icon -->
                    <div
                        class="absolute top-6 left-6 w-12 h-12 bg-white/20 backdrop-blur rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                </div>

                <!-- text below -->
                <div class="p-6 bg-white">
                    <h4 class="font-serif text-2xl mb-2 text-gray-900">Culture</h4>
                    <p class="text-gray-600">Experience the warm culture of wonder land.</p>
                </div>

            </div>
    </section>

    {{-- 7. GOOD TO KNOW (Static) --}}
    <section class="py-10 bg-transparent">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="font-serif text-3xl md:text-4xl text-gray-900 mb-4">Good to Know</h2>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                {{-- Animation: zoom-in (Icons pop into view) --}}
                <div class="text-center group p-6" data-aos="zoom-in" data-aos-delay="0">
                    <div
                        class="w-16 h-16 mx-auto bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mb-4 group-hover:rotate-12 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-currency-exchange" viewBox="0 0 16 16">
                            <path
                                d="M0 5a5 5 0 0 0 4.027 4.905 6.5 6.5 0 0 1 .544-2.073C3.695 7.536 3.132 6.864 3 5.91h-.5v-.426h.466V5.05q-.001-.07.004-.135H2.5v-.427h.511C3.236 3.24 4.213 2.5 5.681 2.5c.316 0 .59.031.819.085v.733a3.5 3.5 0 0 0-.815-.082c-.919 0-1.538.466-1.734 1.252h1.917v.427h-1.98q-.004.07-.003.147v.422h1.983v.427H3.93c.118.602.468 1.03 1.005 1.229a6.5 6.5 0 0 1 4.97-3.113A5.002 5.002 0 0 0 0 5m16 5.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0m-7.75 1.322c.069.835.746 1.485 1.964 1.562V14h.54v-.62c1.259-.086 1.996-.74 1.996-1.69 0-.865-.563-1.31-1.57-1.54l-.426-.1V8.374c.54.06.884.347.966.745h.948c-.07-.804-.779-1.433-1.914-1.502V7h-.54v.629c-1.076.103-1.808.732-1.808 1.622 0 .787.544 1.288 1.45 1.493l.358.085v1.78c-.554-.08-.92-.376-1.003-.787zm1.96-1.895c-.532-.12-.82-.364-.82-.732 0-.41.311-.719.824-.809v1.54h-.005zm.622 1.044c.645.145.943.38.943.796 0 .474-.37.8-1.02.86v-1.674z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Currency</h3>
                    <p class="text-sm text-gray-500">Riel (៛) & USD ($) are accepted everywhere.</p>
                </div>

                <div class="text-center group p-6" data-aos="zoom-in" data-aos-delay="100">
                    <div
                        class="w-16 h-16 mx-auto bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center mb-4 group-hover:rotate-12 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Best Time</h3>
                    <p class="text-sm text-gray-500">Nov - Feb is cool and dry. Perfect for temples.</p>
                </div>

                <div class="text-center group p-6" data-aos="zoom-in" data-aos-delay="200">
                    <div
                        class="w-16 h-16 mx-auto bg-green-50 text-green-500 rounded-2xl flex items-center justify-center mb-4 group-hover:rotate-12 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064">
                            </path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Visa</h3>
                    <p class="text-sm text-gray-500">Visa on Arrival available at all major airports.</p>
                </div>

                <div class="text-center group p-6" data-aos="zoom-in" data-aos-delay="300">
                    <div
                        class="w-16 h-16 mx-auto bg-yellow-50 text-yellow-500 rounded-2xl flex items-center justify-center mb-4 group-hover:rotate-12 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Transport</h3>
                    <p class="text-sm text-gray-500">Use PassApp or Grab for easy TukTuk rides.</p>
                </div>
            </div>
        </div>
    </section>

</x-landing>