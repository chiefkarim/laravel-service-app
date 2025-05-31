<script setup lang="ts">
import { RouterView } from 'vue-router'
import { useUserStore } from './stores/user'
import { computed } from 'vue'
import axios from 'axios'
import { routes } from './router/index.ts'
import { canRead } from './lib/utils'

import { useEcho } from '@laravel/echo-vue'
useEcho(`service-requests`, '.new-request', (e) => {
  console.log(e)
})
const userStore = useUserStore()
const user = computed(() => userStore.user)
const userPermissions = computed(() => user.value?.permissions || [])

const readableRoutes = computed(() => {
  return routes.filter((route) => {
    const permissions = route.meta?.permissions

    if (user.value?.email && route.path === '/login') return false
    if (route.path === '/not-authorized') return false
    if (route.path.includes(':id')) return false
    if (!permissions) return true

    return permissions.some(
      (p) => p.operation === 'read' && canRead(p.resource, userPermissions.value),
    )
  })
})

const logout = async () => {
  try {
    await axios.post('/logout', { id: user.value.id })
    window.location.href = '/'
  } catch (err) {
    console.error('Logout failed', err)
  }
}
</script>
<template>
  <v-app>
    <v-card>
      <v-toolbar extended>
        <v-toolbar-title text="Service Requests"></v-toolbar-title>

        <template v-if="user.email">
          <span class="mr-4 font-weight-bold text-primary">{{ user.email }}</span>
        </template>

        <v-btn variant="text" v-for="route in readableRoutes" :key="route.path" :to="route.path">
          {{ route.name }}
        </v-btn>

        <v-btn color="primary" variant="outlined" v-if="user.email" @click="logout"> Logout </v-btn>
      </v-toolbar>
    </v-card>
    <v-main class="flex justify-center items-center">
      <v-container>
        <RouterView />
      </v-container>
    </v-main>
  </v-app>
</template>
