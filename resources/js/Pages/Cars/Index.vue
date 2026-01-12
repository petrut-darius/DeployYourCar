<script setup>
import Pagination from '@/Components/Pagination.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { usePage, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue';
import _ from 'lodash';
defineProps({
    cars: Object
})

const page = usePage();
const types = page.props.types ?? []
const tags = page.props.tags ?? []

const q = ref(page.props.filters?.q ?? "")
const selectedTypes = ref(page.props.filters?.types ?? [])
const selectedTags = ref(page.props.filters?.tags ?? [])

const search = _.debounce(() => {
  router.get(
    route('cars.index'),
    {
      q: q.value,
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


watch([q, selectedTypes, selectedTags], search)

</script>

<template>
    <AppLayout>
        <!-- page heading-->

        <div class="relative mx-auto">
            <div class=" rounded-lg p-3 shadow-lg space-y-3 gap-4 flex">

                <input
                v-model="q"
                type="text"
                placeholder="Search car name…"
                class="w-full rounded-md   text-sm px-3 py-2
                        placeholder-gray-400 focus:ring-2  focus:outline-none"
                />

                <div>
                <p class="text-xs uppercase tracking-wide mb-1">Types</p>
                <div class="flex flex-wrap gap-2">
                    <label v-for="type in types" :key="type.id"
                        class="flex items-center gap-1 px-2 py-1 rounded-md text-xs cursor-pointer hover:bg-gray-600">
                    <input type="checkbox" class="accent-pink-400" :value="type.id" v-model="selectedTypes" />
                    {{ type.name }}
                    </label>
                </div>
                </div>

                <div>
                <p class="text-xs uppercase tracking-wide mb-1">Tags</p>
                <div class="flex flex-wrap gap-2">
                    <label v-for="tag in tags" :key="tag.id"
                        class="flex items-center gap-1 px-2 py-1 rounded-md text-xs cursor-pointer hover:bg-gray-600">
                    <input type="checkbox" class="accent-pink-400" :value="tag.id" v-model="selectedTags" />
                    {{ tag.name }}
                    </label>
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
                    Engine: {{ car.engineCode }} ({{ car.displacement }}L)
                </p>

                <p class="text-sm text-gray-600">
                    WHP: {{ car.whp }}
                </p>

                <p class="text-sm text-gray-600">
                    Color: {{ car.color }}
                </p>

                <!-- Owner -->
                <div v-if="car.owner" class="mt-2">
                    <p class="font-semibold">Owner:</p>
                    <p>{{ car.owner.name }}</p>
                </div>
                <!--

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
                -->
            </div>
            <div class="flaot-end">
                <Pagination class="mt-4" :links="cars.meta.links"/>
            </div>
        </div>
    </AppLayout>
</template>
