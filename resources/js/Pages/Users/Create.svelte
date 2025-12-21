<script>
    import Layout from '@/Layouts/AppLayout.svelte'
    import {Form} from "@inertiajs/svelte";
    import TextInput from "../../Components/Input/TextInput.svelte";
    import PrimaryButton from "../../Components/Buttons/PrimaryButton.svelte";




    import { route } from '../../../../vendor/tightenco/ziggy';
    import { Ziggy } from '../../ziggy.js';
</script>

<Layout
    title="Create User"
>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Create User</h1>


        <Form
            method="post"
            action={route('users.store', {}, undefined, Ziggy)}
            class="space-y-4"
        >

            <TextInput
                name="name"
                label="Name"
                required
                class="w-full"
            />

            <TextInput
                name="email"
                label="Email"
                type="email"
                required
                class="w-full"
            />

            <TextInput
                name="password"
                label="Password"
                type="password"
                required
                class="w-full"
            />

            <TextInput
                name="password_confirmation"
                label="Confirm Password"
                type="password"
                required
                class="w-full"
            />


            <PrimaryButton
                type="submit"
            >
                Create User
            </PrimaryButton>
        </Form>


    </div>

</Layout>
