<script lang="ts" setup>
import { onBeforeUnmount, ref, shallowRef, watch } from 'vue';
import { Codemirror } from 'vue-codemirror';
import { php } from '@codemirror/lang-php';
import type { TurboDocument } from '@/generated/types/App/Data/Analysis/Turbo';
import axios from 'axios';
import PlaygroundController from '@/actions/App/Http/Controllers/PlaygroundController.ts';
import type {
    AnalyzePlaygroundSourceRequestData,
    AnalyzePlaygroundSourceResponseData,
} from '@/generated/types/App/Data/Playground';

defineOptions({
    layout: {
        title: 'Playground',
        fluid: true,
    },
});

const extensions = [php()];

const layout = ref<'vertical' | 'horizontal'>('horizontal');
const source = ref<string>('');
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
            const resAnalysis = (res.data as AnalyzePlaygroundSourceResponseData).analysis;
            if (resAnalysis) {
                analysis.value = resAnalysis;
            }
        }
    } catch (e) {
        if (axios.isCancel(e)) {
            return;
        }

        if (currentGeneration === generation) {
            analysis.value = undefined;
            error.value = 'Analysis failed.';
        }
    } finally {
        if (currentGeneration === generation) {
            analyzing.value = false;
        }
    }
}

onBeforeUnmount(() => {
    clearTimeout(debounce);
    request?.abort();
});
</script>

<template>
    <Toolbar title="Playground">
        <BButton variant="primary" @click="layout = layout === 'horizontal' ? 'vertical' : 'horizontal'">
            <template v-if="layout === 'vertical'">
                <i-fa6-solid-table-columns />
                Side by side
            </template>
            <template v-else>
                <i-fa6-solid-bars />
                Vertical layout
            </template>
        </BButton>
    </Toolbar>
    <BRow v-if="layout === 'horizontal'">
        <BCol md="6">
            <h2>Source code</h2>
            <Codemirror v-model="source" :autofocus="true" :extensions :indent-with-tab="true" :tab-size="4" />
        </BCol>
        <BCol md="6">
            <h2>
                Analysis
                <BSpinner v-if="analyzing" small />
            </h2>
            <BAlert v-if="error" variant="danger">
                <h4 class="alert-heading">An error occurred</h4>
                <p>Something went wrong while analyzing your code:</p>
                <pre><code>{{ error }}</code></pre>
            </BAlert>
            <TurboDocument v-if="analysis" :document="analysis" />
        </BCol>
    </BRow>

    <template v-else>
        <h2>Source code</h2>
        <div class="mb-3">
            <Codemirror v-model="source" :autofocus="true" :extensions :indent-with-tab="true" :tab-size="4" />
        </div>

        <h2>
            Analysis
            <BSpinner v-if="analyzing" small />
        </h2>
        <BAlert v-if="error" variant="danger">
            <h4 class="alert-heading">An error occurred</h4>
            <p>Something went wrong while analyzing your code:</p>
            <pre><code>{{ error }}</code></pre>
        </BAlert>
        <TurboDocument v-if="analysis" :document="analysis" />
    </template>
</template>
