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
        $this->app->singleton(OpenRouter::class, function () {
            return new OpenRouter();
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
