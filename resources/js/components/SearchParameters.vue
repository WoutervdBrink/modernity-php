<script lang="ts" setup>
import type { SearchParameters } from '@/generated/types/App/Data/Search';

defineProps<{
    parameters: SearchParameters;
}>();
</script>

<template>
    <div class="d-flex gap-1">
        <BBadge :variant="parameters.cutoff ? 'dark' : 'light'">
            <template v-if="parameters.cutoff"> Cutoff: <Timestamp :timestamp="parameters.cutoff" /> </template>
            <template v-else> No cutoff date </template>
        </BBadge>

        <BBadge :variant="parameters.max ? 'dark' : 'light'">
            {{ parameters.max ? `Max: ${parameters.max}` : 'No maximum' }}
        </BBadge>

        <BBadge :variant="parameters.php ? 'dark' : 'light'">
            {{ parameters.php ? `PHP: ${parameters.php}%` : 'No PHP share' }}
        </BBadge>
    </div>
</template>

<style scoped></style>
