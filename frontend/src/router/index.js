import { createRouter, createWebHistory } from 'vue-router';
import PublicHome from '../pages/PublicHome.vue';
import UserHome from '../pages/UserHome.vue'
import UserNews from '@/pages/UserNews.vue';
import UserPublish from '@/pages/UserPublish.vue';

import AdminHome from '@/pages/AdminHome.vue';
import Dashboard from '../pages/Dashboard.vue';
import MyCartPage from '../pages/MyCartPage.vue';
import PaymentPage from '@/pages/PaymentPage.vue';
import LoginPage from '@/pages/LoginPage.vue';
import Laptops from '@/admin_pages/Laptops.vue';
import AdminProduct from '@/admin_pages/AdminProduct.vue';

import UserManage from '@/admin_pages/UserManage.vue';
import UserUpdate from '@/admin_pages/UserUpdate.vue';
import UserInsert from '@/admin_pages/UserInsert.vue'
import ProductUpdate from '@/admin_pages/ProductUpdate.vue';
import ProductInsert from '@/admin_pages/ProductInsert.vue';
import EventManage from '@/admin_pages/EventManage.vue';
import EventUpdate from '@/admin_pages/EventUpdate.vue';
import EventInsert from '@/admin_pages/EventInsert.vue';



import Wearables from '@/admin_pages/Wearables.vue';
import Accessories from '@/admin_pages/Accessories.vue';
import AdminNews from '@/pages/AdminNews.vue';
const routes = [
  {
    path: '/',
    name: 'PublicHome',
    component: PublicHome,
  },
  {
    path: '/login',
    name: 'Login',
    component: LoginPage,
  },
  {
    path: '/user',
    component: UserHome,
    children: [
      {
        path: '',
        redirect: 'laptops'  // 默认跳转到 /user/smartphones
      },
      {
        path: 'cart',
        name: 'MyCart',
        component: MyCartPage,
      },
      {
        path: 'smartphones',
        name: 'Smartphones',
        component: UserManage,
      },
      {
        path: 'laptops',
        name: 'Laptops',
        component: Laptops,
      },
      {
        path: 'accessories',
        name: 'Accessories',
        component: Accessories,
      },

      {
        path: 'wearables',
        name: 'Wearables',
        component: Wearables,
      },
      {
        path: 'news',
        name: 'UserNews',
        component: UserNews,
      },
      {
        path: 'publish',
        name: 'UserPublish',
        component: UserPublish,
      },
      {
        path: 'payment',
        name: 'PaymentPage',
        component: PaymentPage,
      },
    ]
  },
  {
    path: '/admin',
    component: AdminHome,
    children: [
      {
        path: '',
        redirect: 'users'
      },
      {
        path: 'cart',
        name: 'AdminMyCart',
        component: MyCartPage,
      },
      //* -------------- USER MANAGEMENT ---------------------
      {
        path: 'users',
        name: 'AdminUserManagement',
        component: UserManage,
      },
      {
        path: 'update/user',
        name: 'AdminUserUpdate',
        component: UserUpdate,
      },
      {
        path: 'add/user',
        name: 'AdminUserInsert',
        component: UserInsert,
      },
      //* -------------- PRODUCT MANAGEMENT -------------------
      {
        path: 'product',
        name: 'AdminProduct',
        component: AdminProduct,
      },
      {
        path: 'update/product',
        name: 'AdminProductUpdate',
        component: ProductUpdate,
      },
      {
        path: 'add/product',
        name: 'AdminProductInsert',
        component: ProductInsert,
      },
      //* -------------- EVENTS MANAGEMENT -------------------
      {
        path: 'events',
        name: 'AdminEventManage',
        component: EventManage,
      },
      {
        path: '/update/event',
        name: 'AdminEventUpdate',
        component: EventUpdate,
      },
      {
        path: 'add/event',
        name: 'AdminEventInsert',
        component: EventInsert,
      },
      {
        path: 'laptops',
        name: 'AdminLaptops',
        component: Laptops,
      },
      {
        path: 'accessories',
        name: 'AdminAccessories',
        component: Accessories,
      },
      {
        path: 'wearables',
        name: 'AdminWearables',
        component: Wearables,
      },
      {
        path: 'news',
        name: 'AdminNews',
        component: AdminNews,
      },
    ]
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});
export default router;

