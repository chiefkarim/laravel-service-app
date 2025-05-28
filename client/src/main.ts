import './assets/main.css'

import { createApp } from 'vue'

import App from './App.vue'
import router from './router'
import axios from 'axios'
import { createPinia } from 'pinia'
import { useUserStore } from './stores/user'

axios.defaults.baseURL = 'http://localhost:8000'
axios.defaults.withCredentials = true
axios.defaults.withXSRFToken = true

const pinia = createPinia()
const app = createApp(App)
app.use(pinia)

const userStore = useUserStore()

await axios
  .get('/api/user')
  .then((res) => {
    console.log('permissions', res.data)
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
    app.use(router)
    app.mount('#app')
  })
