import { ArticleCategoryInstance } from './article_category.instance';
import { BaseCollection } from './ui.instance';

export interface ArticleInstance {
  id: number | null;
  title: string;
  article_category_id: number | null;
  article_category?: ArticleCategoryInstance;
  slug?: string;
  banner_url?: string;
  banner?: File | null;
  content: string;
  preview?: string;

  created_at?: string;
  updated_at?: string;
}

export interface ResultArticle extends BaseCollection {
  items: ArticleInstance[];
}
