<template>
  <div class="min-h-screen bg-white text-gray-900
              dark:bg-gray-900 dark:text-gray-100">
    <!-- Navbar -->
    <header class="p-4 flex justify-between items-center
                   border-b dark:border-gray-700">
        <h1 class="font-bold text-xl">
            Participants Dashboard
        </h1>
      <button class="px-3 py-1 rounded border dark:border-gray-600"
              @click="isDark = !isDark">
        {{ isDark ? '🌙 Dark' : '☀️ Light' }}
      </button>
    </header>

    <!-- Content slot -->
    <main class="p-6">
      <slot />
    </main>
  </div>
</template>

<script setup lang="ts">
    import { useDarkMode } from '../composables/useDarkMode';

    const { isDark } = useDarkMode();
</script>
