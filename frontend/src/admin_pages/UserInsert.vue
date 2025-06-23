<template>
  <div class="product-insert">
    <h2>Add New Product</h2>

    <form @submit.prevent="submitInsert" class="resource-insert-form">
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
          <input v-model="form.allowance" type="number" />
        </div>

        <div class="field-group">
          <label>Image:</label>
          <input type="file" @change="handleFile" />
          <img
            v-if="form.image"
            :src="`http://localhost:8081/${form.image}`"
            style="width: 150px; margin-top: 10px"
            alt="Preview"
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

      <button type="submit">Add Product</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const form = ref({
  title: '',
  image: '',
  price: '',
  quantity: 0,
  allowance: 0,
  category: '',
  description: ''
})

const submitInsert = async () => {
  try {
    const res = await fetch('http://localhost:8080/admin/add/product', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(form.value)
    })

    if (res.ok) {
      alert('Product added successfully')
      router.push('/admin/product')
    } else {
      alert('Failed to add product')
    }
  } catch (error) {
    console.error('Insert error:', error)
    alert('Error adding product')
  }
}

const handleFile = async (e) => {
  const file = e.target.files[0]
  if (!file) return

  const formData = new FormData()
  formData.append('file', file)

  try {
    const res = await fetch('http://localhost:8080/upload/image', {
      method: 'POST',
      body: formData
    })
    const data = await res.json()

    console.log('Image uploaded to:', data.imagePath)
    form.value.image = data.imagePath
  } catch (error) {
    console.error('Image upload failed:', error)
  }
}
</script>

<style scoped src="@/assets/initial.css"></style>
<style scoped src="@/assets/insert.css"></style>

