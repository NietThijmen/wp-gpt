<?php

namespace App\Mcp\Tools\File;

use App\Models\ClassMethod;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;

class SearchMethod extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        Search for a specific method by class name and method name within the codebase.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $data = $request->validate([
            'method' => 'required|string',
            'limit' => 'required|integer|min:1|max:100',
            'offset' => 'required|integer|min:0',
        ]);

        $data = ClassMethod::search(
            $data['method'],
        )->get()
            ->skip($data['offset'])
            ->take($data['limit'])
            ->withRelationshipAutoloading()
            ->map(function (ClassMethod $item) {
                return [
                    'id' => $item->id,
                    'class' => $item->class->className,
                    'visibility' => $item->visibility,
                    'method_name' => $item->name,
                    'parameters' => $item->parameters,
                    'start_line' => $item->start_line,
                    'end_line' => $item->end_line,
                    'phpdoc' => $item->phpdoc,
                ];
            });


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
            'method' => $schema->string()
                ->description('The method name to search for.')
                ->required(),

            'limit' => $schema->integer()
                ->description('The maximum number of results to return.')
                ->min(1)
                ->max(100)
                ->required(),

            'offset' => $schema->integer()
                ->description('The number of results to skip.')
                ->min(0)
                ->required(),
        ];
    }
}
