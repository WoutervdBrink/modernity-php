<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { BApp, BContainer } from 'bootstrap-vue-next';

withDefaults(
    defineProps<{
        title?: string;
    }>(),
    {
        title: '',
    },
);
</script>

<template>
    <Head :title="title" />
    <BApp>
        <BContainer class="mt-3 mt-md-5">
            <slot />
        </BContainer>
    </BApp>
</template>
