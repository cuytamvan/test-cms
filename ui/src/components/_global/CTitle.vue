<template>
  <div class="flex">
    <div v-if="icon" class="pt-2 pr-5">
      <c-icon :name="icon" class="text-primary-500" />
    </div>
    <div class="flex-1">
      <h1 class="text-2xl font-medium text-gray-700 flex items-center">{{ title }}</h1>
      <p class="text-xs text-gray-500">{{ description }}</p>
    </div>
  </div>
</template>

<script lang="ts">
  import { defineComponent } from 'vue';

  export default defineComponent({
    props: {
      title: {
        type: String,
        required: true,
      },
      icon: {
        type: String,
        default: null,
      },
      description: {
        type: String,
        default: '',
      },
    },
    setup() {},
  });
</script>
