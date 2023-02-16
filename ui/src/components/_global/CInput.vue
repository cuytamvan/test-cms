<template>
  <div class="relative">
    <input
      v-model="value"
      :type="type"
      :class="[
        'border w-full rounded-md transition-all focus:border-primary-200 focus:ring ring-primary-300',
        size === 'md' && 'py-2 px-4',
        size === 'sm' && 'py-1 px-2 text-sm',
      ]"
      @input="updateValue"
      @focus="showFeature = true"
      @click="showFeature = true"
    />
  </div>
</template>

<script lang="ts">
  import { defineComponent, ref, watch } from 'vue';

  export default defineComponent({
    props: {
      modelValue: {
        type: String,
        default: null,
      },
      type: {
        type: String,
        default: 'text',
      },
      size: {
        type: String,
        default: 'md',
      },
      debounce: {
        type: Number,
        default: 0,
      },
    },
    emits: ['update:modelValue'],
    setup(props, ctx) {
      const value = ref(props.modelValue);
      const showFeature = ref(false);

      let debounce: number;
      const updateValue = (e: Event) => {
        if (props.debounce) {
          clearTimeout(debounce);
          debounce = setTimeout(() => {
            ctx.emit('update:modelValue', (e.target as HTMLInputElement).value);
          }, props.debounce);
        } else {
          ctx.emit('update:modelValue', (e.target as HTMLInputElement).value);
        }
      };

      watch(
        () => props.modelValue,
        () => {
          value.value = props.modelValue;
        }
      );

      watch(
        () => value.value,
        (to) => {
          ctx.emit('update:modelValue', to);
        }
      );

      return {
        value,
        showFeature,

        updateValue,
      };
    },
  });
</script>
