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

// Search
const searchQuery = ref("");
const searchResults = ref([]);
const tagsList = ref([]);
const typesList = ref([]);
const selectedTags = ref([]);
const selectedTypes = ref([]);
const dropdownOpen = ref(false);

// Fetch filters
const fetchFilters = async () => {
  try {
    const response = await axios.get(route("cars.filters"));
    tagsList.value = response.data.tags;
    typesList.value = response.data.types;
  } catch (error) {
    console.error("Error fetching filters:", error);
  }
};

onMounted(() => {
  fetchFilters();
});

// Debounced search
const fetchSearchResults = _.debounce(async () => {
  if (
    searchQuery.value.trim() === "" &&
    selectedTags.value.length === 0 &&
    selectedTypes.value.length === 0
  ) {
    searchResults.value = [];
    return;
  }

  try {
    const response = await axios.get(route("cars.search"), {
      params: {
        q: searchQuery.value,
        tags: selectedTags.value,  // use IDs
        types: selectedTypes.value // use IDs
      },
    });
    searchResults.value = response.data;
    dropdownOpen.value = true;
  } catch (error) {
    console.error("Search error:", error);
    searchResults.value = [];
    dropdownOpen.value = false;
  }
}, 300);

watch([searchQuery, selectedTags, selectedTypes], fetchSearchResults);

// Computed to show dropdown
const showDropdown = computed(() => {
  return dropdownOpen.value && (searchResults.value.length > 0 || selectedTags.value.length > 0 || selectedTypes.value.length > 0);
});

// Close dropdown when clicking outside
const onClickOutside = (event) => {
  if (!event.target.closest("#search-dropdown")) {
    dropdownOpen.value = false;
  }
};
onMounted(() => {
  document.addEventListener("click", onClickOutside);
});
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
                <NavLink :href='route("cars.index")' :active="route().current('cars.explore')">Explore</NavLink>
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
            <div
            v-if="showDropdown"
            id="search-dropdown"
            class="absolute top-full left-0 mt-1 p-3 bg-gray-700 rounded-lg shadow-md flex flex-col gap-3 w-full z-20"
            >
            <!-- Tags -->
            <div class="flex flex-wrap gap-2">
                <span class="font-semibold text-gray-200">Tags:</span>
                <span
                v-for="tag in tagsList"
                :key="tag.id"
                class="flex items-center bg-gray-600 text-gray-200 rounded-full px-2 py-1 text-xs hover:bg-blue-500 cursor-pointer transition-colors"
                >
                <input
                    type="checkbox"
                    :value="tag.id"
                    v-model="selectedTags"
                    class="mr-1 w-3 h-3 accent-blue-500"
                />
                {{ tag.name }}
                </span>
            </div>

            <!-- Types -->
            <div class="flex flex-wrap gap-2">
                <span class="font-semibold text-gray-200">Types:</span>
                <span
                v-for="type in typesList"
                :key="type.id"
                class="flex items-center bg-gray-600 text-gray-200 rounded-full px-2 py-1 text-xs hover:bg-blue-500 cursor-pointer transition-colors"
                >
                <input
                    type="checkbox"
                    :value="type.id"
                    v-model="selectedTypes"
                    class="mr-1 w-3 h-3 accent-blue-500"
                />
                {{ type.name }}
                </span>
            </div>

            <!-- Search Results -->
            <div v-if="searchResults.length > 0" class="max-h-64 overflow-auto border border-gray-600 rounded mt-2">
                <div
                v-for="car in searchResults"
                :key="car.id"
                class="p-2 hover:bg-gray-600 cursor-pointer"
                >
                <Link :href="`/cars/${car.id}`" class="block text-white">
                    {{ car.manufacture }} {{ car.model }}
                </Link>
                <small class="text-gray-400 block">
                    Modifications: {{ car.modifications.map(m => m.name).join(', ') }}
                </small>
                </div>
            </div>

            <div v-else-if="searchResults.length === 0 && (searchQuery || selectedTags.length || selectedTypes.length)" class="p-2 text-gray-400">
                No cars found
            </div>
            </div>
        </div>
        </div>

        <!-- Right Section -->
        <div v-if="user" class="flex items-center space-x-4">

            <!-- Create new (+) -->
            <Link :href='route("cars.create")' class="relative py-1 px-2 bg-pink-400 border rounded hover:text-white">Add Car</Link>

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
