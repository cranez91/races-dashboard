import { defineStore } from 'pinia';
import axios from 'axios';

export const useRegistrationsStore = defineStore('registrations', {
    state: () => ({
        registrations: [] as any[],
        links: [] as any[],
        loading: false,
        errors: {} as Record<string, string>,
    }),
    actions: {
        async loadRegistrations(url: string) {
            this.loading = true;
            try {
                const response = await axios.get(url);
                console.log(response);
                this.registrations = response.data.registrations.data;
            } finally {
                this.loading = false;
            }
        },

        async updateRegistration(id: number, payload: any) {
            const index = this.registrations.findIndex(reg => reg.id === id);
            if (index === -1) return;

            // Optimistic update
            const original = { ...this.registrations[index] };
            this.registrations[index] = { ...original, ...payload };

            try {
                const response = await axios.patch(`/registrations/${id}`, payload);
                this.registrations[index] = response.data.registration;
            } catch (e: any) {
                // revert on error
                this.registrations[index] = original;
                if (e.response?.data?.errors) this.errors = e.response.data.errors;
            }
        }
    }
});
