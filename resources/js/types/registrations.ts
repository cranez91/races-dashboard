export interface Participant { id: number; name: string; email: string; }
export interface Race { id: number; name: string; }
export interface Category { id: number; name: string; }
export interface Registration { id: number; participant: Participant; race: Race; category: Category; }
export interface PaginationLink { url: string | null; label: string; active: boolean; }
export interface PaginationMeta { current_page: number; last_page: number; per_page: number; total: number; }
export interface RegistrationsPagination { data: Registration[]; links: PaginationLink[]; meta: PaginationMeta; }
export interface Filters { race?: number | ""; category?: number | ""; }
