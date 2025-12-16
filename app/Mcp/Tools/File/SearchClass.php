<?php

namespace App\Mcp\Tools\File;

use App\Models\FileClass;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;

class SearchClass extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        Search for a specific class by name within the codebase.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $data = $request->validate([
            'class_name' => 'required|string',
            'limit' => 'nullable|integer|min:1|max:100',
            'offset' => 'nullable|integer|min:0',
        ]);

        // Build the search query
        $query = \App\Models\FileClass::search(
            $data['class_name']
        )->get()->skip($data['offset'] ?? 0)->take($data['limit'] ?? 10)->withRelationshipAutoloading()->map(function (FileClass $item) {
            return [
                'id' => $item->id,
                'file' => $item->pluginFile?->name ?: 'N/A',
                'class_name' => $item->className,
                'phpdoc' => $item->phpdoc,
                'methods' => $item->methods->map(function ($method) {
                    return [
                        'id' => $method->id,
                        'method_name' => $method->name,
                        'phpdoc' => $method->phpdoc,
                    ];
                })->toArray(),
            ];
        });

        return Response::text(
            json_encode(
                $query->toArray(),
                JSON_PRETTY_PRINT
            )
        );
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, \Illuminate\Contracts\JsonSchema\JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'class_name' => $schema->string()
                ->description('The name of the class to search for.')
                ->required(),

            'limit' => $schema->integer()
                ->description('The maximum number of results to return.')
                ->min(1)
                ->max(100)
                ->default(10),

            'offset' => $schema->integer()
                ->description('The number of results to skip.')
                ->min(0)
                ->default(0),
        ];
    }
}
