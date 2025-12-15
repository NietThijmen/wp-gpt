<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function (
    \App\Services\Composer $composer,
    \App\Services\HookParser $hookParser
) {
    \App\Actions\ParsePlugin::execute(
        $composer,
        $hookParser,
        'wpackagist-plugin/woocommerce',
        'dev-trunk'
    );

    return view('welcome');
});
