<template>
  <div class="max-w-md mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Edit Service</h1>

    <form v-if="loaded" @submit.prevent="updateService">
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
        class="w-full bg-yellow-600 hover:bg-yellow-700 text-white py-2 px-4 rounded"
        :disabled="loading"
      >
        {{ loading ? 'Updating...' : 'Update Service' }}
      </button>
    </form>

    <div v-else class="text-center text-gray-600">Loading service data...</div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()
const serviceId = route.params.id as string

const form = ref({ name: '' })
const errors = ref<string[]>([])
const loading = ref(false)
const loaded = ref(false)

const fetchService = async () => {
  try {
    const response = await axios.get(`/api/services/${serviceId}`)
    form.value.name = response.data.name
    loaded.value = true
  } catch {
    errors.value = ['Failed to load service.']
  }
}

const updateService = async () => {
  loading.value = true
  errors.value = []

  try {
    await axios.put(`/api/services/${serviceId}`, form.value)
    router.push('/services') // Adjust this route to your actual service list page
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

onMounted(fetchService)
</script>
