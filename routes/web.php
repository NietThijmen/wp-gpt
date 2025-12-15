<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function (
    \App\Services\Composer $composer,
    \App\Services\HookParser $hookParser
) {

//    $composer->generateInstallComposer(
//        public_path('packages/woocommerce'),
//        'wpackagist-plugin/woocommerce',
//        'dev-trunk'
//    );
//
//    $composer->install(
//        public_path('packages/woocommerce')
//    );

    $parsed = $hookParser->parseFiles(
        public_path('packages/woocommerce/data'),
    );

    dump(
        "Found " . count($parsed) . " hooks."
    );

    for($i = 0; $i < 50; $i++) {
        dump($parsed[$i]);
    }





});
