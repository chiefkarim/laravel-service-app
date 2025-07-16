import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

type Permission = { resource: string; operation: string }
type Service = { id: number; name: string; created_at: string; updated_at: string }

export type Notification = {
  id: number
  name: string
  email: string
  details: string
  service: Service
}

export const useUserStore = defineStore(
  'user',
  () => {
    // state
    const id = ref<number | null>(null)
    const email = ref('')
    const role = ref('')
    const permissions = ref<Permission[]>([])
    const notifications = ref<Notification[]>([])
    const loadingUser = ref(true)
    // getters
    const isAdmin = computed(() => ['admin', 'super-admin'].includes(role.value))
    const isLoggedIn = computed(() => !!id.value)
    const user = computed(() => {
      return {
        id: id.value,
        email: email.value,
        role: role.value,
        permissions: permissions.value,
        notifications: notifications.value,
      }
    })
    const unreadCount = computed(() => notifications.value.length)

    // actions
    function setUser(userData: {
      id: number
      email: string
      role: string
      permissions: Permission[]
    }) {
      id.value = userData.id
      email.value = userData.email
      role.value = userData.role
      permissions.value = userData.permissions
    }

    function clearUser() {
      id.value = null
      email.value = ''
      role.value = ''
      permissions.value = []
      notifications.value = []
    }

    function setLoadingUser(value: boolean) {
      loadingUser.value = value
    }
    function addNotification(notification: Notification) {
      notifications.value.unshift(notification)
    }

    function clearNotifications() {
      notifications.value = []
    }

    function deleteNotificationById(notificationId: number) {
      notifications.value = notifications.value.filter((n) => n.id !== notificationId)
    }

    return {
      id,
      email,
      role,
      loadingUser,
      setLoadingUser,
      permissions,
      notifications,
      isAdmin,
      isLoggedIn,
      user,
      unreadCount,
      setUser,
      clearUser,
      addNotification,
      clearNotifications,
      deleteNotificationById,
    }
  },
  {
    persist: {
      pick: ['notifications'],
    },
  },
)
