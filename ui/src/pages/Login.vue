<template>
  <div class="bg-gray-50 min-h-screen">
    <c-section class="flex flex-wrap justify-center pt-10">
      <form class="w-full md:w-5/12 py-10 px-10 bg-white rounded-md shadow-lg shadow-gray-100" @submit.prevent="submit">
        <h1 class="text-3xl text-primary-500 flex items-center mb-1">Welcome to {{ $config.app.name }}</h1>
        <p class="text-sm text-gray-500 mb-10">
          Lorem ipsum dolor, sit amet consectetur adipisicing elit. Deleniti, voluptatum.
        </p>

        <div class="mb-3">
          <label class="text-gray-600">Email</label>
          <input
            v-model="form.email"
            type="text"
            class="py-2 px-4 border w-full rounded-md transition-all focus:border-primary-200 focus:ring ring-primary-300 mt-2"
            autofocus
          />
        </div>

        <div class="mb-5">
          <label class="text-gray-600">Password</label>
          <input
            v-model="form.password"
            type="password"
            class="py-2 px-4 border w-full rounded-md transition-all focus:border-primary-200 focus:ring ring-primary-300 mt-2"
          />
        </div>

        <div class="text-right">
          <button
            class="py-2 px-4 bg-primary-50 hover:bg-primary-100 focus:bg-primary-100 focus:ring ring-primary-200 text-primary-500 rounded-md transition-all"
          >
            Login
          </button>
        </div>
      </form>
    </c-section>
  </div>
</template>

<script lang="ts">
  import { defineComponent, reactive } from 'vue';
  import { useRouter } from 'vue-router';
  import useHandler from '@/composables/handler';
  import { useAuthStore } from '@/stores/auth.store';

  export default defineComponent({
    setup() {
      const router = useRouter();
      const { http } = useHandler();
      const authStore = useAuthStore();

      const form = reactive<{ email: string; password: string }>({
        email: '',
        password: '',
      });

      const submit = async (): Promise<void> => {
        const endpoint = 'api/login';

        const res = await http.post(endpoint, form);

        if (res) {
          if (res.status === 200) {
            localStorage.setItem('token', res.data.api_token);

            authStore.setUser(res.data);
            router.push({
              name: 'home',
            });
          }
        }
      };

      return {
        form,

        submit,
      };
    },
  });
</script>
