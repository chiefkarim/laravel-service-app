<template>
    <div>
        <h1 class="mb-4 text-2xl font-bold">Services</h1>
        <a href="/services/create" class="text-blue-500">+ Add Service</a>
        <ul>
            <li v-for="service in services" :key="service.id" class="mb-2">
                {{ service.name }}
                <a :href="`/services/${service.id}/edit`" class="ml-2 text-blue-500">Edit</a>
                <button @click="deleteService(service.id)" class="ml-2 text-red-500">Delete</button>
            </li>
        </ul>
    </div>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3';

defineProps({ services: Array });

const deleteService = (id: number) => {
    if (confirm('Are you sure you want to delete this service?')) {
        router.delete(`/services/${id}`);
    }
};
</script>
