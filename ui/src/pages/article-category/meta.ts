import { MetaInstance } from '@/instances/ui.instance';

export const Meta: MetaInstance = {
  name: 'article-category',
  title: 'Article Category',
  icon: 'text-6',
  description: 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Minima, maiores.',
  permissionName: 'Article Category',
  endpoint: 'api/article-categories',
  columns: [
    {
      name: 'name',
      field: 'name',
      label: 'Name',
    },
  ],
  searchableColumns: ['name'],
};
