<script lang="ts" setup>
import { Head } from '@inertiajs/vue3';

withDefaults(
    defineProps<{
        title?: string;
        width?: 'narrow' | 'wide';
    }>(),
    {
        title: '',
        width: 'wide',
    },
);
</script>

<template>
    <Head :title="title" />
    <BApp>
        <Navbar />
        <BContainer class="mt-3">
            <template v-if="width === 'narrow'">
                <BRow>
                    <BCol class="mx-auto" lg="6">
                        <slot />
                    </BCol>
                </BRow>
            </template>

            <slot v-else />
        </BContainer>
    </BApp>
</template>
