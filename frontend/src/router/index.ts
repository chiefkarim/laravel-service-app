import { createRouter, createWebHistory } from 'vue-router';
import ServicesIndex from '../pages/Services/Index.vue';

const routes = [{ path: '/', component: ServicesIndex }];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
