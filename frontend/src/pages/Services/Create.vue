<template>
    <div class="mx-auto mt-8 max-w-md">
        <h1 class="mb-4 text-2xl font-bold">Create Service</h1>

        <form @submit.prevent="submit">
            <div class="mb-4">
                <label for="name" class="mb-1 block font-medium">Service Name</label>
                <input v-model="form.name" type="text" id="name" class="w-full rounded border px-3 py-2" />
                <div v-if="errors.name" class="mt-1 text-sm text-red-600">
                    {{ errors.name }}
                </div>
            </div>

            <button type="submit" class="rounded !bg-blue-600 px-4 py-2 text-white hover:bg-blue-700" :disabled="submitting">Create</button>
        </form>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from '../../lib/axios.ts';

const router = useRouter();

const submitting = ref(false);
const form = ref({ name: '' });
const errors = ref<{ name?: string }>({});

const submit = async () => {
    submitting.value = true;
    errors.value = {};

    try {
        await axios.post('/api/services', {
            name: form.value.name,
        });
        router.push('/'); // Redirect to services list or another relevant route
    } catch (error: any) {
        if (error.response && error.response.status === 422) {
            errors.value = error.response.data.errors;
        } else {
            console.error('Failed to create service:', error);
        }
    } finally {
        submitting.value = false;
    }
};
</script>
