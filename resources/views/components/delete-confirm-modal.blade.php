{{-- Reusable Delete Confirmation Modal Component --}}
<div x-data="deleteConfirmModal()"
    x-cloak
    x-show="isOpen"
    @open-delete-modal.window="openModal($event.detail)"
    @keydown.escape.window="if (!isDeleting) closeModal()"
    class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4 sm:p-0"
    style="display: none;"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    role="dialog"
    aria-modal="true"
    aria-labelledby="delete-modal-title">

    {{-- Backdrop Click Area --}}
    <div class="fixed inset-0" @click="if (!isDeleting) closeModal()"></div>

    {{-- Modal Card --}}
    <div x-show="isOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        @click.outside="if (!isDeleting) closeModal()"
        class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 p-6 border border-gray-100 z-10 text-left">

        {{-- Warning Icon Header --}}
        <div class="w-12 h-12 rounded-full bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-4 ring-8 ring-red-50/60 shadow-xs shrink-0">
            <i class="fa-solid fa-trash-can text-xl"></i>
        </div>

        {{-- Title --}}
        <h3 id="delete-modal-title" class="text-lg font-bold text-gray-900 text-center" x-text="title">
            Delete Confirmation
        </h3>

        {{-- Message Body --}}
        <p class="text-sm text-gray-500 text-center mt-2 leading-relaxed" x-html="formattedMessage">
        </p>

        {{-- Action Buttons --}}
        <div class="mt-6 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3">
            <button type="button"
                @click="closeModal()"
                :disabled="isDeleting"
                class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 font-medium text-sm transition text-center cursor-pointer">
                Cancel
            </button>
            <button type="button"
                @click="confirmDelete()"
                :disabled="isDeleting"
                :class="{ 'opacity-75 cursor-not-allowed': isDeleting }"
                class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm shadow-sm transition inline-flex items-center justify-center gap-2 cursor-pointer">
                <template x-if="isDeleting">
                    <span class="inline-flex items-center justify-center gap-2">
                        <i class="fa-solid fa-circle-notch fa-spin text-sm"></i>
                        <span>Deleting...</span>
                    </span>
                </template>
                <template x-if="!isDeleting">
                    <span class="inline-flex items-center justify-center gap-2">
                        <i class="fa-solid fa-trash-can text-sm"></i>
                        <span>Delete</span>
                    </span>
                </template>
            </button>
        </div>

        {{-- Hidden Fallback Form --}}
        <form x-ref="fallbackForm" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>

<script>
    if (typeof window.deleteConfirmModal !== 'function') {
        window.deleteConfirmModal = function () {
            return {
                isOpen: false,
                isDeleting: false,
                title: 'Delete Item?',
                name: '',
                warning: '',
                action: '',
                targetForm: null,

                get formattedMessage() {
                    if (!this.warning) {
                        return '';
                    }
                    const escapeHtml = (str) => {
                        const div = document.createElement('div');
                        div.textContent = str;
                        return div.innerHTML;
                    };

                    let safeWarning = escapeHtml(this.warning);
                    // Support markdown style **Item Name**
                    safeWarning = safeWarning.replace(/\*\*(.*?)\*\*/g, '<strong class="font-bold text-gray-900">$1</strong>');

                    // If safeWarning doesn't already have bold tags for name, wrap name in bold
                    if (this.name) {
                        const safeName = escapeHtml(this.name);
                        if (safeWarning.includes(safeName) && !safeWarning.includes('<strong class="font-bold text-gray-900">' + safeName + '</strong>')) {
                            safeWarning = safeWarning.split(safeName).join('<strong class="font-bold text-gray-900">' + safeName + '</strong>');
                        }
                    }
                    return safeWarning;
                },

                openModal(detail) {
                    this.isOpen = true;
                    this.isDeleting = false;
                    this.title = detail.title || 'Delete Item?';
                    this.name = detail.name || '';
                    this.warning = detail.warning || (this.name
                        ? `Are you sure you want to delete ${this.name}? This action cannot be undone.`
                        : 'Are you sure you want to delete this item? This action cannot be undone.');
                    this.action = detail.action || '';
                    this.targetForm = detail.form || null;
                },

                closeModal() {
                    if (this.isDeleting) return;
                    this.isOpen = false;
                    this.targetForm = null;
                },

                confirmDelete() {
                    if (this.isDeleting) return;
                    this.isDeleting = true;

                    if (this.targetForm && typeof this.targetForm.submit === 'function') {
                        this.targetForm.dataset.confirmed = 'true';
                        HTMLFormElement.prototype.submit.call(this.targetForm);
                        return;
                    }

                    if (this.action && this.$refs.fallbackForm) {
                        this.$refs.fallbackForm.action = this.action;
                        HTMLFormElement.prototype.submit.call(this.$refs.fallbackForm);
                        return;
                    }

                    this.isDeleting = false;
                    this.isOpen = false;
                }
            };
        };

        window.openDeleteModal = function (options) {
            window.dispatchEvent(new CustomEvent('open-delete-modal', { detail: options }));
        };

        document.addEventListener('submit', function (e) {
            const form = e.target.closest('form[data-confirm-delete]');
            if (!form) return;
            if (form.dataset.confirmed === 'true') {
                return;
            }
            e.preventDefault();
            window.openDeleteModal({
                form: form,
                title: form.dataset.title || form.getAttribute('data-delete-title') || 'Delete Item?',
                name: form.dataset.name || '',
                warning: form.dataset.warning || '',
                action: form.action,
            });
        });
    }
</script>

