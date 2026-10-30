<script lang="ts" setup>
import { computed, type ComputedRef, inject } from 'vue';
import { type Lookup, nodeTypeLookupKey } from '@/components/Turbo/index.ts';
import type { TurboNode } from '@/generated/types/App/Data/Analysis/Turbo';

const props = defineProps<{
    node: TurboNode;
}>();

const nodeTypeLookup = inject(nodeTypeLookupKey) as ComputedRef<Lookup>;

const nodeType = computed<
    | {
          name: string;
          backgroundColor: CSSStyleDeclaration['backgroundColor'];
      }
    | undefined
>(() => nodeTypeLookup.value(props.node.type));
</script>

<template>
    <div class="d-flex align-items-center gap-2">
        <span :class="node.ranges.length === 0 ? 'text-secondary' : 'text-primary'">
            {{ nodeType?.name }}
        </span>

        <TurboRange v-for="(range, i) in node.ranges" :key="i" :range />
    </div>

    <ul v-if="node.children.length" class="border-start ms-3 ps-3">
        <li v-for="(n, i) in node.children" :key="i">
            <TurboNode :node="n" />
        </li>
    </ul>
</template>

<style scoped>
li {
    list-style: none;
}
</style>
