import { App } from 'vue';
import { Router } from 'vue-router';

import { useAuthStore } from '@/stores/auth.store';

const boot = (app: App, router: Router): void => {
  router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();

    await authStore.checkUser();

    if (to.meta.auth && !authStore.isLoggedin) next('/login');
    else if (to.meta.guest && authStore.isLoggedin) next('/');
    else next();
  });
};

export default boot;
