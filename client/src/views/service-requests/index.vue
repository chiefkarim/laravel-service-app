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

        <p>
          <strong>Status:</strong>
          <span :class="statusColor(request.status)" class="capitalize font-medium">
            {{ request.status }}
          </span>
        </p>

        <p class="text-sm text-gray-500">
          Submitted: {{ new Date(request.created_at).toLocaleString() }}
        </p>

        <div
          v-if="request.reply"
          class="mt-3 bg-gray-100 border border-gray-300 text-sm p-3 rounded"
        >
          <strong class="text-gray-700">Reply:</strong>
          <p class="text-gray-800 mt-1 whitespace-pre-wrap">{{ request.reply }}</p>
        </div>

        <div v-if="isAdmin" class="mt-4 space-x-2 flex items-center">
          <select
            v-model="request.status"
            @change="updateServiceRequest(request.id, { status: request.status })"
            class="border px-2 py-1 rounded text-sm"
          >
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="declined">Declined</option>
          </select>

          <button
            class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700"
            @click="openReplyDialog(request.id)"
          >
            Add Reply
          </button>
        </div>
      </div>
    </div>

    <div
      v-if="showReplyModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <div class="bg-white rounded p-4 max-w-md w-full">
        <h3 class="text-lg font-semibold mb-2">Add Reply</h3>
        <textarea
          v-model="replyText"
          rows="4"
          class="w-full border px-2 py-1 rounded mb-2"
        ></textarea>
        <div class="flex justify-end space-x-2">
          <button @click="submitReply" class="bg-green-600 text-white px-3 py-1 rounded">
            Submit
          </button>
          <button @click="closeReplyDialog" class="bg-gray-300 px-3 py-1 rounded">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import { useUserStore } from '../../stores/user.ts'

const requests = ref([])
const loading = ref(true)
const error = ref<string | null>(null)

const showReplyModal = ref(false)
const selectedRequestId = ref<number | null>(null)
const replyText = ref('')

const userStore = useUserStore()
const isAdmin = computed(() => userStore.isAdmin)

onMounted(async () => {
  await fetchRequests()
})

const fetchRequests = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/service-requests')
    requests.value = response.data
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to load service requests.'
  } finally {
    loading.value = false
  }
}

const updateServiceRequest = async (id: number, payload: { status?: string; reply?: string }) => {
  try {
    const response = await axios.put(`/api/service-requests/${id}`, payload)
    const updated = response.data
    const index = requests.value.findIndex((r) => r.id === id)
    if (index !== -1) requests.value[index] = updated
  } catch (err) {
    alert('Failed to update service request.')
  }
}

const openReplyDialog = (id: number) => {
  selectedRequestId.value = id
  replyText.value = ''
  showReplyModal.value = true
}

const closeReplyDialog = () => {
  showReplyModal.value = false
  selectedRequestId.value = null
  replyText.value = ''
}

const submitReply = async () => {
  if (!selectedRequestId.value) return
  await updateServiceRequest(selectedRequestId.value, { reply: replyText.value })
  closeReplyDialog()
}

const statusColor = (status: string) => {
  switch (status) {
    case 'approved':
      return 'text-green-600'
    case 'declined':
      return 'text-red-600'
    case 'pending':
    default:
      return 'text-yellow-600'
  }
}
</script>
