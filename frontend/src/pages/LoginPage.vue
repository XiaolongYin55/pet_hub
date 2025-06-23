<!-- Login.vue -->
<template>
  <div class="login-container">
    <header class="header">
      <h1>Pet Adoption Hub</h1>
    </header>

    <div class="login-content">
      <div class="login-box">
        <h2>Welcome</h2>
        <form @submit.prevent="login">
          <div class="form-group">
            <label for="username">Username</label>
            <input 
              id="username" 
              v-model="username" 
              type="text" 
              required 
              placeholder="Enter your username"
            />
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input 
              id="password" 
              v-model="password" 
              type="password" 
              required 
              placeholder="Enter your password"
            />
          </div>
          <button type="submit" class="login-button">Login</button>
        </form>
      </div>
    </div>

    <footer class="footer">
      <p>&copy; 2025 MyCartExpress. All rights reserved.</p>
    </footer>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const username = ref('');
const password = ref('');
//! ----------- version 01 ----------------------------
async function login() {
  if (!username.value || !password.value) {
    alert('请输入用户名和密码');
    return;
  }

  try {
    const response = await fetch('http://localhost:8080/auth/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        username: username.value,
        password: password.value
      })
    });

    const result = await response.json();

    if (result.success) {
      const user = result.user;
      
      // 保存需要的字段到 sessionStorage
      sessionStorage.setItem('username', user.username);
      sessionStorage.setItem('userId', user.id);
      sessionStorage.setItem('role', user.role);

      // storage token
  if (result.token) {
    sessionStorage.setItem('token', result.token);
  }

      // 根据 role 跳转
      if (user.role === 'admin') {
        router.push('/admin/smartphones');
      } else {
        router.push('/user/laptops');
      }
    } else {
      alert(result.message || '登录失败，请检查用户名或密码');
    }
  } catch (error) {
    console.error('Login error:', error);
    alert('服务器错误，请稍后再试');
  }
}
</script>


<style scoped src="@/assets/public-home.css"></style>