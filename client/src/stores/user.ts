import { defineStore } from 'pinia'

type Permission = { resource: string; operation: string }
export const useUserStore = defineStore('user', {
  state: () => ({
    id: null as number | null,
    email: '',
    role: '',
    permissions: [] as Permission[],
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
    },
  },
  getters: {
    isAdmin: (state) => ['admin', 'super-admin'].includes(state.role),
    isLoggedIn: (state) => !!state.id,
    user: (state) => state,
  },
})
