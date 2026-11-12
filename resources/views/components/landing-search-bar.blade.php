<section class="relative z-30 -mt-8 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <form action="{{ route('public.landing') }}#travel" method="GET"
        class="relative z-40 bg-white p-2 rounded-full shadow-2xl flex items-center border border-gray-100"
        data-aos="fade-up" data-aos-delay="600">

        {{-- Search input --}}
        <div class="grow flex items-center pl-3 sm:pl-6 min-w-0">
            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-400 shrink-0" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <input type="text" name="search" value="{{ $search }}" placeholder="{{ __('landing.search_placeholder') }}"
                :placeholder="$store.i18n.t('landing.search_placeholder')"
                class="w-full border-none focus:ring-0 text-gray-700 bg-transparent py-2 sm:py-3 pl-2 sm:px-4 text-xs sm:text-sm md:text-base outline-none">
        </div>

        <div class="w-px h-6 sm:h-8 bg-gray-200 mx-1 sm:mx-3 shrink-0"></div>

        {{-- Category dropdown --}}
        <x-custom-select name="category" variant="pill" :auto-submit="true" :searchable="false"
            :selected="$selectedCategory" :options="$categoryOptions" placeholder="{{ __('landing.all_categories') }}"
            placeholder-key="landing.all_categories" />

        {{-- Submit --}}
        <button type="submit"
            class="bg-gray-900 text-white px-5 sm:px-8 py-2.5 sm:py-3 rounded-full text-xs sm:text-sm md:text-base font-bold hover:bg-blue-600 transition shrink-0 cursor-pointer"
            x-text="$store.i18n.t('landing.search_button')">
            {{ __('landing.search_button') }}
        </button>
    </form>

    {{-- Active filter pills --}}
    @if (request()->filled('search') || request()->filled('category'))
        <div class="relative z-10 mt-4 flex flex-wrap items-center justify-center gap-2.5" data-aos="fade-up">

            {{-- Category pill --}}
            @if (request()->filled('category') && $activeCategoryName)
                <span
                    class="inline-flex items-center gap-1.5 pl-3.5 pr-1.5 py-1.5 rounded-full text-xs sm:text-sm font-medium bg-white/95 text-gray-800 border border-blue-200 shadow-sm backdrop-blur-md transition-all hover:border-blue-300">
                    <span class="text-blue-600 font-semibold"
                        x-text="$store.i18n.t('landing.filter_category') + ':'">{{ __('landing.filter_category') }}:</span>
                    <span class="text-gray-900 font-medium" @if ($activeCategoryI18nKey)
                    x-text="$store.i18n.t('{{ $activeCategoryI18nKey }}')" @endif>{{ $activeCategoryName }}</span>
                    <a href="{{ route('public.landing', request()->except(['category', 'category_id', 'page'])) }}#travel"
                        class="inline-flex items-center justify-center w-5 h-5 ml-1 rounded-full bg-gray-100 hover:bg-red-100 text-gray-500 hover:text-red-600 transition-colors focus:outline-none"
                        aria-label="Remove category filter" title="Remove category filter">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                </span>
            @endif

            {{-- Search pill --}}
            @if (request()->filled('search'))
                <span
                    class="inline-flex items-center gap-1.5 pl-3.5 pr-1.5 py-1.5 rounded-full text-xs sm:text-sm font-medium bg-white/95 text-gray-800 border border-gray-200 shadow-sm backdrop-blur-md transition-all hover:border-gray-300">
                    <span class="text-gray-500 font-semibold"
                        x-text="$store.i18n.t('landing.filter_search') + ':'">{{ __('landing.filter_search') }}:</span>
                    <span class="text-gray-900 font-medium">"{{ $search }}"</span>
                    <a href="{{ route('public.landing', request()->except(['search', 'page'])) }}#travel"
                        class="inline-flex items-center justify-center w-5 h-5 ml-1 rounded-full bg-gray-100 hover:bg-red-100 text-gray-500 hover:text-red-600 transition-colors focus:outline-none"
                        aria-label="Remove search filter" title="Remove search filter">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                </span>
            @endif
        </div>
    @endif
</section>