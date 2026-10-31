<x-admin-layout>
    <x-slot name="title">Edit {{ $destination->place_name }}</x-slot>

    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                <a href="{{ route('admins.destinations.index') }}" class="hover:text-gray-900 transition">Destinations</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                <span class="text-gray-900 font-medium">Edit: {{ $destination->place_name }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Edit Destination</h1>
        </div>
        <a href="{{ route('admins.destinations.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
            <i class="fa-solid fa-arrow-left mr-1"></i> Back to Destinations
        </a>
    </div>

    {{-- Form Container --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <x-form-alerts />

        <form action="{{ route('admins.destinations.update', $destination->slug) }}" method="POST" enctype="multipart/form-data"
            x-data="{ submitting: false }" @submit="if (submitting) { $event.preventDefault(); return false; } submitting = true;">
            @csrf
            @method('PUT')

            {{-- 1. Basic Information --}}
            <div class="mb-8">
                <h2 class="text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 text-xs font-bold flex items-center justify-center">1</span>
                    Basic Information
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Place Name <span class="text-red-500">*</span></label>
                        <input type="text" name="place_name" value="{{ old('place_name', $destination->place_name) }}" required
                            class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500 border-gray-200 outline-none transition">
                        @error('place_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Province <span class="text-red-500">*</span></label>
                        <x-custom-select
                            name="province_id"
                            placeholder="Select a Province"
                            :selected="old('province_id', $destination->province_id)"
                            :options="$provinces->sortBy('name')->map(fn($p) => ['label' => $p->name, 'value' => (string) $p->id])->values()->all()"
                            required
                        />
                        @error('province_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    {{-- Category Dropdown --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
                        <x-custom-select
                            name="category_id"
                            placeholder="Select a Category"
                            :selected="old('category_id', $destination->category_id)"
                            :options="$categories->sortBy('name')->map(fn($c) => ['label' => $c->name, 'value' => (string) $c->id])->values()->all()"
                            required
                        />
                        @error('category_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Rating --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Rating (0 - 5.0)</label>
                        <input type="number" step="0.1" max="5" min="0" name="rating"
                            value="{{ old('rating', $destination->rating) }}"
                            class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500 border-gray-200 outline-none transition">
                        @error('rating')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Recommendation Checkbox --}}
                <div class="mb-6">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input id="is_recommended" type="checkbox" name="is_recommended" value="1"
                            {{ old('is_recommended', $destination->is_recommended) ? 'checked' : '' }}
                            class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
                        <span class="text-sm font-medium text-gray-800">Mark as "Recommended for You"</span>
                    </label>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-sm font-medium text-gray-700">Short Description <span class="text-red-500">*</span></label>
                        <span class="text-xs text-gray-400">Max 500 characters</span>
                    </div>
                    <textarea name="short_description" rows="3" required maxlength="500"
                        class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500 border-gray-200 outline-none transition">{{ old('short_description', $destination->short_description) }}</textarea>
                    @error('short_description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- 2. Images --}}
            <div class="mb-8">
                <h2 class="text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 text-xs font-bold flex items-center justify-center">2</span>
                    Images & Media
                </h2>

                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Main Thumbnail</label>
                    <div class="flex flex-col sm:flex-row items-start gap-4">
                        <label class="w-full sm:w-1/2 cursor-pointer border-2 border-dashed border-gray-200 rounded-xl p-6 hover:bg-gray-50 transition text-center">
                            <i class="fa-solid fa-cloud-arrow-up text-2xl text-gray-400 mb-2"></i>
                            <span class="block text-sm text-gray-600 font-medium">Click to replace thumbnail</span>
                            <span class="block text-xs text-gray-400 mt-1">Leave empty to keep existing image</span>
                            <input type="file" name="thumbnail" class="hidden"
                                accept="image/jpeg,image/png,image/jpg,image/webp"
                                onchange="previewSingleImage(event, 'thumbnail_preview')">
                        </label>
                        <div class="w-full sm:w-1/2 h-40 bg-gray-50 rounded-xl overflow-hidden border border-gray-200">
                            <img id="thumbnail_preview"
                                src="{{ $destination->thumbnail_url ?? 'https://placehold.co/600x400?text=No+Image' }}"
                                class="w-full h-full object-cover">
                        </div>
                    </div>
                    @error('thumbnail')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Gallery Images (Replace or add)</label>
                    <label class="block w-full cursor-pointer border-2 border-dashed border-gray-200 rounded-xl p-4 hover:bg-gray-50 transition text-center mb-4">
                        <i class="fa-solid fa-images text-xl text-gray-400 mr-2"></i>
                        <span class="text-sm font-medium text-gray-600">+ Upload New Gallery Images</span>
                        <input type="file" name="gallery_images[]" multiple class="hidden"
                            accept="image/jpeg,image/png,image/jpg,image/webp"
                            onchange="previewMultipleImages(event)">
                    </label>
                    @error('gallery_images')
                        <p class="text-red-500 text-xs mb-2">{{ $message }}</p>
                    @enderror

                    {{-- Existing gallery images --}}
                    @if (is_array($destination->gallery_images) && count($destination->gallery_images) > 0)
                        <div class="mb-4">
                            <p class="text-xs text-gray-500 mb-2">Current gallery photos:</p>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach ($destination->gallery_urls as $gUrl)
                                    <div class="h-24 rounded-xl overflow-hidden border border-gray-200">
                                        <img src="{{ $gUrl }}" class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div id="gallery_preview_container" class="grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                </div>
            </div>

            {{-- 3. Content --}}
            <div class="mb-8">
                <h2 class="text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 text-xs font-bold flex items-center justify-center">3</span>
                    Content & Details
                </h2>

                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Full Description <span class="text-red-500">*</span></label>
                    <textarea name="place_description" rows="5" required
                        class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500 border-gray-200 outline-none transition">{{ old('place_description', $destination->place_description) }}</textarea>
                    @error('place_description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">History & Background</label>
                        <textarea name="place_history" rows="4"
                            class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500 border-gray-200 outline-none transition">{{ old('place_history', $destination->place_history) }}</textarea>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Visitor Tips & Logistics</label>
                        <textarea name="place_information" rows="4"
                            class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500 border-gray-200 outline-none transition">{{ old('place_information', $destination->place_information) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- 4. Highlights --}}
            <div class="mb-8">
                <h2 class="text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 text-xs font-bold flex items-center justify-center">4</span>
                    Key Highlights
                </h2>

                <div id="highlight_container" class="space-y-2 mb-3">
                    @php
                        $highlights = old('highlights', is_array($destination->highlights) ? $destination->highlights : []);
                    @endphp
                    @if (!empty($highlights))
                        @foreach ($highlights as $highlight)
                            <div class="flex gap-2">
                                <input type="text" name="highlights[]" value="{{ $highlight }}"
                                    class="w-full px-4 py-2 border rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">
                                <button type="button" onclick="removeField(this)" class="px-3 text-red-500 hover:text-red-700">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        @endforeach
                    @else
                        <div class="flex gap-2">
                            <input type="text" name="highlights[]" placeholder="Highlight..."
                                class="w-full px-4 py-2 border rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">
                            <button type="button" onclick="removeField(this)" class="px-3 text-red-500 hover:text-red-700">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    @endif
                </div>

                <button type="button" onclick="addHighlightField()" class="text-sm font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                    <i class="fa-solid fa-plus text-xs"></i> Add Another Highlight
                </button>
            </div>

            {{-- Submit --}}
            <div class="flex justify-end pt-6 border-t border-gray-100 gap-3">
                <a href="{{ route('admins.destinations.index') }}"
                    class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition font-medium text-sm">
                    Cancel
                </a>
                <button type="submit"
                    :disabled="submitting"
                    :class="{ 'opacity-75 cursor-not-allowed': submitting }"
                    class="text-white bg-blue-600 hover:bg-blue-700 font-semibold rounded-xl px-6 py-2.5 shadow-sm transition text-sm flex items-center gap-2 cursor-pointer">
                    <template x-if="submitting">
                        <span class="flex items-center gap-2">
                            <i class="fa-solid fa-circle-notch fa-spin"></i>
                            <span>Saving...</span>
                        </span>
                    </template>
                    <template x-if="!submitting">
                        <span>Update Destination</span>
                    </template>
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
