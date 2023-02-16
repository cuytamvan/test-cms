import { ArticleCategoryInstance } from '@/instances/article_category.instance';
import { MetaInstance } from '@/instances/ui.instance';
import helper from '@/services/helper';

export const Meta: MetaInstance = {
  name: 'article',
  title: 'Article',
  icon: 'files-23',
  description: 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Minima, maiores.',
  permissionName: 'Article',
  endpoint: 'api/articles',
  columns: [
    {
      name: 'title',
      field: 'title',
      label: 'Title',
    },
    {
      name: 'article_category',
      field: 'article_category',
      label: 'Category',
      format: (r: ArticleCategoryInstance) => r.name,
    },
    {
      name: 'created_at',
      field: 'created_at',
      label: 'Created At',
      format: (r: string) => helper.beautyDate(r),
    },
  ],
  searchableColumns: ['title'],
};
