<script setup>
defineProps({
    car: Object
});
</script>
<template>
    <div class="flex flex-col items-center">
      <div class="text-center text-5xl">
        {{ car.data.manufacture }} {{ car.data.model }}
      </div>
      <div>
        <p>Owner: <b>{{ car.data.owner.name }}</b></p>
      </div>

      <div class="bg-gray-400 p-4 rounded-lg shadow">
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
      <div>
        <h2>Story</h2>
        <p>{{ car.data.story.bodyHtml }}</p>
      </div>
    
    
    </div>
</template>