<template>
  <div class="max-w-md mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Create Service</h1>

    <form @submit.prevent="createService">
      <div class="mb-4">
        <label class="block text-sm font-medium">Service Name</label>
        <input
          v-model="form.name"
          type="text"
          class="w-full px-3 py-2 border rounded"
          placeholder="Enter service name"
        />
      </div>

      <div v-if="errors.length" class="mb-4 text-red-600 text-sm">
        <ul>
          <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
        </ul>
      </div>

      <button
        type="submit"
        class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded"
        :disabled="loading"
      >
        {{ loading ? 'Creating...' : 'Create Service' }}
      </button>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const form = ref({
  name: '',
})

const errors = ref([])
const loading = ref(false)

const createService = async () => {
  errors.value = []
  loading.value = true

  try {
    await axios.post('/api/services', form.value)

    router.push('/services')
  } catch (error) {
    if (error.response?.data?.errors) {
      errors.value = Object.values(error.response.data.errors).flat()
    } else if (error.response?.data?.message) {
      errors.value = [error.response.data.message]
    } else {
      errors.value = ['An unexpected error occurred.']
    }
  } finally {
    loading.value = false
  }
}
</script>
