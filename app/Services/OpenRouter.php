<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class OpenRouter
{

    private string $apiBaseUrl;
    private string $apiKey;
    private string $model;


    private string $cachePrefix = '';
    private int $cacheTtlMinutes = 1;

    public function __construct(
    )
    {
        $this->apiKey = config('services.openrouter.key');
        $this->model = config('services.openrouter.model', 'gpt-4o-mini');
        $this->apiBaseUrl = config('services.openrouter.base_url', 'https://openrouter.ai/api/v1/chat/completions');
        $this->cachePrefix = config('services.openrouter.cache.prefix', 'openrouter_');
        $this->cacheTtlMinutes = config('services.openrouter.cache.ttl_minutes', 1);
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
     * @param bool $enableThoughts
     * @param bool $stream
     * @param int|null $maxTokens
     * @param string|null $cacheKey
     * @return array|mixed The response from the model
     * @throws ConnectionException if the request fails (openrouter has an uptime of ~99.9%, so this should be rare)
     * @throws ContainerExceptionInterface if there is an error retrieving from the cache or storing to the cache
     * @throws NotFoundExceptionInterface if the cache key is not found
     */
    public function sendMessage(
        array $messages,
        bool $enableThoughts = false,
        bool $stream = false,
        ?int $maxTokens = null,
        ?string $cacheKey = null,
    )
    {

        if($cacheKey) {
            if($cached = cache()->get($this->cachePrefix . $cacheKey)) {
                return $cached;
            }
        }

        $baseRequest = $this->getBaseRequest();

        $data = [
            'model' => $this->model,
            'messages' => $messages,
            'stream' => $stream,
        ];

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
            cache()->put($this->cachePrefix . $cacheKey, $data, $this->cacheTtlMinutes * 60);
        }

        return $data;

    }

}
