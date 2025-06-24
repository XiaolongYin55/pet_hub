<template>
  <div class="body">
    <!-- 主容器 -->
    <div class="main-container">
      <!-- 顶部横幅 -->
      <header class="banner">
        <h1>Pet Adoption Hub @ Administrator Panel</h1>
                <img :src="logo" alt="Pet Hub Logo" class="logo" />
      </header>

      <!-- 侧边栏 -->
      <aside class="side_bar">
        <ul>
          <!-- Users Manage -->
          <li><router-link to="/admin/users">Users</router-link></li>
          <!-- Products -->
          <li><router-link to="/admin/product">Products</router-link></li>
          <!-- Events -->
          <li><router-link to="/admin/events">Events</router-link></li>
          <!-- Orders -->
          <li><router-link to="/admin/orders">Orders</router-link></li>
          <!-- Logout -->
          <li><a href="#" @click.prevent="logout">Logout</a></li>
        </ul>

        <!-- 日历占位 -->
        <div class="calendar-box">
          📅 Calendar here
        </div>
      </aside>

      <!-- 主内容区域 -->
      <main class="content">
        <router-view />
      </main>

      <!-- 遮罩层，仅移动端需要时显示 -->
      <div class="overlay"></div>
    </div>

    <!-- 页脚 -->
    <footer class="footer">
      <p>
        &copy; {{ new Date().getFullYear() }} Pet Adoption Hub. All rights reserved. |
        Loving Homes for All Pets 🐶🐱
      </p>
    </footer>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
import logo from '@/assets/pet-hub-logo.png'; // ✅ 引入 logo 图片
const username = sessionStorage.getItem('username') || 'Guest';
const router = useRouter();

function logout() {
  sessionStorage.removeItem('username');
  sessionStorage.removeItem('userId');
  sessionStorage.removeItem('role');
  sessionStorage.removeItem('token');
  router.push('/');
}
</script>


<style scoped src="@/assets/user-home.css"></style>