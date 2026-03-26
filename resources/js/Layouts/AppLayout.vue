<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import NavLink from '@/Components/NavLink.vue';
import { usePage, router, Link } from '@inertiajs/vue3'
import _ from 'lodash';

const page = usePage();
const user = page.props.auth.user;

function logout() {
  router.post(route("logout"));
}

console.log(page.props.auth.mustVerifyEmail);
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
            </div>
        </div>

        <!-- Right Section -->
        <div v-if="user" class="flex items-center space-x-4">
            <NavLink v-if="user" :href='route("profile.edit")' :active='route().current("profile.edit")'>Profile</NavLink>

            <!-- Create new (+) -->
            <Link v-if="$page.props.auth?.can?.createCar" :href='route("cars.create")' class="relative py-1 px-2 bg-pink-400 border rounded hover:text-white">Add Car</Link>

            <form @click="logout">
                <DangerButton class="relative py-1 px-2 border rounded">Log out!</DangerButton>
            </form>

            <!-- Avatar -->

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
        <div v-if="page.props.auth.mustVerifyEmail && user.email_verified_at === null">
            <p class="mt-2 text-sm text-gray-800 dark:text-gray-200">
                Your email address is unverified.
                <Link
                    :href="route('verification.send')"
                    method="post"
                    as="button"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                >
                    Click here to re-send the verification email.
                </Link>
            </p>

            <div
                v-show="status === 'verification-link-sent'"
                class="mt-2 text-sm font-medium text-green-600 dark:text-green-400"
            >
                A new verification link has been sent to your email address.
            </div>
        </div>

      <slot />
    </main>

    <!-- Footer -->
    <footer class="text-center">
      © {{ new Date().getFullYear() }} - DeployYourCar - thepdi
    </footer>
  </div>
</template>
