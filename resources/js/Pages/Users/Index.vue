<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    users: Object,
});

</script>
<template>
    <AppLayout>
        <div class="flex justify-center">
            <h1 class="text-4xl">Users</h1>
        </div>
        <div class="flex justify-center mt-6">
        <table class="min-w-full bg-gray-200 rounded-lg shadow">
            <thead class="bg-gray-500 text-white">
            <tr>
                <th class="py-2 px-4 text-left">ID</th>
                <th class="py-2 px-4 text-left">Name</th>
                <th class="py-2 px-4 text-left">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="user in users" :key="user.id" class="bg-gray-100 even:bg-gray-200 hover:bg-gray-300">
                <td class="py-2 px-4">{{ user.id }}</td>
                <td class="py-2 px-4">{{ user.name }}</td>
                <td class="py-2 px-4">
                <Link
                    :href="route('users.show', user.id)"
                    class="py-1 px-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                >
                    View
                </Link>
                </td>
            </tr>
            </tbody>
        </table>
        </div>
    </AppLayout>
</template>