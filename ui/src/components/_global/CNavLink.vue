<template>
  <router-link :to="to" v-slot="{ navigate, href, isActive, isExactActive }" custom>
    <a :href="href" class="flex items-center px-10 py-2 relative text-gray-600" @click="navigate">
      <span
        :class="[
          'absolute block top-0 left-0 bottom-0 w-1 bg-primary-400 rounded-r-md transition-transform',

          !exact && isActive && 'translate-x-0',
          exact && isExactActive && 'translate-x-0',

          !(!exact && isActive) && !(exact && isExactActive) && '-translate-x-1',
        ]"
      ></span>

      <span
        :class="[
          'absolute block top-0 right-5 bottom-0 bg-primary-100 rounded-md transition-all duration-500',

          !exact && isActive && 'opacity-100 left-5',
          exact && isExactActive && 'opacity-100 left-5',

          !(!exact && isActive) && !(exact && isExactActive) && 'opacity-0 left-full',
        ]"
      ></span>

      <span
        :class="[
          'relative z-10',
          !exact && isActive && 'text-primary-600',
          exact && isExactActive && 'text-primary-600',
        ]"
      >
        <slot name="icon" />
      </span>

      <span
        :class="[
          'flex-1 pl-3 text-sm relative z-10',

          !exact && isActive && 'text-primary-600',
          exact && isExactActive && 'text-primary-600',
        ]"
      >
        <slot />
      </span>
    </a>
  </router-link>
</template>

<script lang="ts">
  import { defineComponent } from 'vue';

  export default defineComponent({
    props: {
      to: {
        type: [String, Object],
        required: true,
      },
      active: {
        type: Boolean,
        default: false,
      },
      exact: {
        type: Boolean,
        default: false,
      },
    },
    setup() {},
  });
</script>
