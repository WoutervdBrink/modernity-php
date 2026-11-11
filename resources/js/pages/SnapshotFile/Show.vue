<script lang="ts" setup>
import type { SnapshotFileData } from '@/generated/types/App/Data/SnapshotFile';
import type { TurboDocument } from '@/generated/types/App/Data/Analysis/Turbo';
import type { RepositoryData } from '@/generated/types/App/Data/Repository';
import type { SnapshotData } from '@/generated/types/App/Data/Snapshot';
import type { ContentEncoding } from '@/generated/types/App/Models/Enums';
import 'highlight.js/styles/stackoverflow-light.css';
import hljs from 'highlight.js/lib/core';
import php from 'highlight.js/lib/languages/php';
import FileDetails from '@/components/FileDetails.vue';

hljs.registerLanguage('php', php);

type PageProps = {
    repository: RepositoryData;
    snapshot: SnapshotData;
    snapshotFile: SnapshotFileData;
    contents: {
        encoding: ContentEncoding;
        contents: string;
    };
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

    <FileDetails :analysis :contents :snapshotFile :sourceFile="snapshotFile.sourceFile!" />
</template>
