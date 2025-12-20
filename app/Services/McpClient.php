<?php

namespace App\Services;


use Illuminate\Support\Facades\Log;

/**
 * Class McpToOpenAiSpec
 *
 * A service to convert MCP specifications to OpenAI specifications.
 * And make it possible to call the MCP server back again
 */
class McpClient
{

    protected array $servers = [];
    public function __construct()
    {
        // the servers will be:
        $this->servers['wordpress'] = config('app.url').'/mcp/wordpress';
        $this->servers['administration'] = config('app.url').'/mcp/administration';
    }


    private function getLogger(): \Psr\Log\LoggerInterface
    {
        return Log::channel(
            'mcp_client'
        );
    }

    /**
     * Calls the MCP servers to get the tools and convert them to OpenAI tool spec
     *
     * @return array
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function generateToolsArray(): array
    {
        $raw_tool_array = [];

        foreach ($this->servers as $key=>$url) {
            // call the mcp server to get the tools
            $data = \Http::post($url, [
                'method' => 'tools/list',
            ])->json('tools');

            foreach ($data as $tool) {
                // convert the mcp tool to openai tool
                $data = [
                    'name' => $key . "_" . $tool['name'],
                    'description' => $tool['description'],
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [],
                        'required' => [],
                    ],
                ];

                foreach ($tool['parameters'] as $parameter) {
                    $data['parameters']['properties'][$parameter['name']] = [
                        'type' => $parameter['type'],
                        'description' => $parameter['description'],
                    ];
                    if (isset($parameter['required']) && $parameter['required'] === true) {
                        $data['parameters']['required'][] = $parameter['name'];
                    }
                }

                $raw_tool_array[] = $data;
            }
        }

        $this->getLogger()->info(
            'Generated tool array: ',
            [
                'tools' => $raw_tool_array
            ]
        );
        return $raw_tool_array;
    }

    public function callTool(
        string $toolName,
        array $parameters
    ): array
    {
        // determine which server to call
        $parts = explode('_', $toolName, 2);
        if (count($parts) !== 2) {
            throw new \InvalidArgumentException('Invalid tool name: ' . $toolName);
        }
        $serverKey = $parts[0];
        $actualToolName = $parts[1];

        if (!isset($this->servers[$serverKey])) {
            $this->getLogger()->info(
                "Unknown server key: " . $serverKey
            );
            throw new \InvalidArgumentException('Unknown server key: ' . $serverKey);
        }

        $url = $this->servers[$serverKey];

        // call the mcp server to execute the tool
        $response = \Http::post($url, [
            'method' => 'tools/call',
            'params' => [
                'name' => $actualToolName,
                'arguments' => $parameters,
                '_meta' => [
                    'progressToken' => 0 // for now we don't support progress tokens
                ],
            ]
        ])->json('content');

        if(count($response) === 0) {
            return ['response' => 'No response from tool.'];
        }

        $this->getLogger()->debug(
            'Tool call response: ',
            [
                'tool' => $toolName,
                'parameters' => $parameters,
                'response' => $response,
            ]
        );

        return $response;
    }



}
