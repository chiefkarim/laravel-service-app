<script setup lang="ts">
import { RouterLink, RouterView, useRouter } from 'vue-router'
import HelloWorld from './components/HelloWorld.vue'
import { useUserStore } from './stores/user'
import { computed } from 'vue'
import axios from 'axios'

const userStore = useUserStore()
const user = computed(() => userStore.user)
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
  <header>
    <img alt="Vue logo" class="logo" src="@/assets/logo.svg" width="125" height="125" />

    <div class="wrapper">
      <HelloWorld msg="You did it!" />
      <div v-if="user.email">
        <span class="user-email">{{ user.email }}</span>
      </div>
      <nav>
        <RouterLink to="/services">Services</RouterLink>
        <RouterLink to="/service-requests">Service Requests</RouterLink>
        <RouterLink to="/">Make Request</RouterLink>

        <template v-if="user.email">
          <RouterLink to="/users">Users</RouterLink>
          <button @click="logout" class="auth-button text-black">Logout</button>
        </template>
        <template v-else>
          <RouterLink to="/login">Login</RouterLink>
          <RouterLink to="/register">Register</RouterLink>
        </template>
      </nav>
    </div>
  </header>

  <RouterView />
</template>

<style scoped>
header {
  line-height: 1.5;
  max-height: 100vh;
}

.logo {
  display: block;
  margin: 0 auto 2rem;
}

nav {
  width: 100%;
  font-size: 12px;
  text-align: center;
  margin-top: 2rem;
}

nav a.router-link-exact-active {
  color: var(--color-text);
}

nav a.router-link-exact-active:hover {
  background-color: transparent;
}

nav a {
  display: inline-block;
  padding: 0 1rem;
  border-left: 1px solid var(--color-border);
}

nav a:first-of-type {
  border: 0;
}

.auth-button {
  margin-left: 1rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.user-email {
  font-weight: bold;
  color: #2c3e50;
}

@media (min-width: 1024px) {
  header {
    display: flex;
    place-items: center;
    padding-right: calc(var(--section-gap) / 2);
  }

  .logo {
    margin: 0 2rem 0 0;
  }

  header .wrapper {
    display: flex;
    place-items: flex-start;
    flex-wrap: wrap;
  }

  nav {
    text-align: left;
    margin-left: -1rem;
    font-size: 1rem;

    padding: 1rem 0;
    margin-top: 1rem;
  }
}
</style>
