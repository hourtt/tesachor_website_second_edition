import { Editor } from '@tiptap/core'
import StarterKit from '@tiptap/starter-kit'
import Underline from '@tiptap/extension-underline'
import Link from '@tiptap/extension-link'
import Image from '@tiptap/extension-image'
import TextAlign from '@tiptap/extension-text-align'
import Placeholder from '@tiptap/extension-placeholder'
import TextStyle from '@tiptap/extension-text-style'
import Color from '@tiptap/extension-color'
import Highlight from '@tiptap/extension-highlight'
import Table from '@tiptap/extension-table'
import TableRow from '@tiptap/extension-table-row'
import TableHeader from '@tiptap/extension-table-header'
import TableCell from '@tiptap/extension-table-cell'

export default function registerRichEditor(Alpine) {
  Alpine.data('richEditor', (initial = '', uploadUrl = null) => {
    let editor // kept outside Alpine's reactive proxy on purpose (avoids stack overflow / lag)

    return {
      content: initial,
      tick: 0, // bumped on every change so toolbar active-state re-renders

      init() {
        editor = new Editor({
          element: this.$refs.surface,
          content: initial,
          extensions: [
            StarterKit,
            Underline,
            Link.configure({ openOnClick: false }),
            Image,
            TextAlign.configure({ types: ['heading', 'paragraph'] }),
            Placeholder.configure({ placeholder: 'Start writing…' }),
            TextStyle,
            Color,
            Highlight,
            Table.configure({ resizable: true }),
            TableRow, TableHeader, TableCell,
          ],
          editorProps: {
            attributes: {
              class: 'prose max-w-none min-h-[300px] p-4 focus:outline-none',
            },
          },
          onUpdate: ({ editor }) => {
            this.content = editor.isEmpty ? '' : editor.getHTML()
            this.tick++
          },
          onSelectionUpdate: () => { this.tick++ },
        })
      },

      destroy() { editor?.destroy() },

      is(name, attrs = {}) {
        this.tick // register reactive dependency
        return editor ? editor.isActive(name, attrs) : false
      },
      run(fn) { fn(editor.chain().focus()).run() },

      setLink() {
        const prev = editor.getAttributes('link').href ?? ''
        const url = window.prompt('URL', prev)
        if (url === null) return
        const chain = editor.chain().focus().extendMarkRange('link')
        url === '' ? chain.unsetLink().run() : chain.setLink({ href: url }).run()
      },

      async uploadImage(event) {
        const file = event.target.files[0]
        if (!file || !uploadUrl) return
        const body = new FormData()
        body.append('image', file)
        const res = await fetch(uploadUrl, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            Accept: 'application/json',
          },
          body,
        })
        if (!res.ok) { alert('Image upload failed'); return }
        const { url } = await res.json()
        editor.chain().focus().setImage({ src: url }).run()
        event.target.value = ''
      },
    }
  })
}
