import { defineStore } from 'pinia'

type Permission = { resource: string; operation: string }
type Service = { id: number; name: string; created_at: string; updated_at: string }
type Notification = {
  id: number
  name: string
  email: string
  details: string
  service: Service
}
export const useUserStore = defineStore('user', {
  state: () => ({
    id: null as number | null,
    email: '',
    role: '',
    permissions: [] as Permission[],
    notifications: [] as Notification[],
  }),
  actions: {
    setUser(user: { id: number; email: string; role: string; permissions: Permission[] }) {
      this.id = user.id
      this.email = user.email
      this.role = user.role
      this.permissions = user.permissions
    },
    clearUser() {
      this.id = null
      this.email = ''
      this.role = ''
      this.permissions = []
      this.notifications = []
    },
    addNotification(notification: Notification) {
      this.notifications.unshift(notification)
    },
    clearNotifications() {
      this.notifications = []
    },
    deleteNotificationById(id: number) {
      this.notifications = this.notifications.filter((n) => n.id !== id)
    },
  },
  getters: {
    isAdmin: (state) => ['admin', 'super-admin'].includes(state.role),
    isLoggedIn: (state) => !!state.id,
    user: (state) => state,
    unreadCount: (state) => state.notifications.length,
  },
  persist: {
    paths: ['notifications'],
  },
})
