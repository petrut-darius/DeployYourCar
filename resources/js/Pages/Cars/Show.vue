<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    car: Object
});
</script>
<template>
  <AppLayout>
    <div class="flex flex-col items-center py-4 bg-gray-400">
      <div class="text-center text-5xl">
        {{ car.data.manufacture }} {{ car.data.model }}
      </div>
      <div>
        <p>Owner: <b>{{ car.data.owner.name }}</b></p>
      </div>

      <div v-if="car.data.modifications && car.data.modifications.length" class="bg-gray-400 p-4 rounded-lg shadow">
        <h2 class="text-center text-2xl">Modifications</h2>

        <li v-for="mod in car.data.modifications" :key="mod.id" class="list-none">
          <div>
            <p>Name: <b>{{ mod.name }}</b></p>
            <div>
              <p>Reason: <b>{{ mod.reason }}</b></p>
              <p>Description: <b>{{ mod.description }}</b></p>
            </div>
          </div>
        </li>
      </div>
      <div v-if="car.data.story">
        <div class="prose max-w-none" v-html="car.data.story.bodyHtml"></div>
      </div>
      <div>
          <div v-for="photo in car.data.photos" :key="photo.id" class="mt-2">
            <img :src="photo.original_url" class="rounded shadow"/>
          </div>
      </div>

    </div>
  </AppLayout>
</template>