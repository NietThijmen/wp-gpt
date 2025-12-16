<script lang="ts">
    // This layout is used for authentication-related pages

    export let title: string = "Authentication";
</script>


<svelte:head>
    <title>{title} - MyApp</title>
</svelte:head>

<main class="min-h-screen flex flex-col justify-center items-center bg-gray-100">
    <div class="max-w-2xl w-full p-8 bg-white rounded shadow">
        <h1 class="text-2xl font-bold mb-6 text-center">{title}</h1>

        <div class=" mx-auto">
            <slot />
        </div>

    </div>
</main>
