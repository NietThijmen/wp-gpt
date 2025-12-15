<?php

namespace App\Mcp\Tools\Hooks;

use App\Models\Hook;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;

class DoesHookExist extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        # Search Wordpress Hooks
        You can look up a wordpress hook by name to see if it exists.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $data = $request->validate([
            'name' => 'required|string',
        ]);

        $exists = Hook::where('name', $data['name'])->exists();

        if(!$exists) {
            return Response::error('The hook "' . $data['name'] . '" does not exist in the database.');
        }

        return Response::text('The hook "' . $data['name'] . '" exists in the database.');
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, \Illuminate\Contracts\JsonSchema\JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()
                ->description('The name of the wordpress hook to search for.')
                ->required(),

        ];
    }
}
