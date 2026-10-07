<x-app-layout :hideNav="true">
    {{-- Custom Top Bar --}}
    <nav class="bg-white shadow mb-8">
        <div class="container mx-auto px-6 py-4">

            {{-- Check if we have a target province (e.g. Siem Reap) --}}
            @if (isset($targetProvince))
                {{-- * Already tested, can use only id to navigate cannot use slug* --}}
                <a href="{{ route('admins.provinces.show', $targetProvince->id) }}"
                    class="text-xl font-bold text-gray-700 hover:text-blue-600 flex items-center gap-2 transition-colors">
                    Back to {{ $targetProvince->name }} {{-- Says "Back to Siem Reap" --}}
                </a>
            @else
                {{-- Default fallback if accessed directly --}}
                <a href="{{ route('admins.destinations.index') }}"
                    class="text-xl font-bold text-gray-700 hover:text-blue-600 flex items-center gap-2 transition-colors">
                    Back to List
                </a>
            @endif

        </div>
    </nav>

    {{-- Main Form Container --}}
    <div class="container mx-auto px-4 max-w-4xl mb-10">
        <div class="bg-white rounded-lg shadow-md p-6 sm:p-10">
            <h1 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-4">Create New Destination</h1>

            {{-- Global Error Check (Optional, good for debugging) --}}
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                    <p class="font-bold">Something went wrong!</p>
                    <p>Please check the form for errors below.</p>
                </div>
            @endif

            <form action="{{ route('admins.destinations.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- 1. Basic Information --}}
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4 border-l-4 border-blue-500 pl-2">1. Basic
                        Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        {{-- Place Name --}}
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">Place Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="place_name" value="{{ old('place_name') }}" required
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 transition-colors
                                @error('place_name') border-red-500 focus:ring-red-200 @else border-gray-300 focus:ring-blue-500 @enderror"
                                placeholder="e.g. Angkor Wat">
                            @error('place_name')
                                <p class="text-red-500 text-xs mt-1"><i class="fa-solid fa-circle-exclamation"></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Province Dropdown --}}
                        <div class="mb-4"> {{-- Added a standard margin wrapper --}}
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Province <span class="text-red-500">*</span>
                            </label>

                            <select name="province_id" required
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 transition-colors
                                @error('province_id') border-red-500 focus:ring-red-200 @else border-gray-300 focus:ring-blue-500 @enderror">

                                <option value="" disabled selected>Select a Province</option>

                                {{-- Added ->sortBy('name') here to sort the name alphabetically --}}
                                @foreach ($provinces->sortBy('name') as $province)
                                    <option value="{{ $province->id }}" {{-- Cleaned up logic: uses old input if available, falls back to targetProvince if not --}}
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        {{-- Rating --}}
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">Rating (0 - 5.0)</label>
                            <input type="number" step="0.1" max="5" min="0" name="rating"
                                value="{{ old('rating', 5.0) }}"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 transition-colors
                                @error('rating') border-red-500 focus:ring-red-200 @else border-gray-300 focus:ring-blue-500 @enderror">
                            @error('rating')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Recommendation Checkbox --}}
                        <div class="flex items-center mt-6">
                            <input id="is_recommended" type="checkbox" name="is_recommended" value="1"
                                {{ old('is_recommended') ? 'checked' : '' }}
                                class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
                            <label for="is_recommended"
                                class="ml-2 block text-sm font-medium text-gray-900 cursor-pointer">
                                Mark as "Recommended for You"
                            </label>
                        </div>
                    </div>

                    {{-- Short Description --}}
                    <div class="mb-4">
                        <div class="flex justify-between">
                            <label class="block mb-2 text-sm font-medium text-gray-700">Short Description <span
                                    class="text-red-500">*</span></label>
                            <span class="text-xs text-gray-400">Max 500 chars</span>
                        </div>
                        <textarea name="short_description" rows="3" required maxlength="500"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 transition-colors
                            @error('short_description') border-red-500 focus:ring-red-200 @else border-gray-300 focus:ring-blue-500 @enderror">{{ old('short_description') }}</textarea>
                        @error('short_description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- 2. Images --}}
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4 border-l-4 border-blue-500 pl-2">2. Images</h2>

                    {{-- Thumbnail --}}
                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Main Thumbnail <span
                                class="text-red-500">*</span></label>
                        <div class="flex items-start gap-4">
                            <label
                                class="w-full md:w-1/2 cursor-pointer border-2 border-dashed rounded-lg p-6 hover:bg-gray-50 transition text-center
                                @error('thumbnail') border-red-400 bg-red-50 @else border-gray-300 @enderror">
                                <span class="text-gray-500">Click to Upload Thumbnail</span>
                                <input type="file" name="thumbnail" class="hidden"
                                    accept="image/jpeg,image/png,image/jpg,image/gif"
                                    onchange="previewSingleImage(event, 'thumbnail_preview')">
                            </label>
                            <div class="w-full md:w-1/2 h-40 bg-gray-100 rounded-lg overflow-hidden border">
                                <img id="thumbnail_preview" src="https://placehold.co/600x400?text=Preview"
                                    class="w-full h-full object-cover opacity-60">
                            </div>
                        </div>
                        @error('thumbnail')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Gallery --}}
                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Gallery Images (Multiple)</label>
                        <div class="w-full">
                            <label
                                class="block w-full cursor-pointer border-2 border-dashed border-gray-300 rounded-lg p-4 hover:bg-gray-50 transition text-center mb-4">
                                <span class="text-gray-500">+ Add Gallery Images</span>
                                <input type="file" name="gallery_images[]" multiple class="hidden"
                                    accept="image/jpeg,image/png,image/jpg,image/gif"
                                    onchange="previewMultipleImages(event)">
                            </label>
                            @error('gallery_images')
                                <p class="text-red-500 text-xs mb-2">{{ $message }}</p>
                            @enderror
                            @error('gallery_images.*')
                                <p class="text-red-500 text-xs mb-2">One of your images is invalid (Max 4MB).</p>
                            @enderror

                            <div id="gallery_preview_container" class="grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                        </div>
                    </div>
                </div>

                {{-- 3. Detailed Information --}}
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4 border-l-4 border-blue-500 pl-2">3. Content &
                        History</h2>

                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Full Description <span
                                class="text-red-500">*</span></label>
                        <textarea name="place_description" rows="5" required
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 transition-colors
                            @error('place_description') border-red-500 focus:ring-red-200 @else border-gray-300 focus:ring-blue-500 @enderror">{{ old('place_description') }}</textarea>
                        @error('place_description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">History</label>
                            <textarea name="place_history" rows="4"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 border-gray-300">{{ old('place_history') }}</textarea>
                            @error('place_history')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">General Information (Logistics,
                                Tips)</label>
                            <textarea name="place_information" rows="4"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 border-gray-300">{{ old('place_information') }}</textarea>
                            @error('place_information')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- 4. Highlights --}}
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4 border-l-4 border-blue-500 pl-2">4. Highlights
                    </h2>
                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Bullet Points</label>

                        {{-- Logic to repopulate highlights if validation fails --}}
                        <div id="highlight_container" class="space-y-2">
                            @if (old('highlights'))
                                @foreach (old('highlights') as $highlight)
                                    <div class="flex gap-2">
                                        <input type="text" name="highlights[]" value="{{ $highlight }}"
                                            class="w-full px-4 py-2 border rounded-lg border-gray-300">
                                        <button type="button" onclick="removeField(this)"
                                            class="text-red-500 hover:text-red-700"><i
                                                class="fa-solid fa-trash"></i></button>
                                    </div>
                                @endforeach
                            @else
                                {{-- Default empty field --}}
                                <div class="flex gap-2">
                                    <input type="text" name="highlights[]" placeholder="e.g. Sunrise view"
                                        class="w-full px-4 py-2 border rounded-lg border-gray-300">
                                    <button type="button" onclick="removeField(this)"
                                        class="text-red-500 hover:text-red-700"><i
                                            class="fa-solid fa-trash"></i></button>
                                </div>
                            @endif
                        </div>

                        <button type="button" onclick="addHighlightField()"
                            class="mt-2 text-sm text-blue-600 font-medium hover:underline">+ Add Highlight</button>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="flex justify-end pt-6 border-t">
                    <button type="submit"
                        class="text-white bg-blue-600 hover:bg-blue-700 font-bold rounded-lg px-8 py-3 shadow-lg transition transform hover:-translate-y-0.5">
                        Save Destination
                    </button>
                </div>
            </form>
        </div>
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
                    div.className = "relative h-24 rounded-lg overflow-hidden border shadow-sm";
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
            div.innerHTML =
                `<input type="text" name="highlights[]" placeholder="Another highlight..." class="w-full px-4 py-2 border rounded-lg border-gray-300"><button type="button" onclick="removeField(this)" class="text-red-500 hover:text-red-700"><i class="fa-solid fa-trash"></i></button>`;
            container.appendChild(div);
        }

        function removeField(button) {
            button.parentElement.remove();
        }
    </script>
</x-app-layout>
