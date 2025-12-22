<?php

namespace App\Http\Controllers;

use App\Actions\ParsePlugin;
use App\Services\ClassMethodParser;
use App\Services\Composer;
use App\Services\HookParser;
use Illuminate\Http\Request;

class PluginIndexController extends Controller
{
    public function index(
        Request $request,
        Composer $composer
    )
    {
        $data = $request->validate([
            'vendor' => 'required|string|max:255',
            'package' => 'required|string|max:255',
        ]);

        $searched = $composer->search(
            $data['vendor'] . '/' . $data['package']
        );

        $searched = array_map(function ($item) {
            return [
                'name' => $item->getName(),
                'version' => $item->getVersion(),
                'description' => $item?->getDescription() ?? 'No description available.',
                'release_date' => $item->getReleaseDate() ? $item->getReleaseDate()->getTimestamp() : 'Unknown',
                'homepage' => $item->getHomepage() ?? 'N/A',
            ];
        }, $searched);

        $searched = array_values($searched);

        return response()->json($searched);
    }

    public function store(
        Request $request,
        Composer $composer,
        HookParser $hookParser,
        ClassMethodParser $classMethodParser
    )
    {
        $data = $request->validate([
            'vendor' => 'required|string|max:255',
            'package' => 'required|string|max:255',
            'version' => 'required|string|max:255',
        ]);


        ParsePlugin::execute(
            composer: $composer,
            hookParser: $hookParser,
            classMethodParser: $classMethodParser,
            package_name: $data['vendor'] . "/" . $data['package'],
            package_version: $data['version']
        );


        return response()->json(['status' => 'success']);
    }
}
