<script setup>
import { useEditor, Editor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'


import BoldIcon from 'vue-material-design-icons/FormatBold.vue'
import ItalicIcon from 'vue-material-design-icons/FormatItalic.vue'
import Header1Icon from 'vue-material-design-icons/FormatHeader1.vue'
import Header2Icon from 'vue-material-design-icons/FormatHeader2.vue'
import BulletedListIcon from 'vue-material-design-icons/FormatListBulleted.vue'
import NumberedListIcon from 'vue-material-design-icons/FormatListNumbered.vue'
import QuoteCloseIcon from 'vue-material-design-icons/FormatQuoteClose.vue'
import MinusIcon from "vue-material-design-icons/Minus.vue"

const props = defineProps({
    modelValue: String,
})

const emit = defineEmits(["update:modelValue"])

const editor = useEditor({
    content: props.modelValue,
    onUpdate: ({editor}) => {
        emit("update:modelValue", editor.getHTML())
    },
    extensions: [StarterKit],
    editorProps: {
        attributes: {
            class: "border border-gray-400 p-4 min-h-40 max-h-40 overflow-y-auto outline-none prose max-w-none"
        }
    },
    onTransaction: ({ editor }) => {
        const el = editor.view.dom

        if(!el) return 
        const oldScroll = el.scrollTop
        requestAnimationFrame(() => {
            el.scrollTop = oldScroll
        })
    }
})
</script>
<template>
        <section v-if="editor" class="text-gray-700 buttons flex items-center flex-wrap gap-x-4 border-t border-l border-r border-gray-400 rounded-t px-4 pt-4 pb-3">
            <button
            @click="editor.chain().focus().toggleBold().run()"
            :disabled="!editor.can().chain().focus().toggleBold().run()"
            :class="{ 'bg-gray-200 rounded': editor.isActive('bold') }"
            class="p-1"
            type="button"
            >
                <BoldIcon title="Bold" />
            </button>
            <button
            @click="editor.chain().focus().toggleItalic().run()"
            :disabled="!editor.can().chain().focus().toggleItalic().run()"
            :class="{ 'bg-gray-200 rounded': editor.isActive('italic') }"
            class="p-1"
            type="button"
            >
                <ItalicIcon title="Italic"/>
            </button>
            <button
            @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
            :class="{ 'bg-gray-200 rounded': editor.isActive('heading', { level: 2 }) }"
            class="p-1"
            type="button"
            >
                <Header1Icon title="H1"/>
            </button>
            <button
            @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
            :class="{ 'bg-gray-200 rounded': editor.isActive('heading', { level: 3 }) }"
            class="p-1"
            type="button"
            >
                <Header2Icon title="H2"/>
            </button>

            <button
            @click="editor.chain().focus().toggleBulletList().run()"
            :class="{ 'bg-gray-200 rounded': editor.isActive('bulletList') }"
            class="p-1"
            type="button"
            >
                <BulletedListIcon title="Unordered List"/>
            </button>
            <button
            @click="editor.chain().focus().toggleOrderedList().run()"
            :class="{ 'bg-gray-200 rounded': editor.isActive('orderedList') }"
            >
                <NumberedListIcon title="Ordered List"/>
            </button>
            <button
            @click="editor.chain().focus().toggleBlockquote().run()"
            :class="{ 'bg-gray-200 rounded': editor.isActive('blockquote') }"
            class="p-1"
            type="button"
            >
                <QuoteCloseIcon title="Quote"/>
            </button>
            <button @click="editor.chain().focus().setHorizontalRule().run()" type="button"><MinusIcon title="Horizontal Dividing Line"/></button>
            <button @click="editor.chain().focus().setHardBreak().run()" type="button">Line break</button>
            <button @click="editor.chain().focus().undo().run()" :disabled="!editor.can().chain().focus().undo().run()" class="p-1 disabled:text-gray-400" type="button">
            Undo
            </button>
            <button @click="editor.chain().focus().redo().run()" :disabled="!editor.can().chain().focus().redo().run()" class="p-1 disabled:text-gray-400" type="button">
            Redo
            </button>
        </section>
        <EditorContent :editor="editor"/>
</template>