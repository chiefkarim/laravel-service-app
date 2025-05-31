<template>
  <v-container class="py-6" max-width="lg">
    <v-card class="pa-4" elevation="2">
      <v-card-title class="text-h5 font-weight-bold">Service Requests</v-card-title>

      <v-card-text>
        <div v-if="loading">
          <v-alert type="info" variant="outlined">Loading service requests...</v-alert>
        </div>

        <div v-else-if="error">
          <v-alert type="error" variant="outlined">{{ error }}</v-alert>
        </div>

        <div v-else-if="requests.length === 0">
          <v-alert type="info" variant="outlined">No service requests found.</v-alert>
        </div>

        <v-container v-else fluid>
          <v-row dense v-for="request in requests" :key="request.id">
            <v-col cols="12">
              <v-card class="mb-4" outlined>
                <v-card-text>
                  <div><strong>Service:</strong> {{ request.service?.name }}</div>
                  <div><strong>Email:</strong> {{ request.email }}</div>
                  <div><strong>Name:</strong> {{ request.name }}</div>
                  <div><strong>Details:</strong> {{ request.details }}</div>

                  <div class="mt-2">
                    <strong>Status:</strong>
                    <v-chip :color="statusColor(request.status)" class="text-capitalize">
                      {{ request.status }}
                    </v-chip>
                  </div>

                  <div class="text-grey text-caption mt-1">
                    Submitted: {{ new Date(request.created_at).toLocaleString() }}
                  </div>

                  <v-alert
                    v-if="request.reply"
                    class="mt-3"
                    type="info"
                    variant="outlined"
                    density="compact"
                  >
                    <strong>Reply:</strong>
                    <div class="mt-1" style="white-space: pre-wrap">{{ request.reply }}</div>
                  </v-alert>

                  <div
                    v-if="can('service-requests', 'update')"
                    class="mt-4 d-flex align-center gap-2"
                  >
                    <v-select
                      v-model="request.status"
                      :items="['pending', 'approved', 'declined']"
                      label="Update Status"
                      density="compact"
                      hide-details
                      style="max-width: 180px"
                      @update:modelValue="
                        updateServiceRequest(request.id, { status: request.status })
                      "
                    ></v-select>

                    <v-btn
                      color="primary"
                      @click="openReplyDialog(request.id)"
                      class="ml-2"
                      density="compact"
                    >
                      Add Reply
                    </v-btn>
                  </div>
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
    </v-card>

    <!-- Reply Modal -->
    <v-dialog v-model="showReplyModal" max-width="500">
      <v-card>
        <v-card-title class="text-h6">Add Reply</v-card-title>
        <v-card-text>
          <v-textarea v-model="replyText" rows="4" label="Reply" auto-grow></v-textarea>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="success" @click="submitReply">Submit</v-btn>
          <v-btn color="grey" @click="closeReplyDialog">Cancel</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

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
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'
import { useUserStore } from '../../stores/user.ts'

const requests = ref([])
const loading = ref(true)
const error = ref<string | null>(null)

const currentPage = ref(1)
const lastPage = ref(1)

const showReplyModal = ref(false)
const selectedRequestId = ref<number | null>(null)
const replyText = ref('')

const userStore = useUserStore()
const permissions = ref(userStore.permissions)

onMounted(async () => {
  await fetchRequests()
})
watch(currentPage, () => {
  fetchRequests()
})
const can = (resource: string, operation: string): boolean => {
  return permissions.value.some(
    (perm) => perm.resource === resource && perm.operation === operation,
  )
}

const fetchRequests = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/service-requests', {
      params: { page: currentPage.value },
    })
    requests.value = response.data.data
    lastPage.value = response.data.last_page
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to load service requests.'
  } finally {
    loading.value = false
  }
}

const updateServiceRequest = async (id: number, payload: { status?: string; reply?: string }) => {
  try {
    console.info('hello', id, payload)
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
      return 'green'
    case 'declined':
      return 'red'
    case 'pending':
    default:
      return 'yellow'
  }
}
</script>
