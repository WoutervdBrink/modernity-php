<script lang="ts" setup>
import type { FailedJobData } from '@/generated/types/App/Data';
import { computed } from 'vue';

defineProps<{
    jobs: FailedJobData[];
}>();

const payload = computed<(payload: string) => string>(() => (payload: string) => {
    try {
        return JSON.stringify(JSON.parse(payload), undefined, 2);
    } catch {
        return payload;
    }
});
</script>

<template>
    <h1>Failed jobs</h1>

    <BAccordion>
        <BAccordionItem v-for="(job, i) in jobs" :key="job.id" :visible="i === 0">
            <template #title>
                <Timestamp :timestamp="job.failed_at" relative />
            </template>
            <DetailList :label-size="2">
                <Detail label="ID">{{ job.id }}</Detail>
                <Detail label="UUID">{{ job.uuid }}</Detail>
                <Detail label="Connection">{{ job.connection }} </Detail>
                <Detail label="Queue">{{ job.queue }}</Detail>
                <Detail label="Exception">
                    <pre class="bg-light p-3 border rounded"><code>{{ job.exception }}</code></pre>
                </Detail>
                <Detail label="Payload">
                    <pre class="bg-light p-3 border rounded"><code>{{ payload(job.payload) }}</code></pre>
                </Detail>
            </DetailList>
        </BAccordionItem>
    </BAccordion>
</template>

<style scoped>
pre {
    white-space: pre-wrap;
}
</style>
