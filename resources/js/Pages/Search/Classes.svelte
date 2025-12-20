<script lang="ts">
    import AppLayout from "../../Layouts/AppLayout.svelte";
    import { route } from '../../../../vendor/tightenco/ziggy';
    import { Ziggy } from '../../ziggy.js';

    import TextInput from "../../Components/Input/TextInput.svelte";
    import PrimaryButton from "../../Components/Buttons/PrimaryButton.svelte";
    import Modal from "../../Components/Modal/Modal.svelte";
    import {ScrollArea} from "bits-ui";
    import Scrollable from "../../Components/Scrollable/Scrollable.svelte";

    let foundData = $state([]);
    let details = $state(null);

    const doSearch = async (query: string) => {
        console.info("Searching for classes with query:", query);

        const url = route('search.classes', {
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

        foundData = await rawResponse.json();
    };

    const searchDetails = async (classId: string) => {
        console.info("Fetching details for ID:", classId);

        const url = route('search.class.inspect', {
            class: classId
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
    title="Class Search"
>
    <h1 class="text-3xl font-bold text-center mt-8">Class Search</h1>

    <TextInput
        class="mt-6 mx-auto block w-1/2"
        placeholder="Search for classes..."
        onDebouncedInput={(e) => {
            let value = e.target.value;
            console.log(value)
            doSearch(value);
        }}
    />


    <div class="mt-8 mx-auto w-3/4">
        {#if foundData.length === 0}
            <p class="text-center text-gray-500">No classes found.</p>
        {:else}
            <ul class="space-y-4">
                {#each foundData as data}
                    <li class="p-4 flex flex-col gap-2 border border-gray-300 rounded">
                        <h2 class="text-xl font-semibold">{data.name}</h2>
                        <p class="text-gray-700 mt-2">{data.plugin} {data.plugin_version}</p>
                        <PrimaryButton onClick={() => {
                            searchDetails(data.id);
                        }}>
                            View Details
                        </PrimaryButton>
                    </li>
                {/each}
            </ul>
        {/if}
    </div>


    <Modal
        size="medium"
        isOpen={details !== null}
        onClose={() => {
            details = null;
        }}
    >
        {#snippet title()}
            {#if details !== null}
                Class {details.name}
            {/if}
        {/snippet}

        {#snippet content()}
            {#if details !== null}
                <p class="mb-4"><strong>Plugin:</strong> {details.plugin.name}
                    {#if details.plugin.version}
                        (v{details.plugin.version})
                    {/if}
                </p>

                <p class="mb-4">
                    <strong>Phpdoc</strong>
                    {#if details.phpdoc}
                        <div class="mt-2 p-2 bg-gray-50 rounded">
                            <pre class="whitespace-pre-wrap text-sm">{details.phpdoc}</pre>
                        </div>
                    {:else}
                        <span class="text-gray-500">No phpdoc available.</span>
                    {/if}
                </p>


                <Scrollable
                    type="hover"
                    viewportClasses="max-h-96"
                >
                    {#each details.methods as method, index}
                        <div class="mb-4 p-4 border border-gray-200 rounded">
                            <h3 class="font-semibold mb-2">Method {method.name}</h3>
                            <p><strong>Line:</strong> {method.start_line} - {method.end_line}</p>
                            <p><strong>parameters</strong>: {method.parameters}
                            </p>
                            {#if method.phpdoc}
                                <div class="mt-2 p-2 bg-gray-50 rounded">
                                    <pre class="whitespace-pre-wrap text-sm">{method.phpdoc}</pre>
                                </div>
                            {:else}
                                <span class="text-gray-500">No phpdoc available.</span>
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
