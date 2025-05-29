<template>
  <div class="max-w-md mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Available Services</h1>

    <div v-if="!loading && services.length > 0 && can('services', 'create')" class="mb-4">
      <RouterLink
        to="/services/create"
        class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
      >
        + Create Service
      </RouterLink>
    </div>

    <div v-if="loading" class="text-blue-600 mb-4">Loading services...</div>

    <div v-if="errors.length" class="mb-4 text-red-600 text-sm">
      <ul>
        <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
      </ul>
    </div>

    <ul v-if="services.length" class="space-y-2">
      <li
        v-for="(service, index) in services"
        :key="index"
        class="px-4 py-2 border rounded flex justify-between items-center"
      >
        <span>{{ service.name }}</span>
        <div class="gap-2 flex">
          <div v-if="can('services', 'update')" class="flex space-x-2">
            <RouterLink
              :to="`/services/${service.id}/edit`"
              class="text-blue-600 hover:underline text-sm"
            >
              Edit
            </RouterLink>
          </div>
          <div v-if="can('services', 'delete')" class="flex space-x-2">
            <button @click="deleteService(service.id)" class="text-red-600 hover:underline text-sm">
              Delete
            </button>
          </div>
        </div>
      </li>
    </ul>

    <div v-else-if="!loading" class="text-gray-500">No services found.</div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import { useUserStore } from '@/stores/user' // chemin à adapter selon ton projet

const services = ref([])
const errors = ref([])
const loading = ref(false)

const userStore = useUserStore()
const permissions = computed(() => userStore.permissions)

const can = (resource: string, operation: string): boolean => {
  return permissions.value.some(
    (perm) => perm.resource === resource && perm.operation === operation,
  )
}

const fetchServices = async () => {
  errors.value = []
  loading.value = true

  try {
    const response = await axios.get('/api/services')
    services.value = response.data
  } catch (error) {
    if (error.response?.data?.message) {
      errors.value = [error.response.data.message]
    } else {
      errors.value = ['Failed to fetch services.']
    }
  } finally {
    loading.value = false
  }
}

const deleteService = async (id: number) => {
  if (!confirm('Are you sure you want to delete this service?')) return

  try {
    await axios.delete(`/api/services/${id}`)
    services.value = services.value.filter((service) => service.id !== id)
  } catch (error) {
    alert('Failed to delete service.')
    console.error(error)
  }
}

onMounted(async () => {
  await fetchServices()
})
</script>
