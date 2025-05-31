<script setup lang="ts">
import { RouterView } from 'vue-router'
import { useUserStore } from './stores/user'
import { computed, ref } from 'vue'
import axios from 'axios'
import { routes } from './router/index.ts'
import { canRead } from './lib/utils'

import { useEcho } from '@laravel/echo-vue'
const menu = ref(false) // pour afficher/masquer le menu
const userStore = useUserStore()
const user = computed(() => userStore.user)
const userPermissions = computed(() => user.value?.permissions || [])

useEcho(`service-requests`, '.new-request', (e) => {
  userStore.addNotification(e) // on suppose que `e.request` contient les infos utiles
})

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

        <div v-if="user.email">
          <v-menu v-model="menu" location="bottom end">
            <template #activator="{ props }">
              <v-btn icon v-bind="props">
                <v-badge
                  :content="userStore.unreadCount"
                  color="error"
                  v-if="userStore.unreadCount > 0"
                >
                  <v-icon>mdi-bell</v-icon>
                </v-badge>
                <v-icon v-else>mdi-bell-outline</v-icon>
              </v-btn>
            </template>

            <v-list>
              <template v-if="userStore.unreadCount">
                <v-list-item v-for="(req, index) in userStore.notifications" :key="index">
                  <div class="d-flex gap-2">
                    <v-list-item-title
                      @click="$router.push(`/service-requests/${req.id}/edit`)"
                      style="cursor: pointer"
                    >
                      New request for {{ req.service.name }}
                    </v-list-item-title>

                    <v-icon
                      small
                      color="error"
                      @click.stop="userStore.deleteNotificationById(req.id)"
                    >
                      mdi-delete
                    </v-icon>
                  </div>
                </v-list-item>
              </template>
              <v-list-item v-else>
                <v-list-item-title>No new requests</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </div>

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
