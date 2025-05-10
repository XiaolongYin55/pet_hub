<template>
  <div class="payment-container">
    <h1>Payment Page</h1>
    <form @submit.prevent="handlePayment">
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
            <button @click="submit">Pay Now</button>
    </form>
    <p v-if="paymentStatus">{{ paymentStatus }}</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const router = useRouter();
const route = useRoute();

const paymentStatus = ref('');
const cartList = ref([]);
const user = ref(null);

// 获取从前一页传来的数据
onMounted(() => {
  cartList.value = route.params.cartList || [];
  user.value = route.params.user || null;
});
  const url = 'http://localhost:8080/order/payment';

  function submit() {
  sessionStorage.removeItem("cart");
  router.push('/user');
}


</script>



<style scoped>
.payment-container {
  padding: 20px;
  max-width: 500px;
  margin: auto;
}

.form-group {
  margin-bottom: 15px;
}

label {
  display: block;
  font-weight: bold;
}

input {
  width: 100%;
  padding: 8px;
  margin-top: 5px;
  box-sizing: border-box;
}

.pay-button {
  padding: 10px 20px;
  background-color: #28a745;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
</style>