<template>
  <div class="event-list-container">
    <div v-if="!isLoading && events.length" class="event-grid">
      <div v-for="event in events" :key="event.event_id" class="event-card">
        <div class="event-header">
          <h3>{{ event.title }}</h3>
          <small>ID: {{ event.event_id }}</small>
        </div>

        <img
          v-if="event.image"
          :src="`http://localhost:8081/${event.image}`"
          alt="event image"
          class="event-image"
        />

        <div class="event-description">
          <span v-if="expandedId === event.event_id">{{ event.description }}</span>
          <span v-else>{{ event.description.slice(0, 100) }}<span v-if="event.description.length > 100">...</span></span>
          <button class="desc-toggle-btn" @click="toggle(event.event_id)">
            {{ expandedId === event.event_id ? 'Hide ▲' : 'Show More ▼' }}
          </button>
        </div>

        <div class="event-footer">
          <p><strong>Publisher:</strong> {{ event.publisher }}</p>
        </div>
      </div>
    </div>

    <div v-else-if="isLoading">Loading events...</div>
    <div v-else>No events found.</div>

    <div class="add-button-container">
      <button class="add-btn" @click="goToAddEvent">+ Add New Event</button>
    </div>
  </div>
</template>


<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const events = ref([])
const isLoading = ref(true)
const error = ref(null)
const expandedId = ref(null) // ✅ 用于折叠 description

onMounted(fetchEvents)

async function fetchEvents() {
  isLoading.value = true
  try {
    const res = await fetch('http://localhost:8080/admin/get/events')
    if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`)
    events.value = await res.json()
  } catch (err) {
    error.value = `Failed to load events: ${err.message}`
    console.error(err)
  } finally {
    isLoading.value = false
  }
}

function toggle(id) {
  expandedId.value = expandedId.value === id ? null : id
}
</script>

<style scoped src="@/assets/details.css"></style>

<style scoped>
.event-list-container {
  padding: 20px;
}

.event-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
  gap: 20px;
}

.event-card {
  background: #fff;
  border-radius: 10px;
  padding: 16px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.event-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.event-image {
  width: 100%;
  max-height: 200px;
  object-fit: cover;
  border-radius: 6px;
  margin: 10px 0;
}

.event-description {
  font-size: 14px;
  color: #333;
  white-space: normal;
  word-break: break-word;
  margin-bottom: 10px;
}

.desc-toggle-btn {
  background: none;
  border: none;
  color: #0077cc;
  cursor: pointer;
  font-size: 0.85rem;
  margin-top: 5px;
}
.desc-toggle-btn:hover {
  text-decoration: underline;
}

.event-footer {
  font-size: 13px;
  color: #555;
}

.event-actions {
  margin-top: 10px;
}
.event-actions button {
  margin-right: 10px;
}

.add-button-container {
  text-align: right;
  margin-top: 20px;
}
.add-btn {
  padding: 8px 16px;
  background-color: #2c8be5;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}
.add-btn:hover {
  background-color: #1c6fc1;
}
</style>


