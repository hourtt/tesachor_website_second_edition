@props(['name', 'value' => '', 'uploadUrl' => null])

<div x-data="richEditor(@js(old($name, $value)), @js($uploadUrl))"
     class="rounded-lg border border-gray-300 bg-white">

    {{-- Toolbar --}}
    <div class="flex flex-wrap items-center gap-1 border-b border-gray-200 p-2 text-sm">
        <button type="button" @click="run(c => c.toggleBold())"
                :class="is('bold') && 'bg-gray-200'" class="rounded px-2 py-1 font-bold">B</button>
        <button type="button" @click="run(c => c.toggleItalic())"
                :class="is('italic') && 'bg-gray-200'" class="rounded px-2 py-1 italic">I</button>
        <button type="button" @click="run(c => c.toggleUnderline())"
                :class="is('underline') && 'bg-gray-200'" class="rounded px-2 py-1 underline">U</button>
        <button type="button" @click="run(c => c.toggleStrike())"
                :class="is('strike') && 'bg-gray-200'" class="rounded px-2 py-1 line-through">S</button>

        <span class="mx-1 h-5 w-px bg-gray-300"></span>

        <button type="button" @click="run(c => c.toggleHeading({ level: 2 }))"
                :class="is('heading', { level: 2 }) && 'bg-gray-200'" class="rounded px-2 py-1">H2</button>
        <button type="button" @click="run(c => c.toggleHeading({ level: 3 }))"
                :class="is('heading', { level: 3 }) && 'bg-gray-200'" class="rounded px-2 py-1">H3</button>
        <button type="button" @click="run(c => c.toggleBulletList())"
                :class="is('bulletList') && 'bg-gray-200'" class="rounded px-2 py-1">• List</button>
        <button type="button" @click="run(c => c.toggleOrderedList())"
                :class="is('orderedList') && 'bg-gray-200'" class="rounded px-2 py-1">1. List</button>
        <button type="button" @click="run(c => c.toggleBlockquote())"
                :class="is('blockquote') && 'bg-gray-200'" class="rounded px-2 py-1">“ ”</button>
        <button type="button" @click="run(c => c.toggleCodeBlock())"
                :class="is('codeBlock') && 'bg-gray-200'" class="rounded px-2 py-1">&lt;/&gt;</button>

        <span class="mx-1 h-5 w-px bg-gray-300"></span>

        <button type="button" @click="run(c => c.setTextAlign('left'))" class="rounded px-2 py-1">Left</button>
        <button type="button" @click="run(c => c.setTextAlign('center'))" class="rounded px-2 py-1">Center</button>
        <button type="button" @click="run(c => c.setTextAlign('right'))" class="rounded px-2 py-1">Right</button>

        <span class="mx-1 h-5 w-px bg-gray-300"></span>

        <button type="button" @click="setLink()"
                :class="is('link') && 'bg-gray-200'" class="rounded px-2 py-1">Link</button>
        <input type="color" class="h-7 w-7 cursor-pointer"
               @input="run(c => c.setColor($event.target.value))" title="Text color">
        <button type="button" @click="run(c => c.toggleHighlight())"
                :class="is('highlight') && 'bg-gray-200'" class="rounded px-2 py-1">Highlight</button>

        @if ($uploadUrl)
            <button type="button" @click="$refs.file.click()" class="rounded px-2 py-1">Image</button>
            <input type="file" accept="image/*" x-ref="file" class="hidden" @change="uploadImage($event)">
        @endif

        <button type="button"
                @click="run(c => c.insertTable({ rows: 3, cols: 3, withHeaderRow: true }))"
                class="rounded px-2 py-1">Table</button>

        <span class="mx-1 h-5 w-px bg-gray-300"></span>

        <button type="button" @click="run(c => c.undo())" class="rounded px-2 py-1">Undo</button>
        <button type="button" @click="run(c => c.redo())" class="rounded px-2 py-1">Redo</button>
    </div>

    {{-- Editing surface --}}
    <div x-ref="surface"></div>

    {{-- Submitted with the form --}}
    <input type="hidden" name="{{ $name }}" :value="content">
</div>
