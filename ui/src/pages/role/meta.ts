import { MetaInstance } from '@/instances/ui.instance';

export const Meta: MetaInstance = {
  name: 'role',
  title: 'Role',
  icon: 'general-57',
  description: 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Minima, maiores.',
  permissionName: 'Role',
  endpoint: 'api/roles',
  columns: [
    {
      name: 'name',
      field: 'name',
      label: 'Name',
    },
  ],
  searchableColumns: ['name'],
};
