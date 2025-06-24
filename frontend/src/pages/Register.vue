<template>
  <div class="login-container">
    <header class="header">
      <h1>Pet Adoption Hub</h1>
    </header>

    <div class="login-content">
      <div class="login-box">
        <h2>Register</h2>
        <form @submit.prevent="register">
          <div class="form-group">
            <label for="name">Full Name</label>
            <input id="name" v-model="form.name" type="text" placeholder="Enter full name" required />
          </div>

          <div class="form-group">
            <label for="username">Username</label>
            <input id="username" v-model="form.username" type="text" placeholder="Enter username" required />
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <input id="password" v-model="form.password" type="password" placeholder="Enter password" required />
          </div>

          <div class="form-group">
            <label for="email">Email</label>
            <input id="email" v-model="form.email" type="email" placeholder="Enter email" required />
          </div>
          <button type="submit" class="login-button">Register</button>
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
const form = ref({
  name: '',
  username: '',
  password: '',
  image: null,
  phone_no: null,
  email: '',
  role: 'user',
  address: {
    country: null,
    state: null,
    city: null,
    district: null,
    street_address: null,
    postal_code: null,
    phone_number: null
  }
});

async function register() {
  if (!form.value.username || !form.value.password || !form.value.name || !form.value.email) {
    alert('Please fill in required fields');
    return;
  }

  try {
    const response = await fetch('http://localhost:8080/register', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(form.value)
    });

    const result = await response.json();

    if (response.ok) {
      alert(result.message || 'Registered successfully!');
      router.push('/login');
    } else {
      alert(result.error || 'Failed to register');
    }
  } catch (err) {
    console.error('Registration error:', err);
    alert('Server error, please try again later.');
  }
}
</script>

<style scoped src="@/assets/public-home.css"></style>