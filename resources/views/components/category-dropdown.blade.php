@props([
    'name' => 'category',
    'selected' => request('category', ''),
    'options' => null,
])

@php
    if ($options === null) {
        $dbCategories = \Illuminate\Support\Facades\Schema::hasTable('categories')
            ? \App\Models\Category::orderBy('name')->get()
            : collect();

        if ($dbCategories->isNotEmpty()) {
            $options = array_merge(
                [['label' => __('landing/landing.all_categories'), 'value' => '']],
                $dbCategories->map(fn($c) => ['label' => $c->name, 'value' => $c->slug])->all()
            );
        } else {
            $options = [
                ['label' => __('landing/landing.all_categories'), 'value' => ''],
                ['label' => __('landing/landing.temples'), 'value' => 'temples'],
                ['label' => __('landing/landing.beaches'), 'value' => 'beaches'],
                ['label' => __('landing/landing.nature'), 'value' => 'nature'],
            ];
        }
    }
    $initialOption = collect($options)->firstWhere('value', $selected) ?? $options[0] ?? ['label' => __('landing/landing.all_categories'), 'value' => ''];
@endphp

<div class="hidden md:block relative mr-2"
     x-data="{
         open: false,
         selected: {{ json_encode($initialOption) }},
         options: {{ json_encode($options) }},
         select(option) {
             this.selected = option;
             this.open = false;
         }
     }"
     @click.outside="open = false"
     @keydown.escape.window="open = false"
     {{ $attributes }}>

    {{-- Hidden Input for Form Submission --}}
    <input type="hidden" name="{{ $name }}" :value="selected.value">

    {{-- Trigger Button --}}
    <button type="button"
            @click="open = !open"
            class="flex items-center space-x-2.5 px-4 py-2 rounded-full text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 transition-colors duration-150 focus:outline-none cursor-pointer select-none">
        <span x-text="selected.label" class="whitespace-nowrap">{{ $initialOption['label'] }}</span>
        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 transform"
             :class="{ 'rotate-180 text-gray-700': open }"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    {{-- Dropdown Panel --}}
    <div x-show="open"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
         class="absolute right-0 mt-3 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 p-1.5 z-50 origin-top"
         style="display: none;">
        <div class="space-y-1">
            <template x-for="option in options" :key="option.value">
                <button type="button"
                        @click="select(option)"
                        class="w-full flex items-center justify-between px-3.5 py-2.5 text-sm rounded-xl text-left transition-colors duration-150 cursor-pointer"
                        :class="selected.value === option.value
                            ? 'bg-blue-50 text-blue-600 font-semibold'
                            : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 font-medium'">
                    <span x-text="option.label"></span>
                    <svg x-show="selected.value === option.value"
                         class="w-4 h-4 text-blue-600 shrink-0 ml-2"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </button>
            </template>
        </div>
    </div>
</div>
