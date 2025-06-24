<template>
  <div class="body">
    <!-- 主容器 -->
    <div class="main-container">
      <!-- 顶部横幅 -->
      <header class="banner">
        <h1>Pet Adoption Hub - User Panel @ Welcome: {{ username }}</h1>
                <img :src="logo" alt="Pet Hub Logo" class="logo" />
      </header>

      <!-- 侧边栏 -->
      <aside class="side_bar">
        <ul>
          <li><router-link to="/user/products">Products</router-link></li>
          <li><router-link to="/user/adoptions">Adoption Info</router-link></li>
          <li><router-link to="/user/owner_publish">Publish Adoption</router-link></li>
          <li><router-link to="/user/my_publish">My Publish</router-link></li>
          <li><router-link to="/user/order_history">Orders</router-link></li>
          <li><router-link to="/user/news">News</router-link></li>
          <li><router-link to="/user/cart">My Cart</router-link></li>
          <li><a href="#" @click.prevent="logout">Logout</a></li>
        </ul>

        <!-- 日历占位 -->
        <div class="calendar-box">
          🐾 Calendar or Widget
        </div>
      </aside>

      <!-- 主内容区域 -->
      <main class="content">
        <router-view />
      </main>

      <!-- 遮罩层（移动端） -->
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
import { useRouter } from 'vue-router'
import logo from '@/assets/pet-hub-logo.png'; // ✅ 引入 logo 图片
const username = sessionStorage.getItem('username') || 'Guest'
const router = useRouter()

function logout() {
  sessionStorage.removeItem('username')
  sessionStorage.removeItem('userId')
  sessionStorage.removeItem('role')
  sessionStorage.removeItem('token')
  sessionStorage.removeItem('cart')
  router.push('/')
}
</script>

<style scoped src="@/assets/user-home.css"></style>


