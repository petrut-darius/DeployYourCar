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
const car = page.props.car
const tags = page.props.tags
const types = page.props.types

const form = useForm({
    manufacture: car.data.manufacture ?? "",
    model: car.data.model ?? "",
    displacement: car.data.displacement.toString() ?? "",
    engineCode: car.data.engineCode ?? "",
    whp: car.data.whp.toString() ?? "",
    color: car.data.color ?? "",
    tags: car.data.tags?.map(t => t.id) ?? [],
    types: car.data.types?.map(t => t.id) ?? [],
    story: car.data.story.bodyHtml ?? "plm",
    photos: [],
    modifications: car.data.modifications ?? [
        { name: "", description: "", reason: "" }
    ]
});

const addModification = () => {
    form.modifications.push({
        name: "",
        description: "",
        reason: ""
    });
};

//ref -> o variabila care ia valoarea atuncia nu dupa submit
//computed -> o variabila calculata din alte variabile, care odata ce vede ca o variabila s-o schimbat e updata si ea

//un ref pt area pe care o editezi ca sa poti sa dai focus
//o computed ca sa poti sa zici daca o valoare este editata
//functia insine de patch pt click
//functie pt cancelEdit la orice
//functie pt aratarea valorii vechi la orice


//o ba gen, tre sa fac o functie care sa vada cand am schimbat valoarea astfel incat sa stie sa o baga in parametrii noi ai folmularului
//o functie care sa stearga o modificare
//o functie care sa 

</script>
<template>
    <AppLayout>//sa poti sa editezi doar modificarile culoarea whp codmotor litraj tags types story poze
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

            <div>
                <InputLabel value="Modifications" />

                <div v-for="(mod, index) in form.modifications" :key="index" class="border border-gray-200 bg-white shadow-sm p-6 mb-6 rounded-xl space-y-4">
                    <h3 class="text-lg font-semibold text-gray-700">
                        Modification {{ index + 1 }}
                    </h3>

                    <div>
                        <InputLabel :for="'name-' + index" value="Modification Name" />
                        <TextInput type="text" v-model="mod.name" class="mt-1 block w-full" :id="'name-' + index"/>
                        <InputError :message="form.errors[`modifications.${index}.name`]" />
                    </div>

                    <div>
                        <InputLabel :for="'description-' + index" value="Description" />
                        <TextInput type="text" v-model="mod.description" class="mt-1 block w-full" :id="'description-' + index"/>
                        <InputError :message="form.errors[`modifications.${index}.description`]" />
                    </div>

                    <div>
                        <InputLabel :for="'reason-' + index" value="Reason" />
                        <TextInput type="text" v-model="mod.reason" class="mt-1 block w-full" :id="'reason-' + index"/>
                        <InputError :message="form.errors[`modifications.${index}.reason`]" />
                    </div>
                </div>


                <button type="button" @click="addModification" class="btn btn-primary ml-2 text-purple-400">
                    Add another modification
                </button>
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
                <InputLabel for="photos" value="Photos (must be multiple, and a good quality)"></InputLabel>

                <input
                    type="file"
                    multiple
                    @change="e => form.photos = Array.from(e.target.files)"
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
