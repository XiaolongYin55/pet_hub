<template>
  <div class="order-history-table-container">
    <table class="order-table" border="1" width="100%">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Username</th>
          <th>Order Time</th>
          <th>Items</th>
          <th>Total Price</th>
          <th>Operations</th>
        </tr>
      </thead>
      <tbody>
        <template v-for="order in orders" :key="order.order_id">
          <tr>
            <td>{{ order.order_id }}</td>
            <td>{{ order.username }}</td>
            <td>{{ new Date(order.create_time).toLocaleString() }}</td>
            <td>
              <button @click="toggle(order.order_id)">
                {{ expandedId === order.order_id ? 'Hide Items ▲' : 'Show Items ▼' }}
              </button>
            </td>
            <td>${{ Number(order.total_price).toFixed(2) }}</td>
            <td>
              <button class="danger" @click="deleteOrder(order.order_id)">Delete</button>
            </td>
          </tr>

          <tr v-if="expandedId === order.order_id">
            <td colspan="6">
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

const orders = ref([]);
const expandedId = ref(null);

const toggle = (id) => {
  expandedId.value = expandedId.value === id ? null : id;
};

const fetchOrders = async () => {
  try {
    const res = await fetch('http://localhost:8080/admin/get/orders');
    if (!res.ok) throw new Error('Failed to fetch orders');
    const data = await res.json();
    orders.value = data;
  } catch (err) {
    console.error('❌ Load error:', err);
  }
};

const deleteOrder = async (orderId) => {
  if (!confirm(`Delete order #${orderId}?`)) return;
  try {
    const res = await fetch(`http://localhost:8080/admin/delete/order/${orderId}`, {
      method: 'DELETE'
    });
    const result = await res.json();
    console.log('🗑️ Delete result:', result);

    if (res.ok) {
      alert(`✅ Deleted order #${orderId}`);
      fetchOrders();
    } else {
      alert(`❌ Failed: ${result.message || 'Error'}`);
    }
  } catch (err) {
    console.error('❌ Deletion error:', err);
    alert('Error deleting order.');
  }
};

onMounted(fetchOrders);
</script>
<style scoped src="@/assets/details.css"></style>