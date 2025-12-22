<script>
    import Layout from '@/Layouts/AppLayout.svelte'

    import {
        Form, Link,
        page
    } from '@inertiajs/svelte';
    import Table from "../../Components/Table/Table.svelte";
    import PrimaryButton from "../../Components/Buttons/PrimaryButton.svelte";


    import { route } from '../../../../vendor/tightenco/ziggy';
    import { Ziggy } from '../../ziggy.js';
    import {toast} from "svelte-sonner";
</script>

<Layout
    title="Users"
>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Users</h1>

        <Link
            href={route('users.create', {}, undefined, Ziggy)}
            class="inline-block mb-4"
        >
            <PrimaryButton>
                Create New User
            </PrimaryButton>
        </Link>



        <br/>
        <Table
        className="w-auto"
        headers={['ID', 'Name', 'Email', 'Created At', 'Actions']}
        rawData={$page.props.users}
    >
        {#snippet rowSnippet(data)}
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {data.id}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {data.name}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {data.email}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {new Date(data.created_at).toLocaleDateString()}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <Form
                    method="delete"
                    action={`/users/${data.id}`}
                    onSuccess={() => {
                        toast.success("User deleted successfully!");
                    }}
                >
                    <PrimaryButton
                        type="submit"
                        disabled={data.id === $page.props.user.id}
                    >
                        Delete
                    </PrimaryButton>
                </Form>

            </td>
        {/snippet}
    </Table>
    </div>
</Layout>
