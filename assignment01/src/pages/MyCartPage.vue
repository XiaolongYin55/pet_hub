<template>
  <div class="product-list">
    <div v-if="cart.length === 0">Your cart is empty.</div>
    <table v-else>
      <thead>
        <tr>
          <th>Image</th>
          <th>Name</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Total</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in cart" :key="item.id">
          <td><img :src="`/images/${item.image}`" alt="item" width="50" /></td>
          <td>{{ item.name }}</td>
          <td>${{ item.price }}</td>
          <td>{{ item.quantity }}</td>
          <td>${{ (item.price * item.quantity).toFixed(2) }}</td>
          <td>
            <button @click="increase(item.id)">+</button>
            <button @click="decrease(item.id)">-</button>
          </td>
        </tr>
      </tbody>
    </table>
    <div v-if="cart.length > 0" class="total-section">
      <p><strong>Total: ${{ total.toFixed(2) }}</strong></p>
      <router-link to="/user/payment">
        <button>Proceed to Payment</button>
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';

const cart = ref([]);

function loadCart() {
  cart.value = JSON.parse(sessionStorage.getItem('cart') || '[]');
}

function saveCart() {
  sessionStorage.setItem('cart', JSON.stringify(cart.value));
}

function increase(id) {
  const item = cart.value.find(i => i.id === id);
  if (item) item.quantity++;
  saveCart();
}

function decrease(id) {
  const index = cart.value.findIndex(i => i.id === id);
  if (index !== -1) {
    if (cart.value[index].quantity > 1) {
      cart.value[index].quantity--;
    } else {
      cart.value.splice(index, 1);
    }
    saveCart();
  }
}

const total = ref(0);
watch(cart, () => {
  total.value = cart.value.reduce((sum, item) => sum + item.price * item.quantity, 0);
}, { deep: true });

onMounted(() => {
  loadCart();
});
</script>

<style scoped>
.product-list {
  padding: 20px;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  padding: 8px;
  text-align: left;
  border: 1px solid #ddd;
}

th {
  background-color: #f2f2f2;
}
</style>
