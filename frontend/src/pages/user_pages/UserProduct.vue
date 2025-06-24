<template>
  <div class="table-container">
    <table v-if="!isLoading && items.length">
      <thead>
        <tr>
          <th>Id</th>
          <th>Image</th>
          <th>Title</th>
          <th>Price</th>
          <th>Category</th>
          <th>Allowance</th>
          <th>Operation</th>

        </tr>
      </thead>
      <tbody>
        <tr v-for="item in items" :key="item.id">
          <td>{{ item.id }}</td>
          <td>
            <img
              :src="`http://localhost:8081/${item.image}`"
              alt="product image"
              width="60"
              class="table-img"
            />
          </td>
          <td>{{ item.title }}</td>
          <td>${{ item.price }}</td>
          <td>{{ item.category }}</td>
          <td>{{ item.allowance }}</td>
          <td>
            <button @click="addToCart(item)">Add to Cart</button>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-else-if="isLoading">Loading...</div>
    <div v-else>No products found.</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useCartStore } from '@/stores/cart'
import productsService from '@/services/products'

const cartStore = useCartStore()
const items = ref([])
const isLoading = ref(true)
const error = ref(null)

onMounted(async () => {
  try {
    items.value = await productsService.getProductsFromBackend()
  } catch (err) {
    error.value = `Failed to load items: ${err.message}`
    console.error(err)
  } finally {
    isLoading.value = false
  }
})

function addToCart(item) {
  cartStore.addToCart(item)
}
</script>

<style scoped src="@/assets/details.css"></style>