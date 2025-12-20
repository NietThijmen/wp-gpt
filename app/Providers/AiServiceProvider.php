<?php

namespace App\Providers;

use App\Services\McpClient;
use App\Services\OpenRouter;
use Illuminate\Support\ServiceProvider;

class AiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(McpClient::class, function () {
            return new McpClient();
        });

        $this->app->singleton(OpenRouter::class, function () {
            return new OpenRouter(
                mcpClient: $this->app->make(McpClient::class)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
