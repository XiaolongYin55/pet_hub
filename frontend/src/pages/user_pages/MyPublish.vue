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
              <img :src="`http://localhost:8081/${contract.image}`" alt="contract image" width="60" />
            </td>
            <td>{{ contract.title }}</td>
            <td>
              <span :style="getStatusStyle(contract.status)">
                {{ getStatusText(contract.status) }}
              </span>
            </td>
            <!-- ✅ 显示 owner username -->
            <td>{{ usernames[contract.owner_id]?.username ?? 'Loading...' }}</td>

            <!-- ✅ Adopter 改为折叠按钮 -->
            <td>
              <button @click="toggle(contract.id)">
                {{ expandedId === contract.id ? 'Hide Adopter ▲' : 'Show Adopter ▼' }}
              </button>
            </td>

            <td>{{ contract.description }}</td>
            <td>{{ new Date(contract.create_time).toLocaleString() }}</td>
            <td>
              <button @click="goToConfirmAdoption(contract)">Details</button>
            </td>
          </tr>

          <!-- ✅ 折叠显示 adopter 详细信息 -->
          <tr v-if="expandedId === contract.id && contract.adopter_id">
            <td colspan="9">
              <table class="item-subtable" border="1" width="100%">
                <!-- ✅ 加上 status 显示 -->
                <tr>
                  <td colspan="4">
                    <strong>Status:</strong>
                    <span :style="getStatusStyle(contract.status)">
                      {{ getStatusText(contract.status) }}
                    </span>
                  </td>
                </tr>

                <tr>
                  <td><strong>Adopter ID:</strong> {{ contract.adopter_id }}</td>
                  <td><strong>Username:</strong> {{ usernames[contract.adopter_id]?.username ?? 'Loading...' }}</td>
                  <td><strong>Email:</strong> {{ usernames[contract.adopter_id]?.email ?? '-' }}</td>
                  <td><strong>Phone:</strong> {{ usernames[contract.adopter_id]?.address?.phone_number ?? '-' }}</td>
                </tr>
                <tr>
                  <td colspan="3">
                    <strong>Address:</strong>
                    {{ usernames[contract.adopter_id]?.address?.street_address ?? '-' }},
                    {{ usernames[contract.adopter_id]?.address?.city ?? '' }}
                    {{ usernames[contract.adopter_id]?.address?.state ?? '' }}
                  </td>
                  <td colspan="3">
                    <strong>Country:</strong>
                    {{ usernames[contract.adopter_id]?.address?.country ?? '' }}
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
    const userId = sessionStorage.getItem('userId')
    if (!userId) throw new Error('No userId in sessionStorage')

    const res = await fetch(`http://localhost:8080/user/get/contracts/by_user/${userId}`)
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

function goToConfirmAdoption(contract) {
  router.push({ path: '/user/confirm', query: { contractId: contract.id } })
}

// ✅ Status 显示内容
const getStatusText = (status) => {
  switch (status) {
    case 0: return 'Pending'
    case 1: return 'Applied'
    case 2: return 'Completed'
    default: return 'Unknown'
  }
}

// ✅ Status 样式颜色
const getStatusStyle = (status) => {
  switch (status) {
    case 0: return { color: 'gray' }
    case 1: return { color: 'black' }
    case 2: return { color: 'green' }
    default: return { color: 'black' }
  }
}
</script>

<style scoped src="@/assets/details.css"></style>



