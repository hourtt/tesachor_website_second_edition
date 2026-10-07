<x-app-layout :hideNav="true">
    {{-- 1. Cropper CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />

    <div class="max-w-7xl mx-auto py-10 px-4">

        <div class="container mx-auto px-4 max-w-2xl mb-10">
            <div class="bg-white rounded-lg shadow-md p-6 sm:p-10">
                <h1 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-4">
                    Add New Province
                </h1>

                {{-- The Form --}}
                <form action="{{ route('admins.provinces.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-6">
                        {{-- English Name --}}
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">Province Name (English)</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 border-gray-300 outline-none"
                                placeholder="e.g. Siem Reap">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Khmer Name --}}
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">Province Name (Khmer)</label>
                            <input type="text" name="name_in_khmer" value="{{ old('name_in_khmer') }}" required
                                class="font-khmer font-normal w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 border-gray-300 outline-none"
                                placeholder="e.g. សៀមរាប"> {{-- Using Khmer Font --}}
                        </div>

                        {{-- Image Upload Section (UPDATED) --}}
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">Cover Image</label>

                            <div class="flex flex-col md:flex-row gap-6 items-start">
                                {{-- Preview Box --}}
                                <div
                                    class="shrink-0 w-40 h-40 bg-gray-100 rounded-lg overflow-hidden border border-gray-200 shadow-sm relative group">
                                    {{-- This shows the final cropped result --}}
                                    <img id="final_preview" class="w-full h-full object-cover opacity-50"
                                        src="https://placehold.co/300x300?text=Preview">

                                    {{-- Edit Overlay (Appears on Hover after upload) --}}
                                    <div id="edit_overlay"
                                        class="hidden absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center transition-opacity opacity-0 group-hover:opacity-100 cursor-pointer"
                                        onclick="reOpenCropper()">
                                        <span
                                            class="text-white text-xs font-bold bg-black bg-opacity-50 px-2 py-1 rounded"><i
                                                class="fa-solid fa-pen"></i> Edit Crop</span>
                                    </div>
                                </div>

                                {{-- Input Area --}}
                                <div class="flex-1">
                                    <label class="block mb-2">
                                        <span class="sr-only">Choose profile photo</span>
                                        {{-- IMPORTANT: Added id="image_input" and onchange="startCropping" --}}
                                        <input type="file" id="image_input" name="thumbnail" accept="image/*" required
                                            onchange="startCropping(event)" class="block w-full text-sm text-slate-500
                                                file:mr-4 file:py-2 file:px-4
                                                file:rounded-full file:border-0
                                                file:text-sm file:font-semibold
                                                file:bg-blue-50 file:text-blue-700
                                                hover:file:bg-blue-100
                                                cursor-pointer transition" />
                                    </label>
                                    <p class="text-xs text-gray-500">Select an image to open the crop editor.</p>
                                    @error('thumbnail')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex justify-end pt-8 mt-4 gap-4">
                        <a href="{{ route('admins.dashboard') }}"
                            class="px-6 py-3 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 transition font-medium">Cancel</a>
                        <button type="submit"
                            class="text-white bg-blue-600 hover:bg-blue-700 font-bold rounded-lg px-8 py-3 shadow-lg transition transform hover:-translate-y-0.5">
                            Save Province
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- 2. THE CROPPER MODAL (Hidden by default) --}}
    <div id="cropper_modal"
        class="fixed inset-0 z-50 hidden bg-black bg-opacity-80 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full overflow-hidden">
            <div class="p-4 border-b flex justify-between items-center">
                <h3 class="font-bold text-gray-700">Crop Image</h3>
                <button type="button" onclick="closeCropper()" class="text-gray-500 hover:text-red-500"><i
                        class="fa-solid fa-times text-xl"></i></button>
            </div>

            {{-- Cropper Area --}}
            <div class="h-96 bg-gray-900 flex justify-center items-center">
                <img id="image_to_crop" class="max-h-full max-w-full block" src="">
            </div>

            {{-- Footer Controls --}}
            <div class="p-4 bg-gray-50 flex justify-end gap-3">
                <button type="button" onclick="closeCropper()"
                    class="px-4 py-2 text-gray-600 hover:bg-gray-200 rounded-lg">Cancel</button>
                <button type="button" onclick="performCrop()"
                    class="px-6 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow">
                    <i class="fa-solid fa-check mr-2"></i> Crop & Save
                </button>
            </div>
        </div>
    </div>

    {{-- 3. JAVASCRIPT LOGIC --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        let cropper;
        let originalFile;
        const imageInput = document.getElementById('image_input');
        const imageToCrop = document.getElementById('image_to_crop');
        const modal = document.getElementById('cropper_modal');
        const finalPreview = document.getElementById('final_preview');
        const editOverlay = document.getElementById('edit_overlay');

        // A. Start when user selects file
        function startCropping(event) {
            const file = event.target.files[0];
            if (file) {
                originalFile = file;
                const reader = new FileReader();
                reader.onload = function (e) {
                    imageToCrop.src = e.target.result;
                    openModal();
                }
                reader.readAsDataURL(file);
            }
        }

        // B. Open Modal & Init Cropper
        function openModal() {
            modal.classList.remove('hidden');
            if (cropper) {
                cropper.destroy();
            }

            cropper = new Cropper(imageToCrop, {
                aspectRatio: NaN, // NaN = Free crop. Change to 16/9 or 1 for fixed ratio.
                viewMode: 1, // Restrict crop box to image size
                dragMode: 'move', // Allow moving image
                autoCropArea: 0.8,
            });
        }

        // C. Perform Crop & Update Input
        function performCrop() {
            if (!cropper) return;

            cropper.getCroppedCanvas().toBlob((blob) => {
                // 1. Update Preview
                const url = URL.createObjectURL(blob);
                finalPreview.src = url;
                finalPreview.classList.remove('opacity-50');
                editOverlay.classList.remove('hidden'); // Enable re-edit button

                // 2. Update Input File
                const croppedFile = new File([blob], "cropped_image.jpg", {
                    type: "image/jpeg"
                });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(croppedFile);
                imageInput.files = dataTransfer.files;

                closeCropper();
            }, 'image/jpeg');
        }

        // D. Re-open editor with original file
        function reOpenCropper() {
            if (originalFile) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imageToCrop.src = e.target.result;
                    openModal();
                }
                reader.readAsDataURL(originalFile);
            }
        }

        function closeCropper() {
            modal.classList.add('hidden');
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            // Clear input if cancelled without a valid preview
            if (finalPreview.classList.contains('opacity-50')) {
                imageInput.value = '';
            }
        }
    </script>
</x-app-layout>