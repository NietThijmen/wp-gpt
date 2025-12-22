import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import {
    svelte
} from '@sveltejs/vite-plugin-svelte';
import tailwindcss from "@tailwindcss/vite";
export default defineConfig({
    plugins: [
        svelte(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            ssr: 'resources/js/ssr.js',
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },

    resolve: {
        alias: {
            "@": "/resources/js",
            "@pages": "/resources/js/Pages",
            "@components": "/resources/js/Components",
            "@layouts": "/resources/js/Layouts",
        }
    }
});
