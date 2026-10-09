<script lang="ts" setup>
import type { RepositorySnapshotDiscoveryStatus } from '@/generated/types/App/Models/Enums';
import { computed } from 'vue';
import type { ColorVariant } from 'bootstrap-vue-next';

const props = defineProps<{
    status: RepositorySnapshotDiscoveryStatus;
}>();

const variant = computed<ColorVariant>(() => {
    switch (props.status) {
        case 'pending':
            return 'secondary';
        case 'queued':
            return 'info';
        case 'running':
            return 'primary';
        case 'completed':
            return 'success';
        case 'failed':
            return 'danger';
    }
});

const text = computed<string>(() => {
    return props.status[0].toUpperCase() + props.status.slice(1);
});
</script>

<template>
    <BBadge :variant>{{ text }}</BBadge>
</template>
