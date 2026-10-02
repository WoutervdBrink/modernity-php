<script lang="ts" setup>
import { computed, inject } from 'vue';
import { DEFAULT_LABEL_SIZE, labelSizeKey } from '@/components/DetailList/index.ts';

const labelSize = inject(labelSizeKey, DEFAULT_LABEL_SIZE);
const detailSize = computed<number>(() => 12 - labelSize.value);

withDefaults(
    defineProps<{
        label?: string;
    }>(),
    { label: undefined },
);
</script>

<template>
    <BListGroupItem>
        <div class="row">
            <div :class="`col-lg-${labelSize}`">
                <slot name="label">
                    <strong>{{ label }}</strong>
                </slot>
            </div>
            <div :class="`col-lg-${detailSize}`">
                <slot />
            </div>
        </div>
    </BListGroupItem>
</template>
