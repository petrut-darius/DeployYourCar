<script setup>
import { ref, computed } from 'vue';
import { useNotifications } from '@/Composables/useNotifications';

const { notifications, unreadNotifications, clearAll, removeNotification, markAsRead, markAllAsRead } = useNotifications();
const showDropdown = ref(false);

const unreadCount = computed(() => unreadNotifications.value.length);

function toggleDropdown() {
    showDropdown.value = !showDropdown.value;
}

function handleClearAll() {
    clearAll();
    showDropdown.value = false;
}
</script>

<template>
    <div class="relative">
        <!-- Bell Button -->
        <button
            @click="toggleDropdown"
            class="relative hover:text-white transition-colors duration-150"
            aria-label="Notifications"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>

            <!-- Badge -->
            <span
                v-if="unreadCount > 0"
                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center"
            >
                {{ unreadCount > 9 ? '9+' : unreadCount }}
            </span>
        </button>

        <!-- Dropdown -->
        <Transition
            enter-active-class="transition ease-out duration-150"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-100"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-if="showDropdown"
                class="absolute right-0 mt-2 w-80 bg-white text-gray-800 rounded-lg shadow-xl z-50 origin-top-right"
            >
                <!-- Header -->
                <div class="flex items-center justify-between px-4 py-3 border-b">
                    <span class="font-semibold text-sm">Notifications</span>
                    <div class="flex items-center gap-3">
                        <button
                            v-if="unreadCount > 0"
                            @click="markAllAsRead"
                            class="text-xs text-blue-400 hover:text-blue-600 transition-colors"
                        >
                            Mark all as read
                        </button>
                        <button
                            v-if="notifications.length > 0"
                            @click="handleClearAll"
                            class="text-xs text-gray-400 hover:text-red-500 transition-colors"
                        >
                            Clear all
                        </button>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="unreadNotifications.length === 0" class="p-6 text-sm text-gray-400 text-center">
                    <svg class="w-8 h-8 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    You're all caught up!
                </div>

                <!-- Notification List — only unread -->
                <ul v-else class="max-h-72 overflow-y-auto divide-y divide-gray-100">
                    <li
                        v-for="notif in unreadNotifications"
                        :key="notif.id"
                        class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition-colors"
                    >
                        <!-- Icon by type -->
                        <div class="mt-0.5 shrink-0">
                            <span v-if="notif.type == 'newFollower'" class="text-pink-400 text-lg">👤</span>
                            <span v-else class="text-blue-400 text-lg">🔔</span>
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-700">{{ notif.message }}</p>
                            <span class="text-xs text-gray-400">{{ notif.time }}</span>
                        </div>

                        <!-- Mark as read -->
                        <button
                            @click="markAsRead(notif.id)"
                            class="text-gray-300 hover:text-blue-400 transition-colors shrink-0"
                            aria-label="Mark as read"
                            title="Mark as read"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                    </li>
                </ul>
            </div>
        </Transition>

        <!-- Backdrop to close dropdown -->
        <div
            v-if="showDropdown"
            class="fixed inset-0 z-40"
            @click="showDropdown = false"
        />
    </div>
</template>