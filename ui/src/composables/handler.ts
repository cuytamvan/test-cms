import { reactive } from 'vue';
import { useRoute, useRouter } from 'vue-router';

import { ColumnInstance, DefResult, MetaInstance, PaginationInstance } from '@/instances/ui.instance';

import helper from '@/services/helper';
import config from '@/services/config';
import { Api } from '@/services/api';

import { useAuthStore } from '@/stores/auth.store';
import { useUiStore } from '@/stores/ui.store';
import { useToastStore } from '@/stores/toast.store';

export interface Table {
  data: any[];
  columns: ColumnInstance[];
  pagination: PaginationInstance;
  loading: boolean;
  search: string;
  expands: number[];
}

export const useHandler = (meta?: MetaInstance) => {
  const route = useRoute();
  const router = useRouter();

  const authStore = useAuthStore();
  const { setLoading } = useUiStore();
  const { notify } = useToastStore();

  const http = Api((status) => {
    if (status === 401 && route.name !== 'login') {
      authStore.setUser(null);
      localStorage.removeItem('isLoggedin');
      router.push({ name: 'login' });
    }
  });

  const defPagination: PaginationInstance = {
    count: 0,
    lastPage: 0,
    limit: 10,
    page: 1,
    total: 0,
  };

  const table = reactive<Table>({
    data: [],
    columns: meta ? [...meta.columns] : [],
    pagination: { ...defPagination },
    loading: false,
    search: '',
    expands: [],
  });

  const checkPermission = (permission: string) => authStore.checkPermission(`${permission} ${meta?.permissionName}`);

  const getList = async (append = false): Promise<void> => {
    if (meta) {
      let uri = `${meta.endpoint}?_page=${table.pagination.page}&_limit=${table.pagination.limit}`;
      if (meta.searchableColumns.length && table.search?.length) {
        uri += `&_like=${meta.searchableColumns.join(',')}:${encodeURIComponent(table.search)}`;
      }

      table.loading = true;
      const res: any = (await http.get(uri))?.data;
      table.loading = false;

      table.data = append ? [...table.data, ...res.items] : res.items;
      table.pagination.lastPage = res.meta.last_page;
      table.pagination.limit = res.meta.item_per_page;
      table.pagination.page = res.meta.current_page;
      table.pagination.total = res.meta.total;
      table.pagination.count = res.meta.count;
    }
  };

  const toggleExpand = (index: number) => {
    if (table.expands) {
      if (table.expands.includes(index)) {
        table.expands = table.expands.filter((r) => r !== index);
      } else {
        table.expands = [...table.expands, index];
      }
    }
  };

  const getData = async (id: string | number): Promise<any> => {
    if (meta) {
      setLoading(true);
      const req = await http.get(`${meta.endpoint}/${id}`);
      setLoading(false);

      return req?.data;
    }
    return null;
  };

  const handleNotif = (req: DefResult) => {
    if (req?.status === 200) notify({ type: 'success', title: meta?.title, message: req?.message });
    else notify({ type: 'warning', title: meta?.title, message: req?.message });
  };

  const store = async (model: any) => {
    if (meta) {
      setLoading(true);
      const req = await http.post(meta.endpoint, model);
      setLoading(false);

      req && handleNotif(req);

      return req;
    }
    return null;
  };

  const update = async (id: string | number, model: any) => {
    if (meta) {
      setLoading(true);
      const req = await http.put(`${meta.endpoint}/${id}`, model);
      setLoading(false);

      req && handleNotif(req);
      return req;
    }
    return null;
  };

  const updatePost = async (id: string | number, model: any) => {
    if (meta) {
      setLoading(true);
      const req = await http.post(`${meta.endpoint}/${id}/update`, model);
      setLoading(false);

      req && handleNotif(req);
      return req;
    }
    return null;
  };

  const remove = async (id: string | number): Promise<DefResult | null> => {
    if (meta) {
      setLoading(true);
      const req = await http.del(`${meta.endpoint}/${id}`);
      setLoading(false);

      req && handleNotif(req);
      return req;
    }

    return null;
  };

  return {
    meta,
    helper,
    config,
    http,
    defPagination,

    table,

    setLoading,
    checkPermission,
    getList,
    toggleExpand,
    getData,
    store,
    update,
    updatePost,
    remove,
  };
};

export default useHandler;
