import Cropper from "cropperjs";
import "cropperjs/dist/cropper.css";

let cropper = null;
let originalFile = null;
let hasCropped = false;

function getElements() {
    return {
        imageInput: document.getElementById("image_input"),
        imageToCrop: document.getElementById("image_to_crop"),
        modal: document.getElementById("cropper_modal"),
        finalPreview: document.getElementById("final_preview"),
        editOverlay: document.getElementById("edit_overlay"),
    };
}

window.startCropping = function (event) {
    if (
        !event ||
        !event.target ||
        !event.target.files ||
        !event.target.files.length
    ) {
        return;
    }

    const file = event.target.files[0];
    if (!file || (file.type && !file.type.startsWith("image/"))) {
        return;
    }

    originalFile = file;
    hasCropped = false;

    const reader = new FileReader();
    reader.onload = function (e) {
        const { imageToCrop } = getElements();
        if (imageToCrop) {
            imageToCrop.src = e.target.result;
            window.openModal();
        }
    };
    reader.readAsDataURL(file);
};

window.openModal = function () {
    const { modal, imageToCrop } = getElements();
    if (!modal || !imageToCrop) return;

    modal.classList.remove("hidden");
    modal.classList.add("flex");
    modal.style.display = "flex";

    if (cropper) {
        cropper.destroy();
        cropper = null;
    }

    if (typeof Cropper !== "undefined") {
        cropper = new Cropper(imageToCrop, {
            aspectRatio: NaN,
            viewMode: 1,
            dragMode: "move",
            autoCropArea: 0.8,
        });
    }
};

window.performCrop = function () {
    if (!cropper) return;
    const { finalPreview, editOverlay, imageInput } = getElements();

    cropper.getCroppedCanvas().toBlob((blob) => {
        if (!blob) return;

        const url = URL.createObjectURL(blob);
        if (finalPreview) {
            finalPreview.src = url;
            finalPreview.classList.remove("opacity-50");
        }
        if (editOverlay) {
            editOverlay.classList.remove("hidden");
            editOverlay.classList.add("flex");
            editOverlay.style.display = "flex";
        }

        const croppedFile = new File([blob], "cropped_image.jpg", {
            type: "image/jpeg",
        });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(croppedFile);

        if (imageInput) {
            imageInput.files = dataTransfer.files;
        }

        hasCropped = true;
        window.closeCropper();
    }, "image/jpeg");
};

window.reOpenCropper = function () {
    if (originalFile) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const { imageToCrop } = getElements();
            if (imageToCrop) {
                imageToCrop.src = e.target.result;
                window.openModal();
            }
        };
        reader.readAsDataURL(originalFile);
    }
};

window.closeCropper = function () {
    const { modal, imageInput } = getElements();
    if (modal) {
        modal.classList.add("hidden");
        modal.classList.remove("flex");
        modal.style.display = "none";
    }

    if (cropper) {
        cropper.destroy();
        cropper = null;
    }

    if (!hasCropped && imageInput) {
        imageInput.value = "";
    }
};
