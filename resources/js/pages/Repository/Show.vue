<script lang="ts" setup>
import type { RepositoryData } from '@/generated/types/App/Data/Repository';
import GitHubLink from '@/components/Repository/GitHubLink.vue';
import type { LengthAwarePaginator } from '@/generated/types/Illuminate';
import type { SnapshotData } from '@/generated/types/App/Data/Snapshot';
import type { Column, FilterDefinition } from '@/components/Datatable';
import { router } from '@inertiajs/vue3';
import RepositoryController from '@/actions/App/Http/Controllers/RepositoryController.ts';

const props = defineProps<{
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
        key: 'semver',
        label: 'Semver',
        sortable: true,
        nullLabel: 'Not detected',
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

function discoverSnapshots(): void {
    router.visit(RepositoryController.discoverSnapshots(props.repository), {
        preserveScroll: true,
    });
}
</script>

<template>
    <Toolbar :title="`Repository: ${repository.name}`">
        <BButton :disabled="repository.is_discovering_snapshots" variant="primary" @click="discoverSnapshots">
            <BSpinner v-if="repository.is_discovering_snapshots" small />
            <i-fa6-solid-magnifying-glass v-else />
            Discover snapshots
        </BButton>
    </Toolbar>
    <DetailList class="mb-5">
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
        <Detail label="Snapshot discovery status">
            <RepositorySnapshotDiscoveryStatusBadge :status="repository.snapshot_discovery_status" />
        </Detail>
        <Detail label="Last fetched">
            <Timestamp :timestamp="repository.fetched_at" />
        </Detail>
        <Detail label="Snapshots discovered">
            <Timestamp :timestamp="repository.snapshots_discovered_at" relative />
        </Detail>
        <Detail label="First discovered">
            <Timestamp :timestamp="repository.created_at" relative />
        </Detail>
    </DetailList>

    <Datatable :columns :data="snapshots" :filters searchable title="Snapshots" title-component="h2">
        <template #cell(downloaded_at)="{ item }">
            <YesNoBadge :status="item.downloaded_at !== null" />
        </template>
    </Datatable>
</template>
