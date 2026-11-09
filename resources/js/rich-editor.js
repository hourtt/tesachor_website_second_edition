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
  Alpine.data('richEditor', (initial = '', uploadUrl = null, isKhmer = false, minHeight = 'min-h-[180px]') => {
    let editor // kept outside Alpine's reactive proxy on purpose (prevents call stack overflow / lag)

    return {
      content: initial || '',
      tick: 0, // bumped on every change so toolbar active-state re-renders

      init() {
        const editorClass = `prose max-w-none ${minHeight} p-4 focus:outline-none ${isKhmer ? 'font-khmer leading-loose' : ''}`.trim()

        editor = new Editor({
          element: this.$refs.surface,
          content: initial || '',
          extensions: [
            StarterKit,
            Underline,
            Link.configure({ openOnClick: false }),
            Image,
            TextAlign.configure({ types: ['heading', 'paragraph'] }),
            Placeholder.configure({ placeholder: isKhmer ? 'សរសេរនៅទីនេះ…' : 'Start writing…' }),
            TextStyle,
            Color,
            Highlight,
            Table.configure({ resizable: true }),
            TableRow,
            TableHeader,
            TableCell,
          ],
          editorProps: {
            attributes: {
              class: editorClass,
            },
          },
          onUpdate: ({ editor }) => {
            this.content = editor.isEmpty ? '' : editor.getHTML()
            this.tick++
          },
          onSelectionUpdate: () => {
            this.tick++
          },
        })
      },

      destroy() {
        editor?.destroy()
      },

      // toolbar helpers
      is(name, attrs = {}) {
        this.tick // register reactive dependency
        return editor ? editor.isActive(name, attrs) : false
      },

      run(fn) {
        if (!editor) return
        fn(editor.chain().focus()).run()
      },

      setLink() {
        if (!editor) return
        const prev = editor.getAttributes('link').href ?? ''
        const url = window.prompt('URL', prev)
        if (url === null) return
        const chain = editor.chain().focus().extendMarkRange('link')
        if (url === '') {
          chain.unsetLink().run()
        } else {
          chain.setLink({ href: url }).run()
        }
      },

      async uploadImage(event) {
        const file = event.target.files?.[0]
        if (!file || !uploadUrl) return
        const body = new FormData()
        body.append('image', file)

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        const headers = {
          Accept: 'application/json',
        }
        if (csrfToken) {
          headers['X-CSRF-TOKEN'] = csrfToken
        }

        try {
          const res = await fetch(uploadUrl, {
            method: 'POST',
            headers,
            body,
          })
          if (!res.ok) {
            alert('Image upload failed')
            return
          }
          const { url } = await res.json()
          if (url && editor) {
            editor.chain().focus().setImage({ src: url }).run()
          }
        } catch (err) {
          console.error(err)
          alert('Image upload failed')
        } finally {
          event.target.value = ''
        }
      },
    }
  })
}

