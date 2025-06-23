<template>
  <div class="table-container">
    <table v-if="!isLoading && products.length">
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Price</th>
          <th>Image</th>
          <th>Quantity</th>
          <th>Category</th>
          <th>Allowance</th>
          <th>
            <button @click="goToAddProduct">+ Add New Product</button>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="product in products" :key="product.id">
          <td>{{ product.id }}</td>
          <td>{{ product.title }}</td>
          <td>{{ product.price }}</td>
<td>
  <img v-if="product.image" :src="`http://localhost:8081/${product.image}`" alt="user image" class="table-img" />
</td>
          <td>{{ product.quantity }}</td>
          <td>{{ product.category }}</td>
          <td>{{ product.allowance}}</td>
<td>
  <button @click="editProduct(product.id)">Edit</button>
  <button @click="deleteProduct(product.id, product.image)" style="margin-left: 8px;">Delete</button>
</td>
        </tr>
      </tbody>
    </table>
    <div v-else-if="isLoading">Loading...</div>
    <div v-else>No products found.</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const products = ref([]);
const isLoading = ref(true);
const error = ref(null);

onMounted(fetchProducts);

async function fetchProducts() {
  isLoading.value = true;
  try {
    const res = await fetch('http://localhost:8080/admin/get/products');
    if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
    products.value = await res.json();
  } catch (err) {
    error.value = `Failed to load products: ${err.message}`;
    console.error(err);
  } finally {
    isLoading.value = false;
  }
}

function editProduct(id) {
  router.push({ name: 'AdminProductUpdate', query: { id } });
}

function goToAddProduct() {
  router.push('/admin/add/product');
}

async function deleteProduct(productId, imagePath) {
  if (!confirm('Are you sure you want to delete this product?')) return;

  try {
    // 🗑️ 先删除图片
    if (imagePath) {
      await fetch('http://localhost:8080/delete/image', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ path: imagePath })
      });
    }

    // ❌ 再删除产品
    const res = await fetch(`http://localhost:8080/admin/delete/product/${productId}`, {
      method: 'DELETE'
    });

    if (res.ok) {
      alert('Product deleted successfully');
      await fetchProducts(); // Refresh list
    } else {
      alert('Failed to delete product');
    }
  } catch (err) {
    console.error('Delete error:', err);
    alert('Error deleting product');
  }
}

</script>

<style scoped src="@/assets/details.css"></style>