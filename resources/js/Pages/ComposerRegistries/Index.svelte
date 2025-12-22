<script>
    import Layout from '@/Layouts/AppLayout.svelte'

    import {
        Form,
        Link,
        page
    } from '@inertiajs/svelte';

    import { route } from '../../../../vendor/tightenco/ziggy';
    import { Ziggy } from '../../ziggy.js';
    import Table from "../../Components/Table/Table.svelte";
    import PrimaryButton from "../../Components/Buttons/PrimaryButton.svelte";
    import {toast} from "svelte-sonner";
</script>

<Layout
    name="Composer Registries"
>

    <div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Composer Registries</h1>
        <h3 class="mb-4">Manage your Composer package registries here.</h3>


        <Link
            href={route('composer-registries.create', {}, undefined, Ziggy)}
            class="inline-block mb-4"
        >
            <PrimaryButton>
                Add New Registry
            </PrimaryButton>
        </Link>

        <br/>

        <Table
            className="w-auto"
            headers={['ID', 'URL', 'Created At', 'Actions']}
            rawData={$page.props.registries}
        >
            {#snippet rowSnippet(data)}
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {data.id}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {data.domain}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {new Date(data.created_at).toLocaleDateString()}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <Form
                        method="delete"
                        action={route('composer-registries.destroy', {
                            registry: data.id
                        }, undefined, Ziggy)}
                        onSuccess={() => {
                            toast.success("Registry deleted successfully!");
                        }}
                    >
                        <input type="hidden" name="_method" value="delete" />
                        <PrimaryButton
                            type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                        >
                            Delete
                        </PrimaryButton>
                    </Form>
                </td>
            {/snippet}
        </Table>
    </div>


</Layout>
