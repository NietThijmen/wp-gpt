
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
        onClose = () => {},
        size = 'small',
    }: {
        title: Snippet,
        content: Snippet,
        buttons: Snippet,
        isOpen?: boolean,
        onClose?: () => void,
        size?: 'small' | 'medium' | 'large',
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
    <div class="bg-white rounded-lg shadow-lg w-11/12" class:small={size === 'small'} class:medium={size === 'medium'} class:large={size === 'large'} on:click|stopPropagation>
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
    .small {
        width: 300px;
    }

    .medium {
        width: 600px;
    }

    .large {
        width: 900px;
    }



</style>
