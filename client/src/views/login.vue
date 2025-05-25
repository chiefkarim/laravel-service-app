<template>
  <div class="max-w-md mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Login</h1>

    <form @submit.prevent="login">
      <div class="mb-4">
        <label class="block text-sm font-medium">Email</label>
        <input v-model="form.email" type="email" class="w-full px-3 py-2 border rounded" />
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium">Password</label>
        <input v-model="form.password" type="password" class="w-full px-3 py-2 border rounded" />
      </div>

      <div v-if="errors.length" class="mb-4 text-red-600 text-sm">
        <ul>
          <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
        </ul>
      </div>

      <button
        type="submit"
        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded"
        :disabled="loading"
      >
        {{ loading ? 'Logging in...' : 'Login' }}
      </button>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()
const form = ref({
  email: '',
  password: '',
})

const errors = ref([])
const loading = ref(false)

const login = async () => {
  errors.value = []
  loading.value = true

  try {
    await axios.get('sanctum/csrf-cookie').then(async () => await axios.post('/login', form.value))

    router.push('/services')
  } catch (error) {
    if (error.response && error.response.data.errors) {
      const serverErrors = error.response.data.errors
      errors.value = Object.values(serverErrors).flat()
    } else if (error.response && error.response.data.message) {
      errors.value = [error.response.data.message]
    } else {
      errors.value = ['An unexpected error occurred.']
    }
  } finally {
    loading.value = false
  }
}
</script>
