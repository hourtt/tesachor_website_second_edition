<x-admin-layout>
    <x-slot name="title">Destinations</x-slot>

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Destinations</h1>
            <p class="text-gray-500 text-sm mt-1">Manage all destinations, attractions, and travel points of interest.</p>
        </div>
        <a href="{{ route('admins.destinations.create') }}"
            class="inline-flex items-center px-4 py-2.5 bg-blue-600 text-white font-semibold text-sm rounded-xl shadow-sm hover:bg-blue-700 transition">
            <i class="fa-solid fa-plus mr-2"></i> Add Destination
        </a>
    </div>

    {{-- Search & Filter Bar --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
        <form method="GET" action="{{ route('admins.destinations.index') }}" class="flex flex-col md:flex-row items-stretch md:items-center gap-3">
            {{-- Text Search --}}
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-gray-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search by destination name or description..."
                    class="w-full pl-10 pr-4 py-2 text-sm border rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition">
            </div>

            {{-- Category Filter --}}
            <div class="w-full md:w-56">
                <select name="category_id" onchange="this.form.submit()"
                    class="w-full px-3 py-2 text-sm border rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition bg-white text-gray-700 cursor-pointer">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ (string) request('category_id') === (string) $category->id || request('category') === $category->slug ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Province Filter --}}
            <div class="w-full md:w-56">
                <select name="province_id" onchange="this.form.submit()"
                    class="w-full px-3 py-2 text-sm border rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition bg-white text-gray-700 cursor-pointer">
                    <option value="">All Provinces</option>
                    @foreach ($provinces as $province)
                        <option value="{{ $province->id }}" {{ (string) request('province_id') === (string) $province->id ? 'selected' : '' }}>
                            {{ $province->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-2 shrink-0">
                <button type="submit"
                    class="px-4 py-2 bg-gray-900 text-white rounded-xl text-sm font-semibold hover:bg-blue-600 transition shadow-sm cursor-pointer">
                    Filter
                </button>
            </div>
        </form>

        {{-- Active Filter Pills / Chips --}}
        @php
            $activeAdminCategory = null;
            if (request('category_id')) {
                $activeAdminCategory = $categories->firstWhere('id', request('category_id'))?->name;
            } elseif (request('category')) {
                $activeAdminCategory = $categories->firstWhere('slug', request('category'))?->name ?? ucfirst(request('category'));
            }

            $activeAdminProvince = null;
            if (request('province_id')) {
                $activeAdminProvince = $provinces->firstWhere('id', request('province_id'))?->name;
            }
        @endphp

        @if (request()->hasAny(['search', 'category_id', 'category', 'province_id']))
            <div class="mt-4 pt-3 border-t border-gray-100 flex flex-wrap items-center gap-2 text-xs">
                <span class="text-gray-500 font-medium">Active Filters:</span>

                {{-- Category Pill --}}
                @if ($activeAdminCategory)
                    <span class="inline-flex items-center gap-1.5 pl-3 pr-1.5 py-1 rounded-full font-medium bg-blue-50 text-blue-900 border border-blue-200/70 shadow-xs">
                        <span class="text-blue-500 font-normal">Category:</span>
                        <span class="font-semibold text-blue-950">{{ $activeAdminCategory }}</span>
                        <a href="{{ route('admins.destinations.index', request()->except(['category_id', 'category', 'page'])) }}"
                           class="inline-flex items-center justify-center w-4 h-4 ml-0.5 rounded-full bg-blue-100 hover:bg-blue-200 text-blue-700 hover:text-blue-900 transition"
                           title="Remove Category filter">
                            <i class="fa-solid fa-xmark text-[10px]"></i>
                        </a>
                    </span>
                @endif

                {{-- Province Pill --}}
                @if ($activeAdminProvince)
                    <span class="inline-flex items-center gap-1.5 pl-3 pr-1.5 py-1 rounded-full font-medium bg-emerald-50 text-emerald-900 border border-emerald-200/70 shadow-xs">
                        <span class="text-emerald-600 font-normal">Province:</span>
                        <span class="font-semibold text-emerald-950">{{ $activeAdminProvince }}</span>
                        <a href="{{ route('admins.destinations.index', request()->except(['province_id', 'page'])) }}"
                           class="inline-flex items-center justify-center w-4 h-4 ml-0.5 rounded-full bg-emerald-100 hover:bg-emerald-200 text-emerald-700 hover:text-emerald-900 transition"
                           title="Remove Province filter">
                            <i class="fa-solid fa-xmark text-[10px]"></i>
                        </a>
                    </span>
                @endif

                {{-- Search Pill --}}
                @if (request()->filled('search'))
                    <span class="inline-flex items-center gap-1.5 pl-3 pr-1.5 py-1 rounded-full font-medium bg-gray-100 text-gray-900 border border-gray-200 shadow-xs">
                        <span class="text-gray-500 font-normal">Search:</span>
                        <span class="font-semibold text-gray-950">"{{ request('search') }}"</span>
                        <a href="{{ route('admins.destinations.index', request()->except(['search', 'page'])) }}"
                           class="inline-flex items-center justify-center w-4 h-4 ml-0.5 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-700 hover:text-gray-950 transition"
                           title="Remove Search filter">
                            <i class="fa-solid fa-xmark text-[10px]"></i>
                        </a>
                    </span>
                @endif

            </div>
        @endif
    </div>

    {{-- Destinations Table / Card List --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-8">
        @if ($destinations->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/75 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-4">Destination</th>
                            <th class="px-6 py-4">Category</th>
                            <th class="px-6 py-4">Province</th>
                            <th class="px-6 py-4">Rating</th>
                            <th class="px-6 py-4">Recommended</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @foreach ($destinations as $destination)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl overflow-hidden bg-gray-100 shrink-0 border border-gray-100">
                                            @if ($destination->thumbnail)
                                                <img src="{{ $destination->thumbnail_url }}" alt="{{ $destination->place_name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                    <i class="fa-regular fa-image"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('admins.destinations.show', $destination->slug) }}" class="font-semibold text-gray-900 hover:text-blue-600 transition">
                                                {{ $destination->place_name }}
                                            </a>
                                            <p class="text-xs text-gray-400 line-clamp-1 max-w-xs">{{ $destination->short_description }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($destination->category)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-medium border border-blue-100">
                                            {{ $destination->category->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($destination->province)
                                        <a href="{{ route('admins.provinces.show', $destination->province->slug) }}"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 text-xs font-medium hover:bg-gray-200 transition">
                                            <i class="fa-solid fa-map-pin text-blue-600 text-[10px]"></i>
                                            {{ $destination->province->name }}
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($destination->rating)
                                        <span class="inline-flex items-center gap-1 text-sm font-semibold text-gray-800">
                                            <i class="fa-solid fa-star text-yellow-400 text-xs"></i>
                                            {{ number_format($destination->rating, 1) }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($destination->is_recommended)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                            Recommended
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs">No</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admins.destinations.show', $destination->slug) }}"
                                            class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition" title="View">
                                            <i class="fa-regular fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admins.destinations.edit', $destination->slug) }}"
                                            class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('admins.destinations.destroy', $destination->slug) }}" method="POST"
                                            data-confirm-delete
                                            data-title="Delete Destination?"
                                            data-name="{{ $destination->place_name }}"
                                            data-warning="Are you sure you want to delete {{ $destination->place_name }}? This action cannot be undone."
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition cursor-pointer" title="Delete">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($destinations->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $destinations->links() }}
                </div>
            @endif
        @else
            <div class="py-16 text-center">
                <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4 text-blue-600">
                    <i class="fa-solid fa-location-dot text-2xl"></i>
                </div>
                @if (request()->hasAny(['search', 'category_id', 'category', 'province_id']))
                    <h3 class="text-lg font-medium text-gray-900">No destinations match your filters</h3>
                    <p class="text-gray-500 mb-6 max-w-lg mx-auto">Try adjusting or clearing your search and filter criteria.</p>
                    <a href="{{ route('admins.destinations.index') }}"
                        class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-gray-700 bg-gray-100 hover:bg-gray-200 transition font-medium text-sm">
                        Clear Filters
                    </a>
                @else
                    <h3 class="text-lg font-medium text-gray-900">No destinations found</h3>
                    <p class="text-gray-500 mb-6 max-w-sm mx-auto">Get started by creating your first travel destination.</p>
                    <a href="{{ route('admins.destinations.create') }}"
                        class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-white bg-blue-600 hover:bg-blue-700 transition shadow-sm text-sm font-semibold">
                        <i class="fa-solid fa-plus mr-2"></i> Add Destination
                    </a>
                @endif
            </div>
        @endif
    </div>
</x-admin-layout>
