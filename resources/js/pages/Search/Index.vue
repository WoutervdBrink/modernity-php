<script lang="ts" setup>
import type { SearchData } from '@/generated/types/App/Data/Search';
import SearchStatusBadge from '@/components/SearchStatusBadge.vue';
import SearchController from '@/actions/App/Http/Controllers/SearchController.ts';
import { Link } from '@inertiajs/vue3';

defineOptions({
    layout: { title: 'Searches' },
});

defineProps<{
    searches: SearchData[];
}>();
</script>

<template>
    <Toolbar title="Searches">
        <Link v-if="searches.length > 0" :href="SearchController.create().url" class="btn btn-primary" prefetch>
            New search
        </Link>
    </Toolbar>

    <div v-if="searches.length === 0" class="alert alert-light p-3 p-lg-5">
        <div class="d-flex flex-column align-items-center">
            <p>No searches have been started yet.</p>
            <Link :href="SearchController.create().url" class="btn btn-primary"> New search </Link>
        </div>
    </div>

    <BTableSimple v-if="searches.length">
        <BThead>
            <BTr>
                <BTh>ID</BTh>
                <BTh>Parameters</BTh>
                <BTh>Status</BTh>
                <BTh>Created</BTh>
                <BTh>Started</BTh>
                <BTh>Finished</BTh>
                <BTh></BTh>
            </BTr>
        </BThead>
        <BTbody>
            <BTr v-for="search in searches" :key="search.id">
                <BTh>{{ search.id }}</BTh>
                <BTd><SearchParameters :parameters="search.parameters" /></BTd>
                <BTd><SearchStatusBadge :status="search.status" /></BTd>
                <BTd><Timestamp :timestamp="search.created_at" relative /></BTd>
                <BTd><Timestamp :timestamp="search.started_at" relative /></BTd>
                <BTd><Timestamp :timestamp="search.finished_at" relative /></BTd>
                <BTd>
                    <Link :href="SearchController.show(search.id).url" prefetch> Details </Link>
                </BTd>
            </BTr>
        </BTbody>
    </BTableSimple>
</template>
