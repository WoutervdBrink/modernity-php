<script lang="ts" setup>
import type { SnapshotData } from '@/generated/types/App/Data/Snapshot';
import { Link, router } from '@inertiajs/vue3';
import SnapshotController from '@/actions/App/Http/Controllers/SnapshotController.ts';
import RepositoryController from '@/actions/App/Http/Controllers/RepositoryController.ts';
import type { RepositoryData } from '@/generated/types/App/Data/Repository';
import type { Column, RowActions } from '@/components/Datatable';
import SnapshotFileController from '@/actions/App/Http/Controllers/SnapshotFileController.ts';
import type { LengthAwarePaginator } from '@/generated/types/Illuminate';
import type { SnapshotFileData } from '@/generated/types/App/Data/SnapshotFile';

type PageProps = {
    repository: RepositoryData;
    snapshot: SnapshotData;
    files: LengthAwarePaginator<number, SnapshotFileData>;
};

const props = defineProps<PageProps>();

defineOptions({
    layout: (props: PageProps) => ({
        title: `${props.repository.name} snapshot ${props.snapshot.tag}`,
    }),
});

const columns = [
    {
        key: 'path',
        label: 'Path',
        sortable: true,
    },
    {
        key: 'sha256',
        label: 'SHA256',
        sortable: true,
    },
    {
        key: 'sourceFile',
        accessor: (snapshot) => snapshot.sourceFile.size,
        label: 'Size',
    },
] satisfies Column<SnapshotFileData>[];

const actions: RowActions<SnapshotFileData> = {
    view: (snapshotFile) => SnapshotFileController.show(snapshotFile),
};

function analyze(): void {
    router.visit(SnapshotController.analyze(props.snapshot));
}
</script>

<template>
    <Toolbar :title="`${props.repository.name} snapshot ${props.snapshot.tag}`">
        <BButton :disabled="false" variant="primary" @click="analyze">
            <BSpinner v-if="false" small />
            <i-fa6-solid-magnifying-glass v-else />
            Analyze
        </BButton>
    </Toolbar>

    <DetailList>
        <Detail label="Repository">
            <Link :href="RepositoryController.show(repository)">
                {{ repository.name }}
            </Link>
        </Detail>
        <Detail label="Tag">
            {{ snapshot.tag }}

            <a :href="`https://github.com/${repository.name}/tree/${snapshot.tag}`">
                <i-fa6-solid-up-right-from-square />
            </a>
        </Detail>
        <Detail label="Commit SHA">
            <code>{{ snapshot.commit_sha }}</code>
        </Detail>
        <Detail label="Semantic version">
            <code v-if="snapshot.semver !== null">
                {{ snapshot.semver }}
            </code>
            <i v-else class="text-secondary">None detected</i>
        </Detail>
    </DetailList>

    <Datatable :actions :columns :data="files" title="Files" />
</template>
