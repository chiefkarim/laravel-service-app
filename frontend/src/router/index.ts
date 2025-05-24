import { createRouter, createWebHistory } from 'vue-router';
import ServiceCreate from '../pages/Services/Create.vue';
import ServiceEdit from '../pages/Services/Edit.vue';
import ServicesIndex from '../pages/Services/Index.vue';

const routes = [
    { path: '/', component: ServicesIndex },
    { path: '/services/:id/edit', component: ServiceEdit, name: 'services.edit' },
    { path: '/services/create', component: ServiceCreate, name: 'services.create' },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
