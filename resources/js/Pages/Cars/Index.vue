<script setup>
defineProps({
    cars: Array
})
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <div v-for="car in cars.data" :key="car.id"class="p-4 rounded-lg shadow bg-white">
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

    </div>
</template>
