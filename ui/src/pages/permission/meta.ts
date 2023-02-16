import { MetaInstance } from '@/instances/ui.instance';

export const Meta: MetaInstance = {
  name: 'permission',
  title: 'Permission',
  icon: 'general-57',
  description: 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Minima, maiores.',
  permissionName: 'Permission',
  endpoint: 'api/permissions',
  columns: [
    {
      name: 'name',
      field: 'name',
      label: 'Name',
    },
  ],
  searchableColumns: ['name'],
};
