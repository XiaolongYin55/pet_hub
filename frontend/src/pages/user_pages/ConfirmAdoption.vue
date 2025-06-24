<template>
  <div class="user-application">
    <h2>Update Contract</h2>

    <form @submit.prevent="submitUpdate" class="resource-insert-form">
      <!-- 合同部分 -->
      <fieldset>
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
              <option :value="0">Reject</option>
              <option :value="2">Approve</option>
            </select>
          </div>
        </div>

        <!-- PDF 文件只显示下载 -->
        <div class="row">
          <div class="field-group">
            <div v-if="contract.adoption_doc">
              <a :href="`http://localhost:8081/${contract.adoption_doc}`" target="_blank" download>
                Download Adoption Doc
              </a>
            </div>
            <div v-else style="color: gray">No file available</div>
          </div>

          <div class="field-group">
            <div v-if="contract.application_doc">
              <a :href="`http://localhost:8081/${contract.application_doc}`" target="_blank" download>
                Download Application Doc
              </a>
            </div>
            <div v-else style="color: gray">No file available</div>
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
  try {
    const contractId = route.query.contractId
    if (!contractId) return alert('❌ Missing contractId')

    const res = await fetch(`http://localhost:8080/user/get/contract/${contractId}`)
    if (!res.ok) throw new Error('Failed to fetch contract')

    const data = await res.json()
    contract.value = data
  } catch (err) {
    console.error('❌ Init error:', err)
    alert('Initialization failed')
  }
})

const submitUpdate = async () => {
  try {
    const res = await fetch('http://localhost:8080/user/update/contract', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(contract.value)
    })

    if (!res.ok) {
      const err = await res.json()
      throw new Error(err.error || 'Contract update failed')
    }

    alert('✅ Contract updated successfully')
    router.push('/user/adoptions')
  } catch (err) {
    console.error('❌ Submit error:', err)
    alert(`Update failed: ${err.message}`)
  }
}
</script>

<style scoped src="@/assets/initial.css"></style>
<style scoped src="@/assets/insert.css"></style>

