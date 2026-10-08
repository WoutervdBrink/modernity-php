import inertia from '@inertiajs/vite';
import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import vuePlugin from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';
import Components from 'unplugin-vue-components/vite';
import { BootstrapVueNextResolver } from 'bootstrap-vue-next/resolvers';
import { componentNames } from 'bootstrap-vue-next';
import Icons from 'unplugin-icons/vite';
import IconsResolver from 'unplugin-icons/resolver';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            refresh: true,
        }),
        wayfinder({
            formVariants: true,
        }),
        inertia({
            ssr: false,
        }),
        vuePlugin(),
        Components({
            resolvers: [
                BootstrapVueNextResolver(),
                IconsResolver({
                    enabledCollections: ['fa6-solid'],
                }),
            ],
            types: [
                {
                    from: 'bootstrap-vue-next/components',
                    names: [...componentNames],
                },
            ],
            dts: 'resources/js/generated/components.d.ts',
            dirs: ['resources/js/shared', 'resources/js/components'],
        }),
        Icons({
            compiler: 'vue3',
            scale: 1,
        }),
    ],
});
