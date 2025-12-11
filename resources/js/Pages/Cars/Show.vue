<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, usePage, useForm, router } from '@inertiajs/vue3'
defineProps({
    car: Object
});

const page = usePage()
const user = page.props.auth.user
    
const deletePhoto = (carId, photoId) => {
    router.delete(route("cars.destroyPhoto", {car: carId, photo: photoId}), {
        preserveScroll: true,
        forceFormData: true,
    })
};
    
</script>
<template>
  <AppLayout>
    <div class="flex flex-col items-center py-4 bg-gray-400 pt-6 rounded">
      <div class="text-center text-5xl">
        {{ car.data.manufacture }} {{ car.data.model }}
      </div>
      <div v-if="user.id == car.data.owner.id">
          <Link :href='route("cars.edit", car.data.id)' as="button" class="py-1 px-2 bg-pink-400 border rounded hover:text-white">Edit</Link>
          <Link :href='route("cars.destroy", car.data.id)' method="delete" as="button" class="ml-2 py-1 px-2 bg-red-600 border rounded hover:text-white">Delete</Link>
      </div>
      <div>
        <p>Owner: <b>{{ car.data.owner.name }}</b></p>
      </div>

      <div v-if="car.data.modifications && car.data.modifications.length" class="w-full max-w-3xl p-6 rounded-xl shadow mt-6 bg-gray-600">
        <h2 class="text-center text-3xl font-bold mb-6 text-white">Modifications</h2>

        <div class="space-y-4">
          <div v-for="mod in car.data.modifications" :key="mod.id" class="border border-gray-200 bg-gray-50 p-4 rounded-lg shadow-sm">
            <h3 class="text-xl font-semibold text-gray-800"> {{ mod.name }} </h3>

            <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <p class="text-gray-600 text-sm font-medium">Reason:</p>
                <p class="font-semibold text-gray-800">{{ mod.reason }}</p>
              </div>

              <div>
                <p class="text-gray-600 text-sm font-medium">Description:</p>
                <p class="font-semibold text-gray-800"> {{ mod.description || '—' }} </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-if="car.data.story" class="w-full max-w-3xl p-6 rounded-xl shadow my-6 bg-gray-600">
        <h2 class="text-center text-3xl font-bold mb-6 text-white">Story of the car</h2>
        <div class="prose border border-gray-200 bg-gray-50 p-4 rounded-lg shadow-sm m-auto" v-html="car.data.story.bodyHtml"></div>
      </div>
      //make delete photo button (routes, controller, call from vue)
      <div>
          <div v-for="photo in car.data.photos" :key="photo.id" class="w-full max-w-3xl p-6 rounded-xl shadow my-6 bg-gray-600">
            <img :src="photo.show_url" class="rounded shadow"/>
              //make this DangerButton
            <form @submit.prevent="deletePhoto(car.id, photo.id)" method="delete">
                <button type="submit">Delete photo</button>
            </form>
          </div>
      </div>

    </div>
  </AppLayout>
</template>
