<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenRouter
{

    private string $apiBaseUrl;
    private string $apiKey;
    private string $model;

    private McpClient $mcpClient;

    /**
     * @param McpClient $mcpClient the MCP client to call MCP servers, this is given by DI
     */
    public function __construct(
        McpClient $mcpClient
    )
    {
        $this->apiKey = config('services.openrouter.key');
        $this->model = config('services.openrouter.model', 'gpt-4o-mini');
        $this->apiBaseUrl = config('services.openrouter.base_url', 'https://openrouter.ai/api/v1/chat/completions');

        $this->mcpClient = $mcpClient;
    }

    private function getBaseRequest(): \Illuminate\Http\Client\PendingRequest
    {
        return Http::baseUrl($this->apiBaseUrl)
            ->withHeaders([
                'Http-Referer' => config('app.url'),
                'X-title' => config('app.name'),
            ])->withToken(
                $this->apiKey
            )->timeout(
                300 // 5 minutes (some requests may take a while)
            );
    }

    /**
     * @param array $messages The messages to send to the model
     * @return array|mixed The response from the model
     * @throws \Illuminate\Http\Client\ConnectionException if the request fails (openrouter has an uptime of ~99.9%, so this should be rare)
     */
    public function sendMessage(
        array $messages,

        bool $useTools = false,
        bool $enableThoughts = false,
        bool $stream = false,
        ?int $maxTokens = null,
        ?string $cacheKey = null,
    )
    {

        if($cacheKey) {
            if($cached = cache()->get($cacheKey)) {
                return $cached;
            }
        }

        $baseRequest = $this->getBaseRequest();

        $data = [
            'model' => $this->model,
            'messages' => $messages,
            'stream' => $stream,
        ];

        if($useTools) {
            $tools = $this->mcpClient->generateToolsArray();
            $data['tools'] = $tools;
            $data['tool_choice'] = 'auto';
        }

        $data['reasoning'] = [];
        if($enableThoughts) {
            $data['reasoning']['effort'] = 'high';
        } else {
            $data['reasoning']['effort'] = 'minimal';
        }

        if($maxTokens) {
            $data['max_tokens'] = $maxTokens;
        }

        $response = $baseRequest->post('/api/v1/chat/completions', $data);

        $data =  $response->json();

        if($cacheKey) {
            cache()->put($cacheKey, $data, 10 * 60); // cache for 10 minutes
        }

        return $data;

    }

}
