<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    users: Object,
});

</script>
<template>
    <AppLayout>
        <div v-for="user in users">
            {{ user.id }} -> <strong>{{ user.name }}</strong>
        </div>
    </AppLayout>
</template>