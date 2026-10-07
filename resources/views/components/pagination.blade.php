@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}"
        class="flex items-center justify-center mt-10 mb-6">
        <div class="flex flex-wrap items-center gap-2">

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span
                    class="flex items-center justify-center w-10 h-10 rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed border border-gray-200 shadow-sm">
                    <i class="fa-solid fa-chevron-left"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                    class="flex items-center justify-center w-10 h-10 rounded-lg bg-white text-gray-700 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-300 border border-gray-300 shadow-sm transition-all duration-200">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span
                        class="flex items-center justify-center w-10 h-10 rounded-lg bg-white border border-gray-200 text-gray-400">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span
                                class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-600 text-white border border-blue-600 font-bold shadow-md transform scale-110">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                                class="flex items-center justify-center w-10 h-10 rounded-lg bg-white text-gray-700 hover:bg-gray-50 hover:text-blue-600 border border-gray-300 shadow-sm transition-all duration-200">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                    class="flex items-center justify-center w-10 h-10 rounded-lg bg-white text-gray-700 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-300 border border-gray-300 shadow-sm transition-all duration-200">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            @else
                <span
                    class="flex items-center justify-center w-10 h-10 rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed border border-gray-200 shadow-sm">
                    <i class="fa-solid fa-chevron-right"></i>
                </span>
            @endif
        </div>
    </nav>
@endif
