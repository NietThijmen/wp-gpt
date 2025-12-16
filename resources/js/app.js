import './bootstrap';

import { createInertiaApp } from '@inertiajs/svelte'
import { mount } from 'svelte'
import '../css/app.css'

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.svelte', { eager: false })
        return pages[`./Pages/${name}.svelte`]();
    },
    setup({ el, App, props }) {
        mount(App, { target: el, props })
    },
})
