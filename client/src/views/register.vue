<template>
  <div class="max-w-md mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Register</h1>

    <form @submit.prevent="register">
      <div class="mb-4">
        <label class="block text-sm font-medium">Name</label>
        <input v-model="form.name" type="text" class="w-full px-3 py-2 border rounded" />
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium">Email</label>
        <input v-model="form.email" type="email" class="w-full px-3 py-2 border rounded" />
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium">Password</label>
        <input v-model="form.password" type="password" class="w-full px-3 py-2 border rounded" />
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium">Confirm Password</label>
        <input
          v-model="form.password_confirmation"
          type="password"
          class="w-full px-3 py-2 border rounded"
        />
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
        {{ loading ? 'Registering...' : 'Register' }}
      </button>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const errors = ref([])
const loading = ref(false)

const register = async () => {
  errors.value = []
  loading.value = true

  try {
    await axios
      .get('sanctum/csrf-cookie')
      .then(async () => await axios.post('/register', form.value))

    // On success, redirect or emit event
    alert('Registration successful!')
    // Example: router.push('/dashboard')
  } catch (error) {
    if (error.response && error.response.data.errors) {
      const serverErrors = error.response.data.errors
      errors.value = Object.values(serverErrors).flat()
    } else {
      errors.value = ['An unexpected error occurred.']
    }
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
/* optional styling here */
</style>
