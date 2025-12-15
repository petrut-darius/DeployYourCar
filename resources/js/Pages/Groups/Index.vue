<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';

defineProps({
    groups: Object,
});

const deleteGroup = (id) => {
    if(confirm('Are you sure you want to delete this permission?')) {
        router.delete(route('groups.destroy', id));
    }
}

</script>
<template>
    <AppLayout>
        <div class="flex justify-center">
            <h1 class="text-4xl">Groups</h1>
        </div>
        <div class="mt-4">
            <Link :href="route('groups.create')" class="py-2 px-3 bg-blue-500 text-white rounded hover:bg-blue-600">Add Group</Link>
        </div>
        <div class="flex justify-center mt-6">
        <table class="min-w-full bg-gray-200 rounded-lg shadow">
            <thead class="bg-gray-500 text-white">
            <tr>
                <th class="py-2 px-4 text-center">ID</th>
                <th class="py-2 px-4 text-center">Name</th>
                <th class="py-2 px-4 text-center">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="group in groups" :key="group.id" class="bg-gray-100 even:bg-gray-200 hover:bg-gray-300">
                <td class="py-2 px-4 text-center">{{ group.id }}</td>
                <td class="py-2 px-4 text-center">{{ group.name }}</td>
                <td class="py-2 px-4 text-center">
                    <div class="flex justify-center gap-2">
                        <Link :href="route('groups.edit', group.id)" class="py-1 px-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Edit
                        </Link>
                        <DangerButton as="button" class="py-1 px-2 hover:text-white rounded" @click="deleteGroup(group.id)">
                            Delete
                        </DangerButton>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        </div>
    </AppLayout>
</template>