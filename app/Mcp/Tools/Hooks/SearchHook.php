<?php

namespace App\Mcp\Tools\Hooks;

use App\Models\Hook;
use App\Models\HookOccurance;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;

class SearchHook extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        Search for a hook by name or part of a name.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'query' => 'required|string',
        ]);

        $hookQuery = $validated['query'];
        $hook_array = HookOccurance::search(
            $hookQuery
        )->raw()['hits'];

        return Response::text(json_encode(
            $hook_array,
            JSON_PRETTY_PRINT
        ));
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, \Illuminate\Contracts\JsonSchema\JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()
                ->description('The name or part of the name of the wordpress hook to search for.')
                ->required(),
        ];
    }
}
