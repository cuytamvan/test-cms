<template>
  <c-loading :show="loading" />
  <router-view></router-view>
</template>

<script lang="ts">
  import { computed, defineComponent } from 'vue';
  import { useUiStore } from './stores/ui.store';

  export default defineComponent({
    setup() {
      const uiStore = useUiStore();

      const loading = computed(() => uiStore.loading);

      return {
        loading,
      };
    },
  });
</script>
