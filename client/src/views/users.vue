<template>
  <v-container class="py-6">
    <v-card class="mx-auto pa-6" max-width="960">
      <v-card-title class="text-h5 font-weight-bold">User Management</v-card-title>

      <!-- Add User Form -->
      <v-card class="pa-4 mb-6" variant="outlined" color="primary">
        <v-card-title class="text-subtitle-1 font-weight-medium">Add a User</v-card-title>
        <v-card-text>
          <v-text-field v-model="newUser.name" label="Name" outlined dense />
          <v-text-field v-model="newUser.email" label="Email" outlined dense />
          <v-text-field
            v-model="newUser.password"
            label="Password"
            type="password"
            outlined
            dense
          />
          <v-text-field
            v-model="newUser.password_confirmation"
            label="Confirm Password"
            type="password"
            outlined
            dense
          />
          <v-btn color="primary" class="mt-3" @click="createUser">Add</v-btn>
        </v-card-text>

        <!-- Feedback messages -->

        <v-alert v-if="createUserLoading" type="info" class="mb-4" dense text="Loading ..."></v-alert>
        <v-alert v-if="createUserError" type="error" class="mt-4" dense :text="createUserError"></v-alert>
        <v-alert v-if="createUserSuccess" type="success" class="mt-4" dense text="User added successfully!"></v-alert>
      </v-card>

      <!-- Loading/Error -->
      <v-alert v-if="loading" type="info" :text="loading ? 'Loading...' : ''"></v-alert>
      <v-alert v-else-if="error" type="error" :text="error"></v-alert>

      <!-- User List -->
      <v-container v-else fluid class="pa-0">
        <v-card v-for="user in users" :key="user.id" class="mb-4" outlined>
          <v-card-title class="d-flex justify-space-between align-center">
            <div>
              <div class="font-weight-medium">{{ user.name }}</div>
              <div class="text-caption text-grey">{{ user.email }}</div>
            </div>
            <v-btn icon @click="deleteUser(user.id)" color="error" variant="text">
              <v-icon>mdi-delete</v-icon>
            </v-btn>
          </v-card-title>

          <!-- Permissions Section -->
          <v-card-text>
            <div class="mb-2 font-weight-medium text-body-2">Permissions:</div>

            <div v-if="user.permissions.length === 0" class="text-grey text-caption mb-2">
              No permissions assigned.
            </div>

            <v-chip-group v-else column class="mb-3">
              <v-chip
                v-for="perm in user.permissions"
                :key="perm.id"
                closable
                @click:close="removePermission(user.id, perm.id)"
                color="blue-lighten-3"
                text-color="blue-darken-3"
                class="ma-1"
              >
                {{ perm.resource }} → {{ perm.operation }}
              </v-chip>
            </v-chip-group>

            <!-- Add permission -->
            <v-row dense>
              <v-col cols="12" md="5">
                <v-text-field
                  v-model="resourceInput[user.id]"
                  label="Resource (e.g. articles)"
                  dense
                  outlined
                />
              </v-col>
              <v-col cols="12" md="5">
                <v-select
                  v-model="operationSelect[user.id]"
                  :items="['create', 'read', 'update', 'delete']"
                  label="Operation"
                  dense
                  outlined
                />
              </v-col>
              <v-col cols="12" md="2" class="d-flex align-center">
                <v-btn size="small" @click="addPermission(user.id)">Add</v-btn>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-container>
    </v-card>
  </v-container>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
import {
  VAlert,
  VBtn,
  VCard,
  VCardText,
  VCardTitle,
  VChip,
  VChipGroup,
  VCol,
  VContainer,
  VIcon,
  VRow,
  VSelect,
  VTextField,
} from 'vuetify/components'

const users = ref<any[]>([])
const loading = ref(true)
const error = ref<string | null>(null)
const createUserError = ref<string | null>(null)
const createUserLoading = ref(false)
const createUserSuccess = ref<null | boolean>(null)

const newUser = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const resourceInput = ref<Record<number, string>>({})
const operationSelect = ref<Record<number, string>>({})

onMounted(() => {
  fetchUsers()
})

const fetchUsers = async () => {
  loading.value = true
  try {
    const res = await axios.get('/api/users')
    users.value = res.data
  } catch (err) {
    error.value = 'Failed to load users.'
  } finally {
    loading.value = false
  }
}

const createUser = async () => {
  try {
    createUserLoading.value = true
    createUserError.value = null
    const res = await axios.post('/api/users', newUser.value)
    users.value.push(res.data)
    newUser.value = { name: '', email: '', password: '', password_confirmation: '' }
    createUserSuccess.value = true

    await fetchUsers()
  } catch (err: any) {
    createUserLoading.value = false
    createUserError.value = err.response.data.message
  } finally {
    createUserLoading.value = false
  }
}
//TODO:: update permissions in the store when changed

const deleteUser = async (id: number) => {
  if (!confirm('Are you sure you want to delete this user?')) return
  try {
    await axios.delete(`/api/users/${id}`)
    users.value = users.value.filter((u) => u.id !== id)
  } catch {
    alert('Error deleting user.')
  }
}

const addPermission = async (userId: number) => {
  const resource = resourceInput.value[userId]
  const operation = operationSelect.value[userId]
  if (!resource || !operation) {
    alert('Both resource and operation are required.')
    return
  }

  try {
    await axios.post('/api/permissions/', { user_id: userId, resource, operation })
    await fetchUsers()
    resourceInput.value[userId] = ''
    operationSelect.value[userId] = ''
  } catch (err) {
    console.error(err)
    alert('Error adding permission.')
  }
}

const removePermission = async (userId: number, permId: number) => {
  try {
    await axios.delete(`/api/permissions/${permId}`)
    await fetchUsers()
  } catch {
    alert('Error removing permission.')
  }
}
</script>
