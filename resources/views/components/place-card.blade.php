@props(['image', 'title', 'location'])

<div
    class="group relative bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300">
    <div class="h-48 overflow-hidden">
        <img src="{{ $image }}" alt="{{ $title }}"
            class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
    </div>

    <div class="p-5">
        <h3 class="font-serif text-lg font-bold text-gray-900 mb-1">{{ $title }}</h3>
        <p class="text-gray-500 text-xs uppercase tracking-wide mb-4">{{ $location }}</p>

        <a href="#"
            class="inline-block px-4 py-2 bg-blue-50 text-blue-600 text-xs font-bold rounded-lg hover:bg-blue-100 transition">
            EXPLORE
        </a>
    </div>
</div>
