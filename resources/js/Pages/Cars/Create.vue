<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Tiptap from '@/Components/Tiptap.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3'


const page = usePage()
const user = page.props.auth.user
const tags = page.props.tags
const types = page.props.types

const form = useForm({
    manufacture: "",
    model: "",
    displacement: "",
    engineCode: "",
    whp: "",
    color: "",
    tags: [],
    types: [],
    story: "Write your cars story, how did you got it?",
    media: null
});

const submit = () => {
    form.post(route("cars.store"), {
        preserveScroll: true,
        forceFormData: true,
    });
};


</script>
<template>
    <AppLayout>
        <form @submit.prevent="submit" class=" mx-auto">
            <div class="mt-4">
                <InputLabel for="manufacture" value="Manufacture" />
                
                <TextInput v-model="form.manufacture" id="manufacture" type="text" class="mt-1 block w-full" />

                <InputError :message="form.errors.manufacture" class="mt-2"></InputError>
            </div>

            <div>
                <InputLabel for="model" value="Model" />
                
                <TextInput v-model="form.model" id="model" type="text" class="mt-1 block w-full" />

                <InputError :message="form.errors.model" class="mt-2"></InputError>
            </div>

            <div>
                <InputLabel for="displacement" value="Displacement (L)" />
                
                <TextInput v-model="form.displacement" id="displacement" type="number" class="mt-1 block w-full" step="0.1" min="0.1"/>

                <InputError :message="form.errors.displacement" class="mt-2"></InputError>
            </div>

            <div class="mt-4">
                <InputLabel for="engineCode" value="Engine Code" />
                
                <TextInput v-model="form.engineCode" id="engineCode" type="text" class="mt-1 block w-full" />

                <InputError :message="form.errors.engineCode" class="mt-2"></InputError>
            </div>

            <div class="mt-4">
                <InputLabel for="whp" value="Wheel Horsepower (WHP)" />
                
                <TextInput v-model="form.whp" id="whp" type="number" class="mt-1 block w-full" min="1"/>

                <InputError :message="form.errors.whp" class="mt-2"></InputError>
            </div>

            <div class="mt-4">
                <InputLabel for="color" value="Color" />
                
                <TextInput v-model="form.color" id="color" type="text" class="mt-1 block w-full" />

                <InputError :message="form.errors.color" class="mt-2"></InputError>
            </div>

            <div class="mt-4">
                <InputLabel for="tags" value="Tags" />
                
                <select v-model="form.tags" id="tags" multiple class="mt-1 block w-full max-h-40 overflow-y-auto border rounded">
                    <option v-for="tag in tags" :key="tag.id" :value="tag.id" class="text-center rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:text-black dark:focus:border-blue-600 dark:focus:ring-blue-600 p-1">
                        {{ tag.name }}
                    </option>
                </select>

                <InputError :message="form.errors.tags" class="mt-2"></InputError>
            </div>
            
            <div>
                <InputLabel for="types" value="Types" />
                
                <select v-model="form.types" id="types" multiple class="mt-1 block w-full max-h-40 overflow-y-auto border rounded">
                    <option v-for="type in types" :key="type.id" :value="type.id" class="text-center rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:text-black dark:focus:border-blue-600 dark:focus:ring-blue-600 p-1">
                        {{ type.name }}
                    </option>
                </select>

                <InputError :message="form.errors.types" class="mt-2"></InputError>
            </div>

            <div class="mt-4">
                <InputLabel for="story" value="Story" />
                
                <Tiptap v-model="form.story" />

                <InputError :message="form.errors.story" class="mt-2"/>
            </div>

            <div class="mt-4">
                <InputLabel for="photos" value="Photos (must be multiple)"></InputLabel>

                <input
                    type="file"
                    multiple
                    @change="e => form.media = e.target.files"
                    class="block mt-1"
                    rules="mimes:jpg"
                />
        
                <InputError :message="form.errors.photos" class="mt-2"/>
            </div>

            <div class="mt-4 flex items-center justify-center">
                <PrimaryButton class="ml-4 !text-white !bg-green-400 hover:!bg-green-800" :class="{'opacity-25': form.processing}" :disabled="form.processing">
                    Add Car
                </PrimaryButton>
            </div>
        </form>
    </AppLayout>
</template>
