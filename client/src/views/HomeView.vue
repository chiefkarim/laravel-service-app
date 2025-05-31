<template>
  <v-container class="pa-4" max-width="500">
    <v-card class="mx-auto pa-4" elevation="3">
      <v-card-title class="text-h6 font-weight-bold mb-4"> Create Service Request </v-card-title>

      <v-alert v-if="loading" type="info" class="mb-4" dense> Loading services... </v-alert>
      <v-form v-if="!loading" @submit.prevent="submit" ref="formRef">
        <!-- Email -->
        <v-text-field
          v-model="form.email"
          label="Your Email"
          type="email"
          autocomplete="email"
          :rules="[rules.required, rules.email]"
          required
          class="mb-4"
        />

        <!-- Full Name -->
        <v-text-field
          v-model="form.name"
          label="Full Name"
          autocomplete="name"
          :rules="[rules.required]"
          required
          class="mb-4"
        />

        <!-- Service selection -->
        <v-select
          v-model="form.service_id"
          :items="services"
          item-title="name"
          item-value="id"
          label="Select a Service"
          :rules="[rules.required]"
          required
          class="mb-4"
        />

        <!-- Details -->
        <v-textarea
          v-model="form.details"
          label="Details"
          rows="4"
          :rules="[rules.required]"
          required
          class="mb-4"
        />

        <!-- Submit button -->
        <v-btn type="submit" :loading="loading" color="primary" class="mt-2" block> Submit </v-btn>
      </v-form>

      <!-- Feedback messages -->
      <v-alert v-if="error" type="error" class="mt-4" dense>
        {{ error }}
      </v-alert>
      <v-alert v-if="success" type="success" class="mt-4" dense>
        Service request created successfully!
      </v-alert>
    </v-card>
  </v-container>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'

const formRef = ref()

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

const rules = {
  required: (value: string) => !!value || 'Required field.',
  email: (value: string) => /.+@.+\..+/.test(value) || 'Invalid email format.',
}

onMounted(async () => {
  try {
    error.value = null
    loading.value = true
    const response = await axios.get('/api/services')
    services.value = response.data
    loading.value = false
  } catch (err) {
    error.value = 'Failed to load services.'
    loading.value = false
  }
})

const submit = async () => {
  error.value = null
  success.value = false

  const isValid = await formRef.value.validate()
  if (!isValid.valid) {
    error.value = 'Please correct the highlighted fields.'
    return
  }

  loading.value = true

  try {
    await axios.post('/api/service-requests', form.value)
    success.value = true
    form.value = { email: '', service_id: '', details: '', name: '' }
    formRef.value.reset()
  } catch (err) {
    error.value = err.response?.data?.message || 'An error occurred.'
  } finally {
    loading.value = false
  }
}
</script>
