<script lang="ts">
    import AppLayout from "../../Layouts/AppLayout.svelte";
    import {
        page
    } from '@inertiajs/svelte';
    import TextInput from "../../Components/Input/TextInput.svelte";
    import {codeToHtml} from "shiki";
    import Modal from "../../Components/Modal/Modal.svelte";
    import PrimaryButton from "../../Components/Buttons/PrimaryButton.svelte";

    import { route } from '../../../../vendor/tightenco/ziggy';
    import { Ziggy } from '../../ziggy.js';
    import { marked } from 'marked';
    import Scrollable from "../../Components/Scrollable/Scrollable.svelte";

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



    let aiQuestionState = $state({
        'state': 'idle'
    });

    async function askAboutClass(classId: string) {
        console.info("Asking AI about class", classId);
        aiQuestionState.state = 'loading';

        const url = route(
            'chat.explain-class',
            {
                fileClass: classId
            },
            undefined,
            Ziggy
        )

        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.info("AI response received", data);
            let rawMessage = data.explanation.text || 'No explanation provided by AI.';
            let html = marked.parse(rawMessage);

            aiQuestionState = {
                'state': 'answered',
                'answer': html
            };
        } catch (error) {
            console.error("Error asking AI about class", error);
            aiQuestionState = {
                'state': 'answered',
                'answer': 'An error occurred while fetching the AI response.'
            };
        }

    }

</script>

<AppLayout
    title="Documentation - {plugin.name}"
>
    <div class="flex h-full">
        <!--Sidebar-->
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
                        }} class="text-blue-600 hover:underline text-left ">
                            {#each file.classes as classItem}
                                <div class="text-gray-500">{classItem.className}</div>
                            {/each}

                            <span class="text-sm">
                                {file.path}
                            </span>

                            {#if selectedFile && selectedFile.path === file.path} (Selected){/if}
                        </button>
                    </li>
                {/each}
            </ul>
        </aside>

        <!--Main content-->
        <main class="w-3/4 p-4 overflow-y-auto">
            {#if selectedFile}
                {#each selectedFile.classes as classItem}
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold mb-2">Class: {classItem.className}

                            <PrimaryButton class="ml-4 px-2 py-1 text-sm" onClick={() => {
                                askAboutClass(classItem.id);
                            }}>
                                Ask AI about this class
                            </PrimaryButton>
                        </h2>
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


    <!--AI Modal-->
    <Modal
        isOpen={aiQuestionState.state !== 'idle'}
        onClose={() => { aiQuestionState = { 'state': 'idle' }; }}
        size="large"
    >
        {#snippet title()}
            AI Class Information
        {/snippet}

        {#snippet content()}
            {#if aiQuestionState.state === 'loading'}
                <p>Loading...</p>
            {:else if aiQuestionState.state === 'answered'}
                <Scrollable
                    type="hover"
                    viewportClasses="max-h-96 p-4"
                >
                    {@html aiQuestionState.answer}
                </Scrollable>
            {/if}
        {/snippet}
        {#snippet buttons()}
            <PrimaryButton
                onClick={() => { aiQuestionState = { 'state': 'idle' }; }}
            >
                Close
            </PrimaryButton>
        {/snippet}
    </Modal>



</AppLayout>
