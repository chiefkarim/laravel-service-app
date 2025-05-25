import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import axios from 'axios'
import { useUserStore } from './stores/user'

axios.defaults.baseURL = 'http://localhost:8000'
axios.defaults.withCredentials = true
axios.defaults.withXSRFToken = true
const app = createApp(App)

app.use(createPinia())
app.use(router)

const userStore = useUserStore()

axios
  .get('/api/user')
  .then((res) => {
    console.info('user in main', res)
    userStore.setUser({ id: res.data.id, role: res.data.role, email: res.data.email })
  })
  .catch((err) => {
    console.error('Failed to fetch user', err)
  })
  .finally(() => {
    app.mount('#app')
  })
