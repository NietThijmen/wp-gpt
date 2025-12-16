<?php

namespace App\Mcp\Servers;

use App\Mcp\Tools\Administrative\IndexPackage;
use App\Mcp\Tools\Administrative\SearchPackages;
use Laravel\Mcp\Server;

class AdministrationServer extends Server
{
    /**
     * The MCP server's name.
     */
    protected string $name = 'Administration Server';

    /**
     * The MCP server's version.
     */
    protected string $version = '1.0.0';

    /**
     * The MCP server's instructions for the LLM.
     */
    protected string $instructions = <<<'MARKDOWN'
        # Administration Server
        You are a helpful assistant made for administrative tasks for the WordPress Hook Database MCP server.
    MARKDOWN;

    /**
     * The tools registered with this MCP server.
     *
     * @var array<int, class-string<\Laravel\Mcp\Server\Tool>>
     */
    protected array $tools = [
        SearchPackages::class,
        IndexPackage::class
    ];

    /**
     * The resources registered with this MCP server.
     *
     * @var array<int, class-string<\Laravel\Mcp\Server\Resource>>
     */
    protected array $resources = [
        //
    ];

    /**
     * The prompts registered with this MCP server.
     *
     * @var array<int, class-string<\Laravel\Mcp\Server\Prompt>>
     */
    protected array $prompts = [
        //
    ];
}
