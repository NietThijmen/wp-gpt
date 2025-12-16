<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('Welcome');
});

Route::get('/home', function () {
    return "Home Page";
})->name('home');



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

Route::get('/search', function (Request $request) {
    return \App\Models\HookOccurance::search(
        $request->get('s')
    )->get()->map(function ($hook) {
        return $hook->toArray();
    });
});
