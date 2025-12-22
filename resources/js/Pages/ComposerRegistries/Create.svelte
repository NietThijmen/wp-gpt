<script>
    import Layout from '@/Layouts/AppLayout.svelte'
    import {Form} from "@inertiajs/svelte";

    import { route } from '../../../../vendor/tightenco/ziggy';
    import { Ziggy } from '../../ziggy.js';
    import TextInput from "../../Components/Input/TextInput.svelte";
    import PrimaryButton from "../../Components/Buttons/PrimaryButton.svelte";
    import {toast} from "svelte-sonner";
</script>


<Layout
    title="Create Composer Registry"
>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Create Composer Registry</h1>


        <Form
            method="post"
            action={route('composer-registries.store', {}, undefined, Ziggy)}
            onSuccess={() => {
                toast.success("Registry created successfully!");
            }}
            class="space-y-4">

            <TextInput
                name="domain"
                label="Registry URL"
                required
                class="w-full"
            />


            <TextInput
                name="auth_token"
                label="Authentication Token (optional)"
                class="w-full"
            />

            <TextInput
                name="username"
                label="Username (optional)"
                class="w-full"
            />

            <TextInput
                name="password"
                label="Password (optional)"
                type="password"
                class="w-full"
            />


            <PrimaryButton
                type="submit"
            >
                Create Registry
            </PrimaryButton>

        </Form>
    </div>

</Layout>
