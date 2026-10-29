<script lang="ts" setup>
import type { SearchResultData } from '@/generated/types/App/Data/Search';
import { ref, watch } from 'vue';

const props = defineProps<{
    result: SearchResultData | null;
}>();

const show = ref<boolean>(false);
const emit = defineEmits<{
    close: [];
}>();

watch(
    () => props.result,
    (result) => {
        show.value = result !== null;
    },
);

watch(show, (sh) => {
    if (!sh) emit('close');
});
</script>

<template>
    <BModal v-model="show" no-footer size="lg" title="Search result details">
        <template v-if="result">
            <h5>Details</h5>

            <DetailList :labelSize="3" class="mb-4">
                <Detail label="Repository">
                    <a :href="`https://github.com/${result.repository.name}`" target="_blank">{{
                        result.repository.name
                    }}</a>
                </Detail>
                <Detail label="Description">
                    <template v-if="result.repository.description">{{ result.repository.description }}</template>
                    <span v-else class="text-secondary">None</span>
                </Detail>
                <Detail label="Discovered">
                    <Timestamp :timestamp="result.discovered_at" />
                </Detail>
                <Detail v-if="result.rejection_reason" label="Rejection reason">
                    {{ result.rejection_reason }}
                </Detail>
            </DetailList>

            <h5>Observed data</h5>
            <GitHubRepository :repository="result.observedData" />
        </template>
    </BModal>
</template>

<style scoped></style>
