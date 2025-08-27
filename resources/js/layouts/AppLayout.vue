<template>
  <div :class="['min-h-screen', isDark ? 'bg-gray-900 text-white' : 'bg-white text-gray-900']">
    <!-- Navbar -->
    <header class="p-4 flex justify-between items-center border-b">
        <h1 class="font-bold text-xl">
            Participants Dashboard
        </h1>

        <nav class="flex space-x-4 items-center">
            <!-- Links -->
            <button class="px-3 py-1 rounded border hover:bg-gray-200 dark:hover:bg-gray-800 hover:bg-gray-200 dark:hover:text-black"
                    @click="goTo('registrations')">
                Dashboard
            </button>
            <button class="px-3 py-1 rounded border hover:bg-gray-200
                        dark:hover:bg-gray-800"
                    @click="goTo('profile')">
                Profile
            </button>
            <button class="px-3 py-1 rounded border hover:bg-gray-200
                        dark:hover:bg-gray-800"
                    @click="logout">
                Logout
            </button>

            <!-- Dark Mode Toggle -->
            <button class="px-3 py-1 rounded border"
                    @click="isDark = !isDark">
                {{ isDark ? '🌙 Dark' : '☀️ Light' }}
            </button>
        </nav>
    </header>

    <!-- Content slot -->
    <main class="p-6">
      <slot />
    </main>
  </div>
</template>

<script setup lang="ts">
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3';
    import { useDarkMode } from '../composables/useDarkMode';

    const { isDark } = useDarkMode();

    // Navigate to a route by name
    const goTo = (routeName: string) => {
        router.get(routeName);
    };

    // Logout using Inertia
    const logout = () => {
        router.post('/logout');
    };
</script>
