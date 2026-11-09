---
name: laravel-wysiwyg-editor
description: Step-by-step workflow to add a rich-text (WYSIWYG) editor to a Laravel project using Tiptap v2, Alpine.js, Blade and Tailwind, including toolbar, image upload, HTML sanitization (XSS protection) and PHPUnit tests. Use this skill whenever the user mentions WYSIWYG, rich text editor, rich editor, Tiptap, text editor component, formatted content, blog/post/article body editor, or wants bold/headings/lists/tables/images/links editing inside a Laravel form, even if they don't name a library.
---

# Laravel WYSIWYG editor (Tiptap + Alpine + Blade + Tailwind)

Builds a reusable `<x-rich-editor>` Blade component. Tiptap is headless, so the toolbar is plain Blade/Tailwind and every format (bold, italic, underline, strike, headings, lists, quote, code block, alignment, link, colour, highlight, images, tables, undo/redo) is available.

Data flow: Tiptap → hidden input → controller validates → Purify sanitizes → DB → display with `prose`.

## Before starting

Check the project first instead of asking the user, so the steps fit their stack:
- Laravel version (`composer.json`) → decides `casts()` method vs `$casts` property.
- Tailwind v3 or v4 (`package.json`) → decides how the typography plugin is enabled.
- Is Livewire used? → changes how Alpine is registered (see references file).
- Which model/column stores the content (default example: `Post.body`) and its create/edit form.

Ask only for what cannot be found (usually: which model/field and which form).

## Steps

Templates live in `assets/`. Copy them rather than retyping, then adapt names.

1. **Install packages** (pin Tiptap to v2; v3 moved Underline/Link into StarterKit, which breaks these imports):
   ```bash
   npm install @tiptap/core@^2 @tiptap/pm@^2 @tiptap/starter-kit@^2 \
     @tiptap/extension-underline@^2 @tiptap/extension-link@^2 \
     @tiptap/extension-image@^2 @tiptap/extension-text-align@^2 \
     @tiptap/extension-placeholder@^2 @tiptap/extension-text-style@^2 \
     @tiptap/extension-color@^2 @tiptap/extension-highlight@^2 \
     @tiptap/extension-table@^2 @tiptap/extension-table-row@^2 \
     @tiptap/extension-table-header@^2 @tiptap/extension-table-cell@^2
   npm install -D @tailwindcss/typography
   composer require stevebauman/purify
   php artisan storage:link
   ```
   Enable typography: Tailwind v4 → `@plugin "@tailwindcss/typography";` in `resources/css/app.css`; v3 → `require('@tailwindcss/typography')` in `tailwind.config.js` plugins.

2. **Alpine component**: copy `assets/rich-editor.js` to `resources/js/rich-editor.js`. In `resources/js/app.js` call `registerRichEditor(Alpine)` before `Alpine.start()`. (Livewire 3: register inside the `alpine:init` event instead.) The `editor` instance stays in a closure and the `tick` counter drives toolbar state; both matter, because putting the editor on the reactive object causes lag/stack overflow and without `tick` active buttons never highlight.

3. **Blade component**: copy `assets/rich-editor.blade.php` to `resources/views/components/rich-editor.blade.php`. Append `assets/editor.css` to `resources/css/app.css`. Ensure the layout has `<meta name="csrf-token" content="{{ csrf_token() }}">` and `@vite(['resources/css/app.css','resources/js/app.js'])`.

4. **Use in a form**:
   ```blade
   <x-rich-editor name="body" :value="$post->body ?? ''" :upload-url="route('editor.upload')" />
   @error('body') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
   ```
   Omit `:upload-url` to hide the image button.

5. **Image upload endpoint**: copy `assets/EditorUploadController.php` to `app/Http/Controllers/`, then add
   `Route::middleware('auth')->post('/editor/upload', EditorUploadController::class)->name('editor.upload');`

6. **Sanitize (never skip)**: editor output is raw HTML, so rendering it with `{!! !!}` unsanitized is an XSS hole. Add the cast `'body' => PurifyHtmlOnSet::class` (`Stevebauman\Purify\Casts\PurifyHtmlOnSet`) to the model, publish the Purify config and extend the allowed tags/CSS as described in `references/extensions-and-troubleshooting.md`. Test by saving content using every format and confirm it survives.

7. **Display**: `<article class="prose max-w-none">{!! $post->body !!}</article>`

8. **Tests**: copy `assets/RichEditorTest.php` into `tests/Feature/`, adapt model/route/field names, run `php artisan test`. It checks that `<script>` is stripped and the upload returns a URL.

9. **Verify in browser**: `npm run dev`, open the form, try each toolbar button, save, reload the edit page (content must reappear), check the rendered page.

## Extending or debugging

Read `references/extensions-and-troubleshooting.md` when the user wants extra formats (task lists, YouTube, character count, font family, table row/column buttons), uses Livewire, or hits a problem (inactive toolbar, laggy editor, missing content on edit, 404 images, stripped alignment/colour).

## Guidelines

- Guide the user step by step and wait for confirmation if they are following along manually; apply the changes directly if you have file access to the project.
- Keep names consistent with the user's existing code (their model, routes, layout) rather than the `Post`/`body` examples.
- Any new format needs three things: the extension, a toolbar button, and an allowance in the Purify config. Forgetting the last one is the most common cause of formatting vanishing after save.
