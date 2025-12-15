<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function (
    \App\Services\Composer   $composer,
    \App\Services\HookParser $hookParser
) {
    \App\Actions\ParsePlugin::execute(
        $composer,
        $hookParser,
        'roots/wordpress-no-content',
        '6.9'
    );

    return view('welcome');
});


Route::get('/search', function (Request $request) {
    return \App\Models\HookOccurance::search(
        $request->get('s')
    )->get()->map(function ($hook) {
        return $hook->toArray();
    });
});
