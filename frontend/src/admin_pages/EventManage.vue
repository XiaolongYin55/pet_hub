<template>
  <div class="table-container">
    <table v-if="!isLoading && events.length">
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Image</th>
          <th>Description</th>
          <th>Publisher</th>
          <th>
            <button @click="goToAddEvent">+ Add New Event</button>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="event in events" :key="event.id">
          <td>{{ event.event_id }}</td>
          <td>{{ event.title }}</td>
<td>
  <img v-if="event.image" :src="`http://localhost:8081/${event.image}`" alt="user image" class="table-img"  />
</td>
          <td>{{ event.description }}</td>
          <td>{{ event.publisher}}</td>

          <td>
            <button @click="editEvent(event.event_id)">Edit</button>
        <button @click="deleteEvent(event.event_id, event.image)" style="margin-left: 8px;">Delete</button>

          </td>
        </tr>
      </tbody>
    </table>
    <div v-else-if="isLoading">Loading...</div>
    <div v-else>No events found.</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const events = ref([])
const isLoading = ref(true)
const error = ref(null)

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

function editEvent(id) {
  router.push({ name: 'AdminEventUpdate', query: { id } })
}

function goToAddEvent() {
  router.push('/admin/add/event')
}

async function deleteEvent(eventId, imagePath) {
  if (!confirm('Are you sure you want to delete this event?')) return;

  try {
    // 🗑️ 先删除图片（如果有）
    if (imagePath) {
      // 如果只是文件名，补上路径（保险处理）
      if (!imagePath.startsWith('oss/images/')) {
        imagePath = 'oss/images/' + imagePath;
      }

      await fetch('http://localhost:8080/delete/image', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ path: imagePath })
      });
    }

    // ❌ 再删除活动
    const res = await fetch(`http://localhost:8080/admin/delete/event/${eventId}`, {
      method: 'DELETE'
    });

    if (res.ok) {
      alert('Event deleted successfully');
      await fetchEvents();
    } else {
      alert('Failed to delete event');
    }
  } catch (err) {
    console.error('Delete error:', err);
    alert('Error deleting event');
  }
}

</script>

<style scoped src="@/assets/details.css"></style>
