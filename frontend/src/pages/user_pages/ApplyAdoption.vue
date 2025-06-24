<template>
  <div class="user-application">
    <h2>Update Profile & Contract</h2>

    <form @submit.prevent="submitUpdate" class="resource-insert-form">
      <!-- 地址信息 -->
      <fieldset>
        <legend>Address Info</legend>
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

      <!-- 合同部分 -->
      <fieldset style="margin-top: 30px">
        <legend>Contract Info</legend>
        <div class="contract-row">
          <div class="field-group">
            <label>Title:</label>
            <input v-model="contract.title" type="text" />
          </div>
          <div class="field-group">
            <label>Description:</label>
            <input v-model="contract.description" type="text" />
          </div>
          <div class="field-group">
            <label>Status:</label>
            <select v-model="contract.status">
              <option :value="0">Giving Up</option>
              <option :value="1">Application</option>
            </select>
          </div>
        <!-- </div>
                <div class="row">
          <div class="field-group">
            <label>Adoption Doc (PDF):</label>
            <input type="file" @change="e => handleUpload(e, 'adoption_doc')" accept="application/pdf" />
            <a v-if="contract.adoption_doc" :href="`http://localhost:8081/${contract.adoption_doc}`" download style="display: block; margin-top: 10px; color: blue">Download PDF</a>
          </div> -->

          <div class="field-group">
            <label>Application Doc (PDF):</label>
            <input type="file" @change="e => handleUpload(e, 'application_doc')" accept="application/pdf" />
            <a v-if="contract.application_doc" :href="`http://localhost:8081/${contract.application_doc}`" download style="display: block; margin-top: 10px; color: blue">Download PDF</a>
          </div>
        </div>
        
      </fieldset>

      <button type="submit">Submit Updates</button>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const form = ref({
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
  id: null,
  title: '',
  description: '',
  status: 0,
  owner_id: null,
  adopter_id: null,
  image: '',
  adoption_doc: '',
  application_doc: ''
})

const route = useRoute()
const router = useRouter()

onMounted(async () => {
  const userId = sessionStorage.getItem('userId')
  if (!userId) return alert('❌ User not logged in')

  form.value.id = parseInt(userId)

  try {
    // 获取用户数据
    const res = await fetch(`http://localhost:8080/admin/get/user/${userId}`)
    if (!res.ok) throw new Error('Failed to fetch user')
    const data = await res.json()
    form.value = data

    // 获取合同数据
    const contractId = route.query.contractId
    if (!contractId) return alert('❌ Missing contractId')

    const contractRes = await fetch(`http://localhost:8080/user/get/contract/${contractId}`)
    if (!contractRes.ok) throw new Error('Failed to fetch contract')
    const contractData = await contractRes.json()
    contract.value = contractData
  } catch (err) {
    console.error('❌ Init error:', err)
    alert('Initialization failed')
  }
})

const submitUpdate = async () => {
  try {
    // Step 1 - 更新用户信息
    const userRes = await fetch('http://localhost:8080/user/update', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(form.value)
    })

    if (!userRes.ok) {
      const err = await userRes.json()
      throw new Error(err.error || 'User update failed')
    }

    // Step 2 - 更新合同信息
    contract.value.adopter_id = form.value.id

    const contractRes = await fetch('http://localhost:8080/user/update/contract', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(contract.value)
    })

    if (!contractRes.ok) {
      const err = await contractRes.json()
      throw new Error(err.error || 'Contract update failed')
    }

    alert('✅ Both user & contract updated')
    router.push('/user/adoptions') // ✅ 跳转路径可自定义
  } catch (err) {
    console.error('❌ Submit error:', err)
    alert(`Update failed: ${err.message}`)
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




