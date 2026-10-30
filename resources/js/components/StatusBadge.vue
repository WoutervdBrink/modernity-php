<script lang="ts" setup>
import type { ProcessStatus, SearchStatus } from '@/generated/types/App/Models/Enums';
import { computed } from 'vue';
import type { ColorVariant } from 'bootstrap-vue-next';
import { getStatusLabel, getStatusVariant } from '@/common.ts';

const props = defineProps<{
    status: ProcessStatus | SearchStatus;
}>();

const variant = computed<ColorVariant>(() => getStatusVariant(props.status));
const text = computed<string>(() => getStatusLabel(props.status));
</script>

<template>
    <BBadge :variant>{{ text }}</BBadge>
</template>
