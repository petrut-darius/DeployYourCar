<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: [ String, Number],
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:text-black dark:focus:border-blue-600 dark:focus:ring-blue-600"
        v-model="model"
        ref="input"
    />
</template>
