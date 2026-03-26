<script setup>
import Pagination from '@/Components/Pagination.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { usePage, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue';
import axios from 'axios';
import _ from 'lodash';

defineProps({
    cars: Object
})

const page = usePage();
const types = page.props.types ?? []
const tags = page.props.tags ?? []

const carName= ref(page.props.filters?.carName ?? "")
const selectedTypes = ref(page.props.filters?.types ?? [])
const selectedTags = ref(page.props.filters?.tags ?? [])

const search = _.debounce(() => {
  router.get(
    route('cars.index'),
    {
      carName: carName.value,
      types: selectedTypes.value.map(Number),
      tags: selectedTags.value.map(Number),
    },
    {
      preserveState: true,
      replace: true,
      preserveScroll: true,
    }
  )
}, 400)

const isActiveTags = ref(false);
const isActiveTypes = ref(false);

const showTags = (() => {
    isActiveTags.value = !isActiveTags.value;
});

const showTypes = (() => {
    isActiveTypes.value = !isActiveTypes.value;
});

watch([carName, selectedTypes, selectedTags], search, {deep: true})

</script>

<template>
    <AppLayout>
        <!-- page heading-->
        <div class="mx-auto w-auto">
            <div>
                <input type="text" name="carName" v-model="carName" placeholder="car name?" class="rounded-md">

                <div>
                    <p v-on:click="showTypes" class="cursor-pointer">Types?</p>
                    <div :class="isActiveTypes ? 'block' : 'hidden'">
                        <span v-for="type in types" class="m-1" :key="type.id">
                            <input type="checkbox" :value="type.id" v-model="selectedTypes">
                            {{ type.name }}
                        </span>
                    </div>
                </div>
                <div>
                    <p v-on:click="showTags" class="cursor-pointer">Tags?</p>
                    <div :class="isActiveTags ? 'block' : 'hidden'">
                        <span v-for="tag in tags" class="mt-1" :key="tag.id">
                            <input type="checkbox" :value="tag.id" v-model="selectedTags">
                            {{ tag.name }}
                        </span>
                    </div>
                </div>
            </div>

        </div>

        <div class="flex flex-col items-center">
            <div v-for="car in cars.data" :key="car.id" class="p-4 rounded-lg shadow bg-gray-400 flex flex-col justify-center w-4/6 my-2 ">
                <h2 class="text-xl font-semibold">
                    <a :href='route("cars.show", { car: car.id})'>{{ car.manufacture }} {{ car.model }}</a>
                </h2>

                <p class="text-sm text-gray-600">
                    Engine: {{ car.engineCode }} ({{ car.displacement }} cm&#179;)
                </p>

                <p class="text-sm text-gray-600">
                    WHP: {{ car.whp }}
                </p>

                <p class="text-sm text-gray-600">
                    Color: {{ car.color }}
                </p>

                <div v-if="car.owner" class="mt-2">
                    <p class="font-semibold">Owner:</p>
                    <p>{{ car.owner.name }}</p>
                </div>

                <div v-if="car.photos.length != 0" class="mt-2">
                    <span>Photos:</span>
                    <div v-for="photo in car.photos" :key="photo.id">
                        <img :src="photo.show_url" width="200">
                    </div>
                </div>

                <div v-if="car.tags?.length" class="mt-2">
                    <p class="font-semibold">Tags:</p>
                    <div class="flex flex-wrap gap-1">
                        <span
                            v-for="tag in car.tags"
                            :key="tag.id"
                            class="px-2 py-1 text-xs bg-gray-200 rounded"
                        >
                            {{ tag.name }}
                        </span>
                    </div>
                </div>

                <div v-if="car.types?.length" class="mt-2">
                    <p class="font-semibold">Types:</p>
                    <div class="flex flex-wrap gap-1">
                        <span
                            v-for="type in car.types"
                            :key="type.id"
                            class="px-2 py-1 text-xs bg-blue-200 rounded"
                        >
                            {{ type.name }}
                        </span>
                    </div>
                </div>

            </div>
            <div class="float-end">
                <Pagination class="mt-4" :links="cars.meta.links"/>
            </div>
        </div>
    </AppLayout>
</template>
