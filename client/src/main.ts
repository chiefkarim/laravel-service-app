import './assets/main.css'

import { createApp } from 'vue'
import { createVuetify } from 'vuetify'

import App from './App.vue'
import router from './router'
import axios from 'axios'
import { createPinia } from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import { useUserStore } from './stores/user'
import { configureEcho } from '@laravel/echo-vue'
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'

axios.defaults.baseURL = 'http://localhost:8000'
axios.defaults.withCredentials = true
axios.defaults.withXSRFToken = true

const pinia = createPinia()
const app = createApp(App)
pinia.use(piniaPluginPersistedstate)
app.use(pinia)

const vuetify = createVuetify()
app.use(vuetify)
const userStore = useUserStore()

await axios
  .get('/api/user')
  .then((res) => {
    userStore.setUser({
      id: res.data.id,
      role: res.data.role,
      email: res.data.email,
      permissions: res.data.permissions,
    })
  })
  .catch((err) => {
    console.error('Failed to fetch user', err)
  })
  .finally(() => {
    configureEcho({
      broadcaster: 'reverb',
      key: import.meta.env.VITE_REVERB_APP_KEY,
      wsHost: import.meta.env.VITE_REVERB_HOST,
      wsPort: import.meta.env.VITE_REVERB_PORT,
      wssPort: import.meta.env.VITE_REVERB_PORT,
      forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
      enabledTransports: ['ws', 'wss'],
      authorizer: (channel: { name: string }, options: any) => {
        return {
          authorize: (socketId: string, callback: (error: boolean, data?: any) => void) => {
            axios
              .post('/broadcasting/auth', {
                socket_id: socketId,
                channel_name: channel.name,
              })
              .then((response) => {
                callback(false, response.data)
              })
              .catch((error) => {
                callback(true, error.response)
              })
          },
        }
      },
    })
    app.use(router)
    app.mount('#app')
  })
