<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('Welcome');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/home', function () {
        return redirect()->route('documentation.index');
    });

    Route::get('/hook-search', function () {
        return inertia('Search/Hooks');
    })->name('hook-search.index');

    Route::get('/class-search', function () {
        return inertia('Search/Classes');
    })->name('class-search.index');

    Route::resource('/documentation', \App\Http\Controllers\PluginDocumentorController::class);
    Route::resource('/chat', \App\Http\Controllers\ChatController::class)->names([
        'index' => 'chat.index',
        'create' => 'chat.create',
        'store' => 'chat.store',
        'show' => 'chat.show',
        'edit' => 'chat.edit',
        'update' => 'chat.update',
        'destroy' => 'chat.destroy',
    ]);

    Route::get('/chat/{chat}/messages', [\App\Http\Controllers\ChatMessageController::class, 'index'])->name('chat.messages.index');
    Route::post('/chat/{chat}/messages', [\App\Http\Controllers\ChatMessageController::class, 'store'])->name('chat.messages.store');
});


Route::middleware(['auth'])->prefix('/chat')->group(function () {
    Route::get('/explain-class/{fileClass}', [\App\Http\Controllers\Ai\ClassExplainer::class, 'explain'])->name('chat.explain-class');
});



Route::middleware([
    'guest'
])->group(function () {
    Route::get('/login', function () {
        return inertia('Auth/Login');
    })->name('login');
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

    Route::get('/classes', [\App\Http\Controllers\Search\ClassSearchController::class, 'index'])->name('search.classes');
    Route::get('/class/{class}', [
        \App\Http\Controllers\Search\ClassSearchController::class,
        'inspect'
    ])->name('search.class.inspect');
});
