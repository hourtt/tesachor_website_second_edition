// Destination Form Helpers (Single & Multiple Image Preview, Dynamic Highlights)

window.previewSingleImage = function (event, previewId) {
    const reader = new FileReader();
    const preview = document.getElementById(previewId);
    reader.onload = function () {
        if (preview) {
            preview.src = reader.result;
            preview.classList.remove("opacity-60");
        }
    };
    if (event.target.files && event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
};

window.previewMultipleImages = function (event) {
    const container = document.getElementById("gallery_preview_container");
    if (!container) return;
    container.innerHTML = "";
    Array.from(event.target.files).forEach((file) => {
        const reader = new FileReader();
        reader.onload = function (e) {
            const div = document.createElement("div");
            div.className =
                "relative h-24 rounded-xl overflow-hidden border border-gray-200 shadow-sm";
            div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
            container.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
};

window.addHighlightField = function () {
    const container = document.getElementById("highlight_container");
    if (!container) return;
    const div = document.createElement("div");
    div.className = "flex gap-2";
    div.innerHTML = `<input type="text" name="highlights[]" placeholder="Another highlight..." class="w-full px-4 py-2 border rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none"><button type="button" onclick="removeField(this)" class="px-3 text-red-500 hover:text-red-700"><i class="fa-solid fa-trash"></i></button>`;
    container.appendChild(div);
};

window.removeField = function (button) {
    if (button && button.parentElement) {
        button.parentElement.remove();
    }
};

window.handleDestinationSubmit = function (event) {
    const btn = document.getElementById("submitBtn");
    const spinner = document.getElementById("submitBtnSpinner");
    const text = document.getElementById("submitBtnText");
    if (btn) {
        setTimeout(() => {
            btn.disabled = true;
        }, 10);
        btn.classList.add("opacity-75", "cursor-not-allowed");
        if (spinner) spinner.classList.remove("hidden");
        if (text) text.textContent = "Uploading & Saving...";
    }
};
