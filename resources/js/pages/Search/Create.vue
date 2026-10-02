<script lang="ts" setup>
import type { CreateSearchRequestData } from '@/generated/types/App/Data/Search';
import { useForm } from '@inertiajs/vue3';
import SearchController from '@/actions/App/Http/Controllers/SearchController.ts';

defineOptions({
    layout: { title: 'Start new search' },
});

const form = useForm<CreateSearchRequestData>({ parameters: { cutoff: null, max: 100, php: 75 } });

function setCutoffToToday(): void {
    const d = new Date();
    const year = d.getFullYear().toString(10).padStart(4, '0');
    const month = (d.getMonth() + 1).toString(10).padStart(2, '0');
    const day = d.getDate().toString(10).padStart(2, '0');
    form.parameters.cutoff = `${year}-${month}-${day}`;
}
</script>

<template>
    <h1>Start new search</h1>

    <BForm @submit.prevent="form.submit(SearchController.store())">
        <BFormGroup
            class="mb-3"
            description="Do not consider repositories created after this date."
            label="Cutoff date"
            label-for="cutoff"
        >
            <template #label> Cutoff date </template>
            <BInputGroup>
                <BFormInput id="cutoff" v-model="form.parameters.cutoff" type="date" />
                <BButton variant="primary" @click="setCutoffToToday">Today</BButton>
                <BButton variant="secondary" @click="form.parameters.cutoff = null">None</BButton>
            </BInputGroup>
        </BFormGroup>

        <BFormGroup class="mb-3" label="Maximum amount of repositories" label-for="max">
            <template #description> Stop searching after <em>accepting</em> this amount of repositories. </template>
            <BFormInput id="max" v-model="form.parameters.max" min="1" type="number" />
        </BFormGroup>

        <BFormGroup
            class="mb-3"
            description="Require at least this share of PHP code in the repository."
            label="PHP language share"
            label-for="php"
        >
            <BInputGroup>
                <BFormInput id="php" v-model="form.parameters.php" max="100" min="0" step="1" type="number" />
                <BInputGroupText>% (bytes)</BInputGroupText>
            </BInputGroup>
        </BFormGroup>

        <BButton type="submit" variant="primary">Start search</BButton>
    </BForm>
</template>
