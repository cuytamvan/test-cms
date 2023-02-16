<template>
  <component :is="tag" :type="type" v-html="icon?.svg"></component>
</template>

<script lang="ts">
  import { computed, defineComponent } from 'vue';
  import Dummy from '@/dummy.json';

  export default defineComponent({
    props: {
      tag: {
        type: String,
        default: 'span',
      },
      type: {
        type: String,
        default: null,
      },
      name: {
        type: String,
        required: true,
      },
    },
    setup(props) {
      const icons = Dummy.icons;

      const icon = computed(() => icons.find((r) => r.name === props.name));

      return {
        icon,
      };
    },
  });
</script>
