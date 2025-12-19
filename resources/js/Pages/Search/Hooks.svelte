<script lang="ts">
    import AppLayout from "../../Layouts/AppLayout.svelte";
    import { route } from '../../../../vendor/tightenco/ziggy';
    import { Ziggy } from '../../ziggy.js';

    import TextInput from "../../Components/Input/TextInput.svelte";
    import PrimaryButton from "../../Components/Buttons/PrimaryButton.svelte";
    import Modal from "../../Components/Modal/Modal.svelte";
    import {ScrollArea} from "bits-ui";
    import Scrollable from "../../Components/Scrollable/Scrollable.svelte";

    let foundHooks = $state([]);
    let details = $state(null);

    const doSearch = async (query: string) => {
        console.info("Searching for hooks with query:", query);

        const url = route('search.hooks', {
            query: query
        }, undefined, Ziggy);

        const rawResponse = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            "credentials": "same-origin"
        });

        foundHooks = await rawResponse.json();
    };

    const searchDetails = async (hookId: string) => {
        console.info("Fetching details for hook ID:", hookId);

        const url = route('search.hook.inspect', {
            hook: hookId
        }, undefined, Ziggy);

        const rawResponse = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            "credentials": "same-origin"
        });

        details = await rawResponse.json();
    };
</script>

<AppLayout
    title="Hook Search"
>
    <h1 class="text-3xl font-bold text-center mt-8">Hook Search</h1>

    <TextInput
        class="mt-6 mx-auto block w-1/2"
        placeholder="Search for hooks..."
        onDebouncedInput={(e) => {
            let value = e.target.value;
            console.log(value)
            doSearch(value);
        }}
    />


    <div class="mt-8 mx-auto w-3/4">
        {#if foundHooks.length === 0}
            <p class="text-center text-gray-500">No hooks found.</p>
        {:else}
            <ul class="space-y-4">
                {#each foundHooks as hook}
                    <li class="p-4 flex flex-col gap-2 border border-gray-300 rounded">
                        <h2 class="text-xl font-semibold">{hook.name} ({hook.type})</h2>
                        <p class="text-gray-700 mt-2">{hook.plugin} {hook.plugin_version}</p>
                        <PrimaryButton onClick={() => {
                            searchDetails(hook.id);
                        }}>
                            View Details
                        </PrimaryButton>
                    </li>
                {/each}
            </ul>
        {/if}
    </div>


    <Modal
        isOpen={details !== null}
        onClose={() => {
            details = null;
        }}
    >
        {#snippet title()}
            {#if details !== null}
                Hook {details.name}
            {/if}
        {/snippet}

        {#snippet content()}
            {#if details !== null}
                <p class="mb-4"><strong>Type:</strong> {details.type}</p>
                <p class="mb-4"><strong>Plugin:</strong> {details.plugin}
                    {#if details.plugin_version}
                        (v{details.plugin_version})
                    {/if}
                </p>


                <Scrollable
                    type="hover"
                    viewportClasses="max-h-96"
                >
                    {#each details.occurrences as occurrence, index}
                        <div class="mb-4 p-4 border border-gray-200 rounded">
                            <h3 class="font-semibold mb-2">Occurrence {index + 1}</h3>
                            <p><strong>File:</strong> {occurrence.file_path}</p>
                            <p><strong>Line:</strong> {occurrence.line_number}</p>
                            {#if occurrence.context}
                                <div class="mt-2 p-2 bg-gray-50 rounded">
                                    <pre class="whitespace-pre-wrap text-sm">{occurrence.context}</pre>
                                </div>
                            {/if}
                        </div>
                    {/each}
                </Scrollable>
            {/if}
        {/snippet}

        {#snippet buttons()}
            <PrimaryButton
                onClick={() => {
                    details = null;
                }}
            >
                Close
            </PrimaryButton>
        {/snippet}

    </Modal>



</AppLayout>
