import {wayfinder} from '@laravel/vite-plugin-wayfinder';
import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import inertia from "@inertiajs/vite";
import vuePlugin from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            refresh: true,
        }),
        wayfinder({
            formVariants: true,
        }),
        inertia(),
        vuePlugin()
    ],
});
