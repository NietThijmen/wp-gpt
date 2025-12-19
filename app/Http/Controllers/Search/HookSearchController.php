<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Models\Hook;
use Illuminate\Http\Request;

class HookSearchController extends Controller
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

        $data = Hook::search(
            $validated['query']
        )->get()->withRelationshipAutoloading()->forPage(
            page: $validated['page'] ?? 1,
            perPage: $validated['per_page'] ?? 10,
        )->withRelationshipAutoloading()->map(function (Hook $item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'type' => $item->type,
                'plugin' => $item->plugin->name,
                'plugin_version' => $item->plugin->version,
            ];
        });

        return response()->json($data);
    }

    public function inspect(
        Request $request,
        Hook $hook,
    )
    {
        return response()->json([
            'id' => $hook->id,
            'name' => $hook->name,
            'type' => $hook->type,
            'plugin' => $hook->plugin->name,
            'plugin_version' => $hook->plugin->version,
            'occurrences' => $hook->occurrences->map(function ($item) {
                return [
                    'id' => $item->id,
                    'file_path' => $item->file_path,
                    'line_number' => $item->line,
                    'class' => $item->class,
                    'method' => $item->method,
                    'args' => $item->args,
                    'phpdoc' => $item->class_phpdoc,
                    'context' => $item->surroundingCode,
                ];
            }),
        ]);

    }
}
