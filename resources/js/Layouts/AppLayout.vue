<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import NavLink from '@/Components/NavLink.vue';
import { usePage, router, Link } from '@inertiajs/vue3'

const page = usePage()
const user = page.props.auth.user

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
                <NavLink v-if="user" :href='route("cars.index")' :active="route().current('cars.index')">Your Cars</NavLink>
                <NavLink :href='route("cars.index")' :active="route().current('cars.explore')">Explore</NavLink>
            </div>

            <!-- Search -->
            <div class="hidden md:block">
            <input
                type="text"
                placeholder="Search or jump to…"
                class="bg-gray-800 border border-gray-700 rounded-md px-3 py-1 text-sm placeholder-gray-400 focus:ring-2 focus:ring-blue-600 focus:outline-none w-64"
            />
            </div>
        </div>

        <!-- Right Section -->
        <div v-if="user" class="flex items-center space-x-4">

            <!-- Create new (+) -->
            <button class="relative py-1 px-2 bg-pink-400 border rounded hover:text-white">
                Add Car
            </button>

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
