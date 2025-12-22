<script lang="ts">
    import {
        page
    } from '@inertiajs/svelte';
    import Modal from "../../Components/Modal/Modal.svelte";
    import TextInput from "../../Components/Input/TextInput.svelte";
    import Select from "../../Components/Input/Select.svelte";

    import { route } from '../../../../vendor/tightenco/ziggy';
    import { Ziggy } from '../../ziggy.js';
    import axios from "axios";
    import PrimaryButton from "../../Components/Buttons/PrimaryButton.svelte";


    let {
        isOpen = $bindable(false),
    }: {
        isOpen?: boolean,
    } = $props();



    let vendorName = $state(null);
    let packageName = $state(null);
    let version = $state(null);

    let versions = $state([]);


    const maybeGetVersions = async () => {
        if(!vendorName || !packageName) {
            versions = [];
            return;
        }

        let rawData = await axios.get(
            route('plugin.index', {
                vendor: vendorName,
                package: packageName,
            }, undefined, Ziggy)
        );

        let jsonData = rawData.data;

        versions = [];
        jsonData.forEach((packageData) => {
            versions.push(packageData.version)
        })

        console.info(
            "Fetched versions for",
            vendorName,
            packageName,
            versions
        );
    }


    const indexPlugin = () => {
        axios.post(
            route('plugin.store', {}, undefined, Ziggy),
            {
                vendor: vendorName,
                package: packageName,
                version: version,
            }
        ).then(() => {
            console.info("Plugin indexed successfully");
        }).catch(() => {
            console.error("Failed to index plugin");
        })
    }

</script>

<Modal
    isOpen={isOpen}
    onClose={() => isOpen = false}
    >
    {#snippet title()}
        <h2 class="text-lg font-medium text-gray-900">
            Document a plugin
        </h2>
    {/snippet}

    {#snippet content()}
        <TextInput
            name="vendorName"
            label="Vendor Name"
            onInput={(e) => {
                vendorName = e.target.value;
                maybeGetVersions();
            }}
            required
            class="w-full mb-4"
        />

        <TextInput
            name="packageName"
            label="Package Name"
            onInput={(e) => {
                packageName = e.target.value;
                maybeGetVersions();
            }}
            required
            class="w-full mb-4"
        />

        <Select
            name="version"
            label="Version"
            values={versions.reduce((acc, ver) => {
                acc[ver] = ver;
                return acc;
            }, {})}
            onInput={(e) => version = e.target.value}
            required
            class="w-full"
        />


        <PrimaryButton
            onClick={() => {
                indexPlugin()
            }}
        >
            Index Plugin
        </PrimaryButton>






    {/snippet}

    {#snippet footer()}
        <button
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
            on:click={() => isOpen = false}
        >
            Close
        </button>
    {/snippet}
</Modal>
