<template>
    <div class="mx-auto mt-8 max-w-md">
        <h1 class="mb-4 text-2xl font-bold">Edit Service</h1>

        <div v-if="loading" class="text-center text-gray-600">Loading...</div>

        <form v-else @submit.prevent="submit">
            <div class="mb-4">
                <label for="name" class="mb-1 block font-medium">Service Name</label>
                <input v-model="form.name" type="text" id="name" class="w-full rounded border px-3 py-2" />
                <div v-if="errors.name" class="mt-1 text-sm text-red-600">
                    {{ errors.name }}
                </div>
            </div>

            <button type="submit" class="!hover:bg-green-700 rounded !bg-green-600 px-4 py-2 text-white" :disabled="submitting">Update</button>
        </form>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from '../../lib/axios.ts';

const route = useRoute();
const router = useRouter();
const id = route.params.id;

const loading = ref(true);
const submitting = ref(false);
const form = ref({ name: '' });
const errors = ref<{ name?: string }>({});

const fetchService = async () => {
    try {
        const response = await axios.get(`/api/services/${id}`);
        form.value.name = response.data.name;
    } catch (error) {
        console.error('Failed to fetch service:', error);
    } finally {
        loading.value = false;
    }
};

const submit = async () => {
    submitting.value = true;
    errors.value = {};

    try {
        await axios.put(`/api/services/${id}`, {
            name: form.value.name,
        });

        router.push('/');
    } catch (error: any) {
        if (error.response && error.response.status === 422) {
            errors.value = error.response.data.errors;
        } else {
            console.error('Failed to update service:', error);
        }
    } finally {
        submitting.value = false;
    }
};

onMounted(fetchService);
</script>
