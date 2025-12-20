<script>
    import AccountLayout from "../../Layouts/AccountLayout.svelte";
    import {Form, page} from "@inertiajs/svelte";
    import PrimaryButton from "../../Components/Buttons/PrimaryButton.svelte";
    import Pin from "../../Components/Input/Pin.svelte";
</script>

<AccountLayout
    currentPath={$page.url}
    title="Two-Factor Authentication"
    >
    <div class="max-w-3xl mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6">Two-Factor Authentication</h1>
        <p class="mb-4">Manage your two-factor authentication settings here.</p>

        {#if $page.props.has_two_factor}
            <p>Two-factor authentication is currently enabled on your account.</p>
            <p class="mt-4">Store these recovery codes in a safe place. They can be used to recover access to your account if you lose your two-factor authentication device.</p>


            <div class="grid grid-cols-2 my-2">
                {#each $page.props.recovery_codes as code}
                    <div class="p-2 border m-1 rounded bg-gray-100 text-center font-mono">
                        {code}
                    </div>
                {/each}
            </div>



            <p class="mt-4">If you wish to disable two-factor authentication, click the button below.</p>

            <Form method="delete" action="/user/two-factor-authentication">
                <PrimaryButton
                    type="submit"
                    class="mt-4">
                    Disable Two-Factor Authentication
                </PrimaryButton>
            </Form>

        {:else if $page.props.qr_code_svg}
            <p>Scan the following code and input the 2FA code below.</p>

            <div class="qr-code">
                {@html $page.props.qr_code_svg}
            </div>


            <Form
                method="post"
                action="/user/confirmed-two-factor-authentication"
                class="mt-4"
            >

                <Pin
                    length={6}
                    name="code"
                    label="Two-Factor Authentication Code"
                    required
                    class="mt-4 w-32" />

                <PrimaryButton
                    type="submit"
                    class="mt-4">
                    Confirm
                </PrimaryButton>
            </Form>


        {:else}
            <Form method="post" action="/user/two-factor-authentication">
                <p>You have not enabled two-factor authentication.</p>
                <PrimaryButton
                    type="submit"
                    class="mt-4">
                    Enable Two-Factor Authentication
                </PrimaryButton>
            </Form>

        {/if}
    </div>
</AccountLayout>
