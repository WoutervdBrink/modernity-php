<script lang="ts" setup>
import type { RepositoryData } from '@/generated/types/App/Data/Repository';
import type { LengthAwarePaginator } from '@/generated/types/Illuminate';
import type { Column, FilterDefinition, RowActions } from '@/components/Datatable';
import RepositoryController from '@/actions/App/Http/Controllers/RepositoryController.ts';
import RepositoryAcceptedBadge from '@/components/Repository/RepositoryAcceptedBadge.vue';

defineOptions({
    layout: {
        title: 'Repositories',
    },
});

defineProps<{
    repositories: LengthAwarePaginator<number, RepositoryData>;
    filters: FilterDefinition[];
}>();

const columns = [
    {
        key: 'name',
        label: 'Name',
        sortable: true,
    },
    {
        key: 'description',
        label: 'Description',
        format: 'text-overflow',
        sortable: true,
        width: '500px',
    },
    {
        key: 'is_accepted',
        label: 'Accepted',
        format: 'boolean',
    },
    {
        key: 'created_at',
        label: 'First discovered at',
        format: 'datetime',
    },
] satisfies Column<RepositoryData>[];

const actions: RowActions<RepositoryData> = {
    view: (repository) => RepositoryController.show(repository),
};
</script>

<template>
    <Datatable
        :actions
        :columns
        :data="repositories"
        :filters
        :title="`Repositories (${repositories.meta.total})`"
        searchable
    >
        <template #cell(name)="{ item }">
            <GitHubLink :repository="item" />
        </template>
        <template #cell(is_accepted)="{ item }">
            <RepositoryAcceptedBadge :repository="item" />
        </template>
    </Datatable>
</template>
