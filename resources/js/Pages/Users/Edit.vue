<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const {user, permissions, groups } = defineProps({
    user: Object,
    permissions: Array,
    groups: Array,
});

const form = useForm({
    name: user.name ?? "",
    permissions: user.permission_ids ?? [],
    groups: user.group_ids ?? [],
});

const submit = () => {
    form.put(route("users.update", user.id), {
        preserveScroll: true,
    })
}

</script>
<template>
    <AppLayout>
        <h2>Edit User</h2>
        <form @submit.prevent="submit" class=" mx-auto">
            <div class="mt-4">
                <InputLabel for="name" value="Name" />
                
                <TextInput v-model="form.name" id="name" type="text" class="mt-1 block w-full" />

                <InputError :message="form.errors.name" class="mt-2"></InputError>
            </div>

            <div class="mt-4">
                <InputLabel for="permissions" value="Permissions" />
                
                <select v-model="form.permissions" id="permissions" multiple class="mt-1 block w-full max-h-40 overflow-y-auto border rounded">
                    <option v-for="permission in permissions" :key="permission.id" :value="permission.id" class="text-center rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:text-black dark:focus:border-blue-600 dark:focus:ring-blue-600 p-1">
                        {{ permission.name }}
                    </option>
                </select>

                <InputError :message="form.errors.permissions" class="mt-2"></InputError>
            </div>

            <div class="mt-4">
                <InputLabel for="groups" value="Groups" />
                
                <select v-model="form.groups" id="groups" multiple class="mt-1 block w-full max-h-40 overflow-y-auto border rounded">
                    <option v-for="group in groups" :key="group.id" :value="group.id" class="text-center rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:text-black dark:focus:border-blue-600 dark:focus:ring-blue-600 p-1">
                        {{ group.name }}
                    </option>
                </select>

                <InputError :message="form.errors.groups" class="mt-2"></InputError>
            </div>

            <div class="mt-4 flex items-center justify-center">
                <PrimaryButton class=" !text-white !bg-green-400 hover:!bg-green-800" :class="{'opacity-25': form.processing}" :disabled="form.processing">
                    Update User
                </PrimaryButton>
            </div>
        </form>
    </AppLayout>
</template>