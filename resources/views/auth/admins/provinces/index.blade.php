<x-app-layout :hideNav="true">
    {{-- Font Awesome (Ensuring icons load) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row justify-between items-end mb-8 gap-4 border-b border-gray-100 pb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Provinces & Cities</h1>
                <p class="text-gray-500 mt-2">Manage the {{ $provinces->total() }} locations available in the system.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admins.dashboard') }}"
                    class="text-gray-500 hover:text-gray-700 font-medium text-sm transition-colors mr-2">
                    Back to Dashboard
                </a>
                <a href="{{ route('admins.provinces.create') }}"
                    class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white font-semibold text-sm rounded-xl shadow-md hover:bg-blue-700 hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-plus mr-2"></i> Add New Province
                </a>
            </div>
        </div>

        {{-- GRID LAYOUT --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
            @forelse($provinces as $province)
                <div
                    class="group relative aspect-[3/4] rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 bg-gray-100">

                    {{-- 1. Full Background Image --}}
                    <img src="{{ asset('storage/' . $province->thumbnail) }}" alt="{{ $province->name }}"
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">

                    {{-- 2. Gradient Overlay --}}
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent opacity-80 group-hover:opacity-90 transition-opacity">
                    </div>

                    {{-- 3. Floating Action Buttons (Top Right) --}}
                    <div
                        class="absolute top-3 right-3 flex flex-col gap-2 translate-x-12 group-hover:translate-x-0 transition-transform duration-300 z-20">
                        {{-- Edit --}}
                        <a href="{{ route('admins.provinces.edit', $province->slug) }}"
                            class="w-9 h-9 bg-white/20 backdrop-blur-md border border-white/30 text-white rounded-full flex items-center justify-center hover:bg-white hover:text-blue-600 transition shadow-sm"
                            title="Edit Province">
                            <i class="fa-solid fa-pen text-xs"></i>
                        </a>

                        {{-- Delete --}}
                        <form action="{{ route('admins.provinces.destroy', $province->slug) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete {{ $province->name }}? This will also delete all destinations inside it.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-9 h-9 bg-white/20 backdrop-blur-md border border-white/30 text-white rounded-full flex items-center justify-center hover:bg-white hover:text-red-600 transition shadow-sm"
                                title="Delete Province">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </form>
                    </div>

                    {{-- 4. Destination Count Badge (Top Left) --}}
                    <div class="absolute top-3 left-3 z-20">
                        <span
                            class="bg-black/50 backdrop-blur-md text-white text-[10px] font-bold px-2.5 py-1 rounded-full border border-white/20 shadow-sm flex items-center gap-1">
                            <i class="fa-solid fa-map-location-dot text-blue-400"></i>
                            @if ($province->destinations->count() == 0)
                                {{-- * One place only --}}
                                {{ $province->destinations->count() }} Place
                            @else
                                {{-- *More than one place* --}}
                                {{ $province->destinations->count() }} Places
                            @endif
                        </span>
                    </div>

                    {{-- 5. Bottom Content --}}
                    <div class="absolute bottom-0 inset-x-0 p-6 text-center z-10 flex flex-col items-center">
                        <x-province-card-title :province="$province" />

                        {{-- Clickable Area Link --}}
                        <a href="{{ route('admins.provinces.show', $province->slug) }}" class="absolute inset-0 z-0"
                            title="View details"></a>
                    </div>
                </div>
            @empty
                {{-- Empty State --}}
                <div class="col-span-full py-16 text-center bg-white rounded-xl border-2 border-dashed border-gray-200">
                    <div
                        class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4 text-blue-500">
                        <i class="fa-regular fa-map text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">No provinces found</h3>
                    <p class="text-gray-500 mb-6 max-w-sm mx-auto">Get started by adding the first province or city to
                        your database.</p>
                    <a href="{{ route('admins.provinces.create') }}"
                        class="inline-flex items-center justify-center px-6 py-2.5 border border-transparent font-semibold rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition shadow-md">
                        <i class="fa-solid fa-plus mr-2"></i> Create Province
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($provinces->hasPages())
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                {{ $provinces->links() }}
            </div>
        @endif
    </div>
</x-app-layout>