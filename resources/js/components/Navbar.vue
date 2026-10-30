<script lang="ts" setup>
import { Link, usePage } from '@inertiajs/vue3';
import DashboardController from '@/actions/App/Http/Controllers/DashboardController.ts';
import { href, menu, type MenuItem } from '@/menu.ts';
import { computed } from 'vue';
import FailedJobsController from '@/actions/App/Http/Controllers/FailedJobsController.ts';

const page = usePage();

defineProps<{ fluid: boolean }>();

const isActive = computed(() => (item: MenuItem): boolean => {
    const root = page.component.includes('/') ? page.component.split('/')[0] : page.component;

    return root === item.root;
});

const failedJobs: MenuItem = {
    label: 'Failed Jobs',
    root: 'FailedJob',
    href: FailedJobsController.index,
};
</script>

<template>
    <BNavbar v-b-color-mode="'dark'" :container="fluid ? 'fluid' : 'lg'" sticky="top" toggleable="md" variant="dark">
        <Link :href="DashboardController.index.url()" class="navbar-brand">PHP Modernity</Link>
        <BNavbarToggle target="nav-collapse" />
        <BCollapse id="nav-collapse" is-nav>
            <BNavbarNav class="me-auto">
                <InertiaBNavItem
                    v-for="item in menu"
                    :key="item.root"
                    :active="isActive(item)"
                    :href="href(item.href)"
                    prefetch
                >
                    {{ item.label }}
                </InertiaBNavItem>
            </BNavbarNav>
            <BNavbarNav>
                <InertiaBNavItem :active="isActive(failedJobs)" :href="href(failedJobs.href)" prefetch>
                    {{ failedJobs.label }}
                </InertiaBNavItem>
            </BNavbarNav>
        </BCollapse>
    </BNavbar>
</template>

<style scoped></style>
