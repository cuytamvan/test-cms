import { ref } from 'vue';
import { defineStore } from 'pinia';

import { NotifInstance } from '@/instances/ui.instance';

export const useToastStore = defineStore('toast', () => {
  const data = ref<NotifInstance[]>([]);
  let id = 1;

  const notify = (options: NotifInstance) => {
    const { type, title, message, timeout } = options;
    id++;
    const notif: NotifInstance = { id, type, title, message };
    data.value.unshift(notif);

    setTimeout(() => {
      notif.id && remove(notif.id);
    }, (timeout || 3) * 1000);
  };

  const remove = (id: number) => {
    const index = data.value.findIndex((r) => r.id === id);
    if (index > -1) data.value.splice(index, 1);
  };

  return {
    data,

    notify,
    remove,
  };
});
