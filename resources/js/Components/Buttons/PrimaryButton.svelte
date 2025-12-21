<script lang="ts">
    import { Button } from "bits-ui";

    let {
        onClick = () => {},
        disabled = false,
        className = "",
    }: {
        onClick?: (event: MouseEvent) => void;
        disabled?: boolean;
        className?: string;
    } = $props();

</script>


<Button.Root
    class="rounded-input bg-dark text-background shadow-mini hover:bg-dark/95 inline-flex
	h-12 items-center justify-center px-[21px] text-[15px]
	font-semibold active:scale-[0.98] active:transition-all {className} {disabled ? 'cursor-not-allowed' : ''}"

    {disabled}

    onclick={(event) => {
        onClick(event);
    }}

>
    <slot />
</Button.Root>
