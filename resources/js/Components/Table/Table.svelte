<script lang="ts">
    import type {Snippet} from "svelte";

    let {
        headers = [] as string[],
        rawData = [] as Object[],
        rowSnippet = (row: Object) => null as Snippet,
    }: {
        headers?: string[],
        rawData?: Object[],
        rowSnippet?: (row: Object) => Snippet,
    } = $props();
</script>

<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            {#each headers as header}
                <th
                    scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                    {header}
                </th>
            {/each}
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        {#each rawData as data}
            <tr>
                {@render rowSnippet(data)}
            </tr>
        {/each}
    </tbody>
</table>

