<script lang="ts" setup>
import type { ProcessStatusCounts } from '@/generated/types/App/Data';
import { ALL_PROCESS_STATUSES, getStatusLabel, getStatusVariant } from '@/common.ts';

defineProps<{
    counts: ProcessStatusCounts;
}>();

const numberFormatter = new Intl.NumberFormat('en-US');
</script>

<template>
    <BProgress :max="counts.total" height="1.5rem">
        <template v-for="status in ALL_PROCESS_STATUSES" :key="status">
            <BProgressBar
                v-if="counts[status] > 0"
                :key="status"
                :value="counts[status]"
                :variant="getStatusVariant(status)"
            >
                {{ getStatusLabel(status) }} ({{ numberFormatter.format(counts[status]) }})
            </BProgressBar>
        </template>
    </BProgress>
</template>
