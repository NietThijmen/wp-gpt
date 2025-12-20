<script lang="ts">
    import {
        Label,
        PinInput,
        REGEXP_ONLY_DIGITS,
        type PinInputRootSnippetProps
    } from "bits-ui";
    import cn from "clsx";
    import { page } from "@inertiajs/svelte";

    let value = $state("");

    type CellProps = PinInputRootSnippetProps["cells"][0];


    let {
        label = null,
        name = "pin-input",
        length = 6,
        onComplete = () => {}
    }: {
        label?: string | null;
        name?: string;
        length?: number;
        onComplete?: (value: number) => void;
    } = $props();

    function onCompleteInternal() {
        onComplete(Number(value));
    }
</script>

<div class="mb-4">
    {#if label}
        <Label.Root
            id="{name}-label"
            for={name}
            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 mb-1 block"
        >
            {
                label
            }
        </Label.Root>
    {/if}



    {#if name}
        <input
            type="hidden"
            name={name}
            value={value}
        />
    {/if}

    <PinInput.Root
        bind:value
        class="group/pininput text-foreground has-disabled:opacity-30 flex items-center"
        maxlength={6}
        {onCompleteInternal}
        pattern={REGEXP_ONLY_DIGITS}
    >
        {#snippet children({ cells })}
            <div class="flex">
                {#each cells.slice(0, 3) as cell, i (i)}
                    {@render Cell(cell)}
                {/each}
            </div>

            <div class="flex w-10 items-center justify-center">
                <div class="bg-border h-1 w-3 rounded-full"></div>
            </div>

            <div class="flex">
                {#each cells.slice(3, 6) as cell, i (i)}
                    {@render Cell(cell)}
                {/each}
            </div>
        {/snippet}
    </PinInput.Root>


    {#if name && $page.props.errors && $page.props.errors[name]}
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


{#snippet Cell(cell: CellProps)}
    <PinInput.Cell
        {cell}
        class={cn(
      // Custom class to override global focus styles
      "focus-override",
      "relative h-14 w-10 text-[2rem]",
      "flex items-center justify-center",
      "transition-all duration-75",
      "border-foreground/20 border-y border-r first:rounded-l-md first:border-l last:rounded-r-md",
      "text-foreground group-focus-within/pininput:border-foreground/40 group-hover/pininput:border-foreground/40",
      "outline-0",
      "data-active:outline-1 data-active:outline-white"
    )}
    >
        {#if cell.char !== null}
            <div>
                {cell.char}
            </div>
        {/if}
        {#if cell.hasFakeCaret}
            <div
                class="animate-caret-blink pointer-events-none absolute inset-0 flex items-center justify-center"
            >
                <div class="h-8 w-px bg-white"></div>
            </div>
        {/if}
    </PinInput.Cell>
{/snippet}
