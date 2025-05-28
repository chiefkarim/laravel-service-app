import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import Register from '../views/register.vue'
import Login from '../views/login.vue'
import Services from '../views/services/index.vue'
import ServiceCreate from '../views/services/create.vue'
import ServiceEdit from '../views/services/edit.vue'
import ServiceRequests from '../views/service-requests/index.vue'
import Users from '../views/users.vue'
import { useUserStore } from '@/stores/user'
import { hasAllPermissions } from '@/lib/utils'
import NotAuthorized from '../views/not-authorized.vue'

//TODO: type meta field in the routes for better DX
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
      meta: {
        permissions: [
          {
            resource: 'services',
            operation: 'create',
          },
        ],
      },
    },
    {
      path: '/services/:id/edit',
      name: 'Edit service',
      component: ServiceEdit,
      meta: {
        permissions: [
          {
            resource: 'services',
            operation: 'update',
          },
        ],
      },
    },
    {
      path: '/service-requests',
      name: 'Show service requests',
      component: ServiceRequests,
      meta: {
        permissions: [
          {
            resource: 'service-request',
            operation: 'read',
          },
        ],
      },
    },
    {
      path: '/users',
      name: 'Manage users',
      component: Users,
      meta: {
        permissions: [
          {
            resource: 'users',
            operation: 'read',
          },
          { resource: 'permissions', operation: 'read' },
        ],
      },
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue'),
    },
    { path: '/not-authorized', name: 'Not autherized', component: NotAuthorized },
  ],
})

router.beforeEach((to, from, next) => {
  const store = useUserStore()
  const autherized = to.meta?.permissions
    ? hasAllPermissions(to.meta.permissions as any, store.user.permissions)
    : true
  if (autherized) return next()

  return next('/not-authorized')
})

export default router
