<script lang="ts" setup>
defineProps<{
    title: string;
}>();
</script>

<template>
    <div class="d-flex mb-3 justify-content-between align-items-center">
        <h1>{{ title }}</h1>
        <slot />
    </div>
</template>
