<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Tiptap from '@/Components/Tiptap.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3'
import { ref } from "vue";

const props = defineProps({
    permissions: Object
});

const form = useForm({
    name: "",
    permissions: []
});

const submit = () => {
    form.post(route("groups.store"), {
        preserveScroll: true,
    });
};

</script>
<template>
    <AppLayout>
        <h1 class="text-center text-5xl">Create Group</h1>
        <form @submit.prevent="submit" class=" mx-auto">
            <div class="mt-4">
                <InputLabel for="name" value="Name" />
                
                <TextInput v-model="form.name" id="name" type="text" class="mt-1 block w-full" />

                <InputError :message="form.errors.name" class="mt-2"></InputError>
            </div>

            <div class="mt-4">
                <InputLabel for="permissions" value="Permissions" />
                
                <select v-model="form.permissions" id="permissions" multiple class="mt-1 block w-full max-h-40 overflow-y-auto border rounded">
                    <option v-for="permission in props.permissions" :key="permission.id" :value="permission.id" class="text-center rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:text-black dark:focus:border-blue-600 dark:focus:ring-blue-600 p-1">
                        {{ permission.name }}
                    </option>
                </select>

                <InputError :message="form.errors.permissions" class="mt-2"></InputError>
            </div>

            <div class="mt-4 flex items-center justify-center">
                <PrimaryButton class="ml-4 !text-white !bg-green-400 hover:!bg-green-800" :class="{'opacity-25': form.processing}" :disabled="form.processing">
                    Add Group
                </PrimaryButton>
            </div>
        </form>
    </AppLayout>
</template>
