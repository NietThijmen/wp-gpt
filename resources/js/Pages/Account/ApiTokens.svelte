<script lang="ts">
    import Layout from '../../Layouts/AccountLayout.svelte'
    import {
        Form,
        page
    } from '@inertiajs/svelte';
    import PrimaryButton from "../../Components/Buttons/PrimaryButton.svelte";

    import { route } from '../../../../vendor/tightenco/ziggy';
    import { Ziggy } from '../../ziggy.js';
    import Table from "../../Components/Table/Table.svelte";
    import Modal from "../../Components/Modal/Modal.svelte";
    import TextInput from "../../Components/Input/TextInput.svelte";
    import {toast} from "svelte-sonner";


    let isCreateModalOpen = $state(false)
    let createdToken = $state(null);
</script>

<Layout
    title="API Tokens"
    currentPath={$page.url}

>
    <h1 class="text-2xl font-semibold mb-4">API Tokens</h1>
    <p>Manage your API tokens here</p>
    <PrimaryButton onClick={() => isCreateModalOpen = true}>
        Create New Token
    </PrimaryButton>



    <Table
        headers={['Name', 'Created At', 'Actions']}
        rawData={$page.props.tokens}
    >
        {#snippet rowSnippet(data)}
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {data.name}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {new Date(data.created_at).toLocaleDateString()}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <Form
                    method="delete"
                    action={
                        route('account.api-tokens.destroy', {
                            api_token: $page.props.user.id,
                         }, undefined, Ziggy)
                    }

                    onSuccess={() => {
                        toast.success("API Token deleted successfully!");
                    }}
                >
                    <input type="hidden" name="token_id" value={data.id} />

                    <PrimaryButton
                        type="submit"
                        class="bg-red-600 hover:bg-red-700"
                    >
                        Delete
                    </PrimaryButton>
                </Form>
            </td>
        {/snippet}
    </Table>



    <Modal
        isOpen={isCreateModalOpen}
        onClose={() => isCreateModalOpen = false}
    >
        {#snippet title()}
            <h2 class="text-lg font-semibold">Create New API Token</h2>
        {/snippet}

        {#snippet content()}
            <Form
                method="post"
                action={route('account.api-tokens.store', {}, undefined, Ziggy)}
                class="space-y-4"
                onSuccess={() => {isCreateModalOpen = false; createdToken = $page.props.token; toast.success("API Token created successfully!");}}
            >
                <TextInput
                    name="name"
                    label="Token Name"
                    required
                    class="w-full"
                />

                <PrimaryButton
                    type="submit"
                    form="create-token-form"
                >
                    Create
                </PrimaryButton>
            </Form>
        {/snippet}

        {#snippet buttons()}

            <PrimaryButton
                class="bg-gray-500 hover:bg-gray-600"
                onClick={() => isCreateModalOpen = false}
            >
                Cancel
            </PrimaryButton>
        {/snippet}

    </Modal>


    <Modal
        isOpen={createdToken !== null}
        onClose={() => {
            createdToken = null;
        }}
        size="medium"
    >
        {#snippet title()}
            <h2 class="text-lg font-semibold">API Token Created</h2>
        {/snippet}

        {#snippet content()}
            <p class="mb-4">Please copy your new API token. For your security, it won't be shown again.</p>
            <div class="bg-gray-100 p-4 rounded">
                <code class="break-all font-mono">{createdToken}</code>
            </div>
        {/snippet}

        {#snippet buttons()}
            <PrimaryButton
                onClick={() => {
                    createdToken = null;
                }}
            >
                Close
            </PrimaryButton>
        {/snippet}
    </Modal>

</Layout>
