<template>
  <v-container class="pa-4" max-width="500">
    <v-card class="mx-auto pa-4" elevation="3">
      <v-card-title class="text-h6 font-weight-bold mb-4"> Edit Service </v-card-title>

      <v-alert v-if="!loaded && !errors.length" type="info" dense class="mb-4">
        Loading service data...
      </v-alert>

      <v-form v-if="loaded" @submit.prevent="updateService" ref="formRef">
        <!-- Service Name -->
        <v-text-field
          v-model="form.name"
          label="Service Name"
          autocomplete="off"
          :rules="[rules.required]"
          required
          class="mb-4"
        />

        <!-- Submit button -->
        <v-btn type="submit" :loading="loading" color="primary" block class="mt-2">
          Update Service
        </v-btn>
      </v-form>

      <!-- Error messages -->
      <v-alert v-if="errors.length" type="error" class="mt-4" dense>
        <ul class="ma-0 pa-0">
          <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
        </ul>
      </v-alert>
    </v-card>
  </v-container>
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
const formRef = ref()

const rules = {
  required: (value: string) => !!value || 'Required field.',
}

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

  const isValid = await formRef.value?.validate()
  if (!isValid?.valid) {
    errors.value = ['Please correct the highlighted fields.']
    loading.value = false
    return
  }

  try {
    await axios.put(`/api/services/${serviceId}`, form.value)
    router.push('/services')
  } catch (error: any) {
    if (error.response?.data?.errors) {
      errors.value = Object.values(error.response.data.errors).flat().map((e: any) => String(e))
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
