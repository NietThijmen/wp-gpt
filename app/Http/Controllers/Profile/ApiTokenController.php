<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class ApiTokenController extends Controller
{
    public function index()
    {
        $user = auth()->user();


        return inertia('Account/ApiTokens', [
            'tokens'  => $user->tokens->map(function ($token) {
                return [
                    'id' => $token->id,
                    'name' => $token->name,
                    'abilities' => $token->abilities,
                    'last_used_at' => $token->last_used_at,
                    'created_at' => $token->created_at,
                ];
            }),
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'abilities' => 'nullable|array',
            'abilities.*' => 'string',
        ]);

        $token = $user->createToken(
            $data['name'],
            $data['abilities'] ?? ['*']
        );

        return redirect()->back()->with('success', 'API token created successfully.')->with('token', $token->plainTextToken);

    }

    public function destroy(Request $request, User $user)
    {
        $data = $request->validate([
            'token_id' => 'required|string|exists:personal_access_tokens,id',
        ]);


        $token = PersonalAccessToken::where('id',  $data['token_id']);
        if ($token) {
            $token->delete();
            return redirect()->back()->with('success', 'API token revoked successfully.');
        } else {
            return redirect()->back()->withErrors(['token_id' => 'API token not found.']);
        }
    }
}
