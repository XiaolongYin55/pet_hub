<template>
  <div class="product-list">
    <!-- <h2>Smartphones</h2> -->
    <table>
      <thead>
        <tr>
          <th>Id</th>
          <th>Image</th>
          <th>Name</th>
          <th>Price</th>
          <th>Brand</th>
          <th>FREE Delivery</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in smartphones" :key="item.id">
          <td>{{ item.id }}</td>
          <td><img :src="`/images/${item.image}`" alt="item" width="50" /></td>
          <td>{{ item.name }}</td>
          <td>${{ item.price }}</td>
          <td>{{ item.brand }}</td>
          <td>YES</td>
          <td><button @click="addToCart(item)">Add to Cart</button></td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

// 从 sessionStorage 中获取 laptops 数据
const productData = JSON.parse(sessionStorage.getItem("productData") || '{}');
const smartphones = ref(productData.smartphones || []);

// 添加到购物车
function addToCart(item) {
  let cart = JSON.parse(sessionStorage.getItem('cart') || '[]');
  
  // 查找是否已存在相同 id 商品
  const existingItem = cart.find(cartItem => cartItem.id === item.id && cartItem.name === item.name);
  if (existingItem) {
    existingItem.quantity += 1;
  } else {
    cart.push({ ...item });
  }

  sessionStorage.setItem('cart', JSON.stringify(cart));
  alert(`${item.name} added to cart!`);
}
</script>

<style scoped src="@/assets/user-home.css"></style>