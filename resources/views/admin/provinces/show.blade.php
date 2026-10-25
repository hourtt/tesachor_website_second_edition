<x-admin-layout>
    <x-slot name="title">{{ $province->name }}</x-slot>

    {{-- Breadcrumb / Header --}}
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                <a href="{{ route('admins.provinces.index') }}" class="hover:text-gray-900 transition">Provinces</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                <span class="text-gray-900 font-medium">{{ $province->name }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">{{ $province->name }}</h1>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admins.destinations.create', ['province_id' => $province->id]) }}"
                class="inline-flex items-center px-4 py-2.5 bg-blue-600 text-white font-semibold text-sm rounded-xl shadow-sm hover:bg-blue-700 transition">
                <i class="fa-solid fa-plus mr-2"></i> Add Destination
            </a>
            <a href="{{ route('admins.provinces.edit', $province->slug) }}"
                class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-200 text-gray-700 font-semibold text-sm rounded-xl shadow-sm hover:bg-gray-50 transition">
                <i class="fa-solid fa-pen-to-square mr-2"></i> Edit
            </a>
            <form action="{{ route('admins.provinces.destroy', $province->slug) }}" method="POST"
                onsubmit="return confirm('Are you sure you want to delete {{ $province->name }}? This will also delete all its destinations.');">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center px-4 py-2.5 bg-white border border-red-200 text-white-600 font-semibold text-sm rounded-xl shadow-sm hover:bg-blue-50 transition">
                    <i class="fa-solid fa-trash mr-2"></i> Delete
                </button>
            </form>
        </div>
    </div>

    {{-- Province Details Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="md:flex">
            <div class="md:w-1/3 h-64 md:h-auto relative group">
                <img src="{{ $province->thumbnail_url }}" alt="{{ $province->name }}"
                    class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/10"></div>
            </div>

            <div class="p-6 sm:p-8 md:w-2/3 flex flex-col justify-center">
                <h2 class="text-3xl font-bold font-khmer text-gray-900 mb-2">{{ $province->name_in_khmer }}</h2>
                <p class="text-gray-500 text-lg mb-6">{{ $province->name }}</p>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                        <span class="block text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">Total Destinations</span>
                        <span class="text-3xl font-bold text-blue-700">{{ $province->destinations->count() }}</span>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Slug</span>
                        <span class="text-sm font-medium text-gray-700 font-mono break-all">{{ $province->slug }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Destinations Section --}}
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h3 class="text-xl font-bold text-gray-900">Destinations in {{ $province->name }}</h3>
            <p class="text-gray-500 text-sm mt-0.5">{{ $province->destinations->count() }} places documented</p>
        </div>
    </div>

    {{-- Destinations Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($province->destinations as $destination)
            <div class="group relative aspect-4/5 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 bg-gray-100">
                <img src="{{ $destination->thumbnail_url }}" alt="{{ $destination->place_name }}"
                    class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">

                <div class="absolute inset-0 bg-linear-to-t from-black/90 via-black/30 to-transparent opacity-80 group-hover:opacity-90 transition-opacity"></div>

                <div class="absolute top-3 right-3 flex flex-col gap-2 translate-x-12 group-hover:translate-x-0 transition-transform duration-300 z-10">
                    <a href="{{ route('admins.destinations.edit', $destination->slug) }}"
                        class="w-8 h-8 bg-white/20 backdrop-blur-md border border-white/30 text-white rounded-full flex items-center justify-center hover:bg-white hover:text-blue-600 transition shadow-sm"
                        title="Edit">
                        <i class="fa-solid fa-pen text-xs"></i>
                    </a>
                    <form action="{{ route('admins.destinations.destroy', $destination->slug) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete {{ $destination->place_name }}?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-8 h-8 bg-white/20 backdrop-blur-md border border-white/30 text-white rounded-full flex items-center justify-center hover:bg-white hover:text-white-600 transition shadow-sm"
                            title="Delete">
                            <i class="fa-solid fa-trash text-xs"></i>
                        </button>
                    </form>
                </div>

                @if ($destination->is_recommended)
                    <div class="absolute top-3 left-3 z-10">
                        <span class="bg-yellow-500 text-white text-[10px] font-bold px-2 py-1 rounded-md shadow-sm uppercase tracking-wider">
                            Recommended
                        </span>
                    </div>
                @endif

                <div class="absolute bottom-0 inset-x-0 p-5 text-center z-10">
                    <h4 class="text-lg font-bold text-white mb-1 font-serif tracking-wide drop-shadow-md">
                        {{ $destination->place_name }}
                    </h4>
                    @if ($destination->rating)
                        <div class="flex items-center justify-center gap-1 text-yellow-400 text-xs">
                            <i class="fa-solid fa-star"></i>
                            <span class="text-white font-medium">{{ $destination->rating }}</span>
                        </div>
                    @endif
                    <a href="{{ route('admins.destinations.show', $destination->slug) }}" class="absolute inset-0 z-0"></a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-2xl border-2 border-dashed border-gray-200">
                <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4 text-blue-600">
                    <i class="fa-regular fa-map-location-dot text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">No destinations yet</h3>
                <p class="text-gray-500 mb-6 max-w-sm mx-auto">Start by adding the first destination for {{ $province->name }}.</p>
                <a href="{{ route('admins.destinations.create', ['province_id' => $province->id]) }}"
                    class="inline-flex items-center justify-center px-5 py-2.5 border border-transparent font-semibold rounded-xl text-white bg-blue-600 hover:bg-blue-700 transition shadow-sm text-sm">
                    <i class="fa-solid fa-plus mr-2"></i> Add Destination
                </a>
            </div>
        @endforelse
    </div>
</x-admin-layout>
