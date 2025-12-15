<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { usePage,  Link } from '@inertiajs/vue3'

const page = usePage();

const {user} = defineProps({
    user: Object, 
});

</script>
<template>
    <AppLayout>
    <div class="flex flex-col items-center py-4 bg-gray-400 pt-6 rounded">
      <div class="text-center text-5xl">
        {{ user.name }}
      </div>

      <div class="text-center text-xl">
        Email: {{ user.email }}
      </div>

      <div v-if="$page.props.auth?.can?.manageUsers">
          <Link :href='route("users.edit", user.id)' as="button" class="py-1 px-2 bg-pink-400 border rounded hover:text-white">Edit</Link>
          <Link :href='route("users.destroy", user.id)' method="delete" as="button" class="ml-2 py-1 px-2 bg-red-600 border rounded hover:text-white">Delete</Link>
      </div>

      <div v-if="user.permissions && user.permissions.length" class="w-full max-w-3xl p-6 rounded-xl shadow mt-6 bg-gray-600">
        <h2 class="text-center text-3xl font-bold mb-6 text-white">Abilities</h2>

        <div class="space-y-4">
          <div v-for="permission in user.permissions" :key="permission" class="border border-gray-200 bg-gray-50 p-4 rounded-lg shadow-sm">
            <h3 class="text-xl font-semibold text-gray-800"> {{ permission }} </h3>
          </div>
        </div>
      </div>

    </div>
    </AppLayout>
</template>