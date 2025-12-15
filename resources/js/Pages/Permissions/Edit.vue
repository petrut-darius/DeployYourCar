<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    permission: Object
});

const form = useForm({
    name: props.permission.name ?? "",
    description: props.permission.description ?? "",
});

const submit = () => {
    form.put(route("permissions.update", props.permission.id), {
        preserveScroll: true,
    })
}

</script>
<template>
    <AppLayout>
        <h1 class="text-center text-5xl">Edit Permission</h1>
        <form @submit.prevent="submit" class=" mx-auto">
            <div class="mt-4">
                <InputLabel for="name" value="Name" />
                
                <TextInput v-model="form.name" id="name" type="text" class="mt-1 block w-full bg-gray-400" disabled />

                <InputError :message="form.errors.name" class="mt-2"></InputError>
            </div>

            <div>
                <InputLabel for="description" value="Description" />
                
                <TextInput v-model="form.description" id="description" type="text" class="mt-1 block w-full" />

                <InputError :message="form.errors.description" class="mt-2"></InputError>
            </div>

            <div class="mt-4 flex items-center justify-center">
                <PrimaryButton class=" !text-white !bg-green-400 hover:!bg-green-800" :class="{'opacity-25': form.processing}" :disabled="form.processing">
                    Update Permission
                </PrimaryButton>
            </div>
        </form>
    </AppLayout>
</template>