<template>
  <component
    :is="to ? 'router-link' : 'button'"
    :to="to"
    class="flex w-full items-center py-2 px-5 mb-1 text-sm text-gray-700 group transition-colors hover:bg-primary-50 cursor-pointer"
  >
    <c-icon :name="icon" class="transition-colors text-gray-400 group-hover:text-primary-500 pr-2" />
    <span class="transition-colors pl-2 group-hover:text-primary-500">{{ label }}</span>
  </component>
</template>

<script lang="ts">
  import { defineComponent } from 'vue';

  export default defineComponent({
    props: {
      to: {
        type: [String, Object],
        default: null,
      },
      label: {
        type: String,
        required: true,
      },
      icon: {
        type: String,
        required: true,
      },
    },
    setup() {},
  });
</script>
