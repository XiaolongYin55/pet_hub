<template>
  <div class="user-update">
    <h2>Update User</h2>

    <form @submit.prevent="submitUpdate" class="resource-insert-form">
      <!-- 第一行：基础信息 -->
      <div class="row">
        <div class="field-group">
          <label>Name:</label>
          <input v-model="form.name" type="text" required />
        </div>

        <div class="field-group">
          <label>Username:</label>
          <input v-model="form.username" type="text" required />
        </div>

        <div class="field-group">
          <label>Password:</label>
          <input v-model="form.password" type="password" required />
        </div>
      </div>

      <!-- 第二行：邮箱、电话、角色 -->
      <div class="row">
        <div class="field-group">
          <label>Email:</label>
          <input v-model="form.email" type="email" required />
        </div>

        <div class="field-group">
          <label>Phone No:</label>
          <input v-model="form.phone_no" type="text" />
        </div>

        <div class="field-group">
          <label>Role:</label>
          <select v-model="form.role">
            <option value="admin">Admin</option>
            <option value="user">User</option>
          </select>
        </div>
      </div>

      <!-- 图片上传：独占一行 -->
      <div class="row">
        <div class="field-group" style="width: 100%">
          <label>Image:</label>
          <input type="file" @change="handleFile" />
          <img
            v-if="form.image"
            :src="`http://localhost:8081/${form.image}`"
            style="width: 150px; margin-top: 10px"
          />
        </div>
      </div>

      <!-- 地址信息 -->
      <fieldset>
        <legend>Address</legend>

        <div class="address-row">
          <div class="field-group">
            <label>Country:</label>
            <input v-model="form.address.country" type="text" />
          </div>
          <div class="field-group">
            <label>State:</label>
            <input v-model="form.address.state" type="text" />
          </div>
          <div class="field-group">
            <label>City:</label>
            <input v-model="form.address.city" type="text" />
          </div>
        </div>

        <div class="address-row">
          <div class="field-group">
            <label>District:</label>
            <input v-model="form.address.district" type="text" />
          </div>
          <div class="field-group">
            <label>Street Address:</label>
            <input v-model="form.address.street_address" type="text" />
          </div>
          <div class="field-group">
            <label>Postal Code:</label>
            <input v-model="form.address.postal_code" type="text" />
          </div>
        </div>

        <div class="address-row">
          <div class="field-group">
            <label>Phone Number:</label>
            <input v-model="form.address.phone_number" type="text" />
          </div>
        </div>
      </fieldset>

      <button type="submit">Update</button>
    </form>
  </div>
</template>


<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'  // ✅ 补上 useRouter

const route = useRoute()
const router = useRouter()   // ✅ 先定义 route 再 router

const form = ref({
  id: null,
  name: '',
  username: '',
  password: '',
  image: '',
  phone_no: '',
  email: '',
  role: '',
  address: {
    country: '',
    state: '',
    city: '',
    district: '',
    street_address: '',
    postal_code: '',
    phone_number: ''
  }
})

onMounted(async () => {
  const userId = route.query.id || 2
  try {
    const res = await fetch(`http://localhost:8080/admin/get/user/${userId}`)
    if (!res.ok) throw new Error('Fetch failed')
    const data = await res.json()
    form.value = data
  } catch (error) {
    console.error('Error fetching user:', error)
  }
})

const submitUpdate = async () => {
  try {
    const res = await fetch(`http://localhost:8080/admin/update/user`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(form.value)
    })
    if (res.ok) {
      alert('User updated successfully')
      router.push('/admin/users'); 
    } else {
      alert('Failed to update user')
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
