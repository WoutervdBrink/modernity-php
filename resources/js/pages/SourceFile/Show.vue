<script lang="ts" setup>
import type { SourceFileData } from '@/generated/types/App/Data/SourceFile';
import type { ContentEncoding } from '@/generated/types/App/Models/Enums';
import type { TurboDocument } from '@/generated/types/App/Data/Analysis/Turbo';
import type { Column, RowActions } from '@/components/Datatable';
import type { SnapshotFileData } from '@/generated/types/App/Data/SnapshotFile';
import SnapshotFileController from '@/actions/App/Http/Controllers/SnapshotFileController.ts';
import type { LengthAwarePaginator } from '@/generated/types/Illuminate';
import PlaygroundController from '@/actions/App/Http/Controllers/PlaygroundController.ts';
import { Link } from '@inertiajs/vue3';

type PageProps = {
    sourceFile: SourceFileData;
    contents: {
        encoding: ContentEncoding;
        contents: string;
    };
    analysis: TurboDocument | null;
    files: LengthAwarePaginator<number, SnapshotFileData>;
};

defineProps<PageProps>();

defineOptions({
    layout: (props: PageProps) => ({
        title: `Source file ${props.sourceFile.sha256}`,
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
        key: 'repository',
        label: 'Repository',
        accessor: (file) => file.snapshot!.repository!.name,
    },
    {
        key: 'snapshot',
        label: 'Snapshot',
        accessor: (file) => file.snapshot!.tag,
    },
] satisfies Column<SnapshotFileData>[];

const actions: RowActions<SnapshotFileData> = {
    view: (snapshotFile) => SnapshotFileController.show(snapshotFile),
};
</script>

<template>
    <Toolbar :title="`Source file ${sourceFile.sha256.substring(0, 10)}...`">
        <Link :href="PlaygroundController.index({ query: { file: sourceFile.sha256 } })" class="btn btn-primary btn-sm">
            Open in playground
        </Link>
    </Toolbar>

    <FileDetails :analysis :contents :sourceFile="sourceFile" />

    <Datatable :actions :columns :data="files" title="Files"> </Datatable>
</template>
