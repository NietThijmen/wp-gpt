<script>
    import AppLayout from "../../Layouts/AppLayout.svelte";
    import {
        Form,
        page
    } from "@inertiajs/svelte";

    import { route } from '../../../../vendor/tightenco/ziggy';
    import { Ziggy } from '../../ziggy.js';
    import TextInput from "../../Components/Input/TextInput.svelte";
    import PrimaryButton from "../../Components/Buttons/PrimaryButton.svelte";
    import AccountLayout from "../../Layouts/AccountLayout.svelte";

    const updateProfileRoute = route('account.profile.update', {
        profile: $page.props.user.id
    }, undefined, Ziggy);
</script>

<AccountLayout
    currentPath={$page.url}
    title="My Account"
>
    <div class="max-w-3xl mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6">My Account</h1>
        <p class="mb-4">Manage your account settings and personal information here.</p>

        <!--Edit username/email-->
        <div class="space-y-4">
            <div>
                <h3 class="text-lg font-semibold">Profile Information</h3>
                <p class="text-gray-600">Update your account's profile information and email address</p>

                <Form
                    method="put"
                    action="{updateProfileRoute}"
                    class="mt-4 space-y-4">
                    <TextInput
                        name="name"
                        label="Name"
                        value="{$page.props.user.name}"
                        required
                        class="w-full" />

                    <TextInput
                        name="email"
                        label="Email"
                        type="email"
                        value="{$page.props.user.email}"
                        required
                        class="w-full" />

                    <PrimaryButton
                        type="submit"
                        class="mt-4">
                        Save
                    </PrimaryButton>
                </Form>
            </div>
        </div>
    </div>
</AccountLayout>
