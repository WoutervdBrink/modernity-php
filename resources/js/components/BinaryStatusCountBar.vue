<script lang="ts" setup>
import { computed } from 'vue';

const props = withDefaults(
    defineProps<{
        yes: number;
        no: number;
        yesLabel?: string;
        noLabel?: string;
    }>(),
    {
        yesLabel: 'Yes',
        noLabel: 'No',
    },
);

const total = computed<number>(() => props.yes + props.no);
const numberFormatter = new Intl.NumberFormat('en-US');
</script>

<template>
    <BProgress :max="total" height="1.5rem">
        <BProgressBar v-if="yes > 0" :value="yes" variant="success">
            {{ yesLabel }} ({{ numberFormatter.format(yes) }})
        </BProgressBar>
        <BProgressBar v-if="no > 0n" :value="no" variant="danger">
            {{ noLabel }} ({{ numberFormatter.format(no) }})
        </BProgressBar>
    </BProgress>
</template>

<style scoped></style>
