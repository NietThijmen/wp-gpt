<script lang="ts">
    // Account layout is a wrapper around the AppLayout for account-related pages
    import AppLayout from "./AppLayout.svelte";
    import {Link} from "@inertiajs/svelte";
    export let title: string = "Account";
    export let currentPath: string;

    let pages = [
        { name: 'Profile', href: '/account/profile' },
        { name: 'Password', href: '/account/password' },
        { name: 'Two Factor', href: '/account/two_factor' },
        { name: 'API Tokens', href: '/account/api_tokens' },
    ]
</script>

<AppLayout {title}>
    <div class="max-w-3xl mx-auto p-4">
        <nav class="mb-6">
            <ul class="flex space-x-8 mx-4">
                {#each pages as page}
                    <li
                        class="{currentPath === page.href ? 'nav-active' : 'nav-not-active'}"
                    >
                        <Link
                            href="{page.href}"
                        >
                            {page.name}
                        </Link>
                    </li>
                {/each}
            </ul>
        </nav>
        <slot />
    </div>
</AppLayout>

<style>
    .nav-active {

        border-bottom: 2px solid #3B82F6;
    }

    .nav-not-active:hover {
        border-bottom: 2px solid #93C5FD;
    }



</style>
