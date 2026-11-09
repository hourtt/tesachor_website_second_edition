@props([
    'name',
    'value' => '',
    'uploadUrl' => null,
    'khmer' => false,
    'minHeight' => 'min-h-[180px]',
])

<div x-data="richEditor(@js(old($name, $value ?? '')), @js($uploadUrl), @js((bool)$khmer), @js($minHeight))"
     class="rounded-xl border border-gray-200 bg-white overflow-hidden shadow-xs focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition">

    {{-- Toolbar --}}
    <div class="flex flex-wrap items-center gap-1 border-b border-gray-200 bg-gray-50/70 p-2 text-xs font-medium text-gray-700">
        {{-- Text styles --}}
        <button type="button" @click="run(c => c.toggleBold())"
                :class="is('bold') ? 'bg-blue-100 text-blue-700 font-bold' : 'hover:bg-gray-200 text-gray-700'"
                class="rounded px-2.5 py-1.5 transition font-bold" title="Bold">
            B
        </button>
        <button type="button" @click="run(c => c.toggleItalic())"
                :class="is('italic') ? 'bg-blue-100 text-blue-700 font-bold' : 'hover:bg-gray-200 text-gray-700'"
                class="rounded px-2.5 py-1.5 transition italic" title="Italic">
            I
        </button>
        <button type="button" @click="run(c => c.toggleUnderline())"
                :class="is('underline') ? 'bg-blue-100 text-blue-700 font-bold' : 'hover:bg-gray-200 text-gray-700'"
                class="rounded px-2.5 py-1.5 transition underline" title="Underline">
            U
        </button>
        <button type="button" @click="run(c => c.toggleStrike())"
                :class="is('strike') ? 'bg-blue-100 text-blue-700 font-bold' : 'hover:bg-gray-200 text-gray-700'"
                class="rounded px-2.5 py-1.5 transition line-through" title="Strikethrough">
            S
        </button>

        <span class="mx-1 h-4 w-px bg-gray-300"></span>

        {{-- Headings & Lists --}}
        <button type="button" @click="run(c => c.toggleHeading({ level: 2 }))"
                :class="is('heading', { level: 2 }) ? 'bg-blue-100 text-blue-700 font-bold' : 'hover:bg-gray-200 text-gray-700'"
                class="rounded px-2 py-1.5 transition" title="Heading 2">
            H2
        </button>
        <button type="button" @click="run(c => c.toggleHeading({ level: 3 }))"
                :class="is('heading', { level: 3 }) ? 'bg-blue-100 text-blue-700 font-bold' : 'hover:bg-gray-200 text-gray-700'"
                class="rounded px-2 py-1.5 transition" title="Heading 3">
            H3
        </button>
        <button type="button" @click="run(c => c.toggleBulletList())"
                :class="is('bulletList') ? 'bg-blue-100 text-blue-700 font-bold' : 'hover:bg-gray-200 text-gray-700'"
                class="rounded p-1.5 transition flex items-center justify-center" title="Bullet List">
            <x-icon name="list-ul" class="w-3.5 h-3.5" />
        </button>
        <button type="button" @click="run(c => c.toggleOrderedList())"
                :class="is('orderedList') ? 'bg-blue-100 text-blue-700 font-bold' : 'hover:bg-gray-200 text-gray-700'"
                class="rounded p-1.5 transition flex items-center justify-center" title="Numbered List">
            <x-icon name="list-ol" class="w-3.5 h-3.5" />
        </button>
        <button type="button" @click="run(c => c.toggleBlockquote())"
                :class="is('blockquote') ? 'bg-blue-100 text-blue-700 font-bold' : 'hover:bg-gray-200 text-gray-700'"
                class="rounded px-2 py-1.5 transition" title="Quote">
            “ ”
        </button>
        <button type="button" @click="run(c => c.toggleCodeBlock())"
                :class="is('codeBlock') ? 'bg-blue-100 text-blue-700 font-bold' : 'hover:bg-gray-200 text-gray-700'"
                class="rounded px-2 py-1.5 transition font-mono" title="Code Block">
            &lt;/&gt;
        </button>

        <span class="mx-1 h-4 w-px bg-gray-300"></span>

        {{-- Alignment --}}
        <button type="button" @click="run(c => c.setTextAlign('left'))"
                :class="is({ textAlign: 'left' }) ? 'bg-blue-100 text-blue-700 font-bold' : 'hover:bg-gray-200 text-gray-700'"
                class="rounded p-1.5 transition flex items-center justify-center" title="Align Left">
            <x-icon name="align-left" class="w-3.5 h-3.5" />
        </button>
        <button type="button" @click="run(c => c.setTextAlign('center'))"
                :class="is({ textAlign: 'center' }) ? 'bg-blue-100 text-blue-700 font-bold' : 'hover:bg-gray-200 text-gray-700'"
                class="rounded p-1.5 transition flex items-center justify-center" title="Align Center">
            <x-icon name="align-center" class="w-3.5 h-3.5" />
        </button>
        <button type="button" @click="run(c => c.setTextAlign('right'))"
                :class="is({ textAlign: 'right' }) ? 'bg-blue-100 text-blue-700 font-bold' : 'hover:bg-gray-200 text-gray-700'"
                class="rounded p-1.5 transition flex items-center justify-center" title="Align Right">
            <x-icon name="align-right" class="w-3.5 h-3.5" />
        </button>

        <span class="mx-1 h-4 w-px bg-gray-300"></span>

        {{-- Formatting & Colors --}}
        <button type="button" @click="setLink()"
                :class="is('link') ? 'bg-blue-100 text-blue-700 font-bold' : 'hover:bg-gray-200 text-gray-700'"
                class="rounded px-2 py-1.5 transition" title="Insert Link">
            Link
        </button>
        <label class="flex items-center gap-1 cursor-pointer rounded px-1.5 py-1 hover:bg-gray-200 transition" title="Text Color">
            <input type="color" class="h-5 w-5 rounded cursor-pointer border border-gray-300"
                   @input="run(c => c.setColor($event.target.value))">
        </label>
        <button type="button" @click="run(c => c.toggleHighlight())"
                :class="is('highlight') ? 'bg-blue-100 text-blue-700 font-bold' : 'hover:bg-gray-200 text-gray-700'"
                class="rounded px-2 py-1.5 transition" title="Highlight">
            Highlight
        </button>

        {{-- Image Upload --}}
        @if ($uploadUrl)
            <button type="button" @click="$refs.file.click()"
                    class="rounded px-2 py-1.5 hover:bg-gray-200 text-gray-700 transition" title="Insert Image">
                Image
            </button>
            <input type="file" accept="image/*" x-ref="file" class="hidden" @change="uploadImage($event)">
        @endif

        {{-- Table --}}
        <button type="button"
                @click="run(c => c.insertTable({ rows: 3, cols: 3, withHeaderRow: true }))"
                class="rounded px-2 py-1.5 hover:bg-gray-200 text-gray-700 transition" title="Insert Table">
            Table
        </button>

        <span class="mx-1 h-4 w-px bg-gray-300"></span>

        {{-- History --}}
        <button type="button" @click="run(c => c.undo())"
                class="rounded px-2 py-1.5 hover:bg-gray-200 text-gray-700 transition" title="Undo">
            ↺
        </button>
        <button type="button" @click="run(c => c.redo())"
                class="rounded px-2 py-1.5 hover:bg-gray-200 text-gray-700 transition" title="Redo">
            ↻
        </button>
    </div>

    {{-- Editing surface --}}
    <div x-ref="surface" class="bg-white"></div>

    {{-- Submitted with the form --}}
    <input type="hidden" name="{{ $name }}" :value="content">
</div>

