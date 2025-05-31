<template>
  <v-container class="py-6" max-width="sm">
    <v-card class="pa-4" elevation="2">
      <v-card-title class="text-h6 font-weight-bold">Edit Service Request</v-card-title>

      <v-card-text v-if="loading">
        <v-alert type="info" variant="outlined">Loading...</v-alert>
      </v-card-text>

      <v-card-text v-else-if="error">
        <v-alert type="error" variant="outlined">{{ error }}</v-alert>
      </v-card-text>

      <v-card-text v-else>
        <div><strong>Service:</strong> {{ request.service?.name }}</div>
        <div><strong>Email:</strong> {{ request.email }}</div>
        <div><strong>Name:</strong> {{ request.name }}</div>
        <div><strong>Details:</strong> {{ request.details }}</div>

        <div class="mt-3">
          <v-select
            v-model="request.status"
            :items="['pending', 'approved', 'declined']"
            label="Status"
            density="compact"
            hide-details
            @update:modelValue="updateServiceRequest(request.id, { status: request.status })"
          />
        </div>

        <div class="mt-3">
          <v-textarea v-model="replyText" label="Reply" rows="4" auto-grow />
          <v-btn color="primary" class="mt-2" @click="submitReply">Submit Reply</v-btn>
        </div>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const id = Number(route.params.id)

const request = ref<any>(null)
const loading = ref(true)
const error = ref<string | null>(null)
const replyText = ref('')

onMounted(async () => {
  try {
    const { data } = await axios.get(`/api/service-requests/${id}`)
    request.value = data
    replyText.value = data.reply || ''
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to fetch service request.'
  } finally {
    loading.value = false
  }
})

const updateServiceRequest = async (id: number, payload: { status?: string; reply?: string }) => {
  try {
    const { data } = await axios.put(`/api/service-requests/${id}`, payload)
    request.value = data
  } catch (err) {
    alert('Failed to update service request.')
  }
}

const submitReply = async () => {
  await updateServiceRequest(id, { reply: replyText.value })
}
</script>
