<script>
    import AppLayout from "../Layouts/AppLayout.svelte";
    import {
        Form,
        page, router
    } from "@inertiajs/svelte";
    import TextInput from "../Components/Input/TextInput.svelte";
    import Modal from "../Components/Modal/Modal.svelte";
    import PrimaryButton from "../Components/Buttons/PrimaryButton.svelte";

    import { route } from '../../../vendor/tightenco/ziggy';
    import { Ziggy } from '../ziggy.js';


    const conversations = $page.props.chats;



    let isChatModalShown = $state(false);

</script>

<AppLayout
    title="Chat"
>
    <div class="flex h-full">
        <!--Sidebar-->
        <aside class="w-1/4 border-r p-4 overflow-y-auto">
            <h2 class="text-xl font-bold mb-4">Conversations</h2>
            <ul>

                <li class="mb-2">
                    <button onclick={() => {
                        isChatModalShown = true;
                    }} class="text-blue-600 hover:underline text-left ">
                        <span class="text-sm">
                            Start New Conversation
                        </span>
                    </button>
                </li>

                {#each $page.props.chats as conversation}
                    <li class="mb-2">
                        <button onclick={() => {

                        }} class="text-blue-600 hover:underline text-left ">


                            <span class="text-sm">
                                {conversation.title}
                            </span>
                        </button>
                    </li>
                {/each}

                {#if conversations.length === 0}
                    <li class="text-blue-400">No conversations found.</li>
                {/if}
            </ul>
        </aside>


        <!--Main content-->
        <main class="w-3/4 p-4 overflow-y-auto">
            <h1 class="text-2xl font-bold mb-4">Welcome to the Chat Page</h1>
            <p>Select a conversation from the sidebar to view details.</p>
        </main>
    </div>


    <Modal
        isOpen={isChatModalShown}
        size="medium"
        onClose={() => {
            isChatModalShown = false
        }}
    >
        {#snippet title()}
            Start New Conversation
        {/snippet}

        {#snippet content()}
            <Form
                action={
                    route("chat.store", undefined, undefined, Ziggy)
                }

                method="post"
                options={{
                    preserveScroll: true,
                    preserveState: true,
                    preserveUrl: true,
                    replace: true,
                    only: ['chats'],
                    reset: ['title']
                }}

                onSuccess={() => {
                    isChatModalShown = false
                    router.reload({
                        only: ['chats']
                    })
                }}
            >
                <TextInput
                    name="title"
                    label="Conversation Title"
                    required={true}
                />

                <PrimaryButton
                    type="submit"
                    class="mt-4"
                >
                    Create Conversation
                </PrimaryButton>
            </Form>
        {/snippet}

        {#snippet buttons()}
            <PrimaryButton
                onClick={() => {
                    isChatModalShown = false
                }}
            >
                Close
            </PrimaryButton>
        {/snippet}
    </Modal>
</AppLayout>
