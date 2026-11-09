# Extending the editor & troubleshooting

## Contents
- Adding more formats
- Purify config
- Livewire notes
- Troubleshooting

## Adding more formats
Each format = one Tiptap v2 extension package + one toolbar button.

| Want | Package | Toolbar command |
|---|---|---|
| Task list | `@tiptap/extension-task-list`, `-task-item` | `toggleTaskList()` |
| YouTube embed | `@tiptap/extension-youtube` | `setYoutubeVideo({ src })` |
| Subscript / superscript | `@tiptap/extension-subscript`, `-superscript` | `toggleSubscript()` / `toggleSuperscript()` |
| Character count | `@tiptap/extension-character-count` | `editor.storage.characterCount.characters()` |
| Font family | `@tiptap/extension-font-family` (needs TextStyle) | `setFontFamily('Inter')` |
| Horizontal rule | in StarterKit | `setHorizontalRule()` |

Steps: `npm install <pkg>@^2` → import + add to `extensions: [...]` in rich-editor.js → add a button in the Blade toolbar → allow the resulting tags in `config/purify.php` (e.g. `iframe[src]` for YouTube, `ul[data-type],li[data-checked]` for tasks) → add a sanitization test.

Table editing buttons (once a table exists): `addRowAfter()`, `addColumnAfter()`, `deleteRow()`, `deleteColumn()`, `deleteTable()`, `mergeCells()`.

## Purify config (`config/purify.php`)
Publish with `php artisan vendor:publish --provider="Stevebauman\Purify\PurifyServiceProvider"`.
Extend `HTML.Allowed` with: `img[src|alt]`, `a[href|target]`, `table,thead,tbody,tr,th,td`, `mark`, `pre,code`, `*[style]`.
Keep `CSS.AllowedProperties` limited to `text-align,color,background-color`.
Always verify by saving sample content with every format and checking what survives.
Never render editor output with `{!! !!}` unless it has passed through Purify (cast `PurifyHtmlOnSet`, or `clean()` on output).

## Livewire 3
Livewire bundles Alpine, so do not import Alpine yourself. Register with:
`document.addEventListener('alpine:init', () => registerRichEditor(window.Alpine))`
If the editor sits inside a Livewire component, add `wire:ignore` to the wrapper div so re-renders don't destroy the editor, and sync with `$wire.set('body', content)` inside `onUpdate`.

## Troubleshooting
- **Toolbar doesn't show active state** → `is()` must read `this.tick`; `onUpdate` and `onSelectionUpdate` must bump it.
- **"Maximum call stack" / laggy typing** → `editor` must be a closure variable, never a property on the Alpine data object.
- **Existing content missing on edit page** → pass `@js(old($name, $value))`.
- **Images 404** → `php artisan storage:link`, check `APP_URL` and the `public` disk URL.
- **Underline/Link duplicate-extension warning** → you're on Tiptap v3 (they're in StarterKit). Either pin v2 or remove the separate imports.
- **Alignment/colour lost after save** → Purify is stripping `style`; allow `*[style]` and the CSS properties above.
- **Validation says body required though editor has text** → hidden input is bound via `:value="content"`; make sure the `<x-rich-editor>` is inside the `<form>`.
- **Tailwind `prose` not styled** → typography plugin not enabled (v4: `@plugin "@tailwindcss/typography";`, v3: add to `plugins`).
