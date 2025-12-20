<script>
    import AccountLayout from "../../Layouts/AccountLayout.svelte";
    import {
        Form,
        page
    } from "@inertiajs/svelte";
    import PrimaryButton from "../../Components/Buttons/PrimaryButton.svelte";
    import TextInput from "../../Components/Input/TextInput.svelte";

    import { route } from '../../../../vendor/tightenco/ziggy';
    import { Ziggy } from '../../ziggy.js';

    const updatePasswordRoute = route('account.password.update', {
        password: $page.props.user.id
    }, undefined, Ziggy);
</script>

<AccountLayout
    currentPath={$page.url}
    title="Change Password"
    >
    <div class="max-w-3xl mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6">Change Password</h1>
        <p class="mb-4">Update your account's password here.</p>

        <Form
            method="put"
            action={updatePasswordRoute}
            class="mt-4 space-y-4">
            <div>
                <TextInput
                    name="current_password"
                    label="Current Password"
                    type="password"
                    required
                    class="w-full" />
            </div>

            <div>
                <TextInput
                    name="new_password"
                    label="New Password"
                    type="password"
                    required
                    class="w-full" />
            </div>

            <div>
                <TextInput
                    name="new_password_confirmation"
                    label="Confirm New Password"
                    type="password"
                    required
                    class="w-full" />
            </div>

            <PrimaryButton
                type="submit"
                class="mt-4">
                Update Password
            </PrimaryButton>
        </Form>
    </div>
</AccountLayout>
