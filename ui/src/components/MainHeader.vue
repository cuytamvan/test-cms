<template>
  <div class="shadow-lg shadow-gray-100 mb-8 relative z-10">
    <c-section class="py-5">
      <div class="flex items-center gap-1">
        <div class="flex pr-4 items-center md:hidden">
          <c-icon tag="button" name="abstract-10" class="text-primary-500" @click="toggle" />
        </div>

        <div class="flex-1"></div>

        <div class="relative">
          <button class="shadow-sm rounded-full" @click="openProfile">
            <img src="/img/avatar.png" alt="Cuytamvan" class="w-8 h-8 rounded-full" />
          </button>

          <div
            :class="[
              'absolute pb-2 w-60 border-gray-50 shadow-lg shadow-gray-200 right-0 top-full rounded-md bg-white transition-all duration-300',
              profile.show ? '' : 'opacity-0 pointer-events-none translate-y-3',
            ]"
          >
            <div class="mb-3 px-5 py-3 flex items-center justify-center border-b">
              <div class="flex-1 pr-2">
                <p class="text-sm pt-1 text-gray-700">{{ authStore.user?.name }}</p>
                <p class="text-xs text-gray-500">
                  {{ authStore.role?.name }}
                </p>
              </div>

              <div class="w-10">
                <img src="/img/avatar.png" alt="Cuytamvan" class="w-10 h-10 rounded-full" />
              </div>
            </div>

            <c-item-profile label="Logout" icon="arrow-48" @click="logout" />
          </div>
        </div>
      </div>
    </c-section>
  </div>
</template>

<script lang="ts">
  import { defineComponent, onMounted, reactive } from 'vue';
  import { useRouter } from 'vue-router';

  import { useAuthStore } from '@/stores/auth.store';

  export default defineComponent({
    emits: ['show'],
    setup(props, { emit }) {
      const authStore = useAuthStore();
      const router = useRouter();

      const search = reactive({
        show: false,
        val: '',
      });

      const lang = reactive({
        show: false,
      });

      const profile = reactive({
        show: false,
      });

      const toggle = () => {
        emit('show');
      };

      const openProfile = () => {
        profile.show = !profile.show;

        lang.show = false;
      };

      const logout = async () => {
        await authStore.logout();
        router.push({ name: 'login' });
      };

      onMounted(async () => {});

      return {
        authStore,

        search,
        lang,
        profile,

        toggle,
        openProfile,
        logout,
      };
    },
  });
</script>
