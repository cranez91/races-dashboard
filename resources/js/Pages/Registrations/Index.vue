<template>
  <AppLayout>
    <div class="p-6">
      <!-- Filters -->
      <div class="flex space-x-4 mb-6">
        <select class="border rounded p-2 dark:text-black"
                id="lstRaces"
                v-model="raceId">
          <option value="">All Races</option>
          <option :key="race.id"
                  :value="race.id"
                  v-for="race in races">
            {{ race.name }}
          </option>
        </select>

        <select class="border rounded p-2 dark:text-black"
                id="lstCategories"
                v-model="categoryId">
          <option value="">All Categories</option>
          <option :key="category.id"
                  :value="category.id"
                  v-for="category in categories">
            {{ category.name }}
          </option>
        </select>
      </div>

      <!-- Data Table -->
      <table class="w-full border-collapse border border-gray-300 dark:border-gray-700">
        <thead class="bg-gray-100 dark:bg-gray-800">
          <tr>
            <th class="border p-2 dark:border-gray-600 dark:text-gray-400">Name</th>
            <th class="border p-2 dark:border-gray-600 dark:text-gray-400">Email</th>
            <th class="border p-2 dark:border-gray-600 dark:text-gray-400">Race</th>
            <th class="border p-2 dark:border-gray-600 dark:text-gray-400">Category</th>
            <th class="border p-2 dark:border-gray-600 dark:text-gray-400">Notes</th>
          </tr>
        </thead>
        <tbody>
          <tr class="hover:bg-gray-50 dark:hover:bg-gray-700"
              :key="reg.id"
              v-for="reg in registrationsStore.registrations">
            <!-- Inline editable notes -->
            <td class="border p-2 dark:bg-gray-800 dark:text-black dark:border-gray-600">
              <input class="border rounded p-1 w-full"
                     v-model="reg.participant.name"
                     @focus="setOriginalValues(reg)"
                     @blur="checkAndUpdate(reg)"/>
            </td>
            <td class="border p-2 dark:border-gray-600 dark:text-gray-200">
              {{ reg.participant.email }}
            </td>

            <!-- Inline editable race -->
            <td class="border p-2 dark:border-gray-600 dark:text-black">
              <select v-model="reg.race_id"
                      @change="update(reg)">
                <option :key="race.id"
                        :value="race.id"
                        v-for="race in races">
                  {{ race.name }}
                </option>
              </select>
            </td>

            <!-- Inline editable category -->
            <td class="border p-2 dark:border-gray-600 dark:text-black">
              <select v-model="reg.category"
                      @change="update(reg)">
                <option :key="category.id"
                        :value="category.id"
                        v-for="category in categories">
                  {{ category.name }}
                </option>
              </select>
            </td>

            <!-- Inline editable notes -->
            <td class="border p-2 dark:bg-gray-800 dark:text-black dark:border-gray-600">
              <input class="border rounded p-1 w-full"
                     id="txtNotes"
                     v-model="reg.notes"
                     @focus="setOriginalValues(reg)"
                     @blur="checkAndUpdate(reg)"/>
            </td>
          </tr>
          <tr v-if="!registrationsStore.registrations.length">
            <td colspan="5"
                class="text-center p-4 text-gray-500 dark:text-white">
              No registrations found.
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="mt-4 flex space-x-2">
        <button class="px-3 py-1 rounded border dark:text-white"
                :class="{
                  'bg-blue-500 text-white': link.active,
                  'text-gray-600': !link.active,
                  'opacity-50 cursor-not-allowed': !link.url,
                }"
                :key="link.label"
                :disabled="!link.url"
                v-html="link.label"
                v-for="link in registrations.links"
                @click.prevent="goToPage(link.url)"
        />
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, watch, onMounted, onBeforeUnmount } from "vue";
import { router } from "@inertiajs/vue3";
import { useRegistrationsStore } from '../../stores/registrations';
import Swal from 'sweetalert2';
import AppLayout from '../../layouts/AppLayout.vue';
import type {
  RegistrationsPagination,
  Registration,
  Filters,
  Race,
  Category
} from '../../types/registrations';

const props = defineProps<{
  registrations: RegistrationsPagination;
  filters: Filters;
  races: Race[];
  categories: Category[];
}>();

// refs for filters
const raceId = ref(props.filters.race || "");
const categoryId = ref(props.filters.category || "");

// Pinia store
const registrationsStore = useRegistrationsStore();
registrationsStore.registrations = props.registrations.data;

// Load data when filters change
watch([raceId, categoryId], ([newRace, newCat]) => {
  router.get(
    'registrations',
    { race: newRace, category: newCat },
    { preserveState: true, replace: true }
  );
});

watch(
  () => props.registrations,
  (newRegs) => {
    registrationsStore.registrations = newRegs.data;
    registrationsStore.links = newRegs.links;
  },
  { immediate: true }
);

const setOriginalValues = (reg: Registration) => {
  reg._originalParticipantName = reg.participant.name;
  reg._originalNotes = reg.notes;
};

const checkAndUpdate = (reg: Registration) => {
  const changedName = reg.participant.name !== reg._originalParticipantName;
  const changedNotes = reg.notes !== reg._originalNotes;
  if (changedName || changedNotes) {
    update(reg);
  }
};

// Update a registration with optimistic UI
const update = async (reg: Registration) => {
  try {
    await registrationsStore.updateRegistration(reg.id, {
      category: reg.category,
      race_id: reg.race_id,
      participant_name: reg.participant.name,
      notes: reg.notes,
    });

    Swal.fire({
      icon: 'success',
      title: 'Updated!',
      text: 'Registration updated successfully',
      showConfirmButton: true,
      allowOutsideClick: false
    });
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Something went wrong while updating',
    });
  }
};

const goToPage = (url: string | null) => {
  if (!url) return;
  router.get(url, {}, { preserveState: true, replace: true });
};

// ⌨️ Keyboard shortcuts
const handleKeydown = (e: KeyboardEvent) => {
  if (e.ctrlKey && e.key === "r") {
    // Ctrl+r -> focus race filter
    e.preventDefault();
    const el = document.querySelector("#lstRaces") as HTMLSelectElement;
    el?.focus();
  }
  if (e.ctrlKey && e.key === "c") {
    // Ctrl+c -> focus categories filter
    e.preventDefault();
    const el = document.querySelector("#lstCategories") as HTMLSelectElement;
    el?.focus();
  }
  if (e.ctrlKey && e.key === "ArrowLeft") {
    // Ctrl+ArrowLeft -> Go to previous page
    e.preventDefault();
    const prev = props.registrations.links.find((l: any) => l.label.includes("Previous"));
    if (prev?.url) goToPage(prev.url);
  }
  if (e.ctrlKey && e.key === "ArrowRight") {
    // Ctrl+ArrowRight -> Go to next page
    e.preventDefault();
    const next = props.registrations.links.find((l: any) => l.label.includes("Next"));
    if (next?.url) goToPage(next.url);
  }
};

onMounted(() => {
  window.addEventListener("keydown", handleKeydown);
});

onBeforeUnmount(() => {
  window.removeEventListener("keydown", handleKeydown);
});
</script>
