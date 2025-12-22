<script lang="ts">
    import { page } from '@inertiajs/svelte'
    import Navigation from "../Components/Navigation/Navigation.svelte";
    import { Toaster } from 'svelte-sonner'
    export let title: string = "App";
</script>


<svelte:head>
    <title>{title} - {$page.props.appName}</title>

    <meta name="description" content="A powerful MCP for your IDE that enables lightning-fast searches through WordPress hooks from the core and plugins. Built with MeiliSearch for instant results." />
    <meta name="keywords" content="WordPress, MCP, IDE, Hook Search, MeiliSearch, Plugin Development, WordPress Development" />
    <meta name="author" content="NietThijmen" />

    <meta property="og:title" content="{title} - WordPress Hook Search & MCP" />
    <meta property="og:description" content="A powerful MCP for your IDE that enables lightning-fast searches through WordPress hooks from the core and plugins. Built with MeiliSearch for instant results." />
    <meta property="og:type" content="website" />
</svelte:head>

<main class="min-h-screen bg-gray-100">
    <Navigation/>
    <div class=" mx-auto">
        <slot />
    </div>
    <Toaster position="bottom-right" />


</main>
