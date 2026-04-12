import { ref, computed, onMounted, onUnmounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { notificationHandlers } from "../notificationsHandlers"

// Module-level state — created once, shared across all composable calls
const notifications = ref([]);
let echoChannel = null;
let echoChannelName = null;
let listenerRegistered = false;

export function useNotifications() {
    const page = usePage();
    const user = page.props.auth.user;

    const unreadNotifications = computed(() =>
        notifications.value.filter(n => !n.read)
    );

    async function fetchUnread() {
        const res = await fetch('/notifications/unread', {
            credentials: "same-origin",
            headers: { 'Accept': 'application/json' },
        });
        const data = await res.json();

        // Map Laravel's notification structure to our shape
        notifications.value = data.map(n => ({
            id: n.id,
            type: n.data.type ?? 'general',
            message: n.data.message,
            time: new Date(n.created_at).toLocaleTimeString(),
            read: false, // these are unread by definition
        }));
    }

    function addNotification(notification) {
        notifications.value.unshift(notification);
    }

    function clearAll() {
        notifications.value = [];
    }

    function getXsrfToken() {
        const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
        return match ? decodeURIComponent(match[1]) : null;
    }


    async function markAsRead(id) {
        // Optimistic update — flip it immediately, revert on failure
        const notif = notifications.value.find(n => n.id === id);
        if (!notif) return;
        notif.read = true;

        const token = getXsrfToken();

        if (!token) {
            console.error('Missing XSRF token');
            notif.read = false;
            return;
        }     

        try {
            await fetch(`/notifications/${id}/read`, {
                method: 'PATCH',
                credentials: "same-origin",
                headers: {
                    'Accept': 'application/json',
                    'X-XSRF-TOKEN': token
                },
            });
        } catch (e) {
            // Revert on failure
            notif.read = false;
            console.error('Failed to mark notification as read', e);
        }
    }

    async function markAllAsRead() {
        // Optimistic update
        const previous = notifications.value.map(n => ({ ...n }));
        notifications.value.forEach(n => n.read = true);

        const token = getXsrfToken();

        if (!token) {
            console.error('Missing XSRF token');
            notifications.value = previous
            return;
        }     
    
        try {
            await fetch('/notifications/read-all', {
                method: 'PATCH',
                credentials: "same-origin",
                headers: {
                    'Accept': 'application/json',
                    'X-XSRF-TOKEN': token
                },
            });
        } catch (e) {
            // Revert on failure
            notifications.value = previous;
            console.error('Failed to mark all notifications as read', e);
        }
    }

    onMounted(async () => {
        if (!user) return;

        await fetch('/sanctum/csrf-cookie', {
            credentials: 'same-origin'
        });

        // Always fetch fresh unread from DB on first mount
        if (!listenerRegistered) {
            await fetchUnread();
        }

        if (listenerRegistered) return;

        echoChannelName = `User.${user.id}.notifications`;
        echoChannel = window.Echo.private(echoChannelName);

        Object.entries(notificationHandlers).forEach(([event, transform]) => {
            echoChannel.listen(event, (e) => {
                addNotification({
                    ...transform(e),
                    time: new Date().toLocaleDateString(),
                    read: false,
                });
            });
        });

        listenerRegistered = true;
    });

    onUnmounted(() => {
        // Do NOT leave the channel here.
        // The channel must live for the entire session, not per component lifecycle.
        // Clean up only on logout via destroyNotifications().
    });

    return {
        notifications,
        unreadNotifications,
        addNotification,
        clearAll,
        markAsRead,
        markAllAsRead,
    };
}

// Call this explicitly on logout to clean up the Echo channel
export function destroyNotifications() {
    if (echoChannel) {
        Object.keys(notificationHandlers).forEach(event => {
            echoChannel.stopListening(event);
        });
        window.Echo.leave(echoChannelName);
        echoChannelName = null;
    }
    listenerRegistered = false;
    notifications.value = [];
}