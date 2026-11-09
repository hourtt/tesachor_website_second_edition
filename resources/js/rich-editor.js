export default function registerRichEditor(Alpine) {
    Alpine.data(
        "richEditor",
        (
            initial = "",
            uploadUrl = null,
            isKhmer = false,
            minHeight = "min-h-[180px]"
        ) => {
            let editor; // Kept outside Alpine's reactive proxy to prevent call stack issues

            return {
                content: initial || "",
                tick: 0,
                ready: false,

                async init() {
                    const editorClass =
                        `prose max-w-none ${minHeight} p-4 focus:outline-none ${isKhmer ? "font-khmer leading-loose" : ""}`.trim();

                    const { createEditor } = await import("./editor-engine.js");

                    editor = createEditor({
                        element: this.$refs.surface,
                        content: initial || "",
                        editorClass,
                        isKhmer,
                        onUpdate: ({ editor: ed }) => {
                            this.content = ed.isEmpty ? "" : ed.getHTML();
                            this.tick++;
                        },
                        onSelectionUpdate: () => {
                            this.tick++;
                        },
                    });

                    this.ready = true;
                    this.tick++;
                },

                destroy() {
                    editor?.destroy();
                },

                // Toolbar helpers
                is(name, attrs = {}) {
                    this.tick; // Reactive dependency
                    return editor ? editor.isActive(name, attrs) : false;
                },

                run(fn) {
                    if (!editor) return;
                    fn(editor.chain().focus()).run();
                },

                setLink() {
                    if (!editor) return;
                    const prev = editor.getAttributes("link").href ?? "";
                    const url = window.prompt("URL", prev);
                    if (url === null) return;
                    const chain = editor.chain().focus().extendMarkRange("link");
                    if (url === "") {
                        chain.unsetLink().run();
                    } else {
                        chain.setLink({ href: url }).run();
                    }
                },

                async uploadImage(event) {
                    const file = event.target.files?.[0];
                    if (!file || !uploadUrl) return;
                    const body = new FormData();
                    body.append("image", file);

                    const csrfToken = document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content");
                    const headers = {
                        Accept: "application/json",
                    };
                    if (csrfToken) {
                        headers["X-CSRF-TOKEN"] = csrfToken;
                    }

                    try {
                        const res = await fetch(uploadUrl, {
                            method: "POST",
                            headers,
                            body,
                        });

                        if (!res.ok) {
                            const err = await res.json().catch(() => ({}));
                            alert(
                                err.message ||
                                    "Image upload failed. Please try again."
                            );
                            return;
                        }

                        const { url } = await res.json();
                        if (editor && url) {
                            editor.chain().focus().setImage({ src: url }).run();
                        }
                    } catch (e) {
                        alert("Network error while uploading image.");
                    } finally {
                        event.target.value = "";
                    }
                },
            };
        }
    );
}
