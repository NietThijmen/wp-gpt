<script lang="ts">
    import AppLayout from "../../Layouts/AppLayout.svelte";
    import {
        page
    } from '@inertiajs/svelte';
    import TextInput from "../../Components/Input/TextInput.svelte";
    import {codeToHtml} from "shiki";


    const plugin = $page.props.plugin;
    const files = $page.props.files;

    let searchQuery = $state('');
    let selectedFile = $state(null);

    const theme = 'min-light';


    // Store generated HTML for each method (keyed by method name)
    let methodCodeBlocks = $state({});

    let _methodGen = 0;

    $effect(() => {
        const file = selectedFile;

        if (!file) {
            methodCodeBlocks = {};
            return;
        }

        const gen = ++_methodGen;
        let cancelled = false;

        (async () => {
            console.info("Generating method code blocks (gen)", gen);
            const blocks = {};
            try {
                for (const classItem of (file.classes || [])) {
                    blocks[classItem.className + '-phpdoc'] = await codeToHtml(classItem.phpdoc || '', {
                        lang: 'php',
                        theme: theme
                    });

                    for (const method of (classItem.methods || [])) {
                        const contentLines = (file.content || '').split('\n');
                        const str = contentLines.slice(method.start_line - 1, method.end_line).join('\n');
                        // await shiki conversion
                        blocks[method.name] = await codeToHtml(str, {lang: 'php', theme: theme});
                        blocks[method.name + '-phpdoc'] = await codeToHtml(method.phpdoc || '', {
                            lang: 'php',
                            theme: theme
                        });
                    }
                }

                if (!cancelled && gen === _methodGen) {
                    methodCodeBlocks = blocks;
                    console.info("Method code blocks ready", Object.keys(blocks));
                } else {
                    console.info("Discarded outdated generation", gen);
                }
            } catch (err) {
                if (!cancelled) console.error("Error generating method code blocks", err);
            }
        })();

        return () => {
            cancelled = true;
        };
    });

    let searchedFiles = $derived.by(() => {
        console.info("Found a file");
        if (!searchQuery) {
            return files;
        }


        // modify the files filter to search for file.classes in addition to file.path
        return files.filter(file => {
            const pathMatch = file.path.toLowerCase().includes(searchQuery.toLowerCase());
            const classMatch = (file.classes || []).some(classItem =>
                classItem.className.toLowerCase().includes(searchQuery.toLowerCase())
            );
            return pathMatch || classMatch;
        });
    });

</script>

<AppLayout
    title="Documentation - {plugin.name}"
>
    <div class="flex h-full">
        <aside class="w-1/4 border-r p-4 overflow-y-auto">
            <h2 class="text-xl font-bold mb-4">Files</h2>
            <TextInput
                label="Search Files"
                placeholder="Search files..."
                class="mb-4"
                onInput={(e) => {
                    searchQuery = e.target.value;
                }}
            />
            <ul>
                {#each searchedFiles as file}
                    <li class="mb-2">
                        <button onclick={() => {
                            selectedFile = file;
                        }} class="text-blue-600 hover:underline text-left ">{
                            file.path
                        }

                            {#if selectedFile && selectedFile.path === file.path} (Selected){/if}
                        </button>
                    </li>
                {/each}
            </ul>
        </aside>

        <main class="w-3/4 p-4 overflow-y-auto">
            {#if selectedFile}
                {#each selectedFile.classes as classItem}
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold mb-2">Class: {classItem.className}</h2>
                        <pre class="bg-gray-50 p-4 rounded overflow-x-auto">
                                {@html methodCodeBlocks[classItem.className + '-phpdoc'] || ''}
                        </pre>

                        {#each classItem.methods as method}
                            <div class="mt-4">
                                <h3 class="text-lg font-semibold mb-1">[{method.visibility}] Method: {method.name}
                                    ({method.parameters})</h3>
                                <pre class="bg-gray-50 p-3 rounded overflow-x-auto">
                                        {@html methodCodeBlocks[method.name + '-phpdoc'] || ''}
                                    </pre>


                                <div class="mt-2">
                                    <h5 class="font-semibold mb-1">Code:</h5>
                                    <pre class="bg-gray-50 p-3 rounded overflow-x-auto">
                                        {@html methodCodeBlocks[method.name] || ''}
                                    </pre>
                                </div>
                            </div>
                        {/each}
                    </div>
                {/each}
            {:else}
                <p class="text-gray-500">Select a file to view its content.</p>
            {/if}
        </main>
    </div>
</AppLayout>
