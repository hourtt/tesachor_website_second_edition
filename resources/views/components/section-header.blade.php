@props(['title', 'subtitle' => null])

<div class="text-center mb-8 mt-12">
    <h2 class="font-serif text-3xl font-bold text-gray-800 uppercase tracking-wide">
        {{ $title }}
    </h2>
    @if ($subtitle)
        <p class="text-gray-500 text-sm mt-2 uppercase tracking-widest">{{ $subtitle }}</p>
    @endif
    <div class="w-16 h-1 bg-blue-500 mx-auto mt-4 rounded-full"></div>
</div>
