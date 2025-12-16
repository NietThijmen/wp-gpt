<?php

namespace App\Mcp\Tools\File;

use App\Models\PluginFile;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;

class SearchFile extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        Search for a specific file by path or raw content within the codebase.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {

        $data = $request->validate([
            'path' => 'nullable|string|required_without:raw_content',
            'raw_content' => 'nullable|string|required_without:path',
            'plugin' => 'nullable|string',
            'limit' => 'nullable|integer|min:1|max:100',
            'offset' => 'nullable|integer|min:0',
        ]);

        // Build the search query
        $query = \App\Models\PluginFile::search(
            $data['content'] ?? $data['path'] ?? ''
        )->get()->skip($data['offset'])->take($data['limit'])->withRelationshipAutoloading()->map(function (PluginFile $item) {
            return [
                'id' => $item->id,
                'path' => $item->path,
                'plugin' => $item->plugin->name,
                'content' => $item->content
            ];
        })->filter(function ($item) use ($data) {
            if (isset($data['plugin'])) {
                return $item['plugin'] === $data['plugin'];
            }
            return true;
        });

        return Response::text(
            json_encode(
                $query->toArray(),
                JSON_PRETTY_PRINT
            )
        );

        return Response::text(json_encode(
            $file_array,
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
            'raw_content' => $schema->string()
                ->title("Raw Content")
                ->description('The raw content to search for within files.')
                ->required(),

            'path' => $schema->string()
                ->title("File Path")
                ->description('The file path to search for.')
                ->required(),

            'plugin' => $schema->string()
                ->title("Plugin Slug")
                ->description('The plugin slug to filter the search results by.')
                ->required(),

            'limit' => $schema->integer()
                ->title("Limit")
                ->description('The maximum number of results to return.')
                ->default(10)
                ->required(),

            'offset' => $schema->integer()
                ->title("Offset")
                ->description('The number of results to skip before starting to collect the result set.')
                ->default(0)
                ->required(),

        ];
    }
}
