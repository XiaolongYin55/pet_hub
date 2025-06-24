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
import Register from '@/pages/Register.vue';

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
import AdoptionManage from '@/admin_pages/AdoptionManage.vue';


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
    path: '/register',
    name: 'Register',
    component: Register,
  },
  {
    path: '/user',
    component: UserHome,
    children: [
      { path: '', redirect: 'products' },
      { path: 'products', name: 'UserProducts', component: UserProduct },
      { path: 'cart', name: 'MyCart', component: MyCart },
      { path: 'payment', name: 'Payment', component: Payment },
      { path: 'order_history', name: 'OrderHistory', component: OrderHistory },
      { path: 'adoptions', name: 'AdoptionInfo', component: AdoptionInfo },
      { path: 'owner_publish', name: 'OwnerPublish', component: OwnerPublish },
      { path: 'application', name: 'ApplyAdoption', component: ApplyAdoption },
      { path: 'my_publish', name: 'MyPublish', component: MyPublish },
      { path: 'confirm', name: 'ConfirmAdoption', component: ConfirmAdoption },
      { path: 'wearables', name: 'Wearables', component: Wearables },
      { path: 'news', name: 'UserNews', component: UserNews },
      { path: 'publish', name: 'UserPublish', component: UserPublish },
    ]
  },
  {
    path: '/admin',
    component: AdminHome,
    children: [
      { path: '', redirect: 'users' },
      { path: 'users', name: 'AdminUserManagement', component: UserManage },
      { path: 'update/user', name: 'AdminUserUpdate', component: UserUpdate },
      { path: 'add/user', name: 'AdminUserInsert', component: UserInsert },
      { path: 'product', name: 'AdminProduct', component: AdminProduct },
      { path: 'update/product', name: 'AdminProductUpdate', component: ProductUpdate },
      { path: 'add/product', name: 'AdminProductInsert', component: ProductInsert },
      { path: 'events', name: 'AdminEventManage', component: EventManage },
      { path: 'update/event', name: 'AdminEventUpdate', component: EventUpdate },
      { path: 'add/event', name: 'AdminEventInsert', component: EventInsert },
      { path: 'orders', name: 'AdminOrders', component: Orders },
      { path: 'adoption_manage', name: 'AdoptionManage', component: AdoptionManage },
      { path: 'wearables', name: 'AdminWearables', component: Wearables },
      { path: 'news', name: 'AdminNews', component: AdminNews },
    ]
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// ✅ 路由守卫
router.beforeEach((to, from, next) => {
  const publicPages = ['/', '/login', '/register'];
  const token = sessionStorage.getItem('token');
  const role = sessionStorage.getItem('role');

  if (publicPages.includes(to.path)) {
    return next();
  }

  if (!token) {
    alert('请先登录');
    return next('/login');
  }

  if (to.path.startsWith('/admin') && role !== 'admin') {
    alert('Only administrators can access!');
    return next('/user/products');
  }

  if (to.path.startsWith('/user') && role === 'admin') {
    alert('Admin cannot access user resources!');
    return next('/admin/users');
  }

  next();
});

export default router;

