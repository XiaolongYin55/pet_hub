<template>
  <div class="product-update">
    <h2>Update Product</h2>

    <form @submit.prevent="submitUpdate" class="resource-insert-form">
      <!-- 第一行：标题、价格、数量 -->
      <div class="row">
        <div class="field-group">
          <label>Title:</label>
          <input v-model="form.title" type="text" required />
        </div>

        <div class="field-group">
          <label>Price:</label>
          <input v-model="form.price" type="number" step="0.01" required />
        </div>

        <div class="field-group">
          <label>Quantity:</label>
          <input v-model="form.quantity" type="number" required />
        </div>
      </div>

      <!-- 第二行：类别、津贴、图片上传 -->
      <div class="row">
        <div class="field-group">
          <label>Category:</label>
          <select v-model="form.category" required>
            <option value="" disabled>Select a category</option>
            <option value="Food">Food</option>
            <option value="Health">Health</option>
            <option value="Accessory">Accessory</option>
          </select>
        </div>

        <div class="field-group">
          <label>Allowance %:</label>
          <input v-model="form.allowance" type="number" required />
        </div>

        <div class="field-group">
          <label>Image:</label>
          <input type="file" @change="handleFile" />
          <img
            v-if="form.image"
            :src="`http://localhost:8081/${form.image}`"
            alt="Preview"
            style="width: 150px; margin-top: 10px"
          />
        </div>
      </div>

      <!-- 第三行：描述 -->
      <div class="row">
        <div class="field-group" style="flex: 3 1 0">
          <label>Description:</label>
          <textarea v-model="form.description" rows="4" required></textarea>
        </div>
      </div>

      <button type="submit">Update</button>
    </form>
  </div>
</template>


<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

const form = ref({
  id: null,
  title: '',
  image: '',
  price: '',
  quantity: 0,
  allowance: 0,
  category: '',
  description: ''
})

onMounted(async () => {
  const productId = route.query.id
  try {
    const res = await fetch(`http://localhost:8080/admin/get/product/${productId}`)
    if (!res.ok) throw new Error('Fetch failed')
    const data = await res.json()
    form.value = data
  } catch (error) {
    console.error('Error fetching product:', error)
  }
})

const submitUpdate = async () => {
  try {
    const res = await fetch('http://localhost:8080/admin/update/product', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(form.value)
    })
    if (res.ok) {
      alert('Product updated successfully')
      router.push('/admin/product')
    } else {
      alert('Failed to update product')
    }
  } catch (error) {
    console.error('Update error:', error)
  }
}

const handleFile = async (e) => {
  const file = e.target.files[0];

  // 🗑️ 如果已有旧图片，先删除
  if (form.value.image) {
    await fetch('http://localhost:8080/delete/image', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ path: form.value.image })
    });
  }

  // ⬆️ 上传新图片
  const formData = new FormData();
  formData.append('file', file);

  const res = await fetch('http://localhost:8080/upload/image', {
    method: 'POST',
    body: formData
  });

  const data = await res.json();
  form.value.image = data.imagePath;  // eg: oss/images/xxx.jpg
};
</script>

<style scoped src="@/assets/initial.css"></style>
<style scoped src="@/assets/insert.css"></style>
