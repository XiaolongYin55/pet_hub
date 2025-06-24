<template>
  <div class="payment-insert">
    <h1>Payment Page</h1>
    <form @submit.prevent="handlePayment" class="resource-insert-form">
      <div class="form-group">
        <label>Card Number</label>
        <input type="text" value="4111 1111 1111 1111" readonly />
      </div>
      <div class="form-group">
        <label>Cardholder Name</label>
        <input type="text" value="John Doe" readonly />
      </div>
      <div class="form-group">
        <label>Expiration Date</label>
        <input type="text" value="12/25" readonly />
      </div>
      <div class="form-group">
        <label>Security Code</label>
        <input type="text" value="123" readonly />
      </div>
      <button type="submit">Pay Now</button>
    </form>
    <p v-if="paymentStatus">{{ paymentStatus }}</p>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useCartStore } from '@/stores/cart';

const router = useRouter();
const cartStore = useCartStore();
const paymentStatus = ref('');

const cart = ref([]);
const userId = sessionStorage.getItem('userId');
const username = sessionStorage.getItem('username');

onMounted(() => {
  cart.value = JSON.parse(sessionStorage.getItem('cart')) || [];
});

const totalPrice = computed(() => {
  return cart.value.reduce((sum, item) => sum + item.price * item.quantity, 0).toFixed(2);
});

const handlePayment = async () => {
  if (!userId || !username || cart.value.length === 0) {
    paymentStatus.value = 'Missing user or cart info.';
    return;
  }

  try {
    // ✅ 1. 创建订单 - 使用 JSON 请求
    const orderRes = await fetch('http://localhost:8080/user/add/order', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        user_id: parseInt(userId),
        username,
        total_price: totalPrice.value
      })
    });

    const orderData = await orderRes.json();
    console.log('✅ Order created:', orderData);

    if (!orderData.order_id) throw new Error('No order_id returned');

    // ✅ 2. 添加订单项
    const orderId = orderData.order_id;
    const itemsPayload = cart.value.map(item => ({
      item_name: item.title,  // ✅ 正确字段名
      item_price: parseFloat(item.price),
      item_quantity: item.quantity,
      category: item.category,
      image: item.image
    }));

    console.log('📦 Items to be added:', itemsPayload);

    const itemsRes = await fetch(`http://localhost:8080/user/add_items/by_order/${orderId}`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(itemsPayload)
    });

    const itemsResult = await itemsRes.json();
    console.log('🧾 Items added:', itemsResult);

    // ✅ 3. 清空购物车并跳转
    cartStore.clearCart();
    paymentStatus.value = `🎉 Payment successful! Order ID: ${orderId}`;

    setTimeout(() => {
      router.push('/user/products');
    }, 2000);

  } catch (err) {
    console.error('❌ Payment error:', err);
    paymentStatus.value = 'Payment failed. Please try again.';
  }
};
</script>


<style scoped src="@/assets/payment.css"></style>
<style scoped src="@/assets/user-home.css"></style>
<style scoped src="@/assets/initial.css"></style>
<style scoped src="@/assets/insert.css"></style>
<style scoped>
.event-insert {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 20px;
}

.resource-insert-form {
  width: 100%;
  max-width: 600px;
}

.row {
  display: flex;
  justify-content: center;
  margin-bottom: 15px;
}

.field-group {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100%;
}

.field-group label {
  margin-bottom: 8px;
  font-weight: bold;
}

.field-group input,
.field-group textarea,
.field-group select {
  width: 80%;
  max-width: 400px;
  min-width: 250px;
  padding: 8px;
  border-radius: 6px;
  border: 1px solid #ccc;
  text-align: center; /* 内容也居中，可去掉 */
  box-sizing: border-box;
}

button[type="submit"] {
  margin-top: 20px;
  padding: 10px 20px;
  font-weight: bold;
  border: none;
  background-color: #3a86ff;
  color: white;
  border-radius: 6px;
  cursor: pointer;
}

button[type="submit"]:hover {
  background-color: #265dd6;
}
</style>