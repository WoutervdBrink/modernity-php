<script lang="ts" setup>
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

withDefaults(
    defineProps<{
        title?: string;
        width?: 'narrow' | 'wide';
        fluid?: boolean;
    }>(),
    {
        title: '',
        width: 'wide',
        fluid: false,
    },
);

const page = usePage();
const applicationCommit = computed(() => ({
    long: page.props.application_commit,
    short: page.props.application_commit.substring(0, 7),
}));
</script>

<template>
    <Head :title="title" />
    <div class="h-100 d-flex flex-column">
        <BApp>
            <Navbar :fluid />
            <main class="flex-shrink-0">
                <BContainer :fluid class="mt-3">
                    <template v-if="width === 'narrow'">
                        <BRow>
                            <BCol class="mx-auto" lg="6">
                                <slot />
                            </BCol>
                        </BRow>
                    </template>

                    <slot v-else />
                </BContainer>
            </main>
        </BApp>
        <footer class="footer mt-auto py-3 bg-body-tertiary">
            <BContainer>
                Application <code>{{ applicationCommit.long }}</code>
            </BContainer>
        </footer>
    </div>
</template>
