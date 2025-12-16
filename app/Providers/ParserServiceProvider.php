<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ParserServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('hookparser', function ($app) {
            return new \App\Services\HookParser;
        });

        $this->app->singleton('classMethodParser', function ($app) {
            return new \App\Services\ClassMethodParser;
        });
    }

    public function boot(): void {}
}
