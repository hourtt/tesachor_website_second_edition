<x-admin-layout>
    <x-slot name="title">Add Destination</x-slot>

    {{-- Breadcrumb / Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                @if (isset($targetProvince))
                    <a href="{{ route('admins.provinces.show', $targetProvince->slug) }}" class="hover:text-gray-900 transition">{{ $targetProvince->name }}</a>
                @else
                    <a href="{{ route('admins.destinations.index') }}" class="hover:text-gray-900 transition">Destinations</a>
                @endif
                <x-icon name="chevron-right" class="text-[10px] text-gray-400" />
                <span class="text-gray-900 font-medium">Create New</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Create New Destination</h1>
        </div>
        <a href="{{ isset($targetProvince) ? route('admins.provinces.show', $targetProvince->slug) : route('admins.destinations.index') }}"
            class="text-sm font-medium text-gray-600 hover:text-gray-900">
            <x-icon name="arrow-left" class="mr-1" /> Back
        </a>
    </div>

    {{-- Main Form Container --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <x-form-alerts />

        <form action="{{ route('admins.destinations.store') }}" method="POST" enctype="multipart/form-data"
            x-data="{ submitting: false }" @submit="if (submitting) { $event.preventDefault(); return false; } submitting = true;">
            @csrf

            {{-- 1. Basic Information --}}
            <div class="mb-8">
                <h2 class="text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 text-xs font-bold flex items-center justify-center">1</span>
                    Basic Information
                </h2>

                {{-- Place Name: English + Khmer side by side --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Place Name <span class="text-red-500">*</span></label>
                        <input type="text" name="place_name" value="{{ old('place_name') }}" required
                            class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500 border-gray-200 outline-none transition"
                            placeholder="e.g. Angkor Wat">
                        @error('place_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Place Name (Khmer)</label>
                        <input type="text" name="place_name_in_khmer" value="{{ old('place_name_in_khmer') }}"
                            class="font-khmer w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500 border-gray-200 outline-none transition"
                            placeholder="e.g. អង្គរវត្ត">
                        @error('place_name_in_khmer')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    {{-- Province Dropdown --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Province <span class="text-red-500">*</span></label>
                        <x-custom-select
                            name="province_id"
                            placeholder="Select a Province"
                            :selected="old('province_id', $targetProvince->id ?? null)"
                            :options="$provinces->sortBy('name')->map(fn($p) => ['label' => $p->name, 'value' => (string) $p->id])->values()->all()"
                            required
                        />
                        @error('province_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Category Dropdown --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
                        <x-custom-select
                            name="category_id"
                            placeholder="Select a Category"
                            :selected="old('category_id')"
                            :options="$categories->sortBy('name')->map(fn($c) => ['label' => $c->name, 'value' => (string) $c->id])->values()->all()"
                            required
                        />
                        @error('category_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    {{-- Rating --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Rating (0 - 5.0)</label>
                        <input type="number" step="0.1" max="5" min="0" name="rating"
                            value="{{ old('rating', 5.0) }}"
                            class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500 border-gray-200 outline-none transition">
                        @error('rating')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Recommendation Checkbox --}}
                    <div class="flex items-center pt-8">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input id="is_recommended" type="checkbox" name="is_recommended" value="1"
                                {{ old('is_recommended') ? 'checked' : '' }}
                                class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
                            <span class="text-sm font-medium text-gray-800">Mark as "Recommended for You"</span>
                        </label>
                    </div>
                </div>

                {{-- Short Description: English + Khmer side by side --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-sm font-medium text-gray-700">Short Description <span class="text-red-500">*</span></label>
                            <span class="text-xs text-gray-400">Max 500 characters</span>
                        </div>
                        <textarea name="short_description" rows="3" required maxlength="500"
                            class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500 border-gray-200 outline-none transition">{{ old('short_description') }}</textarea>
                        @error('short_description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-sm font-medium text-gray-700">Short Description (Khmer)</label>
                            <span class="text-xs text-gray-400">Max 500 characters</span>
                        </div>
                        <textarea name="short_description_in_khmer" rows="3" maxlength="500"
                            class="font-khmer leading-loose w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500 border-gray-200 outline-none transition">{{ old('short_description_in_khmer') }}</textarea>
                        @error('short_description_in_khmer')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- 2. Images --}}
            <div class="mb-8">
                <h2 class="text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 text-xs font-bold flex items-center justify-center">2</span>
                    Images & Media
                </h2>

                {{-- Thumbnail --}}
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Main Thumbnail <span class="text-red-500">*</span></label>
                    <div class="flex flex-col sm:flex-row items-start gap-4">
                        <label class="w-full sm:w-1/2 cursor-pointer border-2 border-dashed border-gray-200 rounded-xl p-6 hover:bg-gray-50 transition text-center">
                            <x-icon name="cloud-arrow-up" class="text-2xl text-gray-400 mb-2" />
                            <span class="block text-sm text-gray-600 font-medium">Click to upload thumbnail</span>
                            <span class="block text-xs text-gray-400 mt-1">PNG, JPG, WEBP up to 5MB</span>
                            <input type="file" name="thumbnail" class="hidden"
                                accept="image/jpeg,image/png,image/jpg,image/webp"
                                onchange="previewSingleImage(event, 'thumbnail_preview')">
                        </label>
                        <div class="w-full sm:w-1/2 h-40 bg-gray-50 rounded-xl overflow-hidden border border-gray-200">
                            <img id="thumbnail_preview" src="{{ asset('images/placeholder.svg') }}"
                                class="w-full h-full object-cover opacity-60">
                        </div>
                    </div>
                    @error('thumbnail')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Gallery --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Gallery Images (Multiple)</label>
                    <label class="block w-full cursor-pointer border-2 border-dashed border-gray-200 rounded-xl p-4 hover:bg-gray-50 transition text-center mb-4">
                        <x-icon name="images" class="text-xl text-gray-400 mr-2" />
                        <span class="text-sm font-medium text-gray-600">+ Add Gallery Images</span>
                        <input type="file" name="gallery_images[]" multiple class="hidden"
                            accept="image/jpeg,image/png,image/jpg,image/webp"
                            onchange="previewMultipleImages(event)">
                    </label>
                    @error('gallery_images')
                        <p class="text-red-500 text-xs mb-2">{{ $message }}</p>
                    @enderror
                    <div id="gallery_preview_container" class="grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                </div>
            </div>

            {{-- 3. Content & Details --}}
            <div class="mb-8">
                <h2 class="text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 text-xs font-bold flex items-center justify-center">3</span>
                    Content & Details
                </h2>

                {{-- Full Description with Alpine tab switcher --}}
                @php
                    $descHasKmError = $errors->has('place_description_in_khmer');
                    $descHasEnError = $errors->has('place_description');
                    $descInitLang = ($descHasKmError && !$descHasEnError) ? 'km' : 'en';
                @endphp
                <div class="mb-4"
                    x-data="{ lang: '{{ $descInitLang }}' }">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-medium text-gray-700">Full Description <span class="text-red-500">*</span></label>
                        <div class="flex rounded-lg border border-gray-200 overflow-hidden text-xs font-medium">
                            <button type="button" @click="lang = 'en'"
                                :class="lang === 'en' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                                class="px-3 py-1.5 transition">English</button>
                            <button type="button" @click="lang = 'km'"
                                :class="lang === 'km' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                                class="px-3 py-1.5 transition relative">
                                ខ្មែរ
                                @if ($descHasKmError)
                                    <span class="absolute top-1 right-1 w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                @endif
                            </button>
                        </div>
                    </div>
                    <div x-show="lang === 'en'">
                        <textarea name="place_description" rows="5" required
                            class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500 border-gray-200 outline-none transition">{{ old('place_description') }}</textarea>
                        @error('place_description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div x-show="lang === 'km'">
                        <textarea name="place_description_in_khmer" rows="5"
                            class="font-khmer leading-loose w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500 border-gray-200 outline-none transition">{{ old('place_description_in_khmer') }}</textarea>
                        @error('place_description_in_khmer')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- History with Alpine tab switcher --}}
                @php
                    $histHasKmError = $errors->has('place_history_in_khmer');
                    $histHasEnError = $errors->has('place_history');
                    $histInitLang = ($histHasKmError && !$histHasEnError) ? 'km' : 'en';
                @endphp
                <div class="mb-4"
                    x-data="{ lang: '{{ $histInitLang }}' }">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-medium text-gray-700">History & Background</label>
                        <div class="flex rounded-lg border border-gray-200 overflow-hidden text-xs font-medium">
                            <button type="button" @click="lang = 'en'"
                                :class="lang === 'en' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                                class="px-3 py-1.5 transition">English</button>
                            <button type="button" @click="lang = 'km'"
                                :class="lang === 'km' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                                class="px-3 py-1.5 transition relative">
                                ខ្មែរ
                                @if ($histHasKmError)
                                    <span class="absolute top-1 right-1 w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                @endif
                            </button>
                        </div>
                    </div>
                    <div x-show="lang === 'en'">
                        <textarea name="place_history" rows="4"
                            class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500 border-gray-200 outline-none transition">{{ old('place_history') }}</textarea>
                        @error('place_history')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div x-show="lang === 'km'">
                        <textarea name="place_history_in_khmer" rows="4"
                            class="font-khmer leading-loose w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500 border-gray-200 outline-none transition">{{ old('place_history_in_khmer') }}</textarea>
                        @error('place_history_in_khmer')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Visitor Tips with Alpine tab switcher --}}
                @php
                    $infoHasKmError = $errors->has('place_information_in_khmer');
                    $infoHasEnError = $errors->has('place_information');
                    $infoInitLang = ($infoHasKmError && !$infoHasEnError) ? 'km' : 'en';
                @endphp
                <div x-data="{ lang: '{{ $infoInitLang }}' }">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-medium text-gray-700">Visitor Tips & Logistics</label>
                        <div class="flex rounded-lg border border-gray-200 overflow-hidden text-xs font-medium">
                            <button type="button" @click="lang = 'en'"
                                :class="lang === 'en' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                                class="px-3 py-1.5 transition">English</button>
                            <button type="button" @click="lang = 'km'"
                                :class="lang === 'km' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                                class="px-3 py-1.5 transition relative">
                                ខ្មែរ
                                @if ($infoHasKmError)
                                    <span class="absolute top-1 right-1 w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                @endif
                            </button>
                        </div>
                    </div>
                    <div x-show="lang === 'en'">
                        <textarea name="place_information" rows="4"
                            class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500 border-gray-200 outline-none transition">{{ old('place_information') }}</textarea>
                        @error('place_information')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div x-show="lang === 'km'">
                        <textarea name="place_information_in_khmer" rows="4"
                            class="font-khmer leading-loose w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500 border-gray-200 outline-none transition">{{ old('place_information_in_khmer') }}</textarea>
                        @error('place_information_in_khmer')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- 4. Highlights --}}
            <div class="mb-8">
                <h2 class="text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 text-xs font-bold flex items-center justify-center">4</span>
                    Key Highlights
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">English</p>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">ខ្មែរ (Khmer)</p>
                </div>

                <div id="highlight_container" class="space-y-2 mb-3">
                    @php
                        $hlEn  = old('highlights', ['']);
                        $hlKm  = old('highlights_in_khmer', ['']);
                        $hlMax = max(count($hlEn), count($hlKm));
                        while (count($hlEn) < $hlMax) $hlEn[]  = '';
                        while (count($hlKm) < $hlMax) $hlKm[] = '';
                    @endphp
                    @foreach ($hlEn as $i => $enVal)
                        <div class="highlight-row grid grid-cols-1 md:grid-cols-2 gap-2 items-center">
                            <div class="flex gap-2">
                                <input type="text" name="highlights[]" value="{{ $enVal }}"
                                    placeholder="e.g. Sunrise view at Angkor Wat"
                                    class="w-full px-4 py-2 border rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div class="flex gap-2">
                                <input type="text" name="highlights_in_khmer[]" value="{{ $hlKm[$i] ?? '' }}"
                                    placeholder="e.g. ទិដ្ឋភាពថ្ងៃរះ"
                                    class="font-khmer w-full px-4 py-2 border rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">
                                <button type="button" onclick="removeHighlightRow(this)" class="px-3 text-red-500 hover:text-red-700 shrink-0">
                                    <x-icon name="trash" />
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" onclick="addHighlightRow()" class="text-sm font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                    <x-icon name="plus" class="text-xs" /> Add Another Highlight
                </button>
            </div>

            {{-- Submit --}}
            <div class="flex justify-end pt-6 border-t border-gray-100 gap-3">
                <a href="{{ isset($targetProvince) ? route('admins.provinces.show', $targetProvince->slug) : route('admins.destinations.index') }}"
                    class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition font-medium text-sm">
                    Cancel
                </a>
                <button type="submit"
                    :disabled="submitting"
                    :class="{ 'opacity-75 cursor-not-allowed': submitting }"
                    class="text-white bg-blue-600 hover:bg-blue-700 font-semibold rounded-xl px-6 py-2.5 shadow-sm transition text-sm flex items-center gap-2 cursor-pointer">
                    <template x-if="submitting">
                        <span class="flex items-center gap-2">
                            <x-icon name="circle-notch" class="fa-spin" />
                            <span>Saving...</span>
                        </span>
                    </template>
                    <template x-if="!submitting">
                        <span>Save Destination</span>
                    </template>
                </button>
            </div>
        </form>
    </div>

    <script>
        function removeHighlightRow(btn) {
            const container = document.getElementById('highlight_container');
            const row = btn.closest('.highlight-row');
            if (container.querySelectorAll('.highlight-row').length > 1) {
                row.remove();
            } else {
                row.querySelectorAll('input[type="text"]').forEach(i => i.value = '');
            }
        }

        function addHighlightRow() {
            const container = document.getElementById('highlight_container');
            const row = document.createElement('div');
            row.className = 'highlight-row grid grid-cols-1 md:grid-cols-2 gap-2 items-center';
            row.innerHTML = `
                <div class="flex gap-2">
                    <input type="text" name="highlights[]" placeholder="e.g. Sunrise view at Angkor Wat"
                        class="w-full px-4 py-2 border rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div class="flex gap-2">
                    <input type="text" name="highlights_in_khmer[]" placeholder="e.g. ទិដ្ឋភាពថ្ងៃរះ"
                        class="font-khmer w-full px-4 py-2 border rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">
                    <button type="button" onclick="removeHighlightRow(this)" class="px-3 text-red-500 hover:text-red-700 shrink-0">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>`;
            container.appendChild(row);
        }
    </script>
</x-admin-layout>
