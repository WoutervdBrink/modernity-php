<script lang="ts" setup>
import type { SnapshotFileData } from '@/generated/types/App/Data/SnapshotFile';
import type { ContentEncoding } from '@/generated/types/App/Models/Enums';
import { computed, nextTick, ref } from 'vue';
import hljs from 'highlight.js/lib/core';
import php from 'highlight.js/lib/languages/php';
import type { SourceFileData } from '@/generated/types/App/Data/SourceFile';
import type { TurboDocument } from '@/generated/types/App/Data/Analysis/Turbo';

hljs.registerLanguage('php', php);

const props = defineProps<{
    snapshotFile?: SnapshotFileData;
    sourceFile: SourceFileData;
    analysis: TurboDocument | null;
    contents: {
        encoding: ContentEncoding;
        contents: string;
    };
}>();

const actualContents = computed<string>(() =>
    props.contents.encoding === 'base64' ? window.atob(props.contents.contents) : props.contents.contents,
);
const highlighted = computed<string>(() => hljs.highlight(actualContents.value, { language: 'php' }).value);

const showAnalysis = ref<boolean>(props.sourceFile.size < 10 * 1024);

async function doShowAnalysis(): Promise<void> {
    nextTick(() => (showAnalysis.value = true));
}
</script>

<template>
    <DetailList :label-size="3" class="mb-3">
        <Detail v-if="snapshotFile" label="Path">{{ snapshotFile.path }}</Detail>
        <Detail label="SHA256 hash">
            <code>{{ sourceFile.sha256 }}</code>
        </Detail>
        <Detail label="Size">{{ sourceFile.size }}</Detail>
        <Detail label="Exception">
            <em v-if="sourceFile.exception === null" class="text-secondary">None</em>
            <CodeBlock v-else height="20rem">{{ sourceFile.exception }}</CodeBlock>
        </Detail>
    </DetailList>

    <BAlert :model-value="!showAnalysis" variant="warning">
        <h4 class="alert-heading">Warning: large file</h4>
        <p>
            This size of this file is {{ (sourceFile.size / 1024).toFixed(2) }} KiB. Displaying its contents and
            analysis might seriously slow down your browser.
        </p>
        <BButton variant="danger" @click="doShowAnalysis"> I understand, continue </BButton>
    </BAlert>

    <template v-if="showAnalysis">
        <h2>File contents</h2>
        <BAlert :model-value="contents.encoding === 'base64'" variant="info">
            File contents were base64-encoded in the database and might therefore contain invalid UTF-8 byte sequences.
        </BAlert>
        <CodeBlock class="mb-3"><div class="hljs php" v-html="highlighted"></div></CodeBlock>

        <h2>Analysis</h2>
        <TurboDocument v-if="analysis" :document="analysis" />
        <BAlert :model-value="!analysis" variant="light"> No analysis was recorded. </BAlert>
    </template>
</template>
