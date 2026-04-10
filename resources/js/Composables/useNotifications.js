import { ref, computed, onMounted, onUnmounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

// Module-level state — created once, shared across all composable calls
const notifications = ref([]);
let echoChannel = null;
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

    async function markAsRead(id) {
        // Optimistic update — flip it immediately, revert on failure
        const notif = notifications.value.find(n => n.id === id);
        if (!notif) return;
        notif.read = true;

        try {
            await fetch(`/notifications/${id}/read`, {
                method: 'PATCH',
                credentials: "same-origin",
                headers: {
                    'Accept': 'application/json',
                    'X-XSRF-TOKEN': decodeURIComponent(
                        document.cookie
                            .split('XSRF-TOKEN=')[1]
                            .split(';')[0]
                    )
                },
            });

            console.log(page.props.csrf_token);
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

        try {
            await fetch('/notifications/read-all', {
                method: 'PATCH',
                credentials: "same-origin",
                headers: {
                    'Accept': 'application/json',
                    'X-XSRF-TOKEN': decodeURIComponent(
                        document.cookie
                            .split('XSRF-TOKEN=')[1]
                            .split(';')[0]
                    )
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

        // Always fetch fresh unread from DB on first mount
        if (!listenerRegistered) {
            await fetchUnread();
        }

        if (listenerRegistered) return;

        echoChannel = window.Echo.private(`User.${user.id}.notifications`);

        echoChannel.listen('.NewFollowerEvent', (e) => {
            addNotification({
                id: e.id, // use the real DB id from the event payload
                type: 'follower',
                message: `${e.follower.name} started following you!`,
                time: new Date().toLocaleTimeString(),
                read: false,
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
        echoChannel.stopListening('.NewFollowerEvent');
        window.Echo.leave(echoChannel.name);
        echoChannel = null;
    }
    listenerRegistered = false;
    notifications.value = [];
}