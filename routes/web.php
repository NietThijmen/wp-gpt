<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function (
    \App\Services\Composer $composer,
    \App\Services\HookParser $hookParser
) {
    $packages = $composer->search(
        'wpackagist-plugin/classic-editor'
    );

    $packages = array_map(function ($item) {
        return [
            'name' => $item->getName(),
            'version' => $item->getVersion(),
            'description' => $item?->getDescription() ?? 'No description available.',
            'release_date' => $item->getReleaseDate() ? $item->getReleaseDate()->getTimestamp() : 'Unknown',
            'homepage' => $item->getHomepage() ?? 'N/A',
        ];
    }, $packages);

    $packages = array_values($packages);

    dd($packages);
});

Route::get('/search', function (Request $request) {
    return \App\Models\HookOccurance::search(
        $request->get('s')
    )->get()->map(function ($hook) {
        return $hook->toArray();
    });
});
