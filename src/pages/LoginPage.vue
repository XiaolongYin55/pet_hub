<template>
  <div class="login-container">
    <header class="header">
      <h1>MyCartExpress</h1>
    </header>

    <div class="login-content">
      <div class="login-box">
        <h2>Welcome Back</h2>
        <form @submit.prevent="login">
          <div class="form-group">
            <label for="email">Email</label>
            <input 
              id="email" 
              v-model="email" 
              type="email" 
              required 
              placeholder="Enter your email"
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
import axios from 'axios';

const router = useRouter();
const email = ref('');
const password = ref('');
const errorMessage = ref('');

async function login() {
  try {
    const response = await axios.post('http://localhost:8080/login', {
      email: email.value,
      password: password.value
    });

    if (response.data.success) {
      console.log("Token:", response.data.token);  // 打印 token
      sessionStorage.setItem('user', JSON.stringify(response.data.user));
      sessionStorage.setItem('token', response.data.token); // 存储 token
      console.log("Full Response:", response.data); // 打印完整响应
      console.log("Token:", response.data?.token); 
      router.push('/user');
    } else {
      errorMessage.value = 'Invalid email or password';
    }
  } catch (error) {
    console.error("Login failed:", error);
    errorMessage.value = 'Login failed, please try again';
  }
}

</script>
<style scoped src="@/assets/public-home.css"></style>