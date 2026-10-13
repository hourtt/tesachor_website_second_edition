@props([
    'name',
    'options' => [],
    'selected' => null,
    'placeholder' => 'Select an option',
    'searchable' => true,
    'required' => false,
])

@php
    $selectedVal = (string) ($selected ?? '');
    $initialOption = collect($options)->firstWhere('value', $selectedVal) ?? ['label' => $placeholder, 'value' => ''];
@endphp

<div class="relative"
     x-data="{
         open: false,
         search: '',
         selected: {{ json_encode($initialOption) }},
         options: {{ json_encode($options) }},
         select(option) {
             this.selected = option;
             this.open = false;
             this.search = '';
         },
         get filteredOptions() {
             if (!this.search) return this.options;
             return this.options.filter(o => o.label.toLowerCase().includes(this.search.toLowerCase()));
         }
     }"
     @click.outside="open = false; search = ''"
     @keydown.escape.window="open = false; search = ''"
     x-effect="if (open && $refs.searchInput) { setTimeout(() => $refs.searchInput.focus(), 60); }"
     {{ $attributes }}>

    {{-- Hidden Input for Form Submission --}}
    <input type="hidden" name="{{ $name }}" :value="selected.value">

    {{-- Trigger Button --}}
    <button type="button"
            @click="open = !open"
            class="w-full px-4 py-2.5 border rounded-xl bg-white border-gray-200 text-sm text-left flex items-center justify-between transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer select-none"
            :class="{ 'ring-2 ring-blue-500 border-blue-500': open }">
        <span x-text="selected.value ? selected.label : '{{ $placeholder }}'"
              class="block truncate"
              :class="selected.value ? 'text-gray-900 font-medium' : 'text-gray-400'">
            {{ $initialOption['value'] ? $initialOption['label'] : $placeholder }}
        </span>
        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 transform flex-shrink-0 ml-2"
             :class="{ 'rotate-180 text-blue-600': open }"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    {{-- Animated Dropdown Panel --}}
    <div x-show="open"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
         class="absolute left-0 right-0 mt-2 bg-white rounded-2xl shadow-xl border border-gray-100 p-2 z-50 origin-top"
         style="display: none;">

        @if ($searchable)
            <div x-show="options.length > 6" class="p-1 mb-1 border-b border-gray-100">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-400 text-xs"></i>
                    <input type="text"
                           x-ref="searchInput"
                           x-model="search"
                           @click.stop
                           placeholder="Search..."
                           class="w-full pl-8 pr-3 py-1.5 text-xs bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:bg-white transition">
                </div>
            </div>
        @endif

        <div class="max-h-60 overflow-y-auto space-y-1">
            <template x-for="option in filteredOptions" :key="option.value">
                <button type="button"
                        @click="select(option)"
                        class="w-full flex items-center justify-between px-3.5 py-2.5 text-sm rounded-xl text-left transition-colors duration-150 cursor-pointer"
                        :class="selected.value == option.value
                            ? 'bg-blue-50 text-blue-600 font-semibold'
                            : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 font-medium'">
                    <span x-text="option.label"></span>
                    <svg x-show="selected.value == option.value"
                         class="w-4 h-4 text-blue-600 flex-shrink-0 ml-2"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </button>
            </template>
            <div x-show="filteredOptions.length === 0" class="px-3 py-3 text-xs text-center text-gray-400">
                No matching results
            </div>
        </div>
    </div>
</div>
