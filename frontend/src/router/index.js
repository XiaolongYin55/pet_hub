import { createRouter, createWebHistory } from 'vue-router';
import PublicHome from '../pages/PublicHome.vue';
import UserHome from '../pages/UserHome.vue'
import UserNews from '@/pages/UserNews.vue';
import UserPublish from '@/pages/UserPublish.vue';
import UserProduct from '@/pages/user_pages/UserProduct.vue';
import MyCart from '@/pages/user_pages/MyCart.vue';
import Payment from '@/pages/user_pages/Payment.vue';
import OrderHistory from '@/pages/user_pages/OrderHistory.vue';
import AdoptionInfo from '@/pages/user_pages/AdoptionInfo.vue';
import OwnerPublish from '@/pages/user_pages/OwnerPublish.vue';
import ApplyAdoption from '@/pages/user_pages/ApplyAdoption.vue';
import MyPublish from '@/pages/user_pages/MyPublish.vue';
import ConfirmAdoption from '@/pages/user_pages/ConfirmAdoption.vue';

import AdminHome from '@/pages/AdminHome.vue';
import Dashboard from '../pages/Dashboard.vue';
import LoginPage from '@/pages/LoginPage.vue';
import AdminProduct from '@/admin_pages/AdminProduct.vue';
import UserManage from '@/admin_pages/UserManage.vue';
import UserUpdate from '@/admin_pages/UserUpdate.vue';
import UserInsert from '@/admin_pages/UserInsert.vue'
import ProductUpdate from '@/admin_pages/ProductUpdate.vue';
import ProductInsert from '@/admin_pages/ProductInsert.vue';
import EventManage from '@/admin_pages/EventManage.vue';
import EventUpdate from '@/admin_pages/EventUpdate.vue';
import EventInsert from '@/admin_pages/EventInsert.vue';
import Orders from '@/admin_pages/Orders.vue';



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
        redirect: 'products'  // 默认跳转到 /user/smartphones
      },
      {
        path: 'products',
        name: 'UserProducts',
        component: UserProduct,
      },
      {
        path: 'cart',
        name: 'MyCart',
        component: MyCart,
      },
      {
        path: 'payment',
        name: 'Payment',
        component: Payment,
      },
    {
      path: 'order_history',
      name: 'OrderHistory',
      component: OrderHistory,
    },
          //* -------------- ADOPTION SECTION ---------------------
      {
        path: 'adoptions',
        name: 'AdoptionInfo',
        component: AdoptionInfo,
      },
      {
        path: 'owner_publish',
        name: 'OwnerPublish',
        component: OwnerPublish,
      },
      {
        path: 'application',
        name: 'ApplyAdoption',
        component: ApplyAdoption,
      },
      {
        path: 'my_publish',
        name: 'MyPublish',
        component: MyPublish,
      },
      {
        path: 'confirm',
        name: 'ConfirmAdoption',
        component: ConfirmAdoption,
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
      //* -------------- ORDER MANAGEMENT -------------------
            {
        path: 'orders',
        name: 'AdminOrders',
        component: Orders,
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

