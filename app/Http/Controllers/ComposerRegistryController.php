<?php

namespace App\Http\Controllers;

use App\Models\ComposerRegistry;
use Illuminate\Http\Request;

class ComposerRegistryController extends Controller
{
    public function index()
    {
        $registries = ComposerRegistry::all()->map(function ($registry) {
            return [
                'id' => $registry->id,
                'domain' => $registry->domain,
                'created_at' => $registry->created_at,
            ];
        });

        return inertia('ComposerRegistries/Index', [
            'registries' => $registries,
        ]);
    }

    public function create()
    {
        return inertia('ComposerRegistries/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'domain' => 'required|string|max:255|unique:composer_registries,domain',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:255|required_with:username',

            'access_token' => 'nullable|string|max:255',
        ]);

        $registry = ComposerRegistry::create([
            'domain' => $data['domain'],
            'username' => $data['username'] ?? null,
            'password' => $data['password'] ?? null,
            'access_token' => $data['access_token'] ?? null,
        ]);

        return redirect()->route('composer-registries.index', $registry);
    }


    public function destroy(ComposerRegistry $registry)
    {
        $registry->delete();
        return redirect()->route('composer-registries.index');
    }
}
