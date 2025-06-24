<template>
  <div id="app">
    <form @submit.prevent="submitInsert" class="resource-insert-form">
      <!-- 每一行用 div.row 包起来 -->
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
          <label>Image:</label>
          <input type="file" @change="handleFile" />
          <img v-if="form.image" :src="`http://localhost:8081/${form.image}`" style="width: 150px; margin-top: 10px" />
        </div>
      </div>

      <div class="row">
        <div class="field-group">
          <label>Role:</label>
          <select v-model="form.role">
            <option value="admin">admin</option>
            <option value="user">user</option>
          </select>
        </div>
      </div>

      <!-- Address -->
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

      <button class="center-button" type="submit">Create User</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const form = ref({
  name: '',
  username: '',
  password: '',
  image: '',
  phone_no: '',
  email: '',
  role: 'user',
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

const submitInsert = async () => {
  try {
    const res = await fetch(`http://localhost:8080/admin/add/user`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(form.value)
    })
    if (res.ok) {
      alert('User inserted successfully')
      router.push('/admin/users')
    } else {
      alert('Failed to insert user')
    }
  } catch (error) {
    console.error('Insert error:', error)
  }
}

const handleFile = async (e) => {
  const file = e.target.files[0];
  const formData = new FormData();
  formData.append('file', file);

  const res = await fetch('http://localhost:8080/upload/image', {
    method: 'POST',
    body: formData
  });

  const data = await res.json();
  console.log(data.imagePath); // oss/images/xxx.jpg

  // ✅ 将图片路径写入 form.image
  form.value.image = data.imagePath;
};
</script>

<style scoped src="@/assets/initial.css"></style>
<style scoped src="@/assets/insert.css"></style>
