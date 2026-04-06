<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ReplyItem from '@/Components/ReplyItem.vue';
import TextInput from '@/Components/TextInput.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

const {car, isLiking} = defineProps({
    car: Object,
    isLiking: Boolean,
});

const page = usePage()

const formForCar = useForm({
  content: "",
})

const submitForCar = () => {
  formForCar.post(route("replies.storeForCar", {car: car.data.id}), {
    preserveScroll: true,
    forceFormData: true,
  })
}

const formForLike = useForm({});
</script>
<template>
  <AppLayout>
    <div class="flex flex-col items-center py-4 bg-gray-400 pt-6 rounded">
      <div class="text-center text-5xl">
        {{ car.data.manufacture }} {{ car.data.model }}
      </div>
      <div v-if="page.props.auth.user">
          <Link v-if="page.props.can?.update" :href='route("cars.edit", car.data.id)' as="button" class="py-1 px-2 bg-pink-400 border rounded hover:text-white">Edit</Link>
          <Link v-if="page.props.can?.delete" :href='route("cars.destroy", car.data.id)' method="delete" as="button" class="ml-2 py-1 px-2 bg-red-600 border rounded hover:text-white">Delete</Link>
      </div>
      <div>
        <p>Owner: <b>{{ car.data.owner.name }}</b></p>
      </div>
      <div v-if="isLiking && page.props.auth.user">
          <button @click="formForLike.delete(route('likeable.destroyForCar', { car: car.data.id }))" class="py-1 px-2 bg-blue-400 border rounded hover:text-white">Dislike</button>
      </div>

      <div v-else>
          <button @click="formForLike.post(route('likeable.storeForCar', { car: car.data.id }))" class="py-1 px-2 bg-blue-400 border rounded hover:text-white">Like</button>
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

      <div v-if="car.data.tags?.length" class="w-full max-w-3xl p-6 rounded-xl shadow mt-6 bg-gray-600">
          <h2 class="text-center text-3xl font-bold mb-6 text-white">Tags</h2>
          <div class="border border-gray-200 bg-gray-50 p-4 rounded-lg shadow-sm">
              <span v-for="tag in car.data.tags" :key="tag.id" class="px-2 py-1 text-xs bg-gray-200 rounded">{{ tag.name }}</span>
          </div>
      </div>

      <div v-if="car.data.types?.length" class="w-full max-w-3xl p-6 rounded-xl shadow mt-6 bg-gray-600">
          <h2 class="text-center text-3xl font-bold mb-6 text-white">Types</h2>
          <div class="border border-gray-200 bg-gray-50 p-4 rounded-lg shadow-sm">
              <span v-for="type in car.data.types" :key="type.id" class="px-2 py-1 text-xs bg-blue-200 rounded">{{ type.name }}</span>
          </div>
      </div>

      <div v-if="car.data.story" class="w-full max-w-3xl p-6 rounded-xl shadow my-6 bg-gray-600">
        <h2 class="text-center text-3xl font-bold mb-6 text-white">Story of the car</h2>
        <div class="prose border border-gray-200 bg-gray-50 p-4 rounded-lg shadow-sm m-auto" v-html="car.data.story.bodyHtml"></div>
      </div>
      <div>
          <div v-for="photo in car.data.photos" :key="photo.id" class="w-full max-w-3xl p-6 rounded-xl shadow my-6 bg-gray-600">
            <img :src="photo.original_url" class="rounded shadow"/>
          </div>
      </div>
      <div>
          <h2>Comments & Questions</h2>
          <div v-if="car.data.replies?.length">
            <ReplyItem v-for="reply in car.data.replies" :key="reply.id" :reply="reply"/>
          </div>
          <div v-else>
            <p>There are no comments or questions, at this moment!</p>
          </div>
          <form @submit.prevent="submitForCar">
            <div>
              <InputLabel for="content" value="What do you think about this car?" />

              <TextInput v-model="formForCar.content" id="content" type="text" class="mt-1 block w-full" />

              <InputError :message="formForCar.errors.content" class="mt-2" />

              <PrimaryButton class="!text-white !bg-green-400 hover:!bg-green-800" :class="{'opacity-25': formForCar.processing}" :disabled="formForCar.processing">
                Send
              </PrimaryButton>
            </div>
          </form>
      </div>
    </div>
  </AppLayout>
</template>
