import { BaseCollection } from './ui.instance';

export interface PermissionInstance {
  id: number | null;
  name: string;
  created_at?: string;
  updated_at?: string;
}

export interface ResultPermission extends BaseCollection {
  items: PermissionInstance[];
}
