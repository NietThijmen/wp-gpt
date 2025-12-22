import './bootstrap';

import { createInertiaApp } from '@inertiajs/svelte'
import { mount, hydrate } from 'svelte'
import '../css/app.css'

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.svelte', { eager: false })
        return pages[`./Pages/${name}.svelte`]();
    },
    setup({ el, App, props }) {
        if (el.dataset.serverRendered === 'true') {
            hydrate(App, { target: el, props })
        } else {
            mount(App, { target: el, props })
        }
    },
})
