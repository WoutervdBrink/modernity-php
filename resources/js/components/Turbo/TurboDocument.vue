<script lang="ts" setup>
import type { TurboDocument } from '@/generated/types/App/Data/Analysis/Turbo';
import { computed, provide } from 'vue';
import { type Lookup, makeLookup, nodeTypeLookupKey, versionLookupKey } from '@/components/Turbo/index.ts';

const props = defineProps<{
    document: TurboDocument;
}>();

const nodeTypeLookup = computed<Lookup>(() => makeLookup(props.document.nodeTypes));
const versionLookup = computed<Lookup>(() => makeLookup(props.document.versions));

provide(nodeTypeLookupKey, nodeTypeLookup);
provide(versionLookupKey, versionLookup);
</script>

<template>
    <DetailList :label-size="2">
        <Detail label="Format version">
            {{ document.formatVersion }}
        </Detail>
        <Detail label="Language"
            ><code>{{ document.language }}</code></Detail
        >
        <Detail label="Versions">
            <div class="d-flex flex-wrap gap-1">
                <BBadge
                    v-for="(v, idx) in document.versions"
                    :key="idx"
                    :style="{ backgroundColor: versionLookup(idx)?.backgroundColor }"
                    class="text-dark"
                >
                    {{ v }}
                </BBadge>
            </div>
        </Detail>
        <Detail label="Node types">
            <div class="d-flex flex-wrap gap-1">
                <BBadge
                    v-for="(nT, idx) in document.nodeTypes"
                    :key="idx"
                    :style="{ backgroundColor: nodeTypeLookup(idx)?.backgroundColor }"
                    class="text-dark"
                >
                    {{ nT }}
                </BBadge>
            </div>
        </Detail>
        <Detail label="AST">
            <TurboNode v-for="(node, i) in document.roots" :key="i" :node />
        </Detail>
    </DetailList>
</template>

<style scoped></style>
