<template>
  <toast />
  <div
    :class="[
      'fixed z-20 flex flex-col top-0 bottom-0 left-0 rounded-r-2xl w-72 bg-gray-50',
      'transition-all duration-300',
      showSide ? 'translate-x-0' : '-translate-x-full',
      'md:translate-x-0',
    ]"
  >
    <button @click="showSide = false" class="absolute top-2 right-2 md:hidden">
      <c-icon name="abstract-47" class="text-primary-500" />
    </button>

    <div class="p-10 flex items-center">
      <div>
        <div
          class="w-12 h-12 p-0.5 bg-gradient-to-br from-blue-200 to-primary-200 rounded-full shadow-xl shadow-primary-200 relative"
        >
          <img src="/img/avatar.png" alt="Photo profile" class="rounded-full" />

          <span class="absolute bottom-0.5 right-0.5 w-3 h-3 bg-green-500 block rounded-full"></span>
        </div>
      </div>

      <div class="flex-1 pl-5 text-gray-700">
        <span class="text-lg block font-medium">{{ authStore.user?.name }}</span>
        <span class="inline-block text-xs">
          {{ authStore.role?.name }}
        </span>
      </div>
    </div>

    <div class="flex-1 overflow-auto custom-scroll">
      <template v-for="(menu, iMenu) in menus" :key="`menu-${iMenu}`">
        <c-nav-link v-if="menu.type === 'menu'" :to="menu.routeName && { name: menu.routeName }" exact>
          <template #icon>
            <c-icon :name="menu.icon" />
          </template>
          {{ menu.title }}
        </c-nav-link>
        <span v-else-if="menu.type === 'header'" class="flex text-xs pl-10 py-2 text-orange-500">
          {{ menu.title }}
        </span>
      </template>
    </div>
  </div>

  <div class="transition-all duration-300 pl-0 md:pl-72">
    <main-header @show="showSide = true" />

    <router-view />
  </div>
</template>

<script lang="ts">
  import { computed, defineComponent, ref } from 'vue';

  import MainHeader from '@/components/MainHeader.vue';
  import { useAuthStore } from '@/stores/auth.store';
  import Toast from '@/components/Toast.vue';

  export default defineComponent({
    components: {
      MainHeader,
      Toast,
    },
    setup() {
      const authStore = useAuthStore();
      const showSide = ref<boolean>(false);

      const menus = computed(() => authStore.menus);

      return {
        authStore,
        showSide,

        menus,
      };
    },
  });
</script>
