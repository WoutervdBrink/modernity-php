<script lang="ts" setup>
import type { SearchData, SearchResultData } from '@/generated/types/App/Data/Search';
import SearchStatusBadge from '@/components/SearchStatusBadge.vue';
import { onMounted, ref, watch } from 'vue';
import { usePoll } from '@inertiajs/vue3';
import type { SearchStatus } from '@/generated/types/App/Models/Enums';

const props = defineProps<{
    current_commit: string;
    search: SearchData;
}>();

defineOptions({
    layout: { title: 'Search details' },
});

const modalResult = ref<SearchResultData | null>(null);

const poll = usePoll(1000, { only: ['search'] }, { autoStart: false });

watch(
    () => props.search.status,
    (status: SearchStatus) => {
        if (status === 'completed' || status === 'failed') {
            poll.stop();
        } else {
            poll.start();
        }
    },
);

onMounted(() => {
    if (props.search.status !== 'completed' && props.search.status !== 'failed') {
        poll.start();
    }
});
</script>

<template>
    <h1>Search details</h1>

    <BTableSimple style="table-layout: fixed">
        <BTbody>
            <BTr>
                <BTh>Application commit</BTh>
                <BTd>
                    <BBadge v-if="search.application_commit === current_commit" variant="success"
                        >Current version</BBadge
                    >
                    <BBadge v-else variant="danger">Older version</BBadge>
                    <br />

                    <code>{{ search.application_commit }}</code>
                </BTd>
            </BTr>
            <BTr>
                <BTh>Parameters</BTh>
                <BTd>
                    <ul class="mb-0 ps-3">
                        <li>Cutoff date: {{ search.parameters.cutoff ?? 'none' }}</li>
                        <li>Maximum amount of repositories: {{ search.parameters.max }}</li>
                        <li>
                            Min. PHP share:
                            <template v-if="search.parameters.php"> {{ search.parameters.php }}% </template>
                            <template v-else>None</template>
                        </li>
                    </ul>
                </BTd>
            </BTr>
            <BTr>
                <BTh>Status</BTh>
                <BTd>
                    <SearchStatusBadge :status="search.status" />
                </BTd>
            </BTr>
        </BTbody>
    </BTableSimple>

    <h2>Results</h2>

    <BProgress :max="1" height="1.5rem">
        <BProgressBar :max="1" :value="search.acceptedResults_count! / search.results_count!" variant="success">
            Accepted ({{ search.acceptedResults_count }})
        </BProgressBar>
        <BProgressBar :max="1" :value="1 - search.acceptedResults_count! / search.results_count!" variant="danger">
            Rejected ({{ search.results_count! - search.acceptedResults_count! }})
        </BProgressBar>
    </BProgress>

    <BTableSimple>
        <BThead>
            <BTr>
                <BTh>Repository</BTh>
                <BTh>Status</BTh>
                <BTh>Rejection reason</BTh>
                <BTh>Discovered</BTh>
                <BTh></BTh>
            </BTr>
        </BThead>
        <BTbody>
            <BTr v-for="result in search.results" :key="result.id">
                <BTh>
                    {{ result.repository.name }}
                </BTh>
                <BTd>
                    <BBadge :variant="result.rejection_reason ? 'danger' : 'success'">
                        {{ result.rejection_reason ? 'Rejected' : 'Accepted' }}
                    </BBadge>
                </BTd>
                <BTd>
                    <template v-if="result.rejection_reason">{{ result.rejection_reason }}</template>
                    <span v-else class="text-secondary">None</span>
                </BTd>
                <BTd>
                    <Timestamp :timestamp="result.discovered_at" relative />
                </BTd>
                <BTd>
                    <BButton class="m-0 p-0 lh-1" prefetch type="button" variant="link" @click="modalResult = result">
                        Details
                    </BButton>
                </BTd>
            </BTr>
        </BTbody>
    </BTableSimple>

    <SearchResultDetailsModal :result="modalResult" @close="modalResult = null" />
</template>
