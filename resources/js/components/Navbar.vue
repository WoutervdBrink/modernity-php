<script lang="ts" setup>
import { Link, usePage } from '@inertiajs/vue3';
import DashboardController from '@/actions/App/Http/Controllers/DashboardController.ts';
import { href, menu, type MenuItem } from '@/menu.ts';
import { computed } from 'vue';

const page = usePage();

const isActive = computed(() => (item: MenuItem): boolean => {
    const root = page.component.includes('/') ? page.component.split('/')[0] : page.component;

    return root === item.root;
});
</script>

<template>
    <BNavbar v-b-color-mode="'dark'" container="lg" sticky="top" toggleable="md" variant="dark">
        <Link :href="DashboardController.url()" class="navbar-brand">PHP Modernity</Link>
        <BNavbarToggle target="nav-collapse" />
        <BCollapse id="nav-collapse" is-nav>
            <BNavbarNav>
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
        </BCollapse>
    </BNavbar>
</template>

<style scoped></style>
