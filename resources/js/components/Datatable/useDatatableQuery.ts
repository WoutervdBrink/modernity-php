import { router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import type { QueryValues } from '@/components/Datatable/index.ts';

export function useDatatableQuery() {
    const page = usePage();
    const currentURL = computed<string>(() => page.url);

    function param(key: string): string {
        return new URL(currentURL.value, window.location.origin).searchParams.get(key) ?? '';
    }

    function update(values: QueryValues, resetPage: boolean = true): void {
        const url = new URL(currentURL.value, window.location.origin);
        for (const [key, value] of Object.entries(values)) {
            if (value === '' || value === null || value === undefined) {
                url.searchParams.delete(key);
            } else {
                url.searchParams.set(key, value);
            }
        }
        if (resetPage) url.searchParams.delete('page');

        router.get(url.pathname, Object.fromEntries(url.searchParams.entries()), {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        });
    }

    return { currentURL, param, update };
}
