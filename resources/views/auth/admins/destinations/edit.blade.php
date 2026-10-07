<x-app-layout :hideNav="true">
    {{-- Custom Top Bar --}}
    <nav class="bg-white shadow mb-8">
        <div class="container mx-auto px-6 py-4">
            <a href="{{ route('admins.provinces.show', $destination->province_id) }}"
                class="text-xl font-bold text-gray-700 hover:text-blue-600 flex items-center gap-2 transition-colors">
                Back to List
            </a>
        </div>
    </nav>

    {{-- Main Container --}}
    <div class="container mx-auto px-4 max-w-4xl mb-10">
        <div class="bg-white rounded-lg shadow-md p-6 sm:p-10">
            <div class="flex items-center justify-between border-b pb-4 mb-6">
                <h1 class="text-2xl font-bold text-gray-800">
                    Edit: <span class="text-blue-600">{{ $destination->place_name }}</span>
                </h1>
                <span class="text-sm text-gray-400">ID: {{ $destination->id }}</span>
            </div>

            <form action="{{ route('admins.destinations.update', $destination->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- 1. Basic Info --}}
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4 border-l-4 border-blue-500 pl-2">1. Basic
                        Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">Place Name</label>
                            <input type="text" name="place_name"
                                value="{{ old('place_name', $destination->place_name) }}" required
                                class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 border-gray-300">
                        </div>

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
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">Rating</label>
                            <input type="number" step="0.1" max="5" min="0" name="rating"
                                value="{{ old('rating', $destination->rating) }}"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 border-gray-300">
                        </div>

                        <div class="flex items-center mt-6">
                            <input id="is_recommended" type="checkbox" name="is_recommended" value="1"
                                {{ $destination->is_recommended ? 'checked' : '' }}
                                class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="is_recommended"
                                class="ml-2 block text-sm font-medium text-gray-900">Recommended</label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Short Description</label>
                        <textarea name="short_description" rows="3" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 border-gray-300">{{ old('short_description', $destination->short_description) }}</textarea>
                    </div>
                </div>

                {{-- 2. Images --}}
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4 border-l-4 border-blue-500 pl-2">2. Images</h2>

                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Main Thumbnail</label>
                        <div class="flex items-start gap-4">
                            <label
                                class="w-full md:w-1/2 cursor-pointer border-2 border-dashed border-gray-300 rounded-lg p-6 hover:bg-gray-50 transition text-center">
                                <span class="text-gray-500">Change Thumbnail</span>
                                <input type="file" name="thumbnail" class="hidden" accept="image/*"
                                    onchange="previewSingleImage(event, 'thumbnail_preview')">
                            </label>
                            <div class="w-full md:w-1/2 h-40 bg-gray-100 rounded-lg overflow-hidden border">
                                <img id="thumbnail_preview"
                                    src="{{ $destination->thumbnail ? asset('storage/' . $destination->thumbnail) : 'https://placehold.co/600x400?text=No+Image' }}"
                                    class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Gallery Images</label>
                        <div class="w-full">
                            <label
                                class="block w-full cursor-pointer border-2 border-dashed border-gray-300 rounded-lg p-4 hover:bg-gray-50 transition text-center mb-4">
                                <span class="text-gray-500">+ Upload New Images (Replaces existing)</span>
                                <input type="file" name="gallery_images[]" multiple class="hidden" accept="image/*"
                                    onchange="previewMultipleImages(event)">
                            </label>

                            <div id="gallery_preview_container" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @if ($destination->gallery_images)
                                    @foreach ($destination->gallery_images as $img)
                                        <div class="relative h-24 rounded-lg overflow-hidden border shadow-sm group">
                                            <img src="{{ asset('storage/' . $img) }}"
                                                class="w-full h-full object-cover">
                                            <div
                                                class="absolute inset-0 bg-black bg-opacity-20 flex items-center justify-center text-white text-xs opacity-0 group-hover:opacity-100 transition">
                                                Current
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3. Content --}}
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4 border-l-4 border-blue-500 pl-2">3. Content &
                        History</h2>
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Full Description</label>
                        <textarea name="place_description" rows="5" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 border-gray-300">{{ old('place_description', $destination->place_description) }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">History</label>
                            <textarea name="place_history" rows="4" class="w-full px-4 py-2 border rounded-lg border-gray-300">{{ old('place_history', $destination->place_history) }}</textarea>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">Info & Tips</label>
                            <textarea name="place_information" rows="4" class="w-full px-4 py-2 border rounded-lg border-gray-300">{{ old('place_information', $destination->place_information) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- 4. Highlights --}}
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4 border-l-4 border-blue-500 pl-2">4. Highlights
                    </h2>
                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Bullet Points</label>
                        <div id="highlight_container" class="space-y-2">
                            @if (!empty($destination->highlights))
                                @foreach ($destination->highlights as $hl)
                                    <div class="flex gap-2">
                                        <input type="text" name="highlights[]" value="{{ $hl }}"
                                            class="w-full px-4 py-2 border rounded-lg border-gray-300 focus:ring-blue-500">
                                        <button type="button" onclick="removeField(this)"
                                            class="text-red-500 hover:text-red-700 p-2"><i
                                                class="fa-solid fa-trash"></i></button>
                                    </div>
                                @endforeach
                            @else
                                <div class="flex gap-2">
                                    <input type="text" name="highlights[]" placeholder="Highlight..."
                                        class="w-full px-4 py-2 border rounded-lg border-gray-300 focus:ring-blue-500">
                                    <button type="button" onclick="removeField(this)" class="text-red-500 p-2"><i
                                            class="fa-solid fa-trash"></i></button>
                                </div>
                            @endif
                        </div>
                        <button type="button" onclick="addHighlightField()"
                            class="mt-2 text-sm text-blue-600 font-medium hover:underline">+ Add Highlight</button>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-end pt-6 border-t gap-4">
                    <a href="{{ route('admins.destinations.index') }}"
                        class="px-6 py-3 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 transition">Cancel</a>
                    <button type="submit"
                        class="text-white bg-blue-600 hover:bg-blue-700 font-bold rounded-lg px-8 py-3 shadow-lg transition transform hover:-translate-y-0.5">
                        Update Destination
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Javascript --}}
    <script>
        function previewSingleImage(event, previewId) {
            const reader = new FileReader();
            const preview = document.getElementById(previewId);
            reader.onload = function() {
                preview.src = reader.result;
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
                    div.innerHTML =
                        `<img src="${e.target.result}" class="w-full h-full object-cover"><div class="absolute inset-0 bg-green-500 bg-opacity-20 flex items-center justify-center text-white text-xs font-bold">New</div>`;
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
                `<input type="text" name="highlights[]" class="w-full px-4 py-2 border rounded-lg border-gray-300 focus:ring-blue-500"><button type="button" onclick="removeField(this)" class="text-red-500 p-2"><i class="fa-solid fa-trash"></i></button>`;
            container.appendChild(div);
        }

        function removeField(button) {
            button.parentElement.remove();
        }
    </script>
</x-app-layout>
