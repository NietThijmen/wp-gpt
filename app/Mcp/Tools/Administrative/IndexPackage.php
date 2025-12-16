<?php

namespace App\Mcp\Tools\Administrative;

use App\Actions\ParsePlugin;
use App\Services\Composer;
use App\Services\HookParser;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;

class IndexPackage extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        Index a package in the composer registries for the app.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(
        Request $request,
        Composer $composer,
        HookParser $hookParser
    ): Response
    {
        $request->validate([
            'vendor' => 'nullable|string',
            'package' => 'required|string',
            'version' => 'nullable|string',
        ]);

        $success = ParsePlugin::execute(
            $composer,
            $hookParser,
            $request->string('vendor', 'wpackagist-plugin') . '/' . $request->string('package'),
            $request->get('version', null)
        );

        if(!$success) {
            return Response::error(
                "Failed to index package " . $request->string('vendor', 'wpackagist-plugin') . '/' . $request->string('package') . "."
            );
        }


        return Response::text('Successfully indexed package ' . $request->string('vendor', 'wpackagist-plugin') . '/' . $request->string('package') . '.');
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
                ->required()
        ];
    }
}
