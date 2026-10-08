<script lang="ts" setup>
import type { RepositoryData } from '@/generated/types/App/Data/Repository';

defineProps<{
    repository: RepositoryData;
}>();
</script>

<template>
    <BBadge :variant="repository.is_accepted ? 'success' : 'danger'">
        {{ repository.is_accepted ? 'Yes' : 'No' }}
    </BBadge>
</template>
