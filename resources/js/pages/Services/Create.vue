<template>
    <div class="mx-auto mt-8 max-w-md">
        <h1 class="mb-4 text-2xl font-bold">Create Service</h1>
        <form @submit.prevent="submit">
            <div class="mb-4">
                <label for="name" class="mb-1 block font-medium">Service Name</label>
                <input v-model="form.name" type="text" id="name" class="w-full rounded border px-3 py-2" />
                <div v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                    {{ form.errors.name }}
                </div>
            </div>

            <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700" :disabled="form.processing">Create</button>
        </form>
    </div>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
});

const submit = () => {
    form.post('/services');
};
</script>
