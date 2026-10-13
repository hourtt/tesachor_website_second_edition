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
                <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                <span class="text-gray-900 font-medium">Create New</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Create New Destination</h1>
        </div>
        <a href="{{ isset($targetProvince) ? route('admins.provinces.show', $targetProvince->slug) : route('admins.destinations.index') }}"
            class="text-sm font-medium text-gray-600 hover:text-gray-900">
            <i class="fa-solid fa-arrow-left mr-1"></i> Back
        </a>
    </div>

    {{-- Main Form Container --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <form action="{{ route('admins.destinations.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- 1. Basic Information --}}
            <div class="mb-8">
                <h2 class="text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-red-100 text-red-600 text-xs font-bold flex items-center justify-center">1</span>
                    Basic Information
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    {{-- Place Name --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Place Name <span class="text-red-500">*</span></label>
                        <input type="text" name="place_name" value="{{ old('place_name') }}" required
                            class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-red-500 border-gray-200 outline-none transition"
                            placeholder="e.g. Angkor Wat">
                        @error('place_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Province Dropdown --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Province <span class="text-red-500">*</span></label>
                        <select name="province_id" required
                            class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-red-500 border-gray-200 outline-none transition bg-white">
                            <option value="" disabled {{ old('province_id', $targetProvince->id ?? null) ? '' : 'selected' }}>Select a Province</option>
                            @foreach ($provinces->sortBy('name') as $province)
                                <option value="{{ $province->id }}"
                                    {{ old('province_id', $targetProvince->id ?? null) == $province->id ? 'selected' : '' }}>
                                    {{ $province->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('province_id')
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
                            class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-red-500 border-gray-200 outline-none transition">
                        @error('rating')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Recommendation Checkbox --}}
                    <div class="flex items-center pt-6">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input id="is_recommended" type="checkbox" name="is_recommended" value="1"
                                {{ old('is_recommended') ? 'checked' : '' }}
                                class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500 cursor-pointer">
                            <span class="text-sm font-medium text-gray-800">Mark as "Recommended for You"</span>
                        </label>
                    </div>
                </div>

                {{-- Short Description --}}
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-sm font-medium text-gray-700">Short Description <span class="text-red-500">*</span></label>
                        <span class="text-xs text-gray-400">Max 500 characters</span>
                    </div>
                    <textarea name="short_description" rows="3" required maxlength="500"
                        class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-red-500 border-gray-200 outline-none transition">{{ old('short_description') }}</textarea>
                    @error('short_description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- 2. Images --}}
            <div class="mb-8">
                <h2 class="text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-red-100 text-red-600 text-xs font-bold flex items-center justify-center">2</span>
                    Images & Media
                </h2>

                {{-- Thumbnail --}}
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Main Thumbnail <span class="text-red-500">*</span></label>
                    <div class="flex flex-col sm:flex-row items-start gap-4">
                        <label class="w-full sm:w-1/2 cursor-pointer border-2 border-dashed border-gray-200 rounded-xl p-6 hover:bg-gray-50 transition text-center">
                            <i class="fa-solid fa-cloud-arrow-up text-2xl text-gray-400 mb-2"></i>
                            <span class="block text-sm text-gray-600 font-medium">Click to upload thumbnail</span>
                            <span class="block text-xs text-gray-400 mt-1">PNG, JPG, WEBP up to 5MB</span>
                            <input type="file" name="thumbnail" class="hidden"
                                accept="image/jpeg,image/png,image/jpg,image/webp"
                                onchange="previewSingleImage(event, 'thumbnail_preview')">
                        </label>
                        <div class="w-full sm:w-1/2 h-40 bg-gray-50 rounded-xl overflow-hidden border border-gray-200">
                            <img id="thumbnail_preview" src="https://placehold.co/600x400?text=Preview"
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
                        <i class="fa-solid fa-images text-xl text-gray-400 mr-2"></i>
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

            {{-- 3. Content & History --}}
            <div class="mb-8">
                <h2 class="text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-red-100 text-red-600 text-xs font-bold flex items-center justify-center">3</span>
                    Content & Details
                </h2>

                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Full Description <span class="text-red-500">*</span></label>
                    <textarea name="place_description" rows="5" required
                        class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-red-500 border-gray-200 outline-none transition">{{ old('place_description') }}</textarea>
                    @error('place_description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">History & Background</label>
                        <textarea name="place_history" rows="4"
                            class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-red-500 border-gray-200 outline-none transition">{{ old('place_history') }}</textarea>
                        @error('place_history')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Visitor Tips & Logistics</label>
                        <textarea name="place_information" rows="4"
                            class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-red-500 border-gray-200 outline-none transition">{{ old('place_information') }}</textarea>
                        @error('place_information')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- 4. Highlights --}}
            <div class="mb-8">
                <h2 class="text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-red-100 text-red-600 text-xs font-bold flex items-center justify-center">4</span>
                    Key Highlights
                </h2>

                <div id="highlight_container" class="space-y-2 mb-3">
                    @if (old('highlights'))
                        @foreach (old('highlights') as $highlight)
                            <div class="flex gap-2">
                                <input type="text" name="highlights[]" value="{{ $highlight }}"
                                    class="w-full px-4 py-2 border rounded-xl border-gray-200 focus:ring-2 focus:ring-red-500 outline-none">
                                <button type="button" onclick="removeField(this)" class="px-3 text-red-500 hover:text-red-700">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        @endforeach
                    @else
                        <div class="flex gap-2">
                            <input type="text" name="highlights[]" placeholder="e.g. Sunrise view at Angkor Wat"
                                class="w-full px-4 py-2 border rounded-xl border-gray-200 focus:ring-2 focus:ring-red-500 outline-none">
                            <button type="button" onclick="removeField(this)" class="px-3 text-red-500 hover:text-red-700">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    @endif
                </div>

                <button type="button" onclick="addHighlightField()" class="text-sm font-semibold text-red-600 hover:text-red-700 flex items-center gap-1">
                    <i class="fa-solid fa-plus text-xs"></i> Add Another Highlight
                </button>
            </div>

            {{-- Submit --}}
            <div class="flex justify-end pt-6 border-t border-gray-100 gap-3">
                <a href="{{ isset($targetProvince) ? route('admins.provinces.show', $targetProvince->slug) : route('admins.destinations.index') }}"
                    class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition font-medium text-sm">
                    Cancel
                </a>
                <button type="submit"
                    class="text-white bg-red-600 hover:bg-red-700 font-semibold rounded-xl px-6 py-2.5 shadow-sm transition text-sm">
                    Save Destination
                </button>
            </div>
        </form>
    </div>

    {{-- JavaScript --}}
    <script>
        function previewSingleImage(event, previewId) {
            const reader = new FileReader();
            const preview = document.getElementById(previewId);
            reader.onload = function() {
                preview.src = reader.result;
                preview.classList.remove('opacity-60');
            }
            if (event.target.files[0]) reader.readAsDataURL(event.target.files[0]);
        }

        function previewMultipleImages(event) {
            const container = document.getElementById('gallery_preview_container');
            container.innerHTML = "";
            Array.from(event.target.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = "relative h-24 rounded-xl overflow-hidden border border-gray-200 shadow-sm";
                    div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                    container.appendChild(div);
                }
                reader.readAsDataURL(file);
            });
        }

        function addHighlightField() {
            const container = document.getElementById('highlight_container');
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            div.innerHTML = `<input type="text" name="highlights[]" placeholder="Another highlight..." class="w-full px-4 py-2 border rounded-xl border-gray-200 focus:ring-2 focus:ring-red-500 outline-none"><button type="button" onclick="removeField(this)" class="px-3 text-red-500 hover:text-red-700"><i class="fa-solid fa-trash"></i></button>`;
            container.appendChild(div);
        }

        function removeField(button) {
            button.parentElement.remove();
        }
    </script>
</x-admin-layout>
