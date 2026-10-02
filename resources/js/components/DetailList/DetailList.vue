<script lang="ts" setup>
import { computed, provide } from 'vue';
import { DEFAULT_LABEL_SIZE, type LabelSize, labelSizeKey } from '@/components/DetailList/index.ts';

const props = withDefaults(
    defineProps<{
        labelSize?: LabelSize;
    }>(),
    {
        labelSize: DEFAULT_LABEL_SIZE.value,
    },
);

const labelSize = computed(() => props.labelSize);

provide(labelSizeKey, labelSize);
</script>

<template>
    <BListGroup>
        <slot />
    </BListGroup>
</template>
