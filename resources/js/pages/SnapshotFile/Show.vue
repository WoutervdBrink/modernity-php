<script lang="ts" setup>
import type { SnapshotFileData } from '@/generated/types/App/Data/SnapshotFile';
import type { TurboDocument } from '@/generated/types/App/Data/Analysis/Turbo';
import type { RepositoryData } from '@/generated/types/App/Data/Repository';
import type { SnapshotData } from '@/generated/types/App/Data/Snapshot';

type PageProps = {
    repository: RepositoryData;
    snapshot: SnapshotData;
    snapshotFile: SnapshotFileData;
    contents: string;
    analysis: TurboDocument | null;
};

defineProps<PageProps>();

defineOptions({
    layout: (props: PageProps) => ({
        title: `${props.repository.name}/${props.snapshot.tag}/${props.snapshotFile.path}`,
    }),
});
</script>

<template>
    <Toolbar :title="`File in ${repository.name}/${snapshot.tag}`"> </Toolbar>

    <DetailList class="mb-3">
        <Detail label="Path">{{ snapshotFile.path }}</Detail>
        <Detail label="SHA256 hash">
            <code>{{ snapshotFile.sha256 }}</code>
        </Detail>
        <Detail label="Size">{{ snapshotFile.sourceFile.size }}</Detail>
        <Detail label="Exception">
            <em v-if="snapshotFile.sourceFile.exception === null" class="text-secondary">None</em>
            <CodeBlock v-else>{{ snapshotFile.sourceFile.exception }}</CodeBlock>
        </Detail>
    </DetailList>

    <h2>File contents</h2>
    <CodeBlock :includeTags="false"><div v-html="contents"></div></CodeBlock>

    <h2>Analysis</h2>
    <TurboDocument v-if="analysis" :document="analysis" />
</template>
