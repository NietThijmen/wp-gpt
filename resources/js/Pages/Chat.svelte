<script>
    import AppLayout from "../Layouts/AppLayout.svelte";
    import {
        Form, Link,
        page, router
    } from "@inertiajs/svelte";
    import TextInput from "../Components/Input/TextInput.svelte";
    import Modal from "../Components/Modal/Modal.svelte";
    import PrimaryButton from "../Components/Buttons/PrimaryButton.svelte";

    import { route } from '../../../vendor/tightenco/ziggy';
    import { Ziggy } from '../ziggy.js';



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
                        <Link
                            href={route("chat.show", { chat: conversation.id}, undefined, Ziggy)}
                            class="text-blue-600 hover:underline text-left ">


                            <span class="text-sm">
                                {conversation.title}
                            </span>
                        </Link>
                    </li>
                {/each}

                {#if $page.props.chats.length === 0}
                    <li class="text-blue-400">No conversations found.</li>
                {/if}
            </ul>
        </aside>


        <!--Main content-->
        <main class="w-3/4 p-4 overflow-y-auto">
            {#if !$page.props.chat}
                <h1 class="text-2xl font-bold mb-4">Welcome to the Chat Page</h1>
                <p>Select a conversation from the sidebar to view details.</p>
            {:else}
                <div class="flex flex-col h-full">
                    <h1 class="text-2xl font-bold mb-4">Conversation: {$page.props.chat.title}</h1>
                    <div class="space-y-4 flex-1 overflow-y-auto">
                        {#each $page.props.chat.messages as message}
                            <div class="p-4 border rounded">
                                <p class="font-semibold">{message.role}:</p>
                                <p>{message.message}</p>
                                <span class="text-xs text-gray-500">{new Date(message.created_at).toLocaleString()}</span>
                            </div>
                        {/each}
                    </div>

                    <Form
                        action={
                            route("chat.messages.store", { chat: $page.props.chat.id }, undefined, Ziggy)
                        }
                        method="post"
                        class="mt-4 flex"
                        options={{
                            preserveScroll: true,
                            preserveState: true,
                            preserveUrl: true,
                            replace: true,
                            only: ['chat']
                        }}>
                        <TextInput
                            name="message"
                            label="Your Message"
                            className="flex-1 mr-2 w-full"
                            wrapperClassName="w-full"
                            required={true}
                        />

                        <PrimaryButton type="submit">
                            Send
                        </PrimaryButton>
                    </Form>
                </div>
            {/if}
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
