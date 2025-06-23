<template>
  <div class="event-update">
    <h2>Update Event</h2>

    <form @submit.prevent="submitUpdate" class="resource-insert-form">
      <div class="row">
        <div class="field-group">
          <label>Title:</label>
          <input v-model="form.title" type="text" required />
        </div>
      </div>

      <div class="row">
        <div class="field-group">
          <label>Description:</label>
          <textarea v-model="form.description" rows="4" required></textarea>
        </div>
      </div>

      <div class="row">
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

      <div class="row">
        <div class="field-group">
          <label>Publisher:</label>
          <input v-model="form.publisher" type="text" required />
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
  description: '',
  image: '',
  publisher: ''
})

onMounted(async () => {
  const eventId = route.query.id
  try {
    const res = await fetch(`http://localhost:8080/admin/get/event/${eventId}`)
    if (!res.ok) throw new Error('Fetch failed')
    const data = await res.json()
    form.value = data
  } catch (error) {
    console.error('Error fetching event:', error)
  }
})

const submitUpdate = async () => {
  try {
    const res = await fetch('http://localhost:8080/admin/update/event', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(form.value)
    })
    if (res.ok) {
      alert('Event updated successfully')
      router.push('/admin/events')
    } else {
      alert('Failed to update event')
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