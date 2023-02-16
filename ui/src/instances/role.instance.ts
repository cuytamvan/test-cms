import { PermissionInstance } from './permission.instance';
import { BaseCollection } from './ui.instance';

export interface RoleInstance {
  id: number | null;
  name: string;
  created_at?: string;
  updated_at?: string;
  permissions?: PermissionInstance[];
  count_permissions?: number;
}

export interface ResultRole extends BaseCollection {
  items: RoleInstance[];
}
