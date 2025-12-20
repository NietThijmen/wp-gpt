<script>
    import AuthLayout from "../../Layouts/AuthLayout.svelte";
    import Pin from "../../Components/Input/Pin.svelte";
    import {Form} from "@inertiajs/svelte";
    import PrimaryButton from "../../Components/Buttons/PrimaryButton.svelte";
</script>

<AuthLayout
    title="Two-Factor Authentication"
>
    <Form class="max-w-3xl mx-auto p-4"
          action="/two-factor-challenge"
          method="post"

    >
        <div class="flex justify-center">
            <Pin
                length={6}
                name="code"
                label="Two-Factor Authentication Code"
                required
            />
        </div>

        <div class="flex justify-center">
            <PrimaryButton>
                Log in
            </PrimaryButton>
        </div>

    </Form>
</AuthLayout>
