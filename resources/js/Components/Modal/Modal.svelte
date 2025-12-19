
<script lang="ts">
    import type {Snippet} from "svelte";
    import {
        onMount
    } from "svelte";

    const {
        title,
        content,
        buttons,

        isOpen = false,
        onClose = () => {}
    }: {
        title: Snippet,
        content: Snippet,
        buttons: Snippet,
        isOpen?: boolean,
        onClose?: () => void,
    } = $props();


    onMount(() => {
        const handleKeyDown = (event: KeyboardEvent) => {
            if (event.key === 'Escape' && isOpen) {
                console.info("Escape key pressed, closing modal.");
                onClose();
            }
        };

        window.addEventListener('keydown', handleKeyDown);

        return () => {
            window.removeEventListener('keydown', handleKeyDown);
        };
    })
</script>


<div class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur bg-opacity-50" class:hidden={!isOpen} on:click={() => onClose()}>
    <div class="bg-white rounded-lg shadow-lg w-11/12 max-w-lg">
        <div class="px-6 py-4 border-b">
            {@render title?.()}
        </div>
        <div class="px-6 py-4">
            {@render content?.()}
        </div>
        <div class="px-6 py-4 border-t flex justify-end space-x-2">
            {@render buttons?.()}
        </div>
    </div>
</div>


<style>

</style>
