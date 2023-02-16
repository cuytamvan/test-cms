import { defineStore } from 'pinia';
import { computed, ref } from 'vue';

import { Api } from '@/services/api';

export const useUiStore = defineStore('ui', () => {
  const http = Api();

  const loading = ref(false);
  const maintenances = ref<string[]>([]);
  const alreadyLoaded = ref<boolean>(false);

  const getMaintenances = computed(() => maintenances.value);

  const setLoading = (val: boolean) => {
    loading.value = val;
  };

  const getMaintenanceModules = async (hardRefresh: boolean = false): Promise<void> => {
    if (!alreadyLoaded.value || hardRefresh) {
      alreadyLoaded.value = true;
      const modules = (await http.get('admin/maintenances/all-modules'))?.data;
      maintenances.value = modules;
    }
  };

  return {
    loading,
    maintenances,

    getMaintenances,

    setLoading,
    getMaintenanceModules,
  };
});
