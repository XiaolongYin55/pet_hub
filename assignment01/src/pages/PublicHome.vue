
<template>
  <div class="public-home">
    <header class="header">
      <h1>Welcome to MyCartExpress</h1>
    </header>

    <div class="content">
      <p>Your one-stop shop for tech gadgets! Browse our collection of smartphones, laptops, accessories, and wearables.</p>
      <button @click="login" class="login-button">Login</button>
    </div>

    <footer class="footer">
      <p>&copy; 2025 MyCartExpress. All rights reserved.</p>
    </footer>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router';

const router = useRouter();
function login() {
  sessionStorage.setItem('username', 'john_doe');  // 模拟登录
  router.push('/login');
}
</script>

<style scoped src="./public-home.css"></style>