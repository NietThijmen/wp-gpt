<script lang="ts">
    import { page } from '@inertiajs/svelte'
    import Navigation from "../Components/Navigation/Navigation.svelte";
    import { Toaster } from 'svelte-sonner'
    export let title: string = "App";
</script>


<svelte:head>
    <title>{title} - {$page.props.appName}</title>
</svelte:head>

<main class="min-h-screen bg-gray-100">
    <Navigation/>
    <div class=" mx-auto">
        <slot />
    </div>
    <Toaster position="bottom-right" />


</main>
