<template>
  <div v-if="editor">
    <div class="flex flex-wrap gap-2">
      <button
        type="button"
        class="border border-gray-200 inline-flex px-1"
        @click="editor?.chain().focus().toggleBold().run()"
        title="bold"
      >
        <span
          :class="['material-icons-outlined text-base', editor.isActive('bold') ? 'text-blue-500' : 'text-gray-700']"
        >
          format_bold
        </span>
      </button>

      <button
        type="button"
        class="border border-gray-200 inline-flex px-1"
        @click="editor?.chain().focus().toggleItalic().run()"
        title="italic"
      >
        <span
          :class="['material-icons-outlined text-base', editor.isActive('italic') ? 'text-blue-500' : 'text-gray-700']"
        >
          format_italic
        </span>
      </button>

      <button
        type="button"
        class="border border-gray-200 inline-flex px-1"
        @click="editor?.chain().focus().toggleStrike().run()"
        title="strike"
      >
        <span
          :class="['material-icons-outlined text-base', editor.isActive('strike') ? 'text-blue-500' : 'text-gray-700']"
        >
          strikethrough_s
        </span>
      </button>

      <span class="text-gray-300">|</span>

      <button
        type="button"
        class="border border-gray-200 inline-flex px-1"
        @click="editor?.chain().focus().setTextAlign('left').run()"
        title="align left"
      >
        <span
          :class="[
            'material-icons-outlined text-base',
            editor.isActive({ textAlign: 'left' }) ? 'text-blue-500' : 'text-gray-700',
          ]"
        >
          format_align_left
        </span>
      </button>

      <button
        type="button"
        class="border border-gray-200 inline-flex px-1"
        @click="editor?.chain().focus().setTextAlign('center').run()"
        title="align center"
      >
        <span
          :class="[
            'material-icons-outlined text-base',
            editor.isActive({ textAlign: 'center' }) ? 'text-blue-500' : 'text-gray-700',
          ]"
        >
          format_align_center
        </span>
      </button>

      <button
        type="button"
        class="border border-gray-200 inline-flex px-1"
        @click="editor?.chain().focus().setTextAlign('right').run()"
        title="align right"
      >
        <span
          :class="[
            'material-icons-outlined text-base',
            editor.isActive({ textAlign: 'right' }) ? 'text-blue-500' : 'text-gray-700',
          ]"
        >
          format_align_right
        </span>
      </button>

      <button
        type="button"
        class="border border-gray-200 inline-flex px-1"
        @click="editor?.chain().focus().setTextAlign('justify').run()"
        title="align justify"
      >
        <span
          :class="[
            'material-icons-outlined text-base',
            editor.isActive({ textAlign: 'justify' }) ? 'text-blue-500' : 'text-gray-700',
          ]"
        >
          format_align_justify
        </span>
      </button>
    </div>

    <div class="border border-gray-100 mt-2 p-2">
      <editor-content :editor="editor" />
    </div>
  </div>
</template>

<script lang="ts">
  import { defineComponent, onUnmounted, watch } from 'vue';
  import { useEditor, EditorContent } from '@tiptap/vue-3';

  import StarterKit from '@tiptap/starter-kit';
  import TextAlign from '@tiptap/extension-text-align';
  import Typography from '@tiptap/extension-typography';
  import Image from '@tiptap/extension-image';

  export default defineComponent({
    components: {
      EditorContent,
    },
    props: {
      modelValue: {
        type: String,
        default: '',
      },
    },
    emits: ['update:modelValue'],
    setup(props, ctx) {
      const editor = useEditor({
        content: props.modelValue,
        extensions: [
          StarterKit,
          Typography,
          Image.configure({
            inline: true,
          }),
          TextAlign.configure({
            types: ['heading', 'paragraph'],
          }),
        ],
        onUpdate: () => {
          ctx.emit('update:modelValue', editor.value?.getHTML());
        },
      });

      watch(
        () => props.modelValue,
        (to) => {
          const isSame = editor.value?.getHTML() === to;
          if (isSame) return;
          editor.value?.commands.setContent(to, false);
        }
      );

      onUnmounted(() => {
        editor.value?.destroy();
      });

      return {
        editor,
      };
    },
  });
</script>

<style lang="scss">
  .ProseMirror {
    min-height: 200px;
  }
</style>
