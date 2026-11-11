<script lang="ts" setup>
import type { ColorVariant } from 'bootstrap-vue-next';

withDefaults(
    defineProps<{
        variant?: ColorVariant | 'none';
        includeTags?: boolean;
        height?: CSSStyleDeclaration['maxHeight'];
    }>(),
    {
        variant: 'light',
        includeTags: true,
        height: '30rem',
    },
);
</script>

<template>
    <div
        :class="variant !== 'none' ? ['border', 'p-3', 'rounded', `bg-${variant}`] : []"
        :style="{ 'max-height': height }"
        class="code-block"
    >
        <template v-if="includeTags">
            <pre class="m-0"><code><slot /></code></pre>
        </template>
        <template v-else>
            <slot />
        </template>
    </div>
</template>

<style>
.code-block {
    overflow: auto;
    display: grid;
}
.code-block pre {
    white-space: pre-wrap;
    word-break: break-word;
    word-wrap: break-word;
}
</style>
