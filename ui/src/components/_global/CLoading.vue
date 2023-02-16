<template>
  <div
    :class="[
      'absolute top-0 left-0 right-0 bottom-0 z-50',
      'flex flex-wrap items-center justify-center bg-black bg-opacity-30',
      'transition-all duration-200',
      !show ? 'opacity-0 pointer-events-none' : '',
    ]"
  >
    <span class="inline-flex items-center justify-center bg-white py-3 px-4 rounded-md text-gray-700">
      <c-icon name="arrow-86" class="text-primary-500 mr-2 animate-spin" />

      Loading...
    </span>
  </div>
</template>

<script lang="ts">
  import { defineComponent } from 'vue';

  export default defineComponent({
    props: {
      show: {
        type: Boolean,
        default: false,
      },
    },
    setup() {},
  });
</script>
