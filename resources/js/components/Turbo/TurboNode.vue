<script lang="ts" setup>
import { computed } from 'vue';
import type { Lookup } from '@/components/Turbo/index.ts';
import type { TurboNode } from '@/generated/types/App/Data/Analysis/Turbo';

const props = defineProps<{
    node: TurboNode;
    nodeTypeLookup: Lookup;
    versionLookup: Lookup;
}>();

const nodeType = computed<
    | {
          name: string;
          backgroundColor: CSSStyleDeclaration['backgroundColor'];
      }
    | undefined
>(() => props.nodeTypeLookup(props.node.type));
</script>

<template>
    <BCard>
        <template #header>
            {{ nodeType?.name }}
        </template>
        <ul v-if="node.ranges.length > 0">
            <li v-for="(range, i) in node.ranges" :key="i">
                <BBadge
                    v-if="range[0]"
                    :style="{ backgroundColor: versionLookup(range[0])?.backgroundColor }"
                    class="text-dark"
                >
                    {{ versionLookup(range[0])?.name }}
                </BBadge>
                <BBadge v-else>none</BBadge>

                &ndash;

                <BBadge
                    v-if="range[1]"
                    :style="{ backgroundColor: versionLookup(range[1])?.backgroundColor }"
                    class="text-dark"
                >
                    {{ versionLookup(range[1])?.name }}
                </BBadge>
                <BBadge v-else>none</BBadge>
            </li>
        </ul>
        <TurboNode v-for="(n, i) in node.children" :key="i" :node="n" :nodeTypeLookup :versionLookup />
    </BCard>
</template>
