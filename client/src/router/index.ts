import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import Register from '../views/register.vue'
import Login from '../views/login.vue'
import Services from '../views/services/index.vue'
import ServiceCreate from '../views/services/create.vue'
import ServiceEdit from '../views/services/edit.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/register',
      name: 'register',
      component: Register,
    },
    {
      path: '/login',
      name: 'login',
      component: Login,
    },
    {
      path: '/services',
      name: 'services',
      component: Services,
    },
    {
      path: '/services/create',
      name: 'create service',
      component: ServiceCreate,
    },
    {
      path: '/services/:id/edit',
      name: 'Edit service',
      component: ServiceEdit,
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue'),
    },
  ],
})

export default router
