<script lang="ts">
    // This layout is used for authentication-related pages

    import { page } from '@inertiajs/svelte'
    export let title: string = "Authentication";
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

<main class="min-h-screen flex flex-col justify-center items-center bg-gray-100">
    <div class="max-w-2xl w-full p-8 bg-white rounded shadow">
        <h1 class="text-2xl font-bold mb-6 text-center">{title}</h1>

        <div class=" mx-auto">
            <slot />
        </div>

    </div>
</main>
