<template>
  <v-container class="pa-4" max-width="500">
    <v-card class="mx-auto pa-4" elevation="3">
      <v-card-title class="text-h6 font-weight-bold mb-4">Login</v-card-title>

      <v-alert v-if="errors.length" type="error" class="mb-4" dense>
        <ul class="ma-0 pa-0">
          <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
        </ul>
      </v-alert>

      <v-form @submit.prevent="login" ref="formRef">
        <!-- Email -->
        <v-text-field
          v-model="form.email"
          label="Email"
          type="email"
          autocomplete="email"
          :rules="[rules.required, rules.email]"
          required
          class="mb-4"
        />

        <!-- Password -->
        <v-text-field
          v-model="form.password"
          label="Password"
          type="password"
          autocomplete="current-password"
          :rules="[rules.required]"
          required
          class="mb-4"
        />

        <!-- Submit button -->
        <v-btn type="submit" :loading="loading" color="primary" block class="mt-2"> Login </v-btn>
      </v-form>
    </v-card>
  </v-container>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'

const formRef = ref()

const form = ref({
  email: '',
  password: '',
})

const errors = ref<string[]>([])
const loading = ref(false)

const rules = {
  required: (value: string) => !!value || 'Required field.',
  email: (value: string) => /.+@.+\..+/.test(value) || 'Invalid email format.',
}

const login = async () => {
  errors.value = []

  const isValid = await formRef.value?.validate()
  if (!isValid?.valid) {
    errors.value.push('Please correct the highlighted fields.')
    return
  }

  loading.value = true

  try {
    await axios.get('/sanctum/csrf-cookie')
    await axios.post('/login', form.value)
    window.location.href = '/'
  } catch (error: any) {
    if (error.response?.data?.errors) {
      const serverErrors = error.response.data.errors
      errors.value = Object.values(serverErrors).flat()
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
