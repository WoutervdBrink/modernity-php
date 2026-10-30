<script lang="ts" setup>
import { type ComputedRef, inject } from 'vue';
import { type Lookup, versionLookupKey } from '@/components/Turbo/index.ts';
import type { TurboNode } from '@/generated/types/App/Data/Analysis/Turbo';

defineProps<{
    range: TurboNode['ranges'][number];
}>();

const versionLookup = inject(versionLookupKey) as ComputedRef<Lookup>;
</script>

<template>
    <div class="range">
        <div
            v-for="i in [0, 1]"
            :key="i"
            :class="range[i] ? 'closed' : 'open'"
            :style="range[i] ? { '--version-color': versionLookup(range[i])?.backgroundColor } : {}"
            class="segment"
        >
            <template v-if="range[i] === null">&mdash;</template>
            <template v-else>
                {{ versionLookup(range[i])?.name }}
            </template>
        </div>
    </div>
</template>

<style scoped>
.range {
    display: flex;
}
.range > .segment {
    --version-color: gray;
    padding: 0.25rem 0.1rem;
    width: 2rem;
    text-align: center;
}
.range > .segment.closed {
    border: 2px solid var(--version-color);
    background-color: var(--version-color);
}
</style>
