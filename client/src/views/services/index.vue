<template>
  <v-container class="pa-4" max-width="600">
    <v-card elevation="0" class="pa-4">
      <v-card-title class="text-h5 font-weight-bold mb-4 pa-0">Available Services</v-card-title>

      <v-btn
        v-if="!loading && services.length > 0 && can('services', 'create')"
        color="primary"
        class="mb-4"
        :to="{ path: '/services/create' }"
      >
        + Create Service
      </v-btn>

      <v-alert v-if="loading" type="info" class="mb-4"> Loading services... </v-alert>

      <v-alert v-if="errors.length" type="error" dense outlined class="mb-4">
        <ul>
          <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
        </ul>
      </v-alert>

      <v-list v-if="services.length">
        <v-list-item v-for="(service, index) in services" :key="index" class="border rounded mb-2">
          <v-list-item-title>{{ service.name }}</v-list-item-title>

          <template #append>
            <v-btn
              v-if="can('services', 'update')"
              :to="`/services/${service.id}/edit`"
              variant="text"
              color="primary"
              class="text-caption"
            >
              Edit
            </v-btn>

            <v-btn
              v-if="can('services', 'delete')"
              @click="deleteService(service.id)"
              variant="text"
              color="error"
              class="text-caption"
            >
              Delete
            </v-btn>
          </template>
        </v-list-item>
      </v-list>

      <div v-else-if="!loading" class="text-medium-emphasis">No services found.</div>
    </v-card>
    <v-pagination
      v-if="lastPage > 1"
      v-model="currentPage"
      :length="lastPage"
      class="mt-4"
      color="primary"
    />
  </v-container>
</template>

<script setup lang="ts">
import { ref, onMounted, computed, watch } from 'vue'
import axios from 'axios'
import { useUserStore } from '@/stores/user' // chemin à adapter selon ton projet

const services = ref([])
const errors = ref([])
const loading = ref(false)

const currentPage = ref(1)
const lastPage = ref(1)

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
    const response = await axios.get('/api/services', {
      params: { page: currentPage.value },
    })
    services.value = response.data.data
    lastPage.value = response.data.last_page
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
watch(currentPage, () => {
  fetchServices()
})
</script>
