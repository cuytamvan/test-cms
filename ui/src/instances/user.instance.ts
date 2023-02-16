import { PermissionInstance } from './permission.instance';
import { RoleInstance } from './role.instance';
import { BaseCollection } from './ui.instance';

export interface UserInstance {
  id: number | null;
  name: string;
  email: string;
  image: File | null;
  image_url: string | null;
  password?: string;
  role_id: number | null;
  role?: RoleInstance;
  api_token?: string;
}

export interface ResultUser extends BaseCollection {
  items: UserInstance[];
}
