<script lang="ts" setup>
defineProps<{
    value: boolean;
}>();
</script>

<template>
    <BBadge :variant="value ? 'success' : 'danger'">
        {{ value ? 'Yes' : 'No' }}
    </BBadge>
</template>
