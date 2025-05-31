<template>
  <v-container class="pa-4" max-width="600">
    <v-card elevation="0" class="pa-4">
      <v-card-title class="text-h5 font-weight-bold pa-0 mb-4"> Create Service </v-card-title>

      <v-form @submit.prevent="createService" ref="formRef">
        <v-text-field
          v-model="form.name"
          label="Service Name"
          placeholder="Enter service name"
          outlined
          dense
          required
          class="mb-4"
        />

        <v-alert
          v-if="errors.length"
          type="error"
          class="mb-4"
          border="start"
          variant="tonal"
          density="compact"
        >
          <ul class="ma-0 pa-0">
            <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
          </ul>
        </v-alert>

        <v-btn type="submit" color="green" class="w-full" :loading="loading" :disabled="loading">
          {{ loading ? 'Creating...' : 'Create Service' }}
        </v-btn>
      </v-form>
    </v-card>
  </v-container>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const form = ref({
  name: '',
})

const errors = ref<string[]>([])
const loading = ref(false)
const formRef = ref()

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
