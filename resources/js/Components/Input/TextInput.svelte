<script lang="ts">
    import { Label } from "bits-ui";

    let {
        label = "",
        centeredLabel = false,

        type = "text",
        name = "",
        value = "",
        placeholder = "",
        required = false,
        autocomplete = "",
        autofocus = false,
        disabled = false,
        className = "",

        onInput = () => {},
    }: {
        label?: string;
        centeredLabel?: boolean;

        type?: string;
        name?: string;
        value?: string;
        placeholder?: string;
        required?: boolean;
        autocomplete?: string;
        autofocus?: boolean;
        disabled?: boolean;
        className?: string;
        onInput?: (event: Event) => void;
    } = $props();
</script>


<div class="mb-4">
    {#if label}
        <Label.Root
            id="{name}-label"
            for={name}
            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 mb-1 block {centeredLabel ? 'text-center' : ''}"
        >
            {
                label
            }
        </Label.Root>
    {/if}
    <input
        type={type}
        name={name}
        id={name}
        class={`border border-gray-300 rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 ${className}`}
        bind:value
        placeholder={placeholder}
        {required}
        {autocomplete}
        {autofocus}
        {disabled}
        oninput={(event) => onInput(event)}
    />
</div>
