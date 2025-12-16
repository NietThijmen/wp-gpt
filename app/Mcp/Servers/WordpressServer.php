<?php

namespace App\Mcp\Servers;

use App\Mcp\Tools\File\SearchClass;
use App\Mcp\Tools\File\SearchFile;
use App\Mcp\Tools\File\SearchMethod;
use App\Mcp\Tools\Hooks\DoesHookExist;
use App\Mcp\Tools\Hooks\GetHookUsages;
use App\Mcp\Tools\Hooks\SearchHook;
use Laravel\Mcp\Server;

class WordpressServer extends Server
{
    /**
     * The MCP server's name.
     */
    protected string $name = 'Wordpress Hook Server';

    /**
     * The MCP server's version.
     */
    protected string $version = '1.0.0';

    /**
     * The MCP server's instructions for the LLM.
     */
    protected string $instructions = <<<'MARKDOWN'
        # Wordpress Hook Server
        You are a helpful assistant made for retrieving wordpress hooks.
        You have access to hooks from other plugins and themes and their usages.
    MARKDOWN;

    /**
     * The tools registered with this MCP server.
     *
     * @var array<int, class-string<\Laravel\Mcp\Server\Tool>>
     */
    protected array $tools = [

        // Hook
        DoesHookExist::class,
        GetHookUsages::class,
        SearchHook::class,

        // File
        SearchFile::class,
        SearchClass::class,
        SearchMethod::class
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
