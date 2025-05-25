<template>
  <div class="p-4 max-w-md mx-auto bg-white rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Create Service Request</h2>

    <form @submit.prevent="submit">
      <!-- Email -->
      <div class="mb-4">
        <label for="email" class="block font-medium">Your Email</label>
        <input
          type="email"
          v-model="form.email"
          id="email"
          class="w-full border px-3 py-2 rounded"
          required
        />
      </div>
      <div class="mb-4">
        <label for="name" class="block font-medium">Full Name</label>
        <input
          type="text"
          v-model="form.name"
          id="name"
          class="w-full border px-3 py-2 rounded"
          required
        />
      </div>
      <!-- Service selection -->
      <div class="mb-4">
        <label for="service_id" class="block font-medium">Select a Service</label>
        <select
          v-model="form.service_id"
          id="service_id"
          class="w-full border px-3 py-2 rounded"
          required
        >
          <option value="" disabled>Select a service</option>
          <option v-for="service in services" :key="service.id" :value="service.id">
            {{ service.name }}
          </option>
        </select>
      </div>

      <!-- Details -->
      <div class="mb-4">
        <label for="details" class="block font-medium">Details</label>
        <textarea
          v-model="form.details"
          id="details"
          class="w-full border px-3 py-2 rounded"
          required
        ></textarea>
      </div>

      <!-- Submit button -->
      <button
        type="submit"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
        :disabled="loading"
      >
        <span v-if="loading">Submitting...</span>
        <span v-else>Submit</span>
      </button>
    </form>

    <!-- Feedback -->
    <div v-if="error" class="mt-4 text-red-600">
      {{ error }}
    </div>
    <div v-if="success" class="mt-4 text-green-600">Service request created successfully!</div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'

// Reactive state
const form = ref({
  email: '',
  service_id: '',
  details: '',
  name: '',
})
const services = ref([])
const loading = ref(false)
const error = ref(null)
const success = ref(false)

// Fetch services on mount
onMounted(async () => {
  try {
    const response = await axios.get('/api/services')
    services.value = response.data
  } catch (err) {
    error.value = 'Failed to load services.'
  }
})

// Form submission
const submit = async () => {
  loading.value = true
  error.value = null
  success.value = false

  try {
    await axios.post('/api/service-requests', {
      email: form.value.email,
      service_id: form.value.service_id,
      details: form.value.details,
      name: form.value.name,
    })
    success.value = true
    form.value = { email: '', service_id: '', details: '' }
  } catch (err) {
    error.value = err.response?.data?.message || 'An error occurred.'
  } finally {
    loading.value = false
  }
}
</script>
