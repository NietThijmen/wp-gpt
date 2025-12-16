<?php

namespace App\Mcp\Tools\Administrative;

use App\Services\Composer;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;

class SearchPackages extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        Search for a package by vendor and package slug in the composer registries for the app.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(
        Request $request,
        Composer $composer
    ): Response {
        $request->validate([
            'vendor' => 'nullable|string',
            'package' => 'required|string',
            'version' => 'nullable|string',
        ]);

        $data = $composer->search(
            $request->string('vendor', 'wpackagist-plugin').'/'.$request->string('package'),
            $request->get('version', null)
        );

        if (count($data) == 0) {
            return Response::error(
                'No packages found for '.$request->string('vendor', 'wpackagist-plugin').'/'.$request->string('package').'.'
            );
        }

        $data = array_map(function ($item) {
            return [
                'name' => $item->getName(),
                'version' => $item->getVersion(),
                'description' => $item?->getDescription() ?? 'No description available.',
                'release_date' => $item->getReleaseDate() ? $item->getReleaseDate()->getTimestamp() : 'Unknown',
                'homepage' => $item->getHomepage() ?? 'N/A',
            ];
        }, $data);

        $data = array_values($data);

        return Response::text(
            json_encode(
                $data,
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
            'vendor' => $schema->string()
                ->description('The vendor slug of the package to search for.')
                ->default('wpackagist-plugin')
                ->required(),

            'package' => $schema->string()
                ->description('The package slug of the package to search for.')
                ->required(),

            'version' => $schema->string()
                ->description('The version of the package to search for.')
                ->nullable(),
        ];
    }
}
