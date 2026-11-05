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
    div.innerHTML = `<input type="text" name="highlights[]" placeholder="Another highlight..." class="w-full px-4 py-2 border rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none"><button type="button" onclick="removeField(this)" class="px-3 text-red-500 hover:text-red-700" title="Remove"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor" aria-hidden="true" width="1em" height="1em" class="inline-block"><path fill="currentColor" d="M136.7 5.9L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-8.7-26.1C306.9-7.2 294.7-16 280.9-16L167.1-16c-13.8 0-26 8.8-30.4 21.9zM416 144L32 144 53.1 467.1C54.7 492.4 75.7 512 101 512L347 512c25.3 0 46.3-19.6 47.9-44.9L416 144z"/></svg></button>`;
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
