<script lang="ts" setup>
import type { RepositoryData } from '@/generated/types/App/Data/Repository';
import GitHubLink from '@/components/Repository/GitHubLink.vue';
import type { LengthAwarePaginator } from '@/generated/types/Illuminate';
import type { SnapshotData } from '@/generated/types/App/Data/Snapshot';
import type { Column, FilterDefinition } from '@/components/Datatable';

defineProps<{
    repository: RepositoryData;
    snapshots: LengthAwarePaginator<number, SnapshotData>;
    filters: FilterDefinition[];
}>();

const columns = [
    {
        key: 'tag',
        label: 'Tag',
        sortable: true,
    },
    {
        key: 'commit_sha',
        label: 'Commit SHA',
        sortable: true,
    },
    {
        key: 'downloaded_at',
        label: 'Downloaded',
    },
    {
        key: 'created_at',
        label: 'First discovered at',
        format: 'datetime',
    },
] satisfies Column<SnapshotData>[];
</script>

<template>
    <Toolbar :title="`Repository: ${repository.name}`">
        <BButton variant="primary">
            <i-fa6-solid-magnifying-glass />
            Discover snapshots
        </BButton>
    </Toolbar>
    <DetailList class="mb-3">
        <Detail label="Name">
            <GitHubLink :repository />
        </Detail>
        <Detail label="GitHub ID">
            <code>{{ repository.github_id }}</code>
        </Detail>
        <Detail label="Description">
            {{ repository.description }}
        </Detail>
        <Detail label="Accepted">
            <RepositoryAcceptedBadge :repository="repository" />
        </Detail>
        <Detail label="Snapshots discovered">
            <Timestamp :timestamp="repository.snapshots_discovered_at" relative />
        </Detail>
        <Detail label="First discovered">
            <Timestamp :timestamp="repository.created_at" relative />
        </Detail>
    </DetailList>

    <h2>Snapshots</h2>

    <Datatable :columns :data="snapshots" :filters>
        <template #cell(downloaded_at)="{ item }">
            <YesNoBadge :status="item.downloaded_at !== null" />
        </template>
    </Datatable>
</template>
