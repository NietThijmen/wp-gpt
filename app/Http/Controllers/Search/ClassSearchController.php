<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Models\ClassMethod;
use App\Models\FileClass;
use App\Models\Hook;
use Illuminate\Http\Request;

class ClassSearchController extends Controller
{
    public function index(
        Request $request,
    )
    {
        $validated = $request->validate([
            'query' => 'required|string',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $data = FileClass::search(
            $validated['query']
        )->get()->withRelationshipAutoloading()->forPage(
            page: $validated['page'] ?? 1,
            perPage: $validated['per_page'] ?? 10,
        )->withRelationshipAutoloading()->map(function (FileClass $item) {
            return [
                'id' => $item->id,
                'name' => $item->className,
                'plugin' => $item->pluginFile->plugin->name,
                'plugin_version' => $item->pluginFile->plugin->version,
            ];
        });

        return response()->json($data);
    }

    public function inspect(
        Request $request,
        FileClass $class,
    )
    {
        return response()->json([
            'id' => $class->id,
            'name' => $class->className,
            'plugin' => $class->pluginFile->plugin,
            'phpdoc' => $class->phpdoc,
            'methods' => $class->methods->map(function (ClassMethod $item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'start_line' => $item->start_line,
                    'end_line' => $item->end_line,
                    'visibility' => $item->visibility,
                    'phpdoc' => $item->phpdoc,
                    'parameters' => $item->parameters
                ];
            }),
        ]);

    }
}
