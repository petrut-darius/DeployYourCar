<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import NavLink from '@/Components/NavLink.vue';
import { usePage, router, Link } from '@inertiajs/vue3'
import { ref, onMounted, watch, computed } from 'vue';
import axios from 'axios';
import _ from 'lodash';

const page = usePage();
const user = page.props.auth.user;

function logout() {
  router.post(route("logout"));
}
</script>

<template>
  <div>
            <!-- Navbar -->
    <nav class="bg-gray-600 text-gray-200 py-4 flex items-center justify-evenly">
        <!-- Left Section -->
        <div class="flex items-center space-x-4">

            <!-- Desktop Nav Links -->
            <div class="hidden md:flex items-center space-x-4">
                <NavLink :href='route("cars.index")' :active="route().current('cars.index')">Cars</NavLink>
                <NavLink v-if="user" :href='route("cars.yourCars")' :active="route().current('cars.yourCars')">Your Cars</NavLink>
                <NavLink v-if="$page.props.auth?.can?.manageUsers" :href="route('users.index')" :active="route().current('users.index')">Users</NavLink>
                <NavLink v-if="$page.props.auth?.can?.manageUsers" :href="route('permissions.index')" :active="route().current('permissions.index')">Permissions</NavLink>
                <NavLink v-if="$page.props.auth?.can?.manageUsers" :href="route('groups.index')" :active="route().current('groups.index')">Groups</NavLink>
            </div>

        <div class="relative w-64">
            <!-- Search input -->
            <input
            v-model="searchQuery"
            type="text"
            placeholder="Search cars..."
            class="w-full px-4 py-2 rounded-lg bg-gray-800 border border-gray-700 text-sm text-gray-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            @focus="dropdownOpen = true"
            />

            <!-- Dropdown -->
          
        </div>
        </div>

        <!-- Right Section -->
        <div v-if="user" class="flex items-center space-x-4">

            <!-- Create new (+) -->
            <Link v-if="$page.props.auth?.can?.createCar" :href='route("cars.create")' class="relative py-1 px-2 bg-pink-400 border rounded hover:text-white">Add Car</Link>

            <form @click="logout">
                <DangerButton class="relative py-1 px-2 border rounded">Log out!</DangerButton>
            </form>

            <!-- Avatar -->
            <img
            class="w-8 h-8 rounded-full border border-gray-700"
            src="https://avatars.githubusercontent.com/u/1?v=4"
            alt="User Avatar"
            />

            <!-- Mobile Menu -->
            <button class="md:hidden hover:text-white">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            </button>
        </div>
        <div v-else class="flex items-center space-x-4">
            <Link class="relative py-1 px-2 border rounded hover:text-white hover:bg-pink-500" href="/login">Log in</Link>
            <Link class="relative py-1 px-2 border rounded hover:text-white hover:bg-pink-500" href="/register">Register</Link>
        </div>

    </nav>


    <!-- Conținutul paginii -->
    <main class="m-4 border rounded w-3/5 mx-auto p-4">

      <slot />
    </main>

    <!-- Footer -->
    <footer class="text-center">
      © 2025 - DeployYourCar - thepdi
    </footer>
  </div>
</template>
