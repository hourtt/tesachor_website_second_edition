<x-admin-layout>
    <x-slot name="title">{{ $destination->place_name }}</x-slot>

    {{-- Breadcrumb / Header --}}
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                <a href="{{ route('admins.destinations.index') }}" class="hover:text-gray-900 transition">Destinations</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                <span class="text-gray-900 font-medium">{{ $destination->place_name }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">{{ $destination->place_name }}</h1>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admins.destinations.edit', $destination->slug) }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 text-gray-700 font-semibold text-sm rounded-xl shadow-sm hover:bg-gray-50 transition">
                <i class="fa-solid fa-pen-to-square mr-2"></i> Edit
            </a>
            <form action="{{ route('admins.destinations.destroy', $destination->slug) }}" method="POST"
                onsubmit="return confirm('Are you sure you want to delete {{ $destination->place_name }}?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-white border border-red-200 text-red-600 font-semibold text-sm rounded-xl shadow-sm hover:bg-red-50 transition">
                    <i class="fa-solid fa-trash mr-2"></i> Delete
                </button>
            </form>
        </div>
    </div>

    {{-- Destination Hero Preview --}}
    <div class="relative w-full h-80 rounded-2xl overflow-hidden mb-8 shadow-sm">
        @if ($destination->thumbnail)
            <img src="{{ asset('storage/' . $destination->thumbnail) }}" alt="{{ $destination->place_name }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400">
                <i class="fa-regular fa-image text-3xl"></i>
            </div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
        <div class="absolute bottom-0 left-0 p-6 sm:p-8 text-white">
            @if ($destination->is_recommended)
                <span class="bg-amber-500 text-white text-[10px] font-bold px-2.5 py-1 rounded uppercase tracking-wider mb-2 inline-block">
                    Recommended
                </span>
            @endif
            <h2 class="text-3xl font-bold font-serif">{{ $destination->place_name }}</h2>
            <div class="flex items-center gap-4 mt-2 text-sm text-gray-200">
                @if ($destination->province)
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-map-pin text-red-400"></i> {{ $destination->province->name }}</span>
                @endif
                @if ($destination->rating)
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-star text-yellow-400"></i> {{ $destination->rating }} / 5.0</span>
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
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Short Description</h3>
                <p class="text-gray-700 leading-relaxed">{{ $destination->short_description }}</p>
            </div>

            {{-- Full Description --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Full Description</h3>
                <div class="prose max-w-none text-gray-700 leading-relaxed">
                    {!! nl2br(e($destination->place_description)) !!}
                </div>
            </div>

            @if ($destination->place_history)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">History & Background</h3>
                    <p class="text-gray-700 leading-relaxed">{!! nl2br(e($destination->place_history)) !!}</p>
                </div>
            @endif

            @if ($destination->place_information)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Logistics & Information</h3>
                    <p class="text-gray-700 leading-relaxed">{!! nl2br(e($destination->place_information)) !!}</p>
                </div>
            @endif
        </div>

        {{-- Right 1 col: Meta, Highlights & Gallery --}}
        <div class="space-y-6">
            {{-- Highlights --}}
            @if (is_array($destination->highlights) && count($destination->highlights) > 0)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-3">Highlights</h3>
                    <ul class="space-y-2">
                        @foreach ($destination->highlights as $hl)
                            <li class="flex items-center gap-2 text-sm text-gray-700">
                                <i class="fa-solid fa-check text-green-500 text-xs"></i>
                                {{ $hl }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Gallery Images --}}
            @if (is_array($destination->gallery_images) && count($destination->gallery_images) > 0)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-3">Gallery ({{ count($destination->gallery_images) }})</h3>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach ($destination->gallery_images as $gImg)
                            <div class="h-24 rounded-xl overflow-hidden border border-gray-100">
                                <img src="{{ asset('storage/' . $gImg) }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
