<script lang="ts">
    import { router } from '@inertiajs/svelte'
    import AuthLayout from "@/Layouts/AuthLayout.svelte";
    import TextInput from "@/Components/Input/TextInput.svelte";
    import PrimaryButton from "@/Components/Buttons/PrimaryButton.svelte";


    let username = $state("");
    let password = $state("");
    function login() {
        router.post(
            "/login",
            {
                email: username,
                password: password,
            }
        );

    }
</script>

<AuthLayout
    title="Login"
>
    <div class="flex flex-col">
        <TextInput
            label="Email Address"
            centeredLabel={false}
            type="email"
            name="email"
            placeholder="Enter your email"
            required={true}
            className="mb-4"
            value={username}
            onInput={(e) => {
                username = e.target.value
            }}
        />

        <TextInput
            label="Password"
            centeredLabel={false}
            type="password"
            name="password"
            placeholder="Enter your password"
            required={true}
            className="mb-6"
            value={password}
            onInput={(e) => {
                password = e.target.value
            }}
        />


        <PrimaryButton

            onClick={login}
        >
            Login
        </PrimaryButton>
    </div>

</AuthLayout>
