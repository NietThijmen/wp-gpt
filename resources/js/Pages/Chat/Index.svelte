<script>
    import Layout from '@/Layouts/AppLayout.svelte'

    import { route } from '../../../../vendor/tightenco/ziggy';
    import { Ziggy } from '../../ziggy.js';

    import {
        Form,
        Link,
        page
    } from '@inertiajs/svelte';

    import PrimaryButton from "../../Components/Buttons/PrimaryButton.svelte";
    import {marked} from "marked";



    let allParsedMessages = $state([]);
    $effect(() => {
        let messages = $page.props.conversation ? $page.props.conversation.messages : [];
        // use marked to parse any markdown in the messages
        allParsedMessages = messages.map(msg => {
            return {
                ...msg,
                parsedContent: marked.parse(msg.content)
            }
        });
    });
</script>




<Layout title="Chat">
<!--    register a sidebar with conversations, and a side where you can view a conversation-->
    <main class="grid grid-cols-4 gap-4 min-h-screen">
        <section class="col-span-1 border-r border-gray-300 p-4">
            <h2 class="text-lg font-semibold mb-4">Conversations</h2>
            <!-- List of conversations -->
            <ul>

                <li class="mb-2">

                    <Form
                        method="POST"
                        href={route('chat.store', undefined, undefined, Ziggy)}
                        >
                        <button
                            class="text-blue-500"
                        >
                            + New Conversation
                        </button>
                    </Form>
                </li>

                {#each $page.props.conversations as conversation}
                    <li class="mb-2">
                        <Link
                            href={route('chat.show', { chat: conversation.id }, undefined, Ziggy)}
                        >
                            {conversation.user.name} {conversation.created_at}
                        </Link>
                    </li>
                {/each}
            </ul>
        </section>
        <section class="col-span-3 p-4 min-h-full">
            <h2 class="text-lg font-semibold mb-4">Chat Window</h2>

            {#if $page.props.conversation}
                <div class="mb-4">
                    <div class="border border-gray-300 rounded p-4 h-96 overflow-y-auto">
                        {#each allParsedMessages as message}
                            <div class="mb-2">
                                <strong>{message.role}:</strong>
                                {@html message.parsedContent}
                            </div>
                        {/each}
                    </div>
                </div>
                <Form method="PATCH" action={route('chat.update', { chat: $page.props.conversation.id }, undefined, Ziggy)}>
                    <textarea
                        name="message"
                        class="w-full border border-gray-300 rounded p-2 mb-2"
                        rows="3"
                        placeholder="Type your message..."
                    ></textarea>
                    <PrimaryButton>
                        Send Message
                    </PrimaryButton>
                </Form>
            {:else}
                <p>Select a conversation to view messages.</p>
            {/if}
        </section>
    </main>


</Layout>
