export interface ColumnInstance {
  name: string;
  field: string;
  label: string;
  format?: (val: any, row?: object) => string;
}

export interface PaginationInstance {
  page: number;
  limit: number;
  total: number;
  count: number;
  lastPage: number;
}

export interface DataInstance {
  [key: string]: any;
}

export interface DefResult {
  status: number;
  message: string;
  data?: any;
}

export interface MenuInstance {
  type: 'menu' | 'header';
  title: string;
  icon?: string;
  routeName?: string;
  children?: MenuInstance[];
  permissions?: string[];
}

export interface BaseCollection {
  links: {
    first_page: string | null;
    last_page: string | null;
    prev: string | null;
    next: string | null;
  };
  meta: {
    current_page: number;
    item_per_page: number;
    last_page: number;
    total: number;
    count: number;
  };
}

export interface BaseTable {
  columns: ColumnInstance[];
  pagination: PaginationInstance;
  loading: boolean;
  search?: string;
  expands?: number[];
}

export interface DateFormat {
  y: number;
  m: number;
  d: number;
  day: number;
  h?: number;
  i?: number;
  s?: number;
}

export interface NotifInstance {
  id?: number;
  type: 'success' | 'error' | 'warning';
  title?: string;
  message?: string;
  timeout?: number;
}

export interface ListDate {
  day: number;
  dayOfWeek: number;
  today: boolean;
  thisMonth: boolean;
  date: string;
}

export interface FormatData {
  name: string;
  value: any;
}

export interface ConfigFormatData {
  data: any;
  name?: string;
  ignore: string[];
}

export interface MetaInstance {
  name: string;
  title: string;
  icon: string;
  description: string | null;
  permissionName: string;
  endpoint: string;
  columns: ColumnInstance[];
  searchableColumns: string[];
}
