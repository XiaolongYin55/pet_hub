<!-- Login.vue -->
<template>
  <div class="login-page">
    <h1>Login to MyCartExpress</h1>
    <form @submit.prevent="login">
      <div>
        <label for="username">Username:</label>
        <input id="username" v-model="username" type="text" required /> <br>
        <label for="Password">Password:</label>
        <input id="password" v-model="password" type="password" required />
      </div>
      <button type="submit">Login</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const username = ref('');

function login() {
  if (username.value) {
    sessionStorage.setItem('username', username.value); // Store username in session
    router.push('/user'); // Redirect to UserHome page
  }
}
</script>

<style scoped>
.login-page {
  max-width: 400px;
  margin: 0 auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 8px;
}

.login-page h1 {
  text-align: center;
}

.login-page form {
  display: flex;
  flex-direction: column;
}

.login-page form div {
  margin-bottom: 10px;
}

.login-page form button {
  margin-top: 10px;
  padding: 10px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
}

.login-page form button:hover {
  background-color: #0056b3;
}
</style>
