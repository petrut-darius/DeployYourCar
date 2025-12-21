<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { usePage,  Link } from '@inertiajs/vue3'
import axios from 'axios';

const page = usePage();

const {user} = defineProps({
    user: Object, 
});

const getUserPdf = async (id) => {
  try{
    const response = await axios.get(route("users.pdf", { user: id}), {responseType: "blob"});

    const url = window.URL.createObjectURL(response.data);
    const link = document.createElement('a');

    link.href = url;

    link.setAttribute("download", "user_" + id + "_info.pdf");

    link.click();

    link.remove();
    window.URL.revokeObjectURL(url);
  } catch(e) {
    console.error("PDF download failed: ", e);
  }

};



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
            <Link v-if="$page.props.auth.user?.id !== user.id" :href='route("users.destroy", user.id)' method="delete" as="button" class="ml-2 py-1 px-2 bg-red-600 border rounded hover:text-white">Delete</Link>
        </div>

        <div v-if="user.permissions && user.permissions.length" class="w-full max-w-3xl p-6 rounded-xl shadow mt-6 bg-gray-600">
          <h2 class="text-center text-3xl font-bold mb-6 text-white">Permissions</h2>

          <div class="space-y-4">
            <div v-for="permission in user.permissions" :key="permission.id" class="border border-gray-200 bg-gray-50 p-4 rounded-lg shadow-sm">
              <h3 class="text-xl font-semibold text-gray-800"> {{ permission }} </h3>
            </div>
          </div>
        </div>

        <div v-if="user.groups && user.groups.length" class="w-full max-w-3xl p-6 rounded-xl shadow mt-6 bg-gray-600">
          <h2 class="text-center text-3xl font-bold mb-6 text-white">Groups</h2>

          <div class="space-y-4">
            <div v-for="group in user.groups" :key="group.id" class="border border-gray-200 bg-gray-50 p-4 rounded-lg shadow-sm">
              <h3 class="text-xl font-semibold text-gray-800"> {{ group.name }} </h3>
            </div>
          </div>
        </div>

        <div class="w-full max-w-3xl p-6 rounded-xl shadow mt-6 bg-white">
          <h2 class="text-center text-3xl font-bold mb-6 ">Download User info</h2>

          <p class="text-blue-600 hover:text-blue-800 underline underline-offset-4 transition cursor-pointer" @click="getUserPdf(user.id)">user_info</p>
        </div>
      </div>
    </AppLayout>
</template>