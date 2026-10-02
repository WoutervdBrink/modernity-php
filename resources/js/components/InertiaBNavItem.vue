<script lang="ts" setup>
import { Link } from '@inertiajs/vue3';

const props = withDefaults(
    defineProps<{
        active?: boolean;
        disabled?: boolean;
        href?: string;
        prefetch?: boolean;
    }>(),
    {
        active: undefined,
        disabled: undefined,
        href: undefined,
        prefetch: undefined,
    },
);
</script>

<template>
    <li class="nav-item">
        <Link
            :class="{ active }"
            :disabled="props.disabled"
            :href
            :prefetch
            :tabindex="props.disabled ? -1 : undefined"
            class="nav-link"
        >
            <slot />
        </Link>
    </li>
</template>
