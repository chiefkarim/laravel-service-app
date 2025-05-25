<template>
  <header class="bg-white shadow p-4 flex justify-between items-center">
    <h1 class="text-xl font-bold">My App</h1>
    <div>
      <template v-if="user">
        <span class="mr-4 text-gray-700">Hello, {{ user.email }}</span>
        <button @click="logout" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
          Logout
        </button>
      </template>

      <template v-else>
        <RouterLink to="/login" class="text-blue-600 hover:underline mr-4">Login</RouterLink>
        <RouterLink to="/register" class="text-blue-600 hover:underline">Register</RouterLink>
      </template>
    </div>
  </header>
</template>

<script setup lang="ts">
import { useUserStore } from '../stores/user'
import { computed } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import axios from 'axios'

const userStore = useUserStore()
const user = computed(() => userStore.user)
const router = useRouter()

const logout = async () => {
  try {
    await axios.post('/api/logout')
    userStore.setUser(null)
    router.push('/login')
  } catch (err) {
    console.error('Logout failed', err)
  }
}
</script>
