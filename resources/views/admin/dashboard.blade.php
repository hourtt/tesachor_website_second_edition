<x-admin-layout>
    <x-slot name="title">Dashboard</x-slot>

    {{-- Page Header --}}
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Welcome back, {{ Auth::user()->name }}</h2>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        {{-- Provinces --}}
        <a href="{{ route('admins.provinces.index') }}"
            class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                    <i class="fa-solid fa-map text-blue-500"></i>
                </div>
                <span class="text-xs font-semibold text-blue-500 bg-blue-50 px-2 py-1 rounded-full">Provinces</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['provinces'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Total provinces & cities</p>
        </a>

        {{-- Destinations --}}
        <a href="{{ route('admins.destinations.index') }}"
            class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                    <i class="fa-solid fa-location-dot text-blue-600"></i>
                </div>
                <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">Places</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['destinations'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Total destinations</p>
        </a>

        {{-- Users --}}
        <a href="{{ route('admins.users.index') }}"
            class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center group-hover:bg-purple-100 transition-colors">
                    <i class="fa-solid fa-users text-purple-500"></i>
                </div>
                <span class="text-xs font-semibold text-purple-500 bg-purple-50 px-2 py-1 rounded-full">Users</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['users'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Registered users</p>
        </a>

        {{-- Recommended --}}
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-star text-amber-500"></i>
                </div>
                <span class="text-xs font-semibold text-amber-500 bg-amber-50 px-2 py-1 rounded-full">Featured</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['recommended'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Recommended places</p>
        </div>
    </div>

    {{-- Recent Content --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Recent Provinces --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-50">
                <h3 class="font-semibold text-gray-900">Recent Provinces</h3>
                <a href="{{ route('admins.provinces.create') }}"
                    class="text-xs font-semibold text-white-600 hover:text-white-700 flex items-center gap-1">
                    <i class="fa-solid fa-plus text-[10px]"></i> Add
                </a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse ($recentProvinces as $province)
                    <div class="flex items-center gap-4 px-6 py-3 hover:bg-gray-50 transition-colors">
                        <img src="{{ asset('storage/' . $province->thumbnail) }}" alt="{{ $province->name }}"
                            class="w-10 h-10 rounded-xl object-cover flex-shrink-0 border border-gray-100">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $province->name }}</p>
                            <p class="text-xs text-gray-400">{{ $province->destinations->count() }} destinations</p>
                        </div>
                        <div class="flex gap-2 flex-shrink-0">
                            <a href="{{ route('admins.provinces.show', $province->slug) }}"
                                class="text-gray-400 hover:text-blue-500 transition-colors text-sm">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('admins.provinces.edit', $province->slug) }}"
                                class="text-gray-400 hover:text-amber-500 transition-colors text-sm">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center">
                        <p class="text-sm text-gray-400">No provinces yet.</p>
                        <a href="{{ route('admins.provinces.create') }}" class="text-xs text-blue-600 hover:underline mt-1 inline-block">Add the first one →</a>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Destinations --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-50">
                <h3 class="font-semibold text-gray-900">Recent Destinations</h3>
                <a href="{{ route('admins.destinations.create') }}"
                    class="text-xs font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                    <i class="fa-solid fa-plus text-[10px]"></i> Add
                </a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse ($recentDestinations as $destination)
                    <div class="flex items-center gap-4 px-6 py-3 hover:bg-gray-50 transition-colors">
                        <img src="{{ asset('storage/' . $destination->thumbnail) }}" alt="{{ $destination->place_name }}"
                            class="w-10 h-10 rounded-xl object-cover flex-shrink-0 border border-gray-100">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $destination->place_name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ $destination->province->name ?? '—' }}</p>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            @if ($destination->is_recommended)
                                <span class="text-[10px] font-semibold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">Featured</span>
                            @endif
                            <a href="{{ route('admins.destinations.edit', $destination->slug) }}"
                                class="text-gray-400 hover:text-amber-500 transition-colors text-sm">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center">
                        <p class="text-sm text-gray-400">No destinations yet.</p>
                        <a href="{{ route('admins.destinations.create') }}" class="text-xs text-blue-600 hover:underline mt-1 inline-block">Add the first one →</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</x-admin-layout>
