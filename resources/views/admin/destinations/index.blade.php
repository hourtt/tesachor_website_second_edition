<x-admin-layout>
    <x-slot name="title">Destinations</x-slot>

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Destinations</h1>
            <p class="text-gray-500 text-sm mt-1">Manage all destinations, attractions, and travel points of interest.</p>
        </div>
    </div>

    {{-- Destinations Table / Card List --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-8">
        @if ($destinations->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/75 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-4">Destination</th>
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
                                                <img src="{{ asset('storage/' . $destination->thumbnail) }}" alt="{{ $destination->place_name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                    <i class="fa-regular fa-image"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('admins.destinations.show', $destination->slug) }}" class="font-semibold text-gray-900 hover:text-white-600 transition">
                                                {{ $destination->place_name }}
                                            </a>
                                            <p class="text-xs text-gray-400 line-clamp-1 max-w-xs">{{ $destination->short_description }}</p>
                                        </div>
                                    </div>
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
                                            onsubmit="return confirm('Are you sure you want to delete {{ $destination->place_name }}?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-400 hover:text-white-600 hover:bg-blue-50 rounded-lg transition" title="Delete">
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
                <h3 class="text-lg font-medium text-gray-900">No destinations found</h3>
                <p class="text-gray-500 mb-6 max-w-sm mx-auto">Get started by creating your first travel destination.</p>
                <a href="{{ route('admins.destinations.create') }}"
                    class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-white bg-blue-600 hover:bg-blue-700 transition shadow-sm text-sm font-semibold">
                    <i class="fa-solid fa-plus mr-2"></i> Add Destination
                </a>
            </div>
        @endif
    </div>
</x-admin-layout>
