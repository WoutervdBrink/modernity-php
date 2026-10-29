<script lang="ts" setup>
import type { SearchData } from '@/generated/types/App/Data/Search';
import SearchController from '@/actions/App/Http/Controllers/SearchController.ts';
import { Link } from '@inertiajs/vue3';
import type { LengthAwarePaginator } from '@/generated/types/Illuminate';
import type { Column, RowActions } from '@/components/Datatable';

defineOptions({
    layout: { title: 'Searches' },
});

defineProps<{
    searches: LengthAwarePaginator<number, SearchData>;
}>();

const columns = [
    {
        key: 'status',
        label: 'Status',
    },
    {
        key: 'parameters',
        label: 'Parameters',
    },
    {
        key: 'created_at',
        label: 'Created at',
        format: 'datetime',
        sortable: true,
    },
    {
        key: 'started_at',
        label: 'Started at',
        format: 'datetime-relative',
        sortable: true,
    },
    {
        key: 'finished_at',
        label: 'Finished at',
        format: 'datetime-relative',
        sortable: true,
    },
] satisfies Column<SearchData>[];

const actions: RowActions<SearchData> = {
    view: (row: SearchData) => SearchController.show(row.id),
};
</script>

<template>
    <Toolbar title="Searches">
        <Link v-if="searches.meta.total > 0" :href="SearchController.create().url" class="btn btn-primary" prefetch>
            New search
        </Link>
    </Toolbar>

    <div v-if="searches.meta.total === 0" class="alert alert-light p-3 p-lg-5">
        <div class="d-flex flex-column align-items-center">
            <p>No searches have been started yet.</p>
            <Link :href="SearchController.create().url" class="btn btn-primary"> New search </Link>
        </div>
    </div>

    <Datatable v-if="searches.meta.total > 0" :actions :columns :data="searches" :filters="[]">
        <template #cell(parameters)="{ item }">
            <SearchParameters :parameters="item.parameters" />
        </template>
        <template #cell(status)="{ item }">
            <StatusBadge :status="item.status" />
        </template>
    </Datatable>
</template>
