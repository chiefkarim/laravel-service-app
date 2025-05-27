import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
  state: () => ({
    id: null as number | null,
    email: '',
    role: '',
    permissions: [] as string[],
  }),
  actions: {
    setUser(user: { id: number; email: string; role: string; permissions: string[] }) {
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
