<template>
  <div class="p-4 max-w-6xl mx-auto bg-white rounded shadow">
    <h2 class="text-2xl font-bold mb-6">User Management</h2>

    <!-- Add User Form -->
    <div class="mb-6 border rounded p-4 space-y-2 bg-gray-50">
      <h3 class="text-lg font-semibold">Add a User</h3>
      <input v-model="newUser.name" placeholder="Name" class="border rounded px-3 py-1 w-full" />
      <input v-model="newUser.email" placeholder="Email" class="border rounded px-3 py-1 w-full" />
      <input
        v-model="newUser.password"
        placeholder="Password"
        type="password"
        class="border rounded px-3 py-1 w-full"
      />
      <input
        v-model="newUser.password_confirmation"
        placeholder="Confirm Password"
        type="password"
        class="border rounded px-3 py-1 w-full"
      />
      <button
        @click="createUser"
        class="bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700"
      >
        Add
      </button>
    </div>

    <div v-if="loading" class="text-gray-600">Loading...</div>
    <div v-else-if="error" class="text-red-600">{{ error }}</div>

    <!-- User List -->
    <div v-else class="space-y-4 max-h-[600px] overflow-y-auto pr-2">
      <div v-for="user in users" :key="user.id" class="border rounded p-4 shadow-sm">
        <div class="flex justify-between items-center mb-2">
          <div>
            <p class="font-semibold">{{ user.name }}</p>
            <p class="text-sm text-gray-600">{{ user.email }}</p>
          </div>
          <button class="text-red-600" @click="deleteUser(user.id)">Delete</button>
        </div>

        <!-- Permissions -->
        <div class="space-y-1 mt-2">
          <h4 class="font-medium text-sm">Permissions:</h4>
          <div v-if="user.permissions.length === 0" class="text-sm text-gray-500">
            No permissions
          </div>
          <div v-else class="flex flex-wrap gap-2">
            <span
              v-for="perm in user.permissions"
              :key="perm.id"
              class="bg-blue-100 text-blue-800 text-sm px-2 py-1 rounded flex items-center gap-1"
            >
              {{ perm.resource }} -> {{ perm.operation }}
              <button
                @click="removePermission(user.id, perm.id)"
                class="text-red-600 hover:text-red-800"
              >
                ×
              </button>
            </span>
          </div>

          <!-- Add a permission -->
          <div class="flex gap-2 mt-2 flex-wrap">
            <input
              v-model="resourceInput[user.id]"
              placeholder="Resource (e.g.: articles)"
              class="border rounded px-2 py-1 text-sm"
            />
            <select v-model="operationSelect[user.id]" class="border rounded px-2 py-1 text-sm">
              <option value="">Operation</option>
              <option value="create">Create</option>
              <option value="read">Read</option>
              <option value="update">Update</option>
              <option value="delete">Delete</option>
            </select>
            <button
              @click="addPermission(user.id)"
              class="bg-gray-200 px-2 py-1 rounded hover:bg-gray-300 text-sm"
            >
              Add
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'

const users = ref<any[]>([])
const loading = ref(true)
const error = ref<string | null>(null)

const newUser = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

// Pour gérer les inputs par utilisateur
const resourceInput = ref<Record<number, string>>({})
const operationSelect = ref<Record<number, string>>({})

onMounted(() => {
  fetchUsers()
})

const fetchUsers = async () => {
  loading.value = true
  try {
    const res = await axios.get('/api/users')
    await axios.get('/api/users/1')
    users.value = res.data
  } catch (err) {
    error.value = 'Erreur lors du chargement des utilisateurs.'
  } finally {
    loading.value = false
  }
}

const createUser = async () => {
  try {
    const res = await axios.post('/api/users', newUser.value)
    users.value.push(res.data)
    newUser.value = { name: '', email: '', password: '', password_confirmation: '' }
  } catch (err: any) {
    alert(err.response?.data?.message || 'Erreur lors de la création.')
  }
}

const deleteUser = async (id: number) => {
  if (!confirm('Supprimer cet utilisateur ?')) return
  try {
    await axios.delete(`/api/users/${id}`)
    users.value = users.value.filter((u) => u.id !== id)
  } catch {
    alert('Erreur lors de la suppression.')
  }
}

const addPermission = async (userId: number) => {
  const resource = resourceInput.value[userId]
  const operation = operationSelect.value[userId]
  if (!resource || !operation) {
    alert('Ressource et opération sont obligatoires.')
    return
  }

  try {
    await axios.post('/api/permissions/', { user_id: userId, resource, operation })
    await fetchUsers()
    resourceInput.value[userId] = ''
    operationSelect.value[userId] = ''
  } catch (err) {
    console.error(err)
    alert('Erreur lors de l’ajout.')
  }
}

const removePermission = async (userId: number, permId: number) => {
  try {
    await axios.delete(`/api/permissions/${permId}`)
    await fetchUsers()
  } catch {
    alert('Erreur lors de la suppression.')
  }
}
</script>
