<script lang="ts" setup>
import type { SearchStatus } from '@/generated/types/App/Models/Enums';
import { computed } from 'vue';
import type { ColorVariant } from 'bootstrap-vue-next';

const props = defineProps<{
    status: SearchStatus;
}>();

const variant = computed<ColorVariant>(() => {
    switch (props.status) {
        case 'pending':
            return 'secondary';
        case 'running':
            return 'primary';
        case 'paused':
            return 'warning';
        case 'completed':
            return 'success';
        case 'failed':
            return 'danger';
        default:
            return 'secondary';
    }
});

const text = computed<string>(() => {
    return props.status[0].toUpperCase() + props.status.slice(1);
});
</script>

<template>
    <BBadge :variant>{{ text }}</BBadge>
</template>
