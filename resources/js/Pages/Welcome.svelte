<script lang="ts">
    import { router } from '@inertiajs/svelte'
    import PrimaryButton from "@/Components/Buttons/PrimaryButton.svelte";
</script>

<svelte:head>
    <title>WP-GPT - WordPress Hook Search & MCP</title>
</svelte:head>

<main class="min-h-screen bg-gradient-to-br from-background via-background to-accent/5">
    <!-- Hero Section -->
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8">
        <div class="text-center">
            <!-- Main Heading -->
            <h1 class="text-5xl font-bold tracking-tight text-foreground sm:text-6xl md:text-7xl">
                <span class="mt-2 block bg-gradient-to-r from-accent-foreground to-tertiary bg-clip-text text-transparent">
                    WordPress at Your Fingertips
                </span>
            </h1>

            <!-- Subtitle -->
            <p class="mx-auto mt-6 max-w-2xl text-lg text-foreground-alt sm:text-xl">
                A powerful MCP for your IDE that enables lightning-fast searches through WordPress hooks
                from the core and plugins. Built with MeiliSearch for instant results.
            </p>

            <!-- CTA Buttons -->
            <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
                <PrimaryButton onClick={() => router.visit('/login')}>
                    Get Started
                </PrimaryButton>
                <a
                    href="https://github.com/NietThijmen/wp-gpt"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex h-12 items-center justify-center rounded-input border border-border-input px-6 text-[15px] font-semibold shadow-mini transition-all hover:border-border-input-hover hover:bg-muted active:scale-[0.98]"
                >
                    View on GitHub
                </a>
            </div>
        </div>

        <!-- Features Grid -->
        <div class="mt-24">
            <h2 class="text-center text-3xl font-bold text-foreground sm:text-4xl">
                Powerful Features
            </h2>
            <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Feature 1 -->
                <div class="rounded-card border border-border-card bg-background-alt p-6 shadow-card transition-all hover:shadow-lg">
                    <div class="flex h-12 w-12 items-center justify-center rounded-10px bg-accent text-accent-foreground">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-foreground">Full WordPress Index</h3>
                    <p class="mt-2 text-foreground-alt">
                        Complete index of WordPress hooks from core and popular plugins. Search through thousands of hooks instantly.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="rounded-card border border-border-card bg-background-alt p-6 shadow-card transition-all hover:shadow-lg">
                    <div class="flex h-12 w-12 items-center justify-center rounded-10px bg-accent text-accent-foreground">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-foreground">Lightning Fast Search</h3>
                    <p class="mt-2 text-foreground-alt">
                        Powered by MeiliSearch for instant, typo-tolerant search results. Find what you need in milliseconds.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="rounded-card border border-border-card bg-background-alt p-6 shadow-card transition-all hover:shadow-lg">
                    <div class="flex h-12 w-12 items-center justify-center rounded-10px bg-accent text-accent-foreground">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-foreground">IDE Integration</h3>
                    <p class="mt-2 text-foreground-alt">
                        Seamlessly integrate with all MCP-compatible IDEs. Search WordPress hooks without leaving your editor.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="rounded-card border border-border-card bg-background-alt p-6 shadow-card transition-all hover:shadow-lg">
                    <div class="flex h-12 w-12 items-center justify-center rounded-10px bg-accent text-accent-foreground">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-foreground">Rich Documentation</h3>
                    <p class="mt-2 text-foreground-alt">
                        View detailed documentation for each hook including examples, usage patterns, and source locations.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="rounded-card border border-border-card bg-background-alt p-6 shadow-card transition-all hover:shadow-lg">
                    <div class="flex h-12 w-12 items-center justify-center rounded-10px bg-accent text-accent-foreground">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-foreground">Plugin Support</h3>
                    <p class="mt-2 text-foreground-alt">
                        Index and search hooks from any WordPress plugin or composer package through the admin MCP.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="rounded-card border border-border-card bg-background-alt p-6 shadow-card transition-all hover:shadow-lg">
                    <div class="flex h-12 w-12 items-center justify-center rounded-10px bg-accent text-accent-foreground">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-foreground">Class & Function Search</h3>
                    <p class="mt-2 text-foreground-alt">
                        Search through WordPress classes, methods, and functions. Perfect for understanding WP internals.
                    </p>
                </div>
            </div>
        </div>

        <!-- How It Works Section -->
        <div class="mt-24">
            <h2 class="text-center text-3xl font-bold text-foreground sm:text-4xl">
                How It Works
            </h2>
            <div class="mt-12 space-y-8">
                <div class="flex flex-col items-start gap-4 sm:flex-row sm:items-center">
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-10px bg-dark text-background font-bold">
                        1
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-foreground">Install & Configure</h3>
                        <p class="mt-1 text-foreground-alt">Clone the repository, install dependencies, and set up MeiliSearch for indexing.</p>
                    </div>
                </div>
                <div class="flex flex-col items-start gap-4 sm:flex-row sm:items-center">
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-10px bg-dark text-background font-bold">
                        2
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-foreground">Index WordPress</h3>
                        <p class="mt-1 text-foreground-alt">Use the admin MCP to index WordPress core hooks and any plugins or packages you need.</p>
                    </div>
                </div>
                <div class="flex flex-col items-start gap-4 sm:flex-row sm:items-center">
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-10px bg-dark text-background font-bold">
                        3
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-foreground">Connect Your IDE</h3>
                        <p class="mt-1 text-foreground-alt">Integrate the MCP with your favorite IDE and start searching hooks instantly.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer CTA -->
        <div class="mt-24 rounded-card border border-border-card bg-gradient-to-r from-accent/10 to-tertiary/10 p-8 text-center shadow-card sm:p-12">
            <h2 class="text-3xl font-bold text-foreground">
                Ready to supercharge your WordPress development?
            </h2>
            <p class="mt-4 text-lg text-foreground-alt">
                Get started now and experience the fastest way to search WordPress hooks.
            </p>
            <div class="mt-8">
                <PrimaryButton onClick={() => router.visit('/login')}>
                    Start Searching Now
                </PrimaryButton>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-16 border-t border-border-card pt-8 text-center text-sm text-foreground-alt">
            <p>
                Built with ❤️ for the WordPress community.
                <a href="https://github.com/NietThijmen/wp-gpt" target="_blank" class="link">
                    Contribute on GitHub
                </a>
            </p>

            <p>
                Support ongoing development by
                <a href="https://www.buymeacoffee.com/nietthijmen" target="_blank" class="link">
                    ☕buying me a coffee!
                </a>
            </p>
        </div>
    </div>
</main>
