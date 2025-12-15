<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function (
    \App\Services\Composer $composer
) {

    $composer->generateInstallComposer(
        public_path('packages/classic-editor'),
        'wpackagist-plugin/classic-editor',
        'dev-trunk'
    );

    $composer->install(
        public_path('packages/classic-editor')
    );

    return 'Composer install file generated.';


});
