import { computed, ref } from 'vue';
import { defineStore } from 'pinia';

import { Api } from '@/services/api';

import { UserInstance } from '@/instances/user.instance';
import { Menus } from '@/services/config';
import { MenuInstance } from '@/instances/ui.instance';

export const useAuthStore = defineStore('auth', () => {
  const http = Api();

  const isLoggedin = ref<boolean>(false);
  const user = ref<UserInstance | null>(null);

  const role = computed(() => {
    return user.value ? user.value.role : null;
  });

  const permissions = computed<string[]>(() => {
    return user.value && user.value.role ? user.value.role.permissions?.map((r) => r.name) || [] : [];
  });

  const menus = computed<MenuInstance[]>(() => {
    return Menus.filter((r) => {
      if (!r.permissions || !r.permissions.length) return true;

      return r.permissions?.filter((permission: string) => permissions.value.includes(permission)).length;
    });
  });

  const setUser = (u: UserInstance | null) => {
    user.value = u;
    isLoggedin.value = !!u;
  };

  const checkUser = async (): Promise<void> => {
    const token = localStorage.getItem('token');
    if (!user.value && token) {
      const req = await http.get('api/me');
      if (req?.status === 200) {
        const data: UserInstance = req?.data;
        setUser(data);
      } else if (req?.status === 401) {
        localStorage.removeItem('token');
      }
    }
  };

  const checkPermission = (permission: string): boolean => {
    return permissions.value.includes(permission);
  };

  const logout = async () => {
    const endpoint = 'admin/logout';
    await http.post(endpoint);

    localStorage.removeItem('token');
    // localStorage.removeItem('isLoggedin');
    setUser(null);
  };

  return {
    isLoggedin,
    user,

    menus,
    role,
    permissions,

    setUser,
    checkUser,
    logout,

    checkPermission,
  };
});
