<script lang="ts">
    import { Label } from "bits-ui";

    import {
        page
    } from "@inertiajs/svelte";

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
        wrapperClassName = "",
        className = "",
        values = $bindable({}),

        onInput = () => {},
        onDebouncedInput = () => {},
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
        wrapperClassName?: string;
        className?: string;
        values?: Record<any, any>;

        onInput?: (event: Event) => void;
        onDebouncedInput?: (event: Event) => void;
    } = $props();


    const debounce = (func: Function, wait: number) => {
        let timeout: ReturnType<typeof setTimeout>;
        return (...args: any[]) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => func(...args), wait);
        };
    };
</script>


<div class="mb-4 {wrapperClassName}">
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
    <select
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
        oninput={(e) => {
            if(onDebouncedInput) {
                debounce(() => onDebouncedInput(e), 300)();
            }

            if(onInput) {
                onInput(e);
            }

        }}
    >
        {#each Object.entries(values) as [optionValue, optionLabel]}
            <option value={optionValue} selected={optionValue == value}>
                {optionLabel}
            </option>
        {/each}
    </select>

    {#if $page.props.errors && $page.props.errors[name]}
        <Label.Root
            id="{name}-error"
            class="text-sm text-red-600 mt-1 block"
        >
            {
                $page.props.errors[name]
            }
        </Label.Root>
    {/if}
</div>
