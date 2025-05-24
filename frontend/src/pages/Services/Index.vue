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
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from '../../lib/axios.ts';

interface Service {
    id: number;
    name: string;
}

const services = ref<Service[]>([]);
const router = useRouter();
const fetchServices = async () => {
    try {
        const response = await axios.get('/api/services');
        services.value = response.data;
    } catch (error) {
        console.error('Failed to fetch services:', error);
    }
};

const deleteService = async (id: number) => {
    if (!confirm('Are you sure you want to delete this service?')) return;
    try {
        await axios.delete(`/api/services/${id}`);
        await fetchServices();
    } catch (error) {
        console.error('Failed to delete service:', error);
    }
};

onMounted(fetchServices);
</script>
