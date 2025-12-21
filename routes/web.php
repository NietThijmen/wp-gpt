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


    Route::prefix('/account')->name('account.')->group(function () {
        Route::resource('/profile', \App\Http\Controllers\Profile\ProfileController::class)
            ->only(['index', 'update', 'destroy'])
            ->names([
                'index' => 'profile.index',
                'update' => 'profile.update',
                'destroy' => 'profile.destroy',
            ]);

        Route::resource('/password', \App\Http\Controllers\Profile\PasswordController::class)
            ->only(['update', 'index'])
            ->names([
                'index' => 'password.index',
                'update' => 'password.update',
            ]);


        Route::resource('/two_factor', \App\Http\Controllers\Profile\TwoFactorController::class)
            ->only(['index', 'store', 'destroy'])
            ->names([
                'index' => 'two-factor.index',
                'store' => 'two-factor.store',
                'destroy' => 'two-factor.destroy',
            ]);

        Route::resource('/api_tokens', \App\Http\Controllers\Profile\ApiTokenController::class)
            ->only(['index', 'store', 'destroy'])
            ->names([
                'index' => 'api-tokens.index',
                'store' => 'api-tokens.store',
                'destroy' => 'api-tokens.destroy',
            ]);
    });
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

    Route::get('/two-factor', function (Request $request) {
        return inertia('Auth/TwoFactor', [
            'status' => session('status'),
        ]);
    })->name('two-factor.login');
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
