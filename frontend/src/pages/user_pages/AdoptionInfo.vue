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
        <template v-for="contract in contracts" :key="contract.id">
          <tr>
            <td>{{ contract.id }}</td>
            <td>
              <img :src="`http://localhost:8081/${contract.image}`" class="table-img"  />
            </td>
            <td>{{ contract.title }}</td>
            <td>
              <span :style="getStatusStyle(contract.status)">
                {{ getStatusText(contract.status) }}
              </span>
            </td>
            <td>
              <button @click="toggle(contract.id)">
                {{ expandedId === contract.id ? 'Hide Owner ▲' : 'Show Owner ▼' }}
              </button>
            </td>
            <td>
              {{ contract.adopter_id
                ? (usernames[contract.adopter_id]?.username ?? 'Loading...')
                : 'No adopter' }}
            </td>
            <td>{{ contract.description }}</td>
            <td>{{ new Date(contract.create_time).toLocaleString() }}</td>
            <td>
              <button @click="goToApplication(contract)">Apply Now</button>
            </td>
          </tr>

          <tr v-if="expandedId === contract.id">
            <td colspan="9">
              <table class="item-subtable" border="1" width="100%">
                <tr>
                  <td><strong>Owner ID:</strong> {{ contract.owner_id }}</td>
                  <td><strong>Username:</strong> {{ usernames[contract.owner_id]?.username ?? 'Loading...' }}</td>
                  <td><strong>Email:</strong> {{ usernames[contract.owner_id]?.email ?? '-' }}</td>
                  <td><strong>Phone:</strong> {{ usernames[contract.owner_id]?.address?.phone_number ?? '-' }}</td>
                </tr>
                <tr>
                  <td colspan="3">
                    <strong>Address:</strong>
                    {{ usernames[contract.owner_id]?.address?.street_address ?? '-' }},
                    {{ usernames[contract.owner_id]?.address?.city ?? '' }}
                    {{ usernames[contract.owner_id]?.address?.state ?? '' }}
                  </td>
                  <td colspan="3">
                    <strong>Country:</strong>
                    {{ usernames[contract.owner_id]?.address?.country ?? '' }}
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </template>
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
const usernames = ref({})
const isLoading = ref(true)
const expandedId = ref(null)
const router = useRouter()

const toggle = (id) => {
  expandedId.value = expandedId.value === id ? null : id
}

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

const fetchUsername = async (id) => {
  try {
    const res = await fetch(`http://localhost:8080/user/get/user/${id}`)
    if (!res.ok) throw new Error('Failed to fetch user')
    const data = await res.json()
    usernames.value[id] = data
  } catch (err) {
    console.error(`❌ Failed to load user ${id}:`, err)
    usernames.value[id] = { username: 'Unknown' }
  }
}

const fetchContracts = async () => {
  try {
    const res = await fetch('http://localhost:8080/user/contracts')
    if (!res.ok) throw new Error('Failed to fetch contracts')
    const data = await res.json()
    contracts.value = data

    const userIds = new Set()
    data.forEach(c => {
      if (c.owner_id) userIds.add(c.owner_id)
      if (c.adopter_id) userIds.add(c.adopter_id)
    })

    for (const id of userIds) {
      await fetchUsername(id)
    }
  } catch (err) {
    console.error('❌ Error loading contracts:', err)
  } finally {
    isLoading.value = false
  }
}

onMounted(fetchContracts)

function goToApplication(contract) {
  router.push({ path: '/user/application', query: { contractId: contract.id } })
}
</script>

<style scoped src="@/assets/details.css"></style>



