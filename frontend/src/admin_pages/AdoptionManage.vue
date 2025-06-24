<template>
  <div class="table-container">
    <table v-if="!isLoading && contracts.length">
      <thead>
        <tr>
          <th>ID</th>
          <th>Image</th>
          <th>Title</th>
          <th>Status</th>
          <th>Owner</th>
          <th>Adopter</th>
          <th>Description</th>
          <th>Created</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="contract in contracts" :key="contract.id">
          <td>{{ contract.id }}</td>
          <td>
            <img :src="`http://localhost:8081/${contract.image}`" class="table-img" />
          </td>
          <td>{{ contract.title }}</td>
          <td>
            <span :style="getStatusStyle(contract.status)">
              {{ getStatusText(contract.status) }}
            </span>
          </td>
          <td>{{ contract.owner_id }}</td>
          <td>{{ contract.adopter_id || 'No adopter' }}</td>
          <td>{{ contract.description }}</td>
          <td>{{ new Date(contract.create_time).toLocaleString() }}</td>
          <td>
            <button @click="deleteContract(contract)" style="margin-top: 5px; color: red;">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-else-if="isLoading">Loading contracts...</div>
    <div v-else>No contracts found.</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const contracts = ref([])
const isLoading = ref(true)
const router = useRouter()

const getStatusText = (status) => {
  if (status === 2) return 'Completed'
  if (status === 1) return 'Applied'
  return 'Pending'
}

const getStatusStyle = (status) => {
  if (status === 2) return { color: 'green' }
  if (status === 1) return { color: 'black' }
  return { color: 'gray' }
}

const fetchContracts = async () => {
  isLoading.value = true
  try {
    const res = await fetch('http://localhost:8080/user/contracts')
    if (!res.ok) throw new Error('Failed to fetch contracts')
    contracts.value = await res.json()
  } catch (err) {
    console.error('❌ Error loading contracts:', err)
  } finally {
    isLoading.value = false
  }
}

onMounted(fetchContracts)
async function deleteContract(contract) {
  if (!confirm(`Are you sure you want to delete contract #${contract.id}?`)) return;

  try {
    const filesToDelete = []
    if (contract.image) filesToDelete.push(contract.image)
    if (contract.adoption_doc) filesToDelete.push(contract.adoption_doc)
    if (contract.application_doc) filesToDelete.push(contract.application_doc)

    for (const path of filesToDelete) {
      await fetch('http://localhost:8080/delete/image', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ path })
      })
    }

    const res = await fetch(`http://localhost:8080/user/delete/contract/${contract.id}`, {
      method: 'DELETE'
    })

    const rawText = await res.text()
    let result
    try {
      result = JSON.parse(rawText)
    } catch {
      alert('⚠️ Invalid response from server')
      return
    }

    if (res.ok) {
      alert(result.message || '✅ Contract deleted successfully')
      await fetchContracts()
    } else {
      alert(result.error || '❌ Failed to delete contract')
    }

  } catch (err) {
    console.error('❌ Delete error:', err)
    alert('❌ Error deleting contract')
  }
}

</script>

<style scoped src="@/assets/details.css"></style>
