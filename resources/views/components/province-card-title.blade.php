@props(['province'])

<div class="pb-4">
    {{-- Khmer Name (Top, Bigger) --}}
    <p class="text-white font-khmer text-2xl mb-1 shadow-black drop-shadow-md">
        {{ $province->name_in_khmer }}
    </p>

    {{-- English Name (Bottom, Smaller) --}}
    <h3 class="text-sm text-gray-300 font-sans tracking-wide drop-shadow-md capitalize">
        {{ $province->name }}
    </h3>

    {{-- Decorative Line --}}
    <div class="w-8 h-0.5 bg-blue-500/80 mx-auto mt-2 rounded-full group-hover:w-16 transition-all duration-500">
    </div>
</div>