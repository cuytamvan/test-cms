import { RoleInstance } from '@/instances/role.instance';
import { MetaInstance } from '@/instances/ui.instance';

export const Meta: MetaInstance = {
  name: 'user',
  title: 'User',
  icon: 'communication-10',
  description: 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Minima, maiores.',
  permissionName: 'User',
  endpoint: 'api/users',
  columns: [
    {
      name: 'name',
      field: 'name',
      label: 'Name',
    },
    {
      name: 'email',
      field: 'email',
      label: 'Email',
    },
    {
      name: 'role',
      field: 'role',
      label: 'Role',
      format: (r: RoleInstance) => r?.name || '-',
    },
  ],
  searchableColumns: ['name', 'username', 'email'],
};
