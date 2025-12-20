<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PasswordController extends Controller
{

    public function index(Request $request)
    {
        return inertia('Account/Password');
    }
    public function update(Request $request, User $password)
    {
        $user = $password; // Get the user model from the route parameter
        $data = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Check if the current password matches
        if (!\Hash::check($data['current_password'], $user->password)) {
            return redirect()->back()->withErrors(['current_password' => __('auth.password')]);
        }

        // Update the user's password
        $user->password = \Hash::make($data['new_password']);
        $user->save();
        return redirect()->back()->with('success', 'Password updated successfully.');
    }
}
