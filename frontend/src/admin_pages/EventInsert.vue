<template>
  <div class="event-insert">
    <h2>Add New Event</h2>
    <form @submit.prevent="submitInsert" class="resource-insert-form">
      <div>
        <label>Title:</label>
        <input v-model="form.title" type="text" required />
      </div>
      <div>
        <label>Description:</label>
        <textarea v-model="form.description" required></textarea>
      </div>
      <div class="field-group">
        <label>Image:</label>
        <input type="file" @change="handleFile" style="max-width: 400px;" />
        <img
          v-if="form.image"
          :src="`http://localhost:8081/${form.image}`"
          style="width: 150px; margin-top: 10px"
        />
      </div>
      <div>
        <label>Publisher:</label>
        <input v-model="form.publisher" type="text" required />
      </div>

      <button type="submit">Add Event</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const form = ref({
  title: '',
  description: '',
  image: '',
  publisher: ''
})

const submitInsert = async () => {
  try {
    const res = await fetch('http://localhost:8080/admin/add/event', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(form.value)
    })
    if (res.ok) {
      alert('✅ Event added successfully')
      router.push('/admin/events')
    } else {
      const errData = await res.json()
      alert('❌ Failed to add event: ' + (errData.message || res.statusText))
    }
  } catch (error) {
    console.error('Insert error:', error)
    alert('❌ Error adding event')
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
    if (data.imagePath) {
      form.value.image = data.imagePath
    } else {
      alert('❌ Image upload failed')
    }
  } catch (err) {
    console.error('Upload error:', err)
    alert('❌ Upload failed')
  }
}
</script>

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
