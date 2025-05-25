<template>
  <div class="p-4 max-w-4xl mx-auto bg-white rounded shadow">
    <h2 class="text-2xl font-bold mb-6">Service Requests</h2>

    <div v-if="loading" class="text-gray-600">Loading service requests...</div>
    <div v-else-if="error" class="text-red-600">{{ error }}</div>
    <div v-else-if="requests.length === 0" class="text-gray-600">No service requests found.</div>

    <div v-else class="space-y-4">
      <div v-for="request in requests" :key="request.id" class="border border-gray-200 rounded p-4">
        <p><strong>Service:</strong> {{ request.service?.name }}</p>
        <p><strong>User:</strong> {{ request.user?.email }}</p>
        <p><strong>Details:</strong> {{ request.details }}</p>
        <p class="text-sm text-gray-500">
          Submitted: {{ new Date(request.created_at).toLocaleString() }}
        </p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'

// Reactive state
const requests = ref([])
const loading = ref(true)
const error = ref<string | null>(null)

onMounted(async () => {
  try {
    const response = await axios.get('/api/service-requests')
    requests.value = response.data
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to load service requests.'
  } finally {
    loading.value = false
  }
})
</script>
