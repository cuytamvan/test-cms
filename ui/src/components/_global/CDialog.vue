<template>
  <teleport to="body">
    <transition name="dialog">
      <div
        v-if="dialog"
        class="fixed z-30 top-0 left-0 right-0 bottom-0 bg-gray-800 bg-opacity-50 flex flex-wrap items-center justify-center"
      >
        <div
          class="absolute top-0 left-0 right-0 bottom-0"
          @click="
            () => {
              if (!disableCloseOverlay) hide();
            }
          "
        ></div>
        <div class="dialog-container relative z-10">
          <slot v-if="$slots.default" />
          <div v-else class="bg-white py-6 px-10 rounded-md" style="width: 400px; max-width: 90vw">
            <h4 class="flex text-xl text-gray-600">
              <span class="flex-1">{{ title }}</span>
              <button
                type="button"
                class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8"
                data-dismiss-target="#toast-success"
                aria-label="Close"
                @click="hide"
              >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                  <path
                    fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd"
                  ></path>
                </svg>
              </button>
            </h4>
            <p class="mt-2 text-gray-500">{{ message }}</p>
          </div>
        </div>
      </div>
    </transition>
  </teleport>
</template>

<script lang="ts">
  import { defineComponent, onMounted, ref } from 'vue';

  export default defineComponent({
    props: {
      title: {
        type: String,
        default: '',
      },
      message: {
        type: String,
        default: '',
      },
      disableCloseOverlay: {
        type: Boolean,
        default: false,
      },
    },
    emits: ['hide', 'show'],
    setup(props, ctx) {
      const dialog = ref(false);

      const show = () => {
        dialog.value = true;
        ctx.emit('show');
      };

      const hide = () => {
        dialog.value = false;
        ctx.emit('hide');
      };

      const triggerKeydown = (e: KeyboardEvent) => {
        const code = e.charCode || e.keyCode;
        if (code === 27) hide();
      };

      onMounted(() => {
        window.addEventListener('keydown', (e: KeyboardEvent) => {
          const code = e.charCode || e.keyCode;
          if (code === 27) hide();
        });
      });

      return {
        dialog,

        show,
        hide,

        triggerKeydown,
      };
    },
  });
</script>
