<script lang="ts" setup>
withDefaults(
    defineProps<{
        status: boolean | undefined | null;
        yesLabel?: string;
        noLabel?: string;
    }>(),
    {
        yesLabel: 'Yes',
        noLabel: 'No',
    },
);
</script>

<template>
    <BBadge :variant="status ? 'success' : 'danger'">
        <template v-if="status">
            <slot name="yes">{{ yesLabel }}</slot>
        </template>
        <template v-else>
            <slot name="no">{{ noLabel }}</slot>
        </template>
    </BBadge>
</template>
