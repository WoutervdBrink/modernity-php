<script lang="ts" setup>
import { onBeforeUnmount, onMounted, ref, shallowRef, watch } from 'vue';
import { Codemirror } from 'vue-codemirror';
import { php } from '@codemirror/lang-php';
import type { TurboDocument } from '@/generated/types/App/Data/Analysis/Turbo';
import axios from 'axios';
import PlaygroundController from '@/actions/App/Http/Controllers/PlaygroundController.ts';
import type {
    AnalyzePlaygroundSourceRequestData,
    AnalyzePlaygroundSourceResponseData,
} from '@/generated/types/App/Data/Playground';
import type { SourceFileContentsData } from '@/generated/types/App/Data/SourceFile';

defineOptions({
    layout: {
        title: 'Playground',
        fluid: true,
    },
});

const props = defineProps<{
    contents: SourceFileContentsData | null;
}>();

const extensions = [php()];

const source = ref<string>(
    props.contents
        ? props.contents.content_encoding === 'base64'
            ? window.atob(props.contents.contents)
            : props.contents.contents
        : '',
);
const analysis = shallowRef<TurboDocument | undefined>(undefined);
const error = ref<string | undefined>(undefined);
const analyzing = ref<boolean>(false);

let debounce: ReturnType<typeof setTimeout> | undefined = undefined;
let request: AbortController | undefined = undefined;
let generation = 0;

watch(source, (value) => {
    clearTimeout(debounce);
    debounce = setTimeout(() => {
        analyze(value);
    }, 500);
});

async function analyze(code: string): Promise<void> {
    request?.abort();

    const controller = new AbortController();
    request = controller;

    const currentGeneration = ++generation;

    analyzing.value = true;
    error.value = undefined;

    try {
        const res = await axios.post(
            PlaygroundController.analyze().url,
            { source: code } as AnalyzePlaygroundSourceRequestData,
            { signal: controller.signal },
        );

        if (currentGeneration === generation) {
            const data = res.data as AnalyzePlaygroundSourceResponseData;

            if (data.analysis) {
                analysis.value = data.analysis;
            } else if (data.error) {
                error.value = data.error;
            } else {
                error.value = 'An unknown error occurred.';
            }
        }
    } catch (e) {
        if (axios.isCancel(e)) {
            return;
        }

        if (currentGeneration === generation) {
            analysis.value = undefined;
            error.value = 'Analysis failed due to an unknown error.';
            if (e instanceof Error) {
                error.value = `${e.name}: ${e.message}\n${e.stack ?? ''}\n${e.cause ?? ''}`;
            }
        }
    } finally {
        if (currentGeneration === generation) {
            analyzing.value = false;
        }
    }
}

onMounted(() => {
    if (props.contents) {
        analyze(source.value);
    }
});

onBeforeUnmount(() => {
    clearTimeout(debounce);
    request?.abort();
});
</script>

<template>
    <Toolbar title="Playground" />
    <BRow>
        <BCol md="6">
            <h2>Source code</h2>
            <Codemirror v-model="source" :autofocus="true" :extensions :indent-with-tab="true" :tab-size="4" />
        </BCol>
        <BCol md="6">
            <h2>
                Analysis
                <BSpinner v-if="analyzing" small />
            </h2>
            <BAlert :model-value="error !== undefined" variant="danger">
                <h4 class="alert-heading">An error occurred</h4>
                <p>Something went wrong while analyzing your code:</p>
                <CodeBlock variant="none">{{ error }}</CodeBlock>
            </BAlert>
            <TurboDocument v-if="analysis" :document="analysis" />
        </BCol>
    </BRow>
</template>
