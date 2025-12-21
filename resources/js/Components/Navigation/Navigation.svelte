<script lang="ts">
    import { Link, page } from '@inertiajs/svelte'
    import { slide } from 'svelte/transition'

    let open = false

    const links = [
        { name: "Hooks", href: "/hook-search" },
        { name: "Classes", href: "/class-search" },
        { name: "Documentor", href: "/documentation" },
        { name: "Users", href: "/users" },
        { name: "Registries", href: "/registries" },
        { name: "Account", href: "/account/profile" },
    ]
</script>

<nav class="bg-gray-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex-shrink-0">
                <a href="/" class="font-bold text-lg hover:underline">{$page.props.appName}</a>
            </div>

            <!-- Desktop links -->
            <div class="hidden sm:flex sm:items-center sm:space-x-4">
                {#each links as link}
                    <Link href={link.href} class="hover:underline">{link.name}</Link>
                {/each}
            </div>

            <!-- Mobile button -->
            <div class="sm:hidden flex items-center">
                <button
                    type="button"
                    class="p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-white"
                    aria-controls="mobile-menu"
                    aria-expanded={open}
                    aria-label="Toggle navigation"
                    on:click={() => (open = !open)}
                >
                    {#if open}
                        <!-- Close icon -->
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    {:else}
                        <!-- Hamburger icon -->
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    {/if}
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    {#if open}
        <div id="mobile-menu" class="sm:hidden px-2 pt-2 pb-3 space-y-1" transition:slide>
            {#each links as link}
                <Link href={link.href} class="block px-3 py-2 rounded-md hover:bg-gray-700">{link.name}</Link>
            {/each}
        </div>
    {/if}
</nav>
