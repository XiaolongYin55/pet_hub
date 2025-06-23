<template>
  <div class="table-container">
    <table  v-if="!isLoading && users.length">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Username</th>
          <th>Image</th>
          <th>Email</th>
          <th>Phone</th>
          <th>
                  <button @click="goToAddUser">+ Add New User</button>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td>{{ user.id }}</td>
          <td>{{ user.name }}</td>
          <td>{{ user.username}}</td>
<td>
  <img v-if="user.image" :src="`http://localhost:8081/${user.image}`" alt="user image" class="table-img" />
</td>
          <td>{{ user.email }}</td>
          <td>{{ user.phone_no }}</td>
          <td>
             <button @click="manageUser(user)">Edit</button>
            <button @click="deleteUser(user.id, user.image)" style="margin-left: 8px;">Delete</button>

          </td>
         
        </tr>
      </tbody>
    </table>
    <div v-else-if="isLoading">Loading...</div>
    <div v-else>No users found.</div>
  </div>
</template>


<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router'
const router = useRouter()
const users = ref([]);
const isLoading = ref(true);
const error = ref(null);

const fetchUsers = async () => {
  isLoading.value = true;
  try {
    const res = await fetch('http://localhost:8080/admin/get/users');
    if (!res.ok) {
      throw new Error(`HTTP error! status: ${res.status}`);
    }
    users.value = await res.json();
  } catch (err) {
    error.value = `Failed to load users: ${err.message}`;
    console.error(err);
  } finally {
    isLoading.value = false;
  }
};

onMounted(async () => {
  try {
    const res = await fetch('http://localhost:8080/admin/get/users');
    if (!res.ok) {
      throw new Error(`HTTP error! status: ${res.status}`);
    }
    users.value = await res.json();
  } catch (err) {
    error.value = `Failed to load users: ${err.message}`;
    console.error(err);
  } finally {
    isLoading.value = false;
  }
});

function manageUser(user) {
  console.log('Managing user:', user);
  router.push({ name: 'AdminUserUpdate', query: { id: user.id } })
}

function goToAddUser() {
  router.push('/admin/add/user');
}

async function deleteUser(userId, imagePath) {
  if (!confirm('Are you sure you want to delete this user?')) return;

  try {
    // 🗑️ 删除头像（可选）
    if (imagePath) {
      await fetch('http://localhost:8080/delete/image', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ path: imagePath })
      });
    }

    const res = await fetch(`http://localhost:8080/admin/delete/user/${userId}`, {
      method: 'DELETE'
    });

    const rawText = await res.text(); // 读取原始文本
    console.log('Raw response text:', rawText);

    let result;
    try {
      result = JSON.parse(rawText);
    } catch (e) {
      console.error('❌ JSON parse error:', e);
      alert('Invalid response from server');
      return;
    }

    console.log('res.ok =', res.ok);
    console.log('res.status =', res.status);
    console.log('result =', result);

    if (res.ok) {
      alert(result.message || 'User deleted successfully');
      await fetchUsers(); // ✅ 刷新列表
    } else {
      alert(result.error || 'Failed to delete user');
    }

  } catch (err) {
    console.error('Delete error:', err);
    alert('Error deleting user');
  }
}



</script>
<style scoped src="@/assets/details.css"></style>