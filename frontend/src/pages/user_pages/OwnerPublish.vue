<template>
  <div class="contract-add">
    <h2>Add New Contract</h2>
    <form @submit.prevent="submitAll" class="resource-insert-form">
      <!-- 地址信息 -->
      <fieldset>
        <legend>My Address Info</legend>
        <div class="row">
          <div class="field-group">
            <label>Country:</label>
            <input v-model="user.address.country" type="text" required />
          </div>
          <div class="field-group">
            <label>State:</label>
            <input v-model="user.address.state" type="text" required />
          </div>
          <div class="field-group">
            <label>City:</label>
            <input v-model="user.address.city" type="text" required />
          </div>
        </div>
        <div class="row">
          <div class="field-group">
            <label>District:</label>
            <input v-model="user.address.district" type="text" />
          </div>
          <div class="field-group">
            <label>Street Address:</label>
            <input v-model="user.address.street_address" type="text" required />
          </div>
          <div class="field-group">
            <label>Postal Code:</label>
            <input v-model="user.address.postal_code" type="text" />
          </div>
        </div>
        <div class="row">
          <div class="field-group">
            <label>Phone Number:</label>
            <input v-model="user.address.phone_number" type="text" required />
          </div>
        </div>
      </fieldset>

      <!-- 合同信息 -->
      <fieldset>
        <legend>Contract Info</legend>
        <div class="row">
          <div class="field-group">
            <label>Title:</label>
            <input v-model="contract.title" type="text" required />
          </div>
          <div class="field-group">
            <label>Status:</label>
            <select v-model="contract.status">
              <option :value="0">Pending</option>
              <option :value="1">Publish</option>
            </select>
          </div>
            <div class="field-group">
            <label>Adoption Doc (PDF):</label>
            <input type="file" @change="e => handleUpload(e, 'adoption_doc')" accept="application/pdf" />
            <a v-if="contract.adoption_doc" :href="`http://localhost:8081/${contract.adoption_doc}`" download style="display: block; margin-top: 10px; color: blue">Download PDF</a>
          </div>
          

        </div>

        <div class="row">
          <div class="field-group">
            <label>Image:</label>
            <input type="file" @change="e => handleUpload(e, 'image')" />
            <img v-if="contract.image" :src="`http://localhost:8081/${contract.image}`" style="width: 150px; margin-top: 10px" />
          </div>

          <!-- <div class="field-group">
            <label>Application Doc (PDF):</label>
            <input type="file" @change="e => handleUpload(e, 'application_doc')" accept="application/pdf" />
            <a v-if="contract.application_doc" :href="`http://localhost:8081/${contract.application_doc}`" download style="display: block; margin-top: 10px; color: blue">Download PDF</a>
          </div> -->
        </div>

        <div class="row">
          <div class="field-group" style="flex: 3 1 0">
            <label>Description:</label>
            <textarea v-model="contract.description" rows="4" required></textarea>
          </div>
        </div>
      </fieldset>

      <button type="submit">Submit All</button>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const user = ref({
  id: null,
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

const contract = ref({
  title: '',
  image: '',
  description: '',
  adoption_doc: '',
  application_doc: '',
  status: 0,
  owner_id: null,
  adopter_id: null
})

onMounted(async () => {
  const userId = sessionStorage.getItem('userId')
  if (!userId) return alert('❌ Not logged in')

  user.value.id = parseInt(userId)
  contract.value.owner_id = parseInt(userId)

  try {
    const res = await fetch(`http://localhost:8080/admin/get/user/${userId}`)
    if (!res.ok) throw new Error('Failed to fetch user')
    user.value = await res.json()
  } catch (err) {
    console.error('❌ Init error:', err)
    alert('Failed to load user')
  }
})

const submitAll = async () => {
  try {
    const userRes = await fetch('http://localhost:8080/user/update', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(user.value)
    })

    if (!userRes.ok) throw new Error('User update failed')

    const contractRes = await fetch('http://localhost:8080/user/add/contract', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(contract.value)
    })

    if (!contractRes.ok) throw new Error('Contract insert failed')

    alert('✅ Submitted successfully')
    router.push('/user/adoptions')
  } catch (err) {
    console.error('❌ Submit error:', err)
    alert(`Submit failed: ${err.message}`)
  }
}

const handleUpload = async (e, field) => {
  const file = e.target.files[0]
  const formData = new FormData()
  formData.append('file', file)

  const res = await fetch('http://localhost:8080/upload/image', {
    method: 'POST',
    body: formData
  })

  const data = await res.json()
  if (data.imagePath) {
    contract.value[field] = data.imagePath
  } else {
    alert('❌ Upload failed')
  }
}
</script>

<style scoped src="@/assets/initial.css"></style>
<style scoped src="@/assets/insert.css"></style>

