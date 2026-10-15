<x-landing>
    {{-- 1. HERO SECTION: ZOOM & FADE --}}
    <section id="home" class="relative h-screen w-full overflow-hidden flex items-center justify-center group">
        {{-- Animation: zoom-out (Creates a cinematic opening effect) --}}
        <div class="absolute inset-0 z-0 bg-gray-900" data-aos="zoom-out" data-aos-duration="2000">
            <video autoplay loop muted playsinline
                class="absolute inset-0 w-full h-full object-cover object-center opacity-80" id="myVideo">
                <source src="{{ url('storage/cambodia-flag-wind-waving.mp4') }}" type="video/mp4">
            </video>
            <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-black/20 to-black/60"></div>
        </div>

        <div class="relative z-10 text-center text-white px-4">
            {{-- Animation: fade-down (Headline drops in from top) --}}
            <h1 class="font-serif text-5xl md:text-7xl lg:text-9xl tracking-tight mb-4 drop-shadow-2xl"
                data-aos="fade-down" data-aos-duration="1000">
                Tesachor
            </h1>
            {{-- Animation: fade-up (Subtitle rises from bottom) --}}
            <p class="text-lg md:text-2xl font-light tracking-[0.2em] font-variant: small-caps mb-8 text-gray-100"
                data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000">
                Discover the Kingdom of Wonder
            </p>
            {{-- Call to Action --}}
            <div data-aos="fade-up" data-aos-delay="400" data-aos-duration="1000">
                <a href="#travel"
                    class="inline-block px-8 py-4 bg-blue-600 text-white font-bold rounded-full hover:bg-blue-700 hover:scale-105 transition-all shadow-lg text-sm md:text-base tracking-wider uppercase">
                    Start Your Journey
                </a>
            </div>
        </div>
    </section>

    {{-- SEARCH BAR --}}
    <section class="relative z-30 -mt-8 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-2 rounded-full shadow-2xl flex items-center border border-gray-100" data-aos="fade-up"
            data-aos-delay="600">
            <div class="flex-grow flex items-center pl-6">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" placeholder="Where do you want to go?"
                    class="w-full border-none focus:ring-0 text-gray-700 bg-transparent py-3 px-4 outline-none">
            </div>
            <div class="hidden md:block w-px h-8 bg-gray-200 mx-4"></div>
            {{-- Category Dropdown --}}
            <x-category-dropdown />

            <button
                class="bg-gray-900 text-white px-8 py-3 rounded-full font-bold hover:bg-blue-600 transition flex-shrink-0">
                Search
            </button>
        </div>
    </section>

    {{-- 2. FEATURED GRID: SLIDE & ZOOM --}}
    <section class="relative z-20 mt-10 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- LEFT COLUMN: Main Feature --}}
            {{-- Added 'data-aos-once' to stop flickering on scroll --}}
            <div class="relative group h-[500px] rounded-3xl overflow-hidden shadow-2xl cursor-pointer"
                data-aos="zoom-in" data-aos-duration="800" data-aos-once="true">

                {{-- Image --}}
                <img src="/storage/container-images/1500-mountains.jpg"
                    class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 will-change-transform"
                    alt="Koh Rong">

                {{-- Gradient Overlay --}}
                <div
                    class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-80 group-hover:opacity-100 transition-opacity duration-500">
                </div>

                {{-- Text Content --}}
                <div
                    class="absolute bottom-0 left-0 p-8 text-white transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                    <span
                        class="inline-block px-3 py-1 bg-blue-500/90 backdrop-blur rounded-full text-[10px] font-bold tracking-widest uppercase mb-3">
                        Featured
                    </span>
                    <h3 class="font-serif text-4xl mb-3 group-hover:text-white-200 transition-colors">
                        Coastal Journey
                    </h3>
                    <p class="font-serif text-xs text-gray-200 mb-3 line-clamp-3">
                        Embark on a journey where the horizon has no end. From the pristine coastlines to the lush
                        countryside, travel in Cambodia is more than just sightseeing...
                    </p>
                </div>
            </div>

            {{-- RIGHT COLUMN: Stacked Cards --}}
            <div class="flex flex-col gap-6">
                {{-- Top Card --}}
                {{-- Changed slide-left to fade-left for smoother entry --}}
                <div class="relative group h-[240px] rounded-3xl overflow-hidden shadow-xl cursor-pointer"
                    data-aos="fade-left" data-aos-delay="100" data-aos-once="true">

                    <img src="/storage/container-images/independence-monument.jpg"
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 will-change-transform"
                        alt="Independent Monument">
                    <div class="absolute inset-0 bg-black/40 group-hover:bg-black/30 transition-colors"></div>

                    <div
                        class="absolute bottom-0 left-0 p-8 text-white transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                        <h3 class="font-serif text-2xl group-hover:text-white-200 transition-colors">Life Style</h3>
                        <p class="font-serif text-xs text-gray-200 mb-2 line-clamp-2">
                            Immerse yourself in the rhythm of the Kingdom. Where ancient traditions meet modern energy.
                        </p>
                        <p class="text-xs text-white-200 mt-1 opacity-90 group-hover:opacity-100 font-semibold">
                            Ancient traditions
                        </p>
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
                        <h3 class="font-serif text-2xl group-hover:text-white-200 transition-colors">Nation Soul</h3>
                        <p class="font-serif text-xs text-gray-200 mb-2 line-clamp-2">
                            The Royal Palace stands as the nation’s soul, symbolizing Cambodia’s monarchy.
                        </p>
                        <p class="text-xs text-white-200 mt-1 opacity-90 group-hover:opacity-100 font-semibold">
                            Royal Palace
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. WHO WE ARE: FADE DIRECTIONS --}}
    <section id="about" class="py-16 bg-transparent overflow-hidden">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up" data-aos-duration="1000">
                <span class="text-white-500 font-bold tracking-widest text-sm uppercase mb-3 block">Who we are</span>
                <h2 class="font-serif text-4xl md:text-5xl text-gray-900 leading-tight mb-6">Authentic Cambodian <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-orange-500">Adventures</span>
                </h2>
                <p class="text-gray-600 leading-relaxed text-lg">
                    <span class="font-bold text-gray-900"><i class="font-serif">Tesachor</i></span> is your ultimate
                    guide to discovering the true beauty of Cambodia. From ancient temples to peaceful villages, explore
                    Khmer nature, culture, and history in one place.
                </p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl transition-shadow text-center"
                    data-aos="fade-up" data-aos-delay="100">
                    <div
                        class="w-14 h-14 mx-auto bg-blue-50 text-white-500 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-xl text-gray-900 mb-3">Explore Anywhere</h3>
                    <p class="text-gray-600 text-sm">Discover hidden gems and popular destinations across all 25
                        provinces.</p>
                </div>
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl transition-shadow text-center"
                    data-aos="fade-up" data-aos-delay="200">
                    <div
                        class="w-14 h-14 mx-auto bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-xl text-gray-900 mb-3">Rich Culture</h3>
                    <p class="text-gray-600 text-sm">Immerse yourself in authentic traditions, delicious cuisine, and
                        local life.</p>
                </div>
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl transition-shadow text-center"
                    data-aos="fade-up" data-aos-delay="300">
                    <div
                        class="w-14 h-14 mx-auto bg-green-50 text-green-500 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-xl text-gray-900 mb-3">Unforgettable</h3>
                    <p class="text-gray-600 text-sm">Create lasting memories with your friends and family in the
                        Kingdom of Wonder.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. RECOMMENDED TRAVEL --}}
    <section id="travel" class="py-10 bg-transparent">
        <div class="max-w-7xl mx-auto px-4 mb-16 text-center">
            <h2 class="font-serif text-4xl md:text-5xl text-gray-900" data-aos="fade-up">Recommended for You</h2>
        </div>

        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
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

                        <a href="{{ route('public.destinations.show', $tour->slug) }}"
                            class="inline-flex items-center text-sm font-bold uppercase tracking-wider text-gray-900 hover:text-white-600 transition-colors">
                            Read More
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="bg-white rounded-2xl p-8 shadow-sm border border-dashed border-gray-300">
                        <p class="text-gray-500 italic mb-5">No recommended places found yet.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </section>

    {{-- 5. Explore province & city --}}
    <section id="provinces" class="py-10 bg-transparent">
        <div class="max-w-5xl mx-auto px-4 mb-16">
            <div
                class="flex flex-col md:flex-row justify-between items-end md:items-center text-center md:text-left gap-4 py-4">
                <div class="w-full md:w-auto">
                    <span class="text-white-500 font-bold tracking-widest text-sm capitalize mb-2 block"
                        data-aos="fade-down">Explore</span>
                    <h2 class="font-serif text-4xl italic text-gray-800" data-aos="fade-up">Provinces & Cities</h2>
                </div>

                <div data-aos="fade-left" class="flex flex-wrap justify-center gap-3">
                    {{-- * See province/city list* --}}
                    <a href="{{ route('public.provinces.index') }}"
                        class="inline-flex items-center px-6 py-3 bg-white text-gray-900 border border-gray-200 rounded-full hover:bg-gray-50 hover:text-white-600 transition shadow-md text-sm font-bold uppercase tracking-wider">
                        <i class="fa-solid fa-list-ul mr-2"></i>
                        See Provinces/City List
                    </a>
                </div>
            </div>
            {{-- * Province as grid layout* --}}
            {{-- Changed Grid Layout: Added lg:grid-cols-4 to make cards smaller on desktop --}}
            <div class="max-w-7xl mx-auto px-4 grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                {{-- * $provinces is came from AdminDashboardController* --}}
                @forelse($provinces as $index => $province)
                    {{-- DYNAMIC LINK: Checks if User is Admin --}}
                    <a href="{{ route('public.provinces.show', $province->slug) }}"
                        class="block rounded-2xl overflow-hidden shadow-lg group hover:-translate-y-2 transition-transform cursor-pointer relative aspect-[3/4] px-5 py-2.5 text-xs md:px-8 md:py-3 md:text-sm"
                        data-aos="flip-left" data-aos-delay="{{ $index * 50 }}">

                        {{-- Full Image Background --}}
                        <img src="{{ asset('storage/' . $province->thumbnail) }}" alt="{{ $province->name }}"
                            class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />

                        {{-- Gradient Overlay --}}
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent opacity-80 group-hover:opacity-100 transition-opacity">
                        </div>

                        {{-- Text Content positioned at Bottom Center --}}
                        <div
                            class="absolute bottom-0 left-0 w-full p-4 text-center z-10 flex flex-col items-center justify-end h-full pointer-events-none">
                            <x-province-card-title :province="$province" />
                        </div>

                    </a>
                @empty
                    <div
                        class="col-span-full text-center py-16 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-300">
                        <p class="text-gray-500 mb-4">No provinces added yet.</p>
                    </div>
                @endforelse
            </div>
    </section>

    {{-- 6. Discover Cambodia --}}
    <section id="lifestyle" class="py-10 bg-transparent">
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

    {{-- 7. GOOD TO KNOW: ICON ZOOM --}}
    <section class="py-10 bg-transparent">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="font-serif text-3xl md:text-4xl text-gray-900 mb-4">Good to Know</h2>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                {{-- Animation: zoom-in (Icons pop into view) --}}
                <div class="text-center group p-6" data-aos="zoom-in" data-aos-delay="0">
                    <div
                        class="w-16 h-16 mx-auto bg-blue-50 text-white-500 rounded-2xl flex items-center justify-center mb-4 group-hover:rotate-12 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
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
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
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
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Transport</h3>
                    <p class="text-sm text-gray-500">Use PassApp or Grab for easy TukTuk rides.</p>
                </div>
            </div>
        </div>
    </section>
</x-landing>
