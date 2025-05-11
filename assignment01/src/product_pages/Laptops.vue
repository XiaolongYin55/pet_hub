<template>
  <div class="product-list">
    <table>
      <thead>
        <tr>
          <th>Id</th>
          <th>Image</th>
          <th>Name</th>
          <th>Price</th>
          <th>Brand</th>
          <th>FREE Delivery</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in laptops" :key="item.id">
          <td>{{ item.id }}</td>
          <td><img :src="`/images/${item.image}`" alt="item" width="60px" /></td>
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
import { useCartStore } from '@/stores/cart';
const cartStore = useCartStore();
const router = useRouter();

// 从 sessionStorage 中获取 laptops 数据
const productData = JSON.parse(sessionStorage.getItem("productData") || '{}');
const laptops = ref(productData.laptops || []);

// 添加到购物车
function addToCart(item) {
cartStore.addToCart(item);
}
</script>

<style scoped src="@/assets/user-home.css"></style>

