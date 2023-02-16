import { RouteRecordRaw } from 'vue-router';

const Routes: Array<RouteRecordRaw> = [
  {
    path: '/',
    component: () => import('../layouts/MainLayout.vue'),
    children: [
      {
        name: 'home',
        path: '',
        component: () => import('../pages/Home.vue'),
        meta: { auth: true },
      },
    ],
  },
  {
    path: '/permissions',
    component: () => import('../layouts/MainLayout.vue'),
    children: [
      {
        name: 'permission-index',
        path: '',
        component: () => import('../pages/permission/Index.vue'),
        meta: { auth: true },
      },
    ],
  },
  {
    path: '/roles',
    component: () => import('../layouts/MainLayout.vue'),
    children: [
      {
        name: 'role-index',
        path: '',
        component: () => import('../pages/role/Index.vue'),
        meta: { auth: true },
      },
    ],
  },
  {
    path: '/users',
    component: () => import('../layouts/MainLayout.vue'),
    children: [
      {
        name: 'user-index',
        path: '',
        component: () => import('../pages/user/Index.vue'),
        meta: { auth: true },
      },
    ],
  },
  {
    path: '/article-categories',
    component: () => import('../layouts/MainLayout.vue'),
    children: [
      {
        name: 'article-category-index',
        path: '',
        component: () => import('../pages/article-category/Index.vue'),
        meta: { auth: true },
      },
    ],
  },
  {
    path: '/articles',
    component: () => import('../layouts/MainLayout.vue'),
    children: [
      {
        name: 'article-index',
        path: '',
        component: () => import('../pages/article/Index.vue'),
        meta: { auth: true },
      },
      {
        name: 'article-detail',
        path: ':slug',
        component: () => import('../pages/article/Detail.vue'),
        props: true,
        meta: { auth: true },
      },
    ],
  },

  {
    name: 'login',
    path: '/login',
    component: () => import('../pages/Login.vue'),
    meta: { guest: true },
  },
];

export default Routes;
