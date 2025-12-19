<?php

namespace App\Http\Controllers;

use App\Models\Plugin;
use App\Models\PluginFile;
use Illuminate\Http\Request;

class PluginDocumentorController extends Controller
{
    public function index()
    {
        $plugins = Plugin::all();
        return inertia('Documentor/PluginSelect', [
            'plugins' => $plugins,
        ]);
    }
    public function show(Plugin $documentation)
    {
        $plugin = $documentation; // a bit weird but keeps route model binding clear

        $files = PluginFile::where('plugin_id', $plugin->id)
            ->with([
                'classes',
                'classes.methods'
            ])
        ->get()
        ->map(function (PluginFile $item) {
            return [
                'id' => $item->id,
                'path' => $item->path,
                'content' => $item->content,
                'classes' => $item->classes->map(function ($class) {
                    return [
                        'id' => $class->id,
                        'className' => $class->className,
                        'phpdoc' => $class->phpdoc,
                        'methods' => $class->methods->map(function ($method) {
                            return [
                                'id' => $method->id,
                                'name' => $method->name,
                                'start_line' => $method->start_line,
                                'end_line' => $method->end_line,
                                'visibility' => $method->visibility,
                                'phpdoc' => $method->phpdoc,
                                'parameters' => $method->parameters,
                            ];
                        }),
                    ];
                }),
            ];
        });


        return inertia('Documentor/PluginDetail', [
            'plugin' => $plugin,
            'files' => $files,
        ]);
    }
}
