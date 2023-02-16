import { BaseCollection } from './ui.instance';

export interface ArticleCategoryInstance {
  id: number | null;
  name: string;
  created_at?: string;
  updated_at?: string;
}

export interface ResultArticleCategory extends BaseCollection {
  items: ArticleCategoryInstance[];
}
