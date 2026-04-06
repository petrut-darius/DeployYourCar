<script setup>
import { toastStore } from '@laravel-inertia-toast/vue';

function startTimer(toast) {
  const duration = toast.duration ?? toastStore.config.duration;

  if(duration > 0) {
    setTimeout(() => toastStore.removeToast(toast.id), duration);
  }
}

</script>

<template>
  <div class="top-4 right-4 z-50 flex flex-col gap-3 float-end">
    <TransitionGroup name="toast">
      <div
        v-for="toast in toastStore.items"
        :key="toast.id"
        @vue:mounted="startTimer(toast)"
        class="w-40 flex"
        :class="{
          'bg-green-500': toast.level === 'success',
          'bg-red-500': toast.level === 'error',
          'bg-yellow-500': toast.level === 'warning',
          'bg-blue-500': toast.level === 'info',
        }"
      >
        <p v-if="toast.title" class="font-bold">{{ toast.title }}</p>
        <p>{{ toast.message }}</p>
        <button @click="toastStore.removeToast(toast.id)">✕</button>
      </div>
    </TransitionGroup>
  </div>
</template>

<style scoped>
.toast-enter-from { opacity: 0; transform: translateX(100%); }
.toast-enter-active { transition: all 0.3s ease; }
.toast-leave-to { opacity: 0; transform: translateX(100%); }
.toast-leave-active { transition: all 0.2s ease; }
</style>