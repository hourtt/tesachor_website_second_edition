<x-admin-layout>
    <x-slot name="title">Add New Province</x-slot>
    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Add New Province</h1>
                <p class="text-gray-500 text-sm mt-1">Create a new province or city for travel destinations.</p>
            </div>
            <a href="{{ route('admins.provinces.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                <i class="fa-solid fa-arrow-left mr-1"></i> Back to Provinces
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
            <form action="{{ route('admins.provinces.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-6">
                    {{-- English Name --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Province Name (English)</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-blue-500 border-gray-200 outline-none transition capitalize"
                            placeholder="e.g. Siem Reap">
                        @error('name')
                            <p class="text-white-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Khmer Name --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Province Name (Khmer)</label>
                        <input type="text" name="name_in_khmer" value="{{ old('name_in_khmer') }}" required
                            pattern="[\u1780-\u17B3\u17B6-\u17D3 ]+" title="Enter Khmer letters only"
                            class="font-khmer font-normal w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-blue-500 border-gray-200 outline-none transition"
                            placeholder="e.g. សៀមរាប">
                        @error('name_in_khmer')
                            <p class="text-white-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Image Upload Section --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Cover Image</label>

                        <div class="flex flex-col md:flex-row gap-6 items-start">
                            {{-- Preview Box --}}
                            <div
                                class="shrink-0 w-44 h-44 bg-gray-50 rounded-xl overflow-hidden border border-gray-200 shadow-sm relative group">
                                <img id="final_preview" class="w-full h-full object-cover opacity-50"
                                    src="https://placehold.co/300x300?text=Preview">
                                <div id="edit_overlay"
                                    class="hidden absolute inset-0 bg-black/50 items-center justify-center transition-opacity opacity-0 group-hover:opacity-100 cursor-pointer"
                                    style="display: none;" onclick="reOpenCropper()">
                                    <span class="text-white text-xs font-bold bg-black/60 px-3 py-1.5 rounded-lg">
                                        <i class="fa-solid fa-pen mr-1"></i> Edit Crop
                                    </span>
                                </div>
                            </div>

                            {{-- Input Area --}}
                            <div class="flex-1">
                                <input type="file" id="image_input" name="thumbnail" accept="image/*" required
                                    onchange="startCropping(event)"
                                    class="block w-full text-sm text-slate-500
                                        file:mr-4 file:py-2.5 file:px-4
                                        file:rounded-xl file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-blue-50 file:text-blue-700
                                        hover:file:bg-blue-100
                                        cursor-pointer transition" />
                                <p class="text-xs text-gray-500 mt-2">Select an image to open the crop editor.</p>
                                @error('thumbnail')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex justify-end pt-8 mt-6 border-t border-gray-100 gap-3">
                    <a href="{{ route('admins.provinces.index') }}"
                        class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition font-medium text-sm">
                        Cancel
                    </a>
                    <button type="submit"
                        class="text-white bg-blue-600 hover:bg-blue-700 font-semibold rounded-xl px-6 py-2.5 shadow-sm transition text-sm cursor-pointer">
                        Save Province
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Cropper Modal --}}
    <div id="cropper_modal" class="fixed inset-0 z-50 hidden bg-black/80 items-center justify-center p-4"
        style="display: none;">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden">
            <div class="p-4 border-b flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Crop Image</h3>
                <button type="button" onclick="closeCropper()" class="text-gray-400 hover:text-gray-600 transition">
                    <i class="fa-solid fa-times text-lg"></i>
                </button>
            </div>

            <div class="h-96 bg-gray-900 flex justify-center items-center">
                <img id="image_to_crop" class="max-h-full max-w-full block" src="">
            </div>

            <div class="p-4 bg-gray-50 flex justify-end gap-3 border-t">
                <button type="button" onclick="closeCropper()"
                    class="px-4 py-2 text-gray-600 hover:bg-gray-200 rounded-xl text-sm font-medium">Cancel</button>
                <button type="button" onclick="performCrop()"
                    class="px-5 py-2 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 shadow-sm text-sm">
                    <i class="fa-solid fa-check mr-1.5"></i> Crop & Save
                </button>
            </div>
        </div>
    </div>
</x-admin-layout>
