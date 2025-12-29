<?php

namespace App\Mcp\Tools\Hooks;

use App\Models\Hook;
use App\Models\HookOccurance;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;

class GetHookUsages extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        # Get Wordpress Hook Usages
        You can look up all usages of a wordpress hook by name.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $data = $request->validate([
            'name' => 'required|string',
        ]);

        \Log::info(
            'GetHookUsages Tool called with name: '.$data['name']
        );

        $hooks = Hook::where('name', $data['name'])->get()->map(function (Hook $hook) {
            return $hook->id;
        });

        if (count($hooks) === 0) {
            return Response::error(
                'The hook "'.$data['name'].'" does not exist in the database.'
            );
        }

        $usages = HookOccurance::whereIn('hook_id', $hooks)->get();

        $json = $usages->toJson(JSON_PRETTY_PRINT);

        return Response::text($json);
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
