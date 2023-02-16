import { MenuInstance } from '@/instances/ui.instance';

interface AppConfig {
  name: string;
  url: string;
}

const Config = {
  app: {
    name: import.meta.env.VITE_APP_NAME || 'Test CMS',
    url: import.meta.env.VITE_APP_URL || 'http://localhost:3000',
  } as AppConfig,
  apiUrl: import.meta.env.VITE_API_URL || 'http://localhost:5000',
};

export const Menus: MenuInstance[] = [
  {
    type: 'menu',
    title: 'Explore',
    icon: 'abstract-1',
    routeName: 'home',
  },
  {
    type: 'header',
    title: 'Master data',
    permissions: ['Read Article Category', 'Read Article'],
  },
  {
    type: 'menu',
    title: 'Article Category',
    icon: 'text-6',
    routeName: 'article-category-index',
    permissions: ['Read Article Category'],
  },
  {
    type: 'menu',
    title: 'Article',
    icon: 'files-23',
    routeName: 'article-index',
    permissions: ['Read Article'],
  },
  {
    type: 'header',
    title: 'User Management',
    permissions: ['Read Permission', 'Read Role', 'Read User'],
  },
  {
    type: 'menu',
    title: 'Permission',
    icon: 'general-57',
    routeName: 'permission-index',
    permissions: ['Read Permission'],
  },
  {
    type: 'menu',
    title: 'Role',
    icon: 'general-57',
    routeName: 'role-index',
    permissions: ['Read Role'],
  },
  {
    type: 'menu',
    title: 'User',
    icon: 'communication-10',
    routeName: 'user-index',
    permissions: ['Read User'],
  },
];

export default Config;
