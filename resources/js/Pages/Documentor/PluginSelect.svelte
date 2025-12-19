<script lang="ts">
    import {Link, page} from '@inertiajs/svelte';
    import AppLayout from "../../Layouts/AppLayout.svelte";
    const plugins = $page.props.plugins;
</script>

<AppLayout>
    <div class="max-w-4xl mx-auto mt-12">
        <h1 class="text-3xl font-bold text-center mb-8">Select a Plugin to Document</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {#each plugins as plugin}
                <Link
                    href="/documentation/{plugin.id}"
                    class="block p-6 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-200"
                >
                    <h2 class="text-xl font-semibold mb-2">{plugin.name}</h2>
                    <p class="text-gray-600">{plugin.version}</p>
                </Link>
            {/each}
        </div>
    </div>
</AppLayout>
