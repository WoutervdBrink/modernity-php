<script lang="ts" setup>
import type { SnapshotFileData } from '@/generated/types/App/Data/SnapshotFile';
import type { TurboDocument } from '@/generated/types/App/Data/Analysis/Turbo';
import type { RepositoryData } from '@/generated/types/App/Data/Repository';
import type { SnapshotData } from '@/generated/types/App/Data/Snapshot';
import { computed, nextTick, ref } from 'vue';
import type { ContentEncoding } from '@/generated/types/App/Models/Enums';
import 'highlight.js/styles/stackoverflow-light.css';
import hljs from 'highlight.js/lib/core';
import php from 'highlight.js/lib/languages/php';

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

const props = defineProps<PageProps>();

defineOptions({
    layout: (props: PageProps) => ({
        title: `${props.repository.name}/${props.snapshot.tag}/${props.snapshotFile.path}`,
    }),
});

const actualContents = computed<string>(() =>
    props.contents.encoding === 'base64' ? window.atob(props.contents.contents) : props.contents.contents,
);
const highlighted = computed<string>(() => hljs.highlight(actualContents.value, { language: 'php' }).value);

const showAnalysis = ref<boolean>(props.snapshotFile.sourceFile.size < 10 * 1024);

async function doShowAnalysis(): Promise<void> {
    nextTick(() => (showAnalysis.value = true));
}
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

    <BAlert :model-value="!showAnalysis" variant="warning">
        <h4 class="alert-heading">Warning: large file</h4>
        <p>
            This size of this file is {{ (snapshotFile.sourceFile.size / 1024).toFixed(2) }} KiB. Displaying its
            contents and analysis might seriously slow down your browser.
        </p>
        <BButton variant="danger" @click="doShowAnalysis"> I understand, continue </BButton>
    </BAlert>

    <template v-if="showAnalysis">
        <h2>File contents</h2>
        <BAlert :model-value="contents.encoding === 'base64'" variant="info">
            File contents were base64-encoded in the database and might therefore contain invalid UTF-8 byte sequences.
        </BAlert>
        <CodeBlock><div class="hljs php" v-html="highlighted"></div></CodeBlock>

        <h2>Analysis</h2>
        <TurboDocument v-if="analysis" :document="analysis" />
    </template>
</template>
