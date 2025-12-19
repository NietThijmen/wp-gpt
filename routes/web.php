<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('Welcome');
});

Route::get('/home', function () {
    return inertia('Chat');
})->name('home');

Route::get('/hook-search', function () {
    return inertia('Search/Hooks');
})->name('hook-search.index');



Route::middleware([
    'guest'
])->group(function () {
    Route::get('/login', function () {
        return inertia('Auth/Login');
    })->name('login.get');
});


Route::get('/test', function (
    \App\Services\Composer $composer,
    \App\Services\HookParser $hookParser,
    \App\Services\ClassMethodParser $classMethodParser
) {
    \App\Actions\ParsePlugin::execute(
        $composer,
        $hookParser,
        $classMethodParser,
        'wpackagist-plugin/akismet',
        'dev-trunk'
    );
});

Route::prefix('/search')->group(function () {
    Route::get('/hooks', [\App\Http\Controllers\Search\HookSearchController::class, 'index'])->name('search.hooks');
    Route::get('/hook/{hook}', [\App\Http\Controllers\Search\HookSearchController::class, 'inspect'])->name('search.hook.inspect');
});
