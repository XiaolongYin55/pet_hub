<template>
  <div class="order-history-table-container">
    <div v-if="orders.length === 0">You have no past orders.</div>

    <table v-else class="order-table" border="1" width="100%">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Placed On</th>
          <th>Items</th>
          <th>Total Price</th>
        </tr>
      </thead>
      <tbody>
        <template v-for="order in orders" :key="order.order_id">
          <tr>
            <td>{{ order.order_id }}</td>
            <td>{{ new Date(order.create_time).toLocaleString() }}</td>
            <td>
              <button @click="toggle(order.order_id)">
                {{ expandedId === order.order_id ? 'Hide Items ▲' : 'Show Items ▼' }}
              </button>
            </td>
            <td>${{ Number(order.total_price).toFixed(2) }}</td>
          </tr>

          <tr v-if="expandedId === order.order_id">
            <td colspan="4">
              <table class="item-subtable" border="1" width="100%">
                <thead>
                  <tr>
                    <th>Item Image</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Price x Quantity</th>
                    <th>Subtotal</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in order.items" :key="item.item_id">
                    <td>
                      <img
                        :src="`http://localhost:8081/${item.image}`"
                        alt="item"
                        width="80"
                        height="80"
                        @error="e => e.target.src='/placeholder.jpg'"
                      />
                    </td>
                    <td>{{ item.item_name }}</td>
                    <td>{{ item.category }}</td>
                    <td>${{ item.item_price }} x {{ item.item_quantity }}</td>
                    <td>${{ (item.item_price * item.item_quantity).toFixed(2) }}</td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
        </template>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const username = sessionStorage.getItem('username');
const orders = ref([]);
const expandedId = ref(null);

const toggle = (id) => {
  expandedId.value = expandedId.value === id ? null : id;
};

onMounted(async () => {
  if (!username) {
    alert('No username found in sessionStorage.');
    return;
  }

  try {
    const res = await fetch(`http://localhost:8080/user/get_orders/by_username/${username}`);
    if (!res.ok) throw new Error(`Failed to fetch orders: ${res.status}`);
    const data = await res.json();
    console.log('📦 Orders loaded:', data);
    orders.value = data;
  } catch (err) {
    console.error('❌ Error loading order history:', err);
  }
});
</script>

<style scoped src="@/assets/details.css"></style>
