import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
  state: () => ({
    id: null as number | null,
    email: '' as string,
    role: '' as string,
  }),
  actions: {
    setUser(user: { id: number; email: string; role: string }) {
      this.id = user.id
      this.email = user.email
      this.role = user.role
    },
    clearUser() {
      this.id = null
      this.email = ''
      this.role = ''
    },
  },
  getters: {
    isAdmin: (state) => ['admin', 'super-admin'].includes(state.role),
    isLoggedIn: (state) => !!state.id,
    user: (state) => state,
  },
})
