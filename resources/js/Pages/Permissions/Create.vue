<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Tiptap from '@/Components/Tiptap.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3'
import { ref } from "vue";

const page = usePage()
const user = page.props.auth.user
const tags = page.props.tags
const types = page.props.types

const form = useForm({
    name: "",
    description: ""
});

const submit = () => {
    form.post(route("permissions.store"), {
        preserveScroll: true,
        forceFormData: true,
    });
};

</script>
<template>
    <AppLayout>
        <h1 class="text-center text-5xl">Create Permission</h1>
        <form @submit.prevent="submit" class=" mx-auto">
            <div class="mt-4">
                <InputLabel for="name" value="Name" />
                
                <TextInput v-model="form.name" id="name" type="text" class="mt-1 block w-full" />

                <InputError :message="form.errors.name" class="mt-2"></InputError>
            </div>

            <div>
                <InputLabel for="description" value="Description" />
                
                <TextInput v-model="form.description" id="description" type="text" class="mt-1 block w-full" />

                <InputError :message="form.errors.description" class="mt-2"></InputError>
            </div>

            <div class="mt-4 flex items-center justify-center">
                <PrimaryButton class="ml-4 !text-white !bg-green-400 hover:!bg-green-800" :class="{'opacity-25': form.processing}" :disabled="form.processing">
                    Add Permission
                </PrimaryButton>
            </div>
        </form>
    </AppLayout>
</template>
