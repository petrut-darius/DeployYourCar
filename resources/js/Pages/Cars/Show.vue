<script setup>
defineProps({
    car: Object
});
</script>
<template>
    {{ car.data.owner.id }}
    <li v-for="mod in car.data.modifications" :key="mod.id">
      {{ mod.name }}, {{ mod.reason }}, {{ mod.description }}
    </li>
    {{ car.data.whp }}
    {{ car.data.color}}
</template>