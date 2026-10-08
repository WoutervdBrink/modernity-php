<script lang="ts" setup>
import type { RepositoryData } from '@/generated/types/App/Data/Repository';

defineProps<{
    repository: RepositoryData;
}>();
</script>

<template>
    <a :href="`https://github.com/${repository.name}`" target="_blank">
        {{ repository.name }}
        <i-fa6-solid-up-right-from-square />
    </a>
</template>
